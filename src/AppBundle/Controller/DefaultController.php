<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Producto;
use AppBundle\Form\Type\ProductoType;
use AppBundle\Entity\Usuario;
use AppBundle\Form\Type\UsuarioType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    /**
     * @Route("/entrar", name="formulario")
     */
    public function loginAction()
    {
        $user = $this->getUser();
        $em = $this->getDoctrine()->getManager();


        return $this->render(':default:formulario.html.twig',
            [
                'usuario' => $user
            ]);
    }

    /**
     * @Route("/admin", name="administracion")
     */
    public function adminAction()
    {
        return $this->render(':default:administracion.html.twig');
    }
    /**
     * @Route("/", name="inicio")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $usuarios = $em->getRepository('AppBundle:Usuario')
            ->createQueryBuilder('u')
            ->orderBy('u.nombreUsuario')
            ->getQuery()
            ->getResult();

        $em = $this->getDoctrine()->getManager();

        $tipoProducto = $em->getRepository('AppBundle:TipoProducto')
            ->findAll();

        return $this->render(':default:inicio.html.twig', [
            'tipoProducto' => $tipoProducto
        ]);
    }
    /**
     * @Route("/cuenta", name="cuenta")
*/
    public function cuentaAction()
    {
        return $this->render('default/cuenta.html.twig');
    }
    /**
     * @Route("/camarero", name="camarero")
     */
    public function camareroAction()
    {
        return $this->render('camarero/inicioCamarero.html.twig');
    }


    }
