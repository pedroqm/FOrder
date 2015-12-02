<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    /**
     * @Route("/entrada", name="formulario")
     */
    public function loginAction()
    {
        return $this->render('default/formulario.html.twig');
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
        return $this->render('index.html.twig', [
        'usuarios' => $usuarios
    ]);
    }
    /**
     * @Route("/cuenta", name="cuenta2")
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
