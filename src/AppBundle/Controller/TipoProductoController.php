<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Producto;
use AppBundle\Form\Type\ProductoType;
use AppBundle\Entity\TipoProducto;
use AppBundle\Form\Type\TipoProductoType;
use AppBundle\Entity\Almacen;
use AppBundle\Form\Type\AlmacenType;
use AppBundle\Entity\Ingredientes;
use AppBundle\Form\Type\IngredienteType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/tipoProducto")
 */
class TipoProductoController extends Controller
{
    /**
     * @Route("/listar", name="listar_carta")
     */
    public function listarAction()
    {
        $em = $this->getDoctrine()->getManager();

        $TipoProducto = $em->getRepository('AppBundle:TipoProducto')
            ->findAll();


        return $this->render(':carta:listar_carta.html.twig', [
            'TipoProducto' => $TipoProducto
        ]);
    }


    /**
     * @Route("/nuevo", name="carta_nueva")
     */
    public function nuevoAction(Request $peticion)
    {
        $TipoProducto = new TipoProducto();

        // Crear el formulario a partir de la clase
        $formulario = $this->createForm(new TipoProductoType(),  $TipoProducto);

        // Procesar el formulario si se ha enviado con un POST
        $formulario->handleRequest($peticion);

        // Si se ha enviado y el contenido es válido, guardar los cambios
        if ($formulario->isSubmitted() && $formulario->isValid()) {

            // Obtener el EntityManager
            $em = $this->getDoctrine()->getManager();

            // Asegurarse de que se tiene en cuenta el nuevo pedido
            $em->persist($TipoProducto);
            // Guardar los cambios
            $em->flush();

            /* $tipo= new TipoProducto();
             $em = $this->getDoctrine()->getManager();
            $tipo->setTipo($producto->getTipo());

             $em->persist($tipo);
             // Guardar los cambios
             $em->flush();
             */

            // Redirigir al usuario a la lista
            return new RedirectResponse(
                $this->generateUrl('listar_carta')
            );
        }
        return $this->render(':carta:nuevo_carta.html.twig' ,[
            'formulario' => $formulario->createView()
        ]);
    }
    /**
     * @Route("/modificar/{TipoProducto}", name="carta_modificar")
     */
    public function modificarAction(Request $peticion, TipoProducto $TipoProducto)
    {
        // Crear el formulario a partir de la clase
        $formulario = $this->createForm(new TipoProductoType(), $TipoProducto);

        // Procesar el formulario si se ha enviado con un POST
        $formulario->handleRequest($peticion);

        // Si se ha enviado y el contenido es válido, guardar los cambios
        if ($formulario->isSubmitted() && $formulario->isValid()) {

            // Obtener el EntityManager
            $em = $this->getDoctrine()->getManager();

            // Asegurarse de que se tiene en cuenta el nuevo pedido
            $em->persist($TipoProducto);
            // Guardar los cambios
            $em->flush();

            // Redirigir al usuario a la lista
            return new RedirectResponse(
                $this->generateUrl('listar_carta')
            );
        }
        return $this->render(':carta:modificar_carta.html.twig', [
            'TipoProducto'=>$TipoProducto,
            'formulario' => $formulario->createView()
        ]);
    }

    /**
     * @Route("/eliminar/{TipoProducto}", name="carta_eliminar")
     */
    public function eliminarAction(TipoProducto $TipoProducto)
    {
        //Eliminar producto

        if(isset($_POST['eliminar_categoria'])){
            $em = $this->getDoctrine()->getManager();
            $em->remove($TipoProducto);
            $em->flush();


            return new RedirectResponse(
                $this->generateUrl('listar_carta')
            );
        }

    }


}
