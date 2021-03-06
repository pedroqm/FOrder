<?php
namespace AppBundle\Controller;
use AppBundle\Entity\DetallePedido;
use AppBundle\Entity\Pedido;
use AppBundle\Entity\Producto;
use AppBundle\Entity\TipoProducto;
use AppBundle\Form\Type\PedidoType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
/**
 * @Route("/pedido")
 */
class PedidoController extends Controller
{

    /**
     * @Route("/pedir/{producto}", name="pedir")
     */
    public function pedirAction(Producto $producto)
    {
        $usuario = $this->getUser();
        //si se pulsa en el boton pedir almacenamos el pedido en una sesión
        if(isset($_POST['pedirP'])) {
            if ($_POST['cantidad'] < 0) {
                echo "<script>alert('No puedes introducir números negativos')</script>";
            } else {
                if (isset($_SESSION['pedido']) != '') {
                    $DatoYaExistente = false;
                    $encontrado=false;
                    $em = $this->getDoctrine()->getManager();
                    $idProduc = $em->getRepository('AppBundle:Producto')
                        ->findOneBy(array('id' => $producto->getId()));
                    $p = [$idProduc, $_POST['cantidad']];
                    //guardamos una copia del carrito para comprobarla y modificarla
                    $carrito = $_SESSION["pedido"];
                    $i = 0;
                    while (isset($_SESSION["pedido"][$i]) <> '') {
                        for ($j = 0; $j <= $i; $j++) {
                            //comprobamos si existe ese producto en el carrito
                            if ($carrito[$j][0]->getId() == $idProduc->getId() && $encontrado==false) {
                                //sumamos la cantidad pedida
                                $p = [$idProduc, $_POST['cantidad'] + $carrito[$j][1]];
                                $_SESSION["pedido"][$i] = $p;
                                $encontrado=true;
                            }
                        }
                        $i++;
                    }
                    for ($j = 0; $j < $i; $j++) {
                        if ($carrito[$j][0]->getId() == $idProduc->getId()) {
                            $DatoYaExistente = true;
                        }
                    }
                    if (!$DatoYaExistente) {
                        $_SESSION["pedido"][$i] = $p;
                    }
                } else {
                    //Primer registro de la sesión del carrito de la compra
                    $em = $this->getDoctrine()->getManager();
                    $idProduc = $em->getRepository('AppBundle:Producto')
                        ->findOneBy(array('id' => $producto->getId()));
                    $p = [$idProduc, $_POST['cantidad']];
                    //Ahora guarda correctamente el primer registro
                    $_SESSION['pedido'][0] = $p;
                }
            }
        }
        //si se pulsa en el boton borrar quitamos el producto del carrito
        if(isset($_POST['borrarP'])) {
            $encontrado=false;
            $em = $this->getDoctrine()->getManager();
            $idProduc = $em->getRepository('AppBundle:Producto')
                ->findOneBy(array('id' => $producto->getId()));
            $carrito = $_SESSION["pedido"];
            $i=0;
            while (isset($_SESSION["pedido"][$i]) <> '') {
                for ($j = 0; $j <= $i; $j++) {
                    //buscamos el producto en el carrito
                    if ($carrito[$j][0]->getId() == $idProduc->getId()&& $encontrado==false) {
                        //borramos la cantidad pedida
                        if($carrito[$j][1]!=0){
                            $p = [$idProduc, 0];
                            $_SESSION["pedido"][$i] = $p;
                            $encontrado=true;
                        }
                    }
                }
                $i++;
            }
        }
        $em = $this->getDoctrine()->getManager();
        $tipoProducto=$_SESSION['tipoProducto'];
        $producto = $em->getRepository('AppBundle:Producto')
            ->findBy(array('tipo' => $_SESSION['tipoProducto']));

        $total=0;
        if (isset($_SESSION['pedido'])) {  //Se muestra la cuenta con los productos que lleva seleccionados
            if ($_SESSION['pedido'] != '') {
                $pedido = $_SESSION['pedido'];
                for ($j = 0; $j < count($pedido); $j++) {
                    $total = $total + ($pedido[$j][0]->getPrecio() * $pedido[$j][1]);
                }
            }else{
                $pedido = null;
                $total=0;
            }
        } else {    //Se muestra la cuenta sin pedidos.
            $pedido = null;
        }

        $em = $this->getDoctrine()->getManager();
        $mesa = $em->getRepository('AppBundle:Mesa')->findOneById($this->getUser()->getMesaOcupada());
        return $this->render(':productos:ver_productos.html.twig', [
            'producto' => $producto,
            'tipoProducto' => $tipoProducto,
            'usuarios' => $usuario,
            'total'=>$total,
            'mesa' => $mesa,
            'pedido' => $pedido
        ]);
    }
    /**
     * @Route("/cambiar/{pedido}", name="terminado")
     */
    public function cambiarAction( Pedido $pedido)
    {
        $em = $this->getDoctrine()->getManager();
        $pedido->setEstado('terminado');
        // Asegurarse de que se tiene en cuenta el nuevo pedido
        $em->persist($pedido);
        // Guardar los cambios
        $em->flush();
        return new RedirectResponse(
            $this->generateUrl('mesa_listar')
        );
    }
    /**
     * @Route("/listo/{deP}", name="listo")
     */
    public function listoAction(DetallePedido $deP)
    {
        $em = $this->getDoctrine()->getManager();
        $pedido=$em->getRepository('AppBundle:Pedido')->findOneBy(array('id'=>$deP->getDpedido()));

        $todoPreparado=false;

        $deP->setListo(true);
        // Asegurarse de que se tiene en cuenta el nuevo pedido
        $em->persist($deP);
        // Guardar los cambios
        $em->flush();

        // Recorremos todos los registros de detalle pedido, y si encontramos todos los registros listos cambiamos el estado del pedido
        for($i=0; $i<=count($deP)-1 ; $i++){
            if($deP->getListo()==true){
                $todoPreparado=true;
            }else{
                $todoPreparado=false;
            }
        }

        if($todoPreparado){
            dump("aqui");
            $pedido->setEstado('preparado');
            $em->persist($pedido);
            $em->flush();
        }



        $Dpedido=$em->getRepository('AppBundle:DetallePedido')->findBy(array('Dpedido'=>$pedido->getId()));

        return $this->render(':mesa:detalle_pedido.html.twig', [
            'detalle'=>$Dpedido,
            'pedido'=>$pedido
        ]);
    }

}