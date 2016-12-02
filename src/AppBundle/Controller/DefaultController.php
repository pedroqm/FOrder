<?php
namespace AppBundle\Controller;
use AppBundle\Entity\DetallePedido;
use AppBundle\Entity\FacturaNoPagada;
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
     */
    public function loginAction()
    {
        $helper = $this->get('security.authentication_utils');

        return $this->render(':default:formulario.html.twig',
            [
                'ultimo_usuario' => $helper->getLastUsername(),
                'error' => $helper->getLastAuthenticationError()
            ]);
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
     * @Route("/mesas", name="mesas")
     * @Security("is_granted('ROLE_CAMARERO')")
     */
    public function servicioAction()
    {
        return $this->render(':mesa:listar_mesa.html.twig');
    }
    /**
     * @Route("/", name="inicio")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $usuario = $this->getUser();
        $cliente = $usuario->getEsCliente();
        $mesas = $em->getRepository('AppBundle:Mesa')
            ->findAll();

        if (isset($_POST['ocupar'])) {

            $usuario = $this->getUser()->setMesaOcupada($_POST['Nmesa']);
            $mesa=$em->getRepository('AppBundle:Mesa')->findOneById($_POST['Nmesa'])->setEstado("ocupado");
            $mesa->setUser($this->getUser());
            $em->persist($usuario);
            $em->persist($mesa);
            // Guardar los cambios
            $em->flush();

        }

            if ($cliente) {
                $tipoProducto = $em->getRepository('AppBundle:TipoProducto')
                    ->findAll();


                // $prueba=$em->getRepository('AppBundle:Producto')->findBy(array('ingredientes'=> $produc->getIngredientes()));
                $prueba = $em->getRepository('AppBundle:Ingredientes')->findBy(array('nombreProducto' => 'bocadillo de lomo')); //funciona
                //$produc = $em->getRepository('AppBundle:Producto')->findby(array('id'=>4));
                // $prueba=$em->getRepository('AppBundle:Ingredientes')->findBy(array('producto'=>getProducto(1)));







                return $this->render(':default:inicio.html.twig', [
                    'tipoProducto' => $tipoProducto,
                    'mesa' => $mesas,
                    'prueba' => $prueba, //---------------------------------------------------------------------------
                    'usuarios' => $usuario
                ]);
            } else {
                $camarero = $usuario->getEsCamarero();
                if ($camarero) {
                    if($this->getUser()->getMesaOcupada()==0){
                        $em = $this->getDoctrine()->getManager();
                        $pedidoRealizado = $em->getRepository('AppBundle:Pedido')->findBy(array('estado' => array('pendiente','preparado')));
                        return $this->render(':mesa:listar_mesa.html.twig', [
                            'mesa' => $mesas,
                            'pedido' => $pedidoRealizado
                        ]);
                    }else{
                        $tipoProducto = $em->getRepository('AppBundle:TipoProducto')
                            ->findAll();

                        return $this->render(':default:inicio.html.twig', [
                            'tipoProducto' => $tipoProducto,
                            'mesa' => $mesas,
                            'usuarios' => $usuario
                        ]);
                    }

                } else {
                    $tipoProducto = $em->getRepository('AppBundle:TipoProducto')
                        ->findAll();
                    return $this->render(':default:inicio.html.twig', [
                        'tipoProducto' => $tipoProducto,
                        'mesa' => $mesas,
                        'usuarios' => $usuario
                    ]);
                }
            }
    }

    /**
     * @Route("/camarero", name="inicioCamarero")
     */
    public function inicioCamareroAction()
    {
        $em = $this->getDoctrine()->getManager();

        $usuario = $this->getUser();
        $mesas = $em->getRepository('AppBundle:Mesa')
            ->findAll();
        $this->getUser()->setMesaOcupada(0);
        $em->flush();

        $pedidoRealizado = $em->getRepository('AppBundle:Pedido')->findBy(array('estado' => array('pendiente','preparado')));

        return $this->render(':mesa:listar_mesa.html.twig', [
            'mesa' => $mesas,
            'pedido' => $pedidoRealizado
        ]);
    }
    /**
     * @Route("/pedidoManual", name="pedidoManual")
     */
    public function pedManualAction()
    {
        $em = $this->getDoctrine()->getManager();
        $usuario = $this->getUser();
        $tipoProducto = $em->getRepository('AppBundle:TipoProducto')
            ->findAll();
        $mesas = $em->getRepository('AppBundle:Mesa')
            ->findAll();
        return $this->render(':default:inicio.html.twig', [
            'tipoProducto' => $tipoProducto,
            'mesa'=>$mesas,
            'usuarios'=> $usuario
        ]);
    }
    /**
     * @Route("/cuenta", name="cuenta")
     */
    public function cuentaAction()
    {
        $em = $this->getDoctrine()->getManager();
        $mesa=$em->getRepository('AppBundle:Mesa')->findOneById($this->getUser()->getMesaOcupada());
        $producto=new Producto();
        $total = 0;
        if(isset($_SESSION['pedido'])){
            if($_SESSION['pedido']==''){
                $pedido=null;
                $total=0;
            }else {
                $pedido = $_SESSION['pedido'];

                for ($j = 0; $j < count($pedido); $j++) {
                    $total = $total + ($pedido[$j][0]->getPrecio() * $pedido[$j][1]);
                }
            }
        }else{    //Se muestra la cuenta sin pedidos.
            $pedido=null;
            $total=0;
        }
        return $this->render('default/cuenta.html.twig',[
            'producto' => $producto,
            'mesa'=>$mesa,
            'total'=>$total,
            'pedido'=>$pedido
        ]);
    }

    /**
    * @Route("/ver_cuenta", name="ver_cuenta")
    */
    public function VerCuentaAction()
    {
        $em = $this->getDoctrine()->getManager();
        $factura=$em->getRepository('AppBundle:FacturaNoPagada')->findBy(array('usuario'=>$this->getUser()));
        $mesa=$em->getRepository('AppBundle:Mesa')->findOneById($this->getUser()->getMesaOcupada());

        //creamos un array con los pedidos que tiene el cliente en la factura
        $arrayPedidos=array();
        $i=0;
        foreach($factura as $f){

            $arrayPedidos[$i]=$f->getIdPedido();
            $i++;
    }
        //creamos un array con los detalles delpedido para que no haya duplicados


        $arrayDetallePedido=array();

        $j=0;
        $i=0;
        $z=0;
        $cantidad=0;

        do{

            $dpedido=$em->getRepository('AppBundle:DetallePedido')->findBy(array('idPedido'=>$arrayPedidos[$j]));
            //var_dump($dpedido); //bien


                if(!$arrayDetallePedido){
                    $arrayDetallePedido[$j] = $dpedido;
                    //var_dump($arrayDetallePedido[0][0]->getCantidad());  //bien
                }else{
              $arrayDetallePedido[$j] = $dpedido;
                //recorremos los pedidos que tenga el cliente y comprobamos si el producto est� en dpedido
                  /*  for ($z = 0; $z < count($arrayDetallePedido); $z++) {
                        // Si el Id pedido es = a IdPedido de DetallePedido
                        var_dump($arrayPedidos[$j]);
                        var_dump($arrayDetallePedido[$z][0]->getIdPedido());
                        if ($arrayPedidos[$j] == $arrayDetallePedido[$z][0]->getIdPedido()) {
                            var_dump("iguales");
                            $arrayDetallePedido[$j] = $dpedido;
                            $cantidad = $arrayDetallePedido[$z][0]->getCantidad();
                            //var_dump($cantidad);
                            $arrayDetallePedido[$z][0]->setCantidad($dpedido[0]->getCantidad() + $cantidad);
                            //var_dump($arrayDetallePedido[$z][0]->getCantidad());
                        } else {
                            $arrayDetallePedido[$j] = $dpedido;
                        }
                    }*/
                }


        $j++;
        }while($j<count($arrayPedidos));


        return $this->render('default/VerCuenta.html.twig',[
            'factura' => $factura,
            'mesa'=>$mesa,
            'dpedido'=>$arrayDetallePedido

        ]);
    }
    /**
     * @Route("/realizar_pedido", name="realizar_pedido")
     */
    public function realizarPedidoAction()
    {
        $total = 0;
        if(isset($_SESSION['pedido'])) {
            if($_SESSION['pedido']!=''){
                $em = $this->getDoctrine()->getManager();
                $pedido = $_SESSION['pedido'];

                $mesa = $em->getRepository('AppBundle:Mesa')->findOneById($this->getUser()->getMesaOcupada());

                //creamos un nuevo pedido
                $pedidoRealizado = new Pedido();
                $pedidoRealizado->setEstado('pendiente');
                $pedidoRealizado->setIncidencias('Sin incidencias');
                $pedidoRealizado->setMesaOcupada($mesa);
                $em->persist($pedidoRealizado);
                // Guardar los cambios
                $em->flush();

                //creamos una factura
                $factura= new FacturaNoPagada();
                $factura->setHora(new \DateTime());
                $factura->setIdPedido($pedidoRealizado->getId());
                $factura->setUsuario($this->getUser());
                $em->persist($factura);
                // Guardar los cambios
                $em->flush();

                //creamos los detalles del pedido


                for ($i = 0; $i < count($pedido); $i++) {
                    $producto = $em->getRepository('AppBundle:Producto')->findOneBy(array('id' => $pedido[$i][0]));


                    //guardamos los detalles del pedido
                    $newPedido = new DetallePedido();
                    $newPedido->setIdPedido($pedidoRealizado->getId());
                    $newPedido->setNombreProducto($pedido[$i][0]->getNombreProducto());
                    $newPedido->setCantidad($pedido[$i][1]);
                    $em->persist($newPedido);
                    // Guardar los cambios
                    $em->flush();

                    //actualizamos la cuenta
                    $precio = $producto->getPrecio();
                    $cantidad = $pedido[$i][1];
                    $cuenta = $mesa->getCuenta();
                    $mesa->setCuenta($cuenta + $precio * $cantidad);
                    $em->persist($mesa);

                    // Guardar los cambios
                    $em->flush();




                    //descontamos los productos en el almacen
                    /*
                    $al = $em->getRepository('AppBundle:Ingredientes')->findAll(array('nombreProducto'=>$pedido[$i][0]->getNombreProducto()));
                    var_dump($al);
                    var_dump('uno');*/
                }

                $this->addFlash(
                    'notice',
                    'Pedido realizado!'
                );

            }

            $em = $this->getDoctrine()->getManager();
            $mesa=$em->getRepository('AppBundle:Mesa')->findOneById($this->getUser()->getMesaOcupada());
            $producto=new Producto();
            $_SESSION['pedido'] = '';
            $pedido=null;


            return $this->render('default/cuenta.html.twig',[
                'producto' => $producto,
                'mesa'=>$mesa,
                'total'=>$total,
                'pedido'=>$pedido
            ]);
        }else{    //Se muestra la cuenta sin pedidos.
            $em = $this->getDoctrine()->getManager();
            $mesa=$em->getRepository('AppBundle:Mesa')->findOneById($this->getUser()->getMesaOcupada());
            $producto=new Producto();
            $pedido=null;
            return $this->render('default/cuenta.html.twig',[
                'producto' => $producto,
                'mesa'=>$mesa,
                'total'=>$total,
                'pedido'=>$pedido
            ]);
        }
    }

    /**
     * @Route("/pagar_cuenta", name="pagar")
     */
    public function PagarAction()
    {
        $total=0;
        $em = $this->getDoctrine()->getManager();
        $mesa=$em->getRepository('AppBundle:Mesa')->findOneById($this->getUser()->getMesaOcupada());
        $mesa->setEstado("cuenta pedida");
        $em->persist($mesa);
        // Guardar los cambios
        $em->flush();

        $producto=new Producto();
        $pedido=null;


        return $this->render('default/cuenta.html.twig',[
            'producto' => $producto,
            'mesa'=>$mesa,
            'total'=>$total,
            'pedido'=>$pedido
        ]);
    }
    /**
     * @Route("/salir", name="salir")
     */
    public function salirAction()
    {
        // Al salir se redirecciona al formulario de login
        return $this->render('default/formulario.html.twig', array());
    }
    /**
     * @Route("/instalar", name="instalar")
     */
    public function instalarAction()
    {
        $em = $this->getDoctrine()->getManager();
        $usu = $em->getRepository('AppBundle:Usuario')->findOneBy(array('id'=>1));
        if(!$usu){
            $usuario= new Usuario();
            $usuario->setNombreUsuario('admin');
            $usuario->setPass('admin');
            $helper =  $password = $this->container->get('security.password_encoder');
            $usuario->setPass($helper->encodePassword($usuario, $usuario->getPassword()));

            $usuario->setNombre('admin');
            $usuario->setApellidos('admin');
            $usuario->setDni('26502842B');
            $usuario->setTelefono(99);
            $usuario->setEmail('demo@demo.com');
            $usuario->setEsAdmin(true);
            $usuario->setEsCamarero(false);
            $usuario->setEsCliente(false);
            $usuario->setMesaOcupada(0);
            // Obtener el EntityManager
            $em = $this->getDoctrine()->getManager();
            $em->persist($usuario);
            // Guardar los cambios
            $em->flush();
        }
        return $this->render(':default:formulario.html.twig');
    }
}