<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Usuario;
use AppBundle\Form\Type\UsuarioType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/usuario")
 */
class UsuarioController extends Controller
{
    /**
     * @Route("/listar", name="usuarios_listar")
     */
    public function listarAction()
    {
        $em = $this->getDoctrine()->getManager();

        $usuarios = $em->getRepository('AppBundle:Usuario')
            ->findAll();

        return $this->render(':usuario:listar_usuario.html.twig', [
            'usuarios' => $usuarios
        ]);
    }
    /**
     * @Route("/nuevaCuenta", name="nueva_cuenta")
     */
    public function nuevaCuentaAction(Request $peticion)
    {
        $usuario = new Usuario();

        // Crear el formulario a partir de la clase
        $formulario = $this->createForm(new UsuarioType(), $usuario);

        // Procesar el formulario si se ha enviado con un POST
        $formulario->handleRequest($peticion);

        // Si se ha enviado y el contenido es válido, guardar los cambios
        if ($formulario->isSubmitted() && $formulario->isValid()) {
            $usuario->setEsAdmin(false);
            $usuario->setEsCamarero(false);
            $usuario->setEsCliente(true);

            // Obtener el EntityManager
            $em = $this->getDoctrine()->getManager();

            $em->persist($usuario);
            // Guardar los cambios
            $em->flush();

            // Redirigir al usuario a la lista
            return new RedirectResponse(
                $this->generateUrl('entrar')
            );
        }
        return $this->render(':usuario:nueva_cuenta.html.twig' ,[
            'usuario' => $usuario,
            'formulario' => $formulario->createView()
        ]);
    }

    /**
     * @Route("/nuevo", name="usuarios_nuevo")
     */
    public function nuevoAction(Request $peticion)
    {
        $usuario = new Usuario();

        // Crear el formulario a partir de la clase
        $formulario = $this->createForm(new UsuarioType(), $usuario);

        // Procesar el formulario si se ha enviado con un POST
        $formulario->handleRequest($peticion);

        // Si se ha enviado y el contenido es válido, guardar los cambios
       if ($formulario->isSubmitted() && $formulario->isValid()) {

           // Obtener el EntityManager
           $em = $this->getDoctrine()->getManager();

           $em->persist($usuario);
           // Guardar los cambios
           $em->flush();

           // Redirigir al usuario a la lista
           return new RedirectResponse(
               $this->generateUrl('usuarios_listar')
           );
       }
        return $this->render(':usuario:nuevo_usuario.html.twig' ,[
            'usuario' => $usuario,
            'formulario' => $formulario->createView()
        ]);
    }
    /**
     * @Route("/modificar/{usuario}", name="usuarios_modificar")
     */
    public function modificarAction(Request $peticion, Usuario $usuario)
    {
        // Crear el formulario a partir de la clase
        $formulario = $this->createForm(new UsuarioType(), $usuario);

                // Procesar el formulario si se ha enviado con un POST
                $formulario->handleRequest($peticion);

                // Si se ha enviado y el contenido es válido, guardar los cambios
                if ($formulario->isSubmitted() && $formulario->isValid()) {

                    // Obtener el EntityManager
                    $em = $this->getDoctrine()->getManager();

                    // Asegurarse de que se tiene en cuenta el nuevo usuario
                    $em->persist($usuario);
                    // Guardar los cambios
                    $em->flush();
                    // Redirigir al usuario a la lista
                    return new RedirectResponse(
                        $this->generateUrl('usuarios_listar')
                    );
            }


        return $this->render(':usuario:modificar_usuario.html.twig', [
            'usuario' => $usuario,
            'formulario' => $formulario->createView()
        ]);
    }


    /**
     * @Route("/cambiar/{usuario}", name="cambiarRol")
     */
    public function cambiarAction(Request $peticion, Usuario $usuario)
    {
        if(isset($_POST['cambiar'])) {
            // Obtener el EntityManager
            $em = $this->getDoctrine()->getManager();

            if($_POST['rol']=='admin') {
                $usuario->setEsAdmin(true);
                $usuario->setEsCamarero(false);
                $usuario->setEsCliente(false);
            }
            if($_POST['rol']=='servicio') {
                $usuario->setEsAdmin(false);
                $usuario->setEsCamarero(true);
                $usuario->setEsCliente(false);
            }
            if($_POST['rol']=='cliente') {
                $usuario->setEsAdmin(false);
                $usuario->setEsCamarero(false);
                $usuario->setEsCliente(true);
            }
                // Asegurarse de que se tiene en cuenta el nuevo usuario
                $em->persist($usuario);
                // Guardar los cambios
                $em->flush();
        }



            // Redirigir al usuario a la lista
            return new RedirectResponse(
                $this->generateUrl('usuarios_listar')
            );
    }


    /**
     * @Route("/eliminar/{usuario}", name="usuarios_eliminar")
     */
    public function eliminarAction(Usuario $usuario)
    {

        //Eliminar usuario

        if(isset($_POST['eliminar_user'])){
            $em = $this->getDoctrine()->getManager();
            $em->remove($usuario);
            $em->flush();


            // Redirigir al usuario a la lista
            return new RedirectResponse(
                $this->generateUrl('usuarios_listar')
            );
        }

    }

}
