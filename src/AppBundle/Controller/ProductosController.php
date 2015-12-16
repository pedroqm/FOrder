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

        $producto = $em->getRepository('AppBundle:Producto')
            ->findAll();


        return $this->render(':productos:listar_productos.html.twig', [
            'producto' => $producto
        ]);
    }


    /**
     * @Route("/tipo_producto/{tipoProducto}", name="ver_productos"), methods={'GET', 'POST'}
     */
    public function verRecetaAction(TipoProducto $tipoProducto)
    {
        session_start();
        if(isset($_POST['ver_pro'])){

            $em = $this->getDoctrine()->getManager();

            $usuario=$em->getRepository('AppBundle:Usuario')->findOneBy(array('id'=>$_SESSION['id']));
            $em = $this->getDoctrine()->getManager();

            $producto = $em->getRepository('AppBundle:Producto')
                ->findBy(array('tipo' => $tipoProducto->getTipo()));

            if($usuario->getEsAdmin()==false){
                return $this->render(':productos:ver_productos.html.twig', [
                    'producto' => $producto,
                    'tipoProducto'=>$tipoProducto,
                    'usuarios'=>$usuario
                ]);
            }else{
                return $this->render(':productos:listar_productos.html.twig', [
                    'producto' => $producto,
                    'tipoProducto'=>$tipoProducto,
                    'usuarios'=>$usuario
                ]);
            }




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
               $this->generateUrl('producto_listar')
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
