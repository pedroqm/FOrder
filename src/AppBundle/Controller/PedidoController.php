<?php

namespace AppBundle\Controller;

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
     * @Route("/listar", name="pedido_listar")
     */
    public function listarAction()
    {
        $em = $this->getDoctrine()->getManager();

        $pedidos = $em->getRepository('AppBundle:Pedido')
            ->findAll();

        return $this->render(':pedido:listar_pedido.html.twig', [
            'pedidos' => $pedidos
        ]);
    }


    /**
     * @Route("/nuevo", name="pedido_nuevo")
     */
    public function nuevoAction(Request $peticion)
    {
        $pedido = new Pedido();

        // Crear el formulario a partir de la clase
        $formulario = $this->createForm(new PedidoType(), $pedido);

        // Procesar el formulario si se ha enviado con un POST
        $formulario->handleRequest($peticion);

        // Si se ha enviado y el contenido es válido, guardar los cambios
       if ($formulario->isSubmitted() && $formulario->isValid()) {

           // Obtener el EntityManager
           $em = $this->getDoctrine()->getManager();

           // Asegurarse de que se tiene en cuenta el nuevo pedido
           $em->persist($pedido);
           // Guardar los cambios
           $em->flush();

           // Redirigir al usuario a la lista
           return new RedirectResponse(
               $this->generateUrl('pedido_listar')
           );
       }
        return $this->render(':pedido:nuevo_pedido.html.twig' ,[
            'formulario' => $formulario->createView()
        ]);
    }
    /**
     * @Route("/pedir/{producto}", name="pedir")
     */
    public function pedirAction(Producto $producto)
    {
        session_start();
        if (!isset($_SESSION['id'])) {
            return $this->render(':default:formulario.html.twig');
        }else{
            if(isset($_POST['pedirP'])){

                if(isset($_SESSION['pedido'])){
                    $em = $this->getDoctrine()->getManager();
                    $idProduc=$em->getRepository('AppBundle:Producto')
                        ->findOneBy(array('id' => $producto->getId()));

                    $p=[$idProduc,$_POST['cantidad']];

                    //array_push($p, $idProduc,$_POST['cantidad']);
                    $i = 0;
                    while(isset($_SESSION["pedido"][$i]) <> ''){
                        $i++;
                    }
                    $_SESSION["pedido"][$i] = $p;


                }else{
                    $em = $this->getDoctrine()->getManager();
                    $idProduc=$em->getRepository('AppBundle:Producto')
                        ->findOneBy(array('id' => $producto->getId()));
                    $p[]=[$idProduc,$_POST['cantidad']];
                    $_SESSION['pedido']=$p;
                }

            }

            $em = $this->getDoctrine()->getManager();

            //$tipoProducto=new TipoProducto();
            $tipoProducto=$em->getRepository('AppBundle:TipoProducto')
                ->findOneBy(array('id' =>$_SESSION['tipoProducto']));
            $producto = $em->getRepository('AppBundle:Producto')
                ->findBy(array('tipo' => $tipoProducto->getTipo()));

            return $this->render(':productos:ver_productos.html.twig', [
                'producto' => $producto,
                'tipoProducto'=>$tipoProducto
            ]);

        }

    }

    /**
     * @Route("/modificar/{pedido}", name="pedido_modificar")
     */
    public function modificarAction(Request $peticion, Pedido $pedido)
    {
        // Crear el formulario a partir de la clase
        $formulario = $this->createForm(new PedidoType(), $pedido);

        // Procesar el formulario si se ha enviado con un POST
        $formulario->handleRequest($peticion);

        // Si se ha enviado y el contenido es válido, guardar los cambios
        if ($formulario->isSubmitted() && $formulario->isValid()) {

            // Obtener el EntityManager
            $em = $this->getDoctrine()->getManager();

            /*$helper =  $password = $this->container->get('security.password_encoder');
            $usuario->setPassword($helper->encodePassword($usuario, $usuario->getPassword()));*/

            // Asegurarse de que se tiene en cuenta el nuevo pedido
            $em->persist($pedido);
            // Guardar los cambios
            $em->flush();

            // Redirigir al usuario a la lista
            return new RedirectResponse(
                $this->generateUrl('pedido_listar')
            );
        }
        return $this->render(':pedido:modificar_pedido.html.twig', [
            'formulario' => $formulario->createView()
        ]);
    }


}
