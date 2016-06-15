<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Almacen;
use AppBundle\Form\Type\AlmacenType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/almacen")
 */
class AlmacenController extends Controller
{
    /**
     * @Route("/listar", name="almacen_listar")
     */
    public function listarAction()
    {
        $em = $this->getDoctrine()->getManager();

        $almacen = $em->getRepository('AppBundle:Almacen')
            ->findAll();

        return $this->render(':almacen:listar_almacen.html.twig', [
            'almacen' => $almacen
        ]);
    }


    /**
     * @Route("/nuevo", name="almacen_nuevo")
     */
    public function nuevoAction(Request $peticion)
    {
        $almacen = new Almacen();

        // Crear el formulario a partir de la clase
        $formulario = $this->createForm(new AlmacenType(), $almacen);

        // Procesar el formulario si se ha enviado con un POST
        $formulario->handleRequest($peticion);

        // Si se ha enviado y el contenido es v�lido, guardar los cambios
       if ($formulario->isSubmitted() && $formulario->isValid()) {

           // Obtener el EntityManager
           $em = $this->getDoctrine()->getManager();

           // Asegurarse de que se tiene en cuenta el nuevo pedido
           $em->persist($almacen);
           // Guardar los cambios
           $em->flush();

           // Redirigir al usuario a la lista
           return new RedirectResponse(
               $this->generateUrl('almacen_listar')
           );
       }
        return $this->render(':almacen:nuevo_almacen.html.twig' ,[
            'formulario' => $formulario->createView()
        ]);
    }
    /**
     * @Route("/modificar/{almacen}", name="almacen_modificar")
     */
    public function modificarAction(Request $peticion, Almacen $almacen)
    {
        // Crear el formulario a partir de la clase
        $formulario = $this->createForm(new AlmacenType(), $almacen);

        // Procesar el formulario si se ha enviado con un POST
        $formulario->handleRequest($peticion);

        // Si se ha enviado y el contenido es v�lido, guardar los cambios
        if ($formulario->isSubmitted() && $formulario->isValid()) {

            // Obtener el EntityManager
            $em = $this->getDoctrine()->getManager();

            // Asegurarse de que se tiene en cuenta el nuevo pedido
            $em->persist($almacen);
            // Guardar los cambios
            $em->flush();

            // Redirigir al usuario a la lista
            return new RedirectResponse(
                $this->generateUrl('almacen_listar')
            );
        }
        return $this->render(':almacen:modificar_almacen.html.twig', [
            'formulario' => $formulario->createView()
        ]);
    }


}