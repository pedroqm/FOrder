<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Ingredientes;
use AppBundle\Form\Type\IngredienteType;
use AppBundle\Entity\Producto;
use AppBundle\Form\Type\ProductoType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Config\Tests\Definition\IntegerNodeTest;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/ingredientes")
 */
class IngredientesController extends Controller
{

    /**
     * @Route("/receta/{id}", name="ver_receta"), methods={'GET', 'POST'}
     */
    public function verRecetaAction(Producto $id)
    {

        if(isset($_POST['ver_receta'])){

            $em = $this->getDoctrine()->getManager();

            $ingrediente = $em->getRepository('AppBundle:Ingredientes')
                ->findBy(array('nombreProducto' => $id->getNombreProducto()));

            return $this->render(':ingredientes:listar_ingredientes.html.twig', [
                'ingrediente' => $ingrediente
            ]);
        }
    }
    /**
     * @Route("/listar", name="ingrediente_listar")
     */
    public function listarAction()
    {
        $em = $this->getDoctrine()->getManager();

        $ingrediente = $em->getRepository('AppBundle:Ingredientes')
            ->findAll();

        return $this->render(':ingredientes:listar_ingredientes.html.twig', [
            'ingrediente' => $ingrediente
        ]);
    }


    /**
     * @Route("/nuevo", name="ingrediente_nuevo")
     */
    public function nuevoAction(Request $peticion)
    {
        $ingrediente= new Ingredientes();

        // Crear el formulario a partir de la clase
        $formulario = $this->createForm(new IngredienteType(), $ingrediente);

        // Procesar el formulario si se ha enviado con un POST
        $formulario->handleRequest($peticion);

        // Si se ha enviado y el contenido es válido, guardar los cambios
       if ($formulario->isSubmitted() && $formulario->isValid()) {

           // Obtener el EntityManager
           $em = $this->getDoctrine()->getManager();

           // Asegurarse de que se tiene en cuenta el nuevo pedido
           $em->persist($ingrediente);
           // Guardar los cambios
           $em->flush();

           // Redirigir al usuario a la lista
           return new RedirectResponse(
               $this->generateUrl('ingrediente_listar')
           );
       }
        return $this->render(':ingredientes:nuevo_ingredientes.html.twig',[
            'formulario' => $formulario->createView()
        ]);
    }
    /**
     * @Route("/modificar/{ingrediente}", name="ingrediente_modificar")
     */
    public function modificarAction(Request $peticion, Ingredientes $ingrediente)
    {

        // Crear el formulario a partir de la clase
        $formulario = $this->createForm(new IngredienteType(), $ingrediente);

        // Procesar el formulario si se ha enviado con un POST
        $formulario->handleRequest($peticion);

        // Si se ha enviado y el contenido es válido, guardar los cambios
        if ($formulario->isSubmitted() && $formulario->isValid()) {

            // Obtener el EntityManager
            $em = $this->getDoctrine()->getManager();

            // Asegurarse de que se tiene en cuenta el nuevo pedido
            $em->persist($ingrediente);
            // Guardar los cambios
            $em->flush();

            // Redirigir al usuario a la lista
            return new RedirectResponse(
                $this->generateUrl('ingrediente_listar')
            );
        }
        return $this->render(':ingredientes:modificar_ingredientes.html.twig', [
            'formulario' => $formulario->createView()
        ]);
    }



}
