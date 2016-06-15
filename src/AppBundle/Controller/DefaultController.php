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
     * @Route("/instalar", name="instalar")
     */
    public function instalarAction()
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
        return $this->render(':default:formulario.html.twig');

    }

    /**
     * @Route("/entrar", name="usuario_entrar")
     */
    public function loginAction()
    {
        return $this->render(':default:formulario.html.twig');
    }

    /**
     * @Route("/comprobar", name="usuario_comprobar")
     */
    public function comprobarAction()
    {
    }

    /**
     * @Route("/admin", name="administracion")
     * @Security("is_granted('ROLE_ADMIN')")
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

        $usuario = $this->getUser();

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
        $session = $this->get('session');

        if(isset($_SESSION['pedido'])){
            if($_SESSION['pedido']==''){
                $pedido=null;
            }else {
                $pedido=$_SESSION['pedido'];

                /*
                //HAY QUE ALMACENAR LA INFORMACION EN DETALLE PEDIDO!!!! idPedido, cantidad y NombreProducto
                for ($i = 0; $i < count($pedido) - 1; $i++) {

                    //$idproducto=$em->getRepository('AppBundle:Producto')->findOneBy(array('id'=>$pedido[$i][0]));
                }*/

            }
            $em = $this->getDoctrine()->getManager();
            $mesa=$em->getRepository('AppBundle:Mesa')->findOneBy(array('id'=>1));

            $producto=new Producto();
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
        if(isset($_SESSION['pedido'])) {
            if($_SESSION['pedido']!=''){


            $em = $this->getDoctrine()->getManager();

            $pedido = $_SESSION['pedido'];


            $newPedido = new DetallePedido();
            $mesa = $em->getRepository('AppBundle:Mesa')->findOneBy(array('id' => 1));

            for ($i = 0; $i < count($pedido); $i++) {
                $producto = $em->getRepository('AppBundle:Producto')->findOneBy(array('id' => $pedido[$i][0]));

                //actualizamos la cuenta
                $precio = $producto->getPrecio();
                $cantidad = $pedido[$i][1];
                $cuenta = $mesa->getCuenta();
                $mesa->setCuenta($cuenta + $precio * $cantidad);


                $em->persist($mesa);
                // Guardar los cambios
                $em->flush();

                //creamos un nuevo pedido
                $pedidoRealizado = new Pedido();
                $pedidoRealizado->setEstado('pendiente');


                $em->persist($mesa);
                // Guardar los cambios
                $em->flush();


                $_SESSION['pedido'] = '';
            }
            }
        }
        if(isset($_SESSION['pedido'])){
            if($_SESSION['pedido']==''){
                $pedido=null;
            }else {
                $pedido=$_SESSION['pedido'];

                /*
                //HAY QUE ALMACENAR LA INFORMACION EN DETALLE PEDIDO!!!! idPedido, cantidad y NombreProducto
                for ($i = 0; $i < count($pedido) - 1; $i++) {

                    //$idproducto=$em->getRepository('AppBundle:Producto')->findOneBy(array('id'=>$pedido[$i][0]));
                }*/

            }
            $em = $this->getDoctrine()->getManager();
            $mesa=$em->getRepository('AppBundle:Mesa')->findOneBy(array('id'=>1));

            $producto=new Producto();
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
        /*return $this->render(':default:inicio.html.twig', [
            'tipoProducto' => $tipoProducto,
            'usuarios'=> $usuario
        ]);*/
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
       /*session_start();
        if (isset($_SESSION['id'])) {
            session_destroy();
        }*/
        // replace this example code with whatever you need
        return $this->render('default/formulario.html.twig', array());
    }

    }
