<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Producto;
use AppBundle\Form\Type\ProductoType;
use AppBundle\Entity\Usuario;
use AppBundle\Form\Type\UsuarioType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

class DefaultController extends Controller
{
    /**
     * @Route("/entrar", name="usuario_entrar")
     * @Route("/", name="entrar")
     */
    public function loginAction()
    {


        //comprobar usuario
        if(isset($_POST['user'])){
            $nombre=$_POST['username'];
            $contraseña=$_POST['password'];

            $em = $this->getDoctrine()->getManager();
            $usu= new Usuario();
            if( $usu = $em->getRepository('AppBundle:Usuario')
                ->findOneBy(array('nombreUsuario'=>$nombre,'pass'=>$contraseña)))
            {


                // Get the global context

                session_start();
                $_SESSION['id']=$em->getRepository('AppBundle:Usuario')
                    ->findOneBy(array('nombreUsuario'=>$nombre,'pass'=>$contraseña))
                    ->getId();
                if($em->getRepository('AppBundle:Usuario')
                    ->findOneBy(array('nombreUsuario'=>$nombre,'pass'=>$contraseña))
                    ->getEsAdmin()==true){
                    return $this->render(':default:administracion.html.twig');


                }else{
                    $em = $this->getDoctrine()->getManager();

                    $usuario=$em->getRepository('AppBundle:Usuario')->findOneBy(array('id'=>$_SESSION['id']));

                    $tipoProducto = $em->getRepository('AppBundle:TipoProducto')
                        ->findAll();
                    return $this->render(':default:inicio.html.twig', [
                        'tipoProducto' => $tipoProducto,
                        'usuarios'=>$usuario
                    ]);
                }



            }else{
                if( $usu = $em->getRepository('AppBundle:Usuario')
                    ->findOneBy(array('nombreUsuario'=>$nombre))){
                    $this->addFlash('danger', 'Ha habido un error, compruebe los datos introducidos');
                    echo "<div id='error' class='alert alert-danger alert-dismissable col-md-6 col-md-offset-3 col-sm-8 col-sm-offset-2'>contraseña incorrecta</div>";

                    $this->get('session')->getFlashBag()->add('danger', 'Ha habido un error, compruebe los datos introducidos');
                    //throw $this->createNotFoundException('contraseña incorrecta');

                }else{
                    echo "<div id='error' class='alert alert-danger alert-dismissable col-md-6 col-md-offset-3 col-sm-8 col-sm-offset-2'>El usuario no existe</div>";
                }

            }
        }

        return $this->render(':default:formulario.html.twig');
    }

    /**
     * @Route("/admin", name="administracion")
     */
    public function adminAction()
    {
        session_start();
        if (!isset($_SESSION['id'])) {
            return $this->render(':default:formulario.html.twig');
        }else{
            $em = $this->getDoctrine()->getManager();

            $usuario=$em->getRepository('AppBundle:Usuario')->findOneBy(array('id'=>$_SESSION['id']));
            if($usuario->getEsAdmin()==false){
                return $this->render(':default:inicio.html.twig');
            }else{
                return $this->render(':default:administracion.html.twig');
            }
        }





    }
    /**
     * @Route("/inicio", name="inicio")
     */
    public function indexAction()
    {
        session_start();
        //si no se a iniciado una sesion se manda al usuario al formulario
        if (!isset($_SESSION['id'])) {
            return $this->render(':default:formulario.html.twig');
        }
        $em = $this->getDoctrine()->getManager();

        $usuario=$em->getRepository('AppBundle:Usuario')->findOneBy(array('id'=>$_SESSION['id']));

        $tipoProducto = $em->getRepository('AppBundle:TipoProducto')
            ->findAll();

        return $this->render(':default:inicio.html.twig', [
            'tipoProducto' => $tipoProducto,
            'usuarios'=> $usuario
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

    /**
     * @Route("/salir", name="salir")
     */
    public function salirAction()
    {
       session_start();
        if (isset($_SESSION['id'])) {
            session_destroy();
        }
        // replace this example code with whatever you need
        return $this->render('default/formulario.html.twig', array());
    }

    }
