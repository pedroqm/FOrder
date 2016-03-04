<?php

namespace AppBundle\Controller;

use AppBundle\Entity\DetallePedido;
use AppBundle\Entity\Mesa;
use AppBundle\Entity\Producto;
use AppBundle\Entity\Pedido;
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
        $em = $this->getDoctrine()->getManager();

        $usu = $em->getRepository('AppBundle:Usuario')
            ->findAll();
        if(!$usu){
            $usuario= new Usuario();
            $usuario->setNombreUsuario('admin');
            $usuario->setPass('admin');
            $usuario->setNombre('admin');
            $usuario->setApellidos('admin');
            $usuario->setDni('26502842B');
            $usuario->setTelefono(99);
            $usuario->setEmail('demo@demo.com');
            $usuario->setEsAdmin(true);
            $usuario->setEsCamarero(false);
            $usuario->setEsCliente(false);

            // Obtener el EntityManager
            $em = $this->getDoctrine()->getManager();

            $em->persist($usuario);
            // Guardar los cambios
            $em->flush();

        }

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
                    $this->get('session')->getFlashBag()->add(
                        'notice',
                        'Contraseña incorrecta.'
                    );


                }else{
                    $this->get('session')->getFlashBag()->add(
                        'notice',
                        'Usuario incorrecto.'
                    );
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
        session_start();
    if(isset($_SESSION['pedido'])){
        if($_SESSION['pedido']==''){
            $em = $this->getDoctrine()->getManager();
            $mesa=$em->getRepository('AppBundle:Mesa')->findOneBy(array('id'=>1));
            $producto=new Producto();
            $pedido=null;
            return $this->render('default/cuenta.html.twig',[
                'producto' => $producto,
                'mesa'=>$mesa,
                'pedido'=>$pedido
            ]);
        }
        $em = $this->getDoctrine()->getManager();

        $mesa=$em->getRepository('AppBundle:Mesa')->findOneBy(array('id'=>1));

        $pedido=$_SESSION['pedido'];
        $cantidad=$pedido[0][1];

        for($i=0; $i<count($pedido)-1; $i++){
            //fallo!!!
            //no se puede usar la entidad producto como array
            $producto=$em->getRepository('AppBundle:Producto')->findOneBy(array('id'=>$pedido[$i][0]));
        }


        return $this->render('default/cuenta.html.twig',[
            'producto' => $producto,
            'mesa'=>$mesa,
            'pedido'=>$pedido
        ]);
    }else{    //Se muestra la cuenta sin pedidos.

        $em = $this->getDoctrine()->getManager();
        $mesa=$em->getRepository('AppBundle:Mesa')->findOneBy(array('id'=>1));
        $producto=new Producto();
        $pedido=null;
        return $this->render('default/cuenta.html.twig',[
            'producto' => $producto,
            'mesa'=>$mesa,
            'pedido'=>$pedido
        ]);
    }
    }

    /**
     * @Route("/realizar_pedido", name="realizar_pedido")
     */
    public function realizarPedidoAction()
    {
        session_start();
        if(isset($_SESSION['pedido'])) {
            $em = $this->getDoctrine()->getManager();

            $pedido = $_SESSION['pedido'];


            $newPedido = new DetallePedido();
            $mesa = $em->getRepository('AppBundle:Mesa')->findOneBy(array('id' => 1));

            for ($i = 0; $i < count($pedido) - 1; $i++) {
                $producto = $em->getRepository('AppBundle:Producto')->findOneBy(array('id' => $pedido[$i][0]));

                //actualizamos la cuenta
                $precio = $producto->getPrecio();
                $cantidad = $pedido[$i][1];
                $cuenta=$mesa->getCuenta();
                $mesa->setCuenta($cuenta+$precio*$cantidad);


                $em->persist($mesa);
                // Guardar los cambios
                $em->flush();

                //creamos un nuevo pedido
                $pedidoRealizado=new Pedido();
                $pedidoRealizado->setEstado('pendiente');


                $em->persist($mesa);
                // Guardar los cambios
                $em->flush();




                $_SESSION['pedido']='';
            }
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
