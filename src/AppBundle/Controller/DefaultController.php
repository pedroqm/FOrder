<?php
namespace AppBundle\Controller;
use AppBundle\Entity\DetallePedido;
use AppBundle\Entity\Mesa;
use AppBundle\Entity\Producto;
use AppBundle\Entity\Pedido;
use AppBundle\Form\Type\ProductoType;
use AppBundle\Entity\Usuario;
use AppBundle\Form\Type\UsuarioType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
class DefaultController extends Controller
{
    /**
     * @Route("/instalar", name="instalar")
     */
    public function instalarAction()
    {
        $em = $this->getDoctrine()->getManager();
        $usu = $em->getRepository('AppBundle:Usuario')
            ->findOneById(1);
        if(!$usu){
            $usuario= new Usuario();
            $usuario->setNombreUsuario('admin');
            $usuario->setPass('admin');
            $usuario->setNombre('admin');
            $usuario->setApellidos('admin');
            $usuario->setDni('26502842B');
            $usuario->setTelefono(99);
            $usuario->setEmail('demo@demo.com');
            $usuario->setEsAdmin(true);
            $usuario->setEsCamarero(false);
            $usuario->setEsCliente(false);
            // Obtener el EntityManager
            $em = $this->getDoctrine()->getManager();
            $em->persist($usuario);
            // Guardar los cambios
            $em->flush();
        }
        return $this->render(':default:formulario.html.twig');
    }
    /**
     * @Route("/entrar", name="usuario_entrar")
     */
    public function loginAction()
    {
        return $this->render(':default:formulario.html.twig');
    }
    /**
     * @Route("/comprobar", name="usuario_comprobar")
     */
    public function comprobarAction()
    {
    }
    /**
     * @Route("/admin", name="administracion")
     * @Security("is_granted('ROLE_ADMIN')")
     */
    public function adminAction()
    {
        return $this->render(':default:administracion.html.twig');
    }
    /**
     * @Route("/mesas", name="mesas")
     * @Security("is_granted('ROLE_CAMARERO')")
     */
    public function servicioAction()
    {
        return $this->render(':mesa:listar_mesa.html.twig');
    }
    /**
     * @Route("/", name="inicio")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $usuario = $this->getUser();
        $cliente=$usuario->getEsCliente();
        if($cliente){
            $tipoProducto = $em->getRepository('AppBundle:TipoProducto')
                ->findAll();
            return $this->render(':default:inicio.html.twig', [
                'tipoProducto' => $tipoProducto,
                'usuarios'=> $usuario
            ]);
        }else{
            $camarero=$usuario->getEsCamarero();
            if($camarero){
                $em = $this->getDoctrine()->getManager();
                $mesa = $em->getRepository('AppBundle:Mesa')
                    ->findAll();
                $pedidoRealizado=$em->getRepository('AppBundle:Pedido')->findBy(array('estado'=>'pendiente'));
                return $this->render(':mesa:listar_mesa.html.twig', [
                    'mesa' => $mesa,
                    'pedido'=>$pedidoRealizado
                ]);
            }else{
                return $this->render(':default:administracion.html.twig');
            }
        }
    }
    /**
     * @Route("/pedidoManual", name="pedidoManual")
     */
    public function pedManualAction()
    {
        $em = $this->getDoctrine()->getManager();
        $usuario = $this->getUser();
        $tipoProducto = $em->getRepository('AppBundle:TipoProducto')
            ->findAll();
        return $this->render(':default:inicio.html.twig', [
            'tipoProducto' => $tipoProducto,
            'usuarios'=> $usuario
        ]);
    }
    /**
     * @Route("/cuenta", name="cuenta")
     */
    public function cuentaAction()
    {
        $em = $this->getDoctrine()->getManager();
        //cambiar mesa por defecto por una mesa especifica
        $mesa=$em->getRepository('AppBundle:Mesa')->findOneBy(array('id'=>1));
        $producto=new Producto();
        $total = 0;
        if(isset($_SESSION['pedido'])){
            if($_SESSION['pedido']==''){
                $pedido=null;
                $total=null;
            }else {
                $pedido = $_SESSION['pedido'];

                $i = 0;
                while (isset($_SESSION["pedido"][$i]) <> '') {
                    for ($j = 0; $j <= $i; $j++) {
                        $total = $total + ($pedido[$j][0]->getPrecio() * $pedido[$j][1]);
                    }
                    $i++;
                }
            }
        }else{    //Se muestra la cuenta sin pedidos.
            $pedido=null;
            $total=null;
        }
        return $this->render('default/cuenta.html.twig',[
            'producto' => $producto,
            'mesa'=>$mesa,
            'total'=>$total,
            'pedido'=>$pedido
        ]);
    }
    /**
     * @Route("/realizar_pedido", name="realizar_pedido")
     */
    public function realizarPedidoAction()
    {
        $total = 0;
        if(isset($_SESSION['pedido'])) {
            if($_SESSION['pedido']!=''){
                $em = $this->getDoctrine()->getManager();
                $pedido = $_SESSION['pedido'];

                //creamos un nuevo pedido
                $pedidoRealizado = new Pedido();
                $pedidoRealizado->setEstado('pendiente');
                $pedidoRealizado->setIncidencias('Sin incidencias');
                $em->persist($pedidoRealizado);
                // Guardar los cambios
                $em->flush();

                //creamos los detalles del pedido
                $newPedido = new DetallePedido();
                $mesa = $em->getRepository('AppBundle:Mesa')->findOneBy(array('id' => 1));


                for ($i = 0; $i < count($pedido); $i++) {
                    $producto = $em->getRepository('AppBundle:Producto')->findOneBy(array('id' => $pedido[$i][0]));


                    //guardamos los detalles del pedido
                    $newPedido->setIdPedido($pedidoRealizado->getId());
                    $newPedido->setNombreProducto($pedido[$i][0]->getNombreProducto());
                    $newPedido->setCantidad($pedido[$i][1]);
                    $em->persist($newPedido);
                    // Guardar los cambios
                    $em->flush();

                    //actualizamos la cuenta
                    $precio = $producto->getPrecio();
                    $cantidad = $pedido[$i][1];
                    $cuenta = $mesa->getCuenta();
                    $mesa->setCuenta($cuenta + $precio * $cantidad);
                    $em->persist($mesa);

                    // Guardar los cambios
                    $em->flush();

                    //descontamos los productos en el almacen
                    /*
                    $al = $em->getRepository('AppBundle:Ingredientes')->findAll(array('nombreProducto'=>$pedido[$i][0]->getNombreProducto()));
                    var_dump($al);
                    var_dump('uno');*/
                }
            }else{
                $pedido=null;
            }
            $em = $this->getDoctrine()->getManager();
            $mesa=$em->getRepository('AppBundle:Mesa')->findOneBy(array('id'=>1));
            $producto=new Producto();
            $_SESSION['pedido'] = '';
            $pedido=null;
            return $this->render('default/cuenta.html.twig',[
                'producto' => $producto,
                'mesa'=>$mesa,
                'total'=>$total,
                'pedido'=>$pedido
            ]);
        }else{    //Se muestra la cuenta sin pedidos.
            $em = $this->getDoctrine()->getManager();
            $mesa=$em->getRepository('AppBundle:Mesa')->findOneBy(array('id'=>1));
            $producto=new Producto();
            $pedido=null;
            return $this->render('default/cuenta.html.twig',[
                'producto' => $producto,
                'mesa'=>$mesa,
                'total'=>$total,
                'pedido'=>$pedido
            ]);
        }
    }
    /**
     * @Route("/salir", name="salir")
     */
    public function salirAction()
    {
        // Al salir se redirecciona al formulario de login
        return $this->render('default/formulario.html.twig', array());
    }
}