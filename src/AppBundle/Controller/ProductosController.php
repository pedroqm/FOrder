<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Producto;
use AppBundle\Form\Type\ProductoType;
use AppBundle\Entity\TipoProducto;
use AppBundle\Entity\Almacen;
use AppBundle\Form\Type\AlmacenType;
use AppBundle\Entity\Ingredientes;
use AppBundle\Form\Type\IngredienteType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/productos")
 */
class ProductosController extends Controller
{
    /**
     * @Route("/listar", name="producto_listar")
     */
    public function listarAction()
    {
        $em = $this->getDoctrine()->getManager();

        $usuario = $this->getUser();
        $producto = $em->getRepository('AppBundle:Producto')
            ->findAll();
        $mesa=$em->getRepository('AppBundle:Mesa')->findOneBy(array('id'=>1));
        $tipoProducto="";
        $pedido=null;

        return $this->render(':productos:ver_productos.html.twig', [
            'producto' => $producto,
            'tipoProducto'=>$tipoProducto,
            'usuarios'=>$usuario,
            'mesa'=>$mesa,
            'pedido'=>$pedido

        ]);
    }
    /**
     * @Route("/tipo_producto/{tipoProducto}", name="ver_productos"), methods={'GET', 'POST'}
     */
    public function verProductosAction(TipoProducto $tipoProducto)
    {

        $total=0;

        if(isset($_SESSION['pedido'])){
            if($_SESSION['pedido']!=''){
                $pedido=$_SESSION['pedido'];
                for ($j = 0; $j < count($pedido); $j++) {
                    $total = $total + ($pedido[$j][0]->getPrecio() * $pedido[$j][1]);
                }
            }else{
                $total=0;
                $pedido=null; //buscar los pedidos realizados y no pagados en la base de datos
            }
            $em = $this->getDoctrine()->getManager();
            $mesa=$em->getRepository('AppBundle:Mesa')->findOneBy(array('id'=>1));


        }else{    //Se muestra la cuenta sin pedidos.

            $em = $this->getDoctrine()->getManager();
            $mesa=$em->getRepository('AppBundle:Mesa')->findOneBy(array('id'=>1));
            $pedido=null;

        }



        $usuario = $this->getUser();

            $em = $this->getDoctrine()->getManager();

            $producto = $em->getRepository('AppBundle:Producto')
                ->findBy(array('tipo' => $tipoProducto->getTipo()));

            $_SESSION['tipoProducto']=$tipoProducto;

            if($usuario->getEsAdmin()==false){
                return $this->render(':productos:ver_productos.html.twig', [
                    'producto' => $producto,
                    'tipoProducto'=>$tipoProducto,
                    'usuarios'=>$usuario,
                    'total'=>$total,
                    'mesa'=>$mesa,
                    'pedido'=>$pedido
                ]);
            }else{
                return $this->render(':productos:listar_productos.html.twig', [
                    'producto' => $producto,
                    'tipoProducto'=>$tipoProducto,
                    'usuarios'=>$usuario,
                    'mesa'=>$mesa,
                    'pedido'=>$pedido
                ]);
            }


    }
    /**
     * @Route("/nuevo", name="producto_nuevo")
     */
    public function nuevoAction(Request $peticion)
    {
        $producto = new Producto();

        // Crear el formulario a partir de la clase
        $formulario = $this->createForm(new ProductoType(), $producto);

        // Procesar el formulario si se ha enviado con un POST
        $formulario->handleRequest($peticion);

        // Si se ha enviado y el contenido es válido, guardar los cambios
       if ($formulario->isSubmitted() && $formulario->isValid()) {

           // Obtener el EntityManager
           $em = $this->getDoctrine()->getManager();

           // Asegurarse de que se tiene en cuenta el nuevo pedido
           $em->persist($producto);
           // Guardar los cambios
           $em->flush();


           // Redirigir al usuario a la lista
           return new RedirectResponse(
               $this->generateUrl('inicio')
           );
       }
        return $this->render(':productos:nuevo_productos.html.twig' ,[
            'formulario' => $formulario->createView()
        ]);
    }
    /**
     * @Route("/modificar/{producto}", name="producto_modificar")
     */
    public function modificarAction(Request $peticion, Producto $producto)
    {
        // Crear el formulario a partir de la clase
        $formulario = $this->createForm(new ProductoType(), $producto);

        // Procesar el formulario si se ha enviado con un POST
        $formulario->handleRequest($peticion);

        // Si se ha enviado y el contenido es válido, guardar los cambios
        if ($formulario->isSubmitted() && $formulario->isValid()) {

            // Obtener el EntityManager
            $em = $this->getDoctrine()->getManager();

            // Asegurarse de que se tiene en cuenta el nuevo pedido
            $em->persist($producto);
            // Guardar los cambios
            $em->flush();

            // Redirigir al usuario a la lista
            return new RedirectResponse(
                $this->generateUrl('producto_listar')
            );
        }
        return $this->render(':productos:modificar_productos.html.twig', [
            'producto'=>$producto,
            'formulario' => $formulario->createView()
        ]);
    }

    /**
     * @Route("/eliminar/{producto}", name="producto_eliminar")
     */
    public function eliminarAction(Producto $producto)
    {
        //Eliminar producto

        if(isset($_POST['eliminar_produc'])){
            $em = $this->getDoctrine()->getManager();
            $em->remove($producto);
            $em->flush();


            return new RedirectResponse(
                $this->generateUrl('inicio')
            );
        }

    }


}
