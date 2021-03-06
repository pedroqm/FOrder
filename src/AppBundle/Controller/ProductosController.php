<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Producto;
use AppBundle\Form\Type\ProductoType;
use AppBundle\Entity\TipoProducto;
use AppBundle\Entity\Almacen;
use AppBundle\Entity\Ingredientes;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bridge\Doctrine\Tests\Form\Type\EntityTypeTest;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;


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
        $mesa=$em->getRepository('AppBundle:Mesa')->findOneById($this->getUser()->getMesaOcupada());
        $pedido=null;
        if($producto) {
            $min=null;
            //buscar existencias
            for ($i = 0; $i < count($producto); $i++) {
                $existencias=0;
                $min=null;
                $ingrediente = $em->getRepository('AppBundle:Ingredientes')->findBy(array('nombreProducto' => $producto[$i]->getNombreProducto()));
                for ($j = 0; $j < count($ingrediente); $j++) {
                    $stock = $ingrediente[$j]->getAlmacenado();
                    $cantidadI = $ingrediente[$j]->getCantidad();
                    $auxExistencias = $stock->getStock() / $cantidadI;
                    if (!$min) {
                        $min = $auxExistencias;
                    } else {
                        if ($min > $auxExistencias) {
                            $min = $auxExistencias;
                        }
                    }
                }
                $existencias = $min;

                $producto[$i]->setExistencias($existencias);

                $em->persist($producto[$i]);
                // Guardar los cambios
                $em->flush();


            }
        }

        return $this->render(':productos:ver_productos.html.twig', [
            'producto' => $producto,
            'usuarios'=>$usuario,
            'mesa'=>$mesa,
            'pedido'=>$pedido

        ]);
    }
    /**
     * @Route("/tipo_producto/{tipoProducto}", name="ver_productos"), methods={'GET', 'POST'}
     */
    public function verProductosAction($tipoProducto)
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
                $pedido=null;
            }

        }else{    //Se muestra la cuenta sin pedidos.
            $pedido=null;
        }

        $em = $this->getDoctrine()->getManager();
        $mesa=$em->getRepository('AppBundle:Mesa')->findOneById($this->getUser()->getMesaOcupada());

        $usuario = $this->getUser();


        $producto = $em->getRepository('AppBundle:Producto')
                ->findBy(array('tipo' => $tipoProducto));


        if($producto) {
            $min=null;
            //buscar existencias
            for ($i = 0; $i < count($producto); $i++) {
                $existencias=0;
                $min=null;
                $ingrediente = $em->getRepository('AppBundle:Ingredientes')->findBy(array('nombreProducto' => $producto[$i]->getNombreProducto()));

                for ($j = 0; $j < count($ingrediente); $j++) {
                    $stock = $ingrediente[$j]->getAlmacenado();
                    $cantidadI = $ingrediente[$j]->getCantidad();
                    $auxExistencias = $stock->getStock() / $cantidadI;
                    if (!$min) {
                        $min = $auxExistencias;
                    } else {
                        if ($min > $auxExistencias) {
                            $min = $auxExistencias;
                        }
                    }
                }
                if(!$min){
                    $min=0;
                }
                $existencias = $min;

                $producto[$i]->setExistencias($existencias);

                $em->persist($producto[$i]);
                // Guardar los cambios
                $em->flush();


            }
        }

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
     * @Security("is_granted('ROLE_ADMIN')")
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

           $producto->setExistencias(0);

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
     * @Security("is_granted('ROLE_ADMIN')")
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
                $this->generateUrl('inicio')
            );
        }
        return $this->render(':productos:modificar_productos.html.twig', [
            'producto'=>$producto,
            'formulario' => $formulario->createView()
        ]);
    }

    /**
     * @Route("/eliminar/{producto}", name="producto_eliminar")
     * @Security("is_granted('ROLE_ADMIN')")
     */
    public function eliminarAction(Producto $producto)
    {
        //Eliminar producto

        if(isset($_POST['eliminar_produc'])){
            $em = $this->getDoctrine()->getManager();
            $em->remove($producto);
            $em->flush();

        }
        return new RedirectResponse(
            $this->generateUrl('inicio')
        );

    }


}
