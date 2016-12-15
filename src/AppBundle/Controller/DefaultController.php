<?php
namespace AppBundle\Controller;

use AppBundle\Entity\DetallePedido;
use AppBundle\Entity\FacturaNoPagada;
use AppBundle\Entity\FacturaPagada;
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
        $facturasNoPagadas = $em->getRepository('AppBundle:FacturaNoPagada')->findBy(array('usuario' => $usuario->getId()));

        $tipoProducto = $em->getRepository('AppBundle:Producto')
            ->createQueryBuilder('v')
            ->select('v.tipo')
            ->distinct()
            ->getQuery()
            ->getResult();

        if (isset($_POST['ocupar'])) {

            $usuario = $this->getUser()->setMesaOcupada($_POST['Nmesa']);
            $mesa = $em->getRepository('AppBundle:Mesa')->findOneById($_POST['Nmesa'])->setEstado("ocupado");
            $mesa->setUser($this->getUser());
            $em->persist($usuario);
            $em->persist($mesa);
            // Guardar los cambios
            $em->flush();

        }

        if ($cliente) {


            return $this->render(':default:inicio.html.twig', [
                'tipoProducto' => $tipoProducto,
                'mesa' => $mesas,
                'FNP' => $facturasNoPagadas,
                'usuarios' => $usuario
            ]);
        } else {
            $camarero = $usuario->getEsCamarero();
            if ($camarero) {
                if ($this->getUser()->getMesaOcupada() == 0) {
                    $em = $this->getDoctrine()->getManager();
                    $pedidoRealizado = $em->getRepository('AppBundle:Pedido')->findBy(array('estado' => array('pendiente', 'preparado')));
                    return $this->render(':mesa:listar_mesa.html.twig', [
                        'mesa' => $mesas,
                        'pedido' => $pedidoRealizado,
                        'usuarios'=>$usuario
                    ]);
                } else {


                    return $this->render(':default:inicio.html.twig', [
                        'tipoProducto' => $tipoProducto,
                        'mesa' => $mesas,
                        'usuarios' => $usuario
                    ]);
                }

            } else {

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
     * @Security("is_granted('ROLE_CAMARERO')")
     */
    public function inicioCamareroAction()
    {
        $em = $this->getDoctrine()->getManager();
        $usuario = $this->getUser();
        $mesas = $em->getRepository('AppBundle:Mesa')->findAll();
        $this->getUser()->setMesaOcupada(0);
        $em->flush();

        $pedidoRealizado = $em->getRepository('AppBundle:Pedido')->findBy(array('estado' => array('pendiente', 'preparado')));

        return $this->render(':mesa:listar_mesa.html.twig', [
            'mesa' => $mesas,
            'pedido' => $pedidoRealizado,
            'usuarios'=>$usuario
        ]);
    }

    /**
     * @Route("/pedidoManual", name="pedidoManual")
     */
    public function pedManualAction()
    {
        $em = $this->getDoctrine()->getManager();
        $usuario = $this->getUser();
        $tipoProducto = $em->getRepository('AppBundle:Producto')
            ->createQueryBuilder('v')
            ->select('v.tipo')
            ->distinct()
            ->getQuery()
            ->getResult();
        $mesas = $em->getRepository('AppBundle:Mesa')
            ->findAll();
        return $this->render(':default:inicio.html.twig', [
            'tipoProducto' => $tipoProducto,
            'mesa' => $mesas,
            'usuarios' => $usuario
        ]);
    }

    /**
     * @Route("/cuenta", name="cuenta")
     */
    public function cuentaAction()
    {
        $em = $this->getDoctrine()->getManager();
        $mesa = $em->getRepository('AppBundle:Mesa')->findOneById($this->getUser()->getMesaOcupada());
        $producto = new Producto();
        $total = 0;
        if (isset($_SESSION['pedido'])) {
            if ($_SESSION['pedido'] == '') {
                $pedido = null;
                $total = 0;
            } else {
                $pedido = $_SESSION['pedido'];

                for ($j = 0; $j < count($pedido); $j++) {
                    $total = $total + ($pedido[$j][0]->getPrecio() * $pedido[$j][1]);
                }
            }
        } else {    //Se muestra la cuenta sin pedidos.
            $pedido = null;
            $total = 0;
        }
        return $this->render('default/cuenta.html.twig', [
            'producto' => $producto,
            'mesa' => $mesa,
            'total' => $total,
            'pedido' => $pedido
        ]);
    }

    /**
     * @Route("/ver_cuenta", name="ver_cuenta")
     */
    public function VerCuentaAction()
    {
        $em = $this->getDoctrine()->getManager();
        $factura = $em->getRepository('AppBundle:FacturaNoPagada')->findBy(array('usuario' => $this->getUser()));
        $mesa = $em->getRepository('AppBundle:Mesa')->findOneById($this->getUser()->getMesaOcupada());

        //creamos un array con los pedidos que tiene el cliente en la factura
        $arrayPedidos = array();
        $i = 0;
        foreach ($factura as $f) {

            $arrayPedidos[$i] = $f->getIdPedido();

            $i++;
        }
        //creamos un array con los detalles delpedido para que no haya duplicados

        $arrayDetallePedido = 0;

        $j = 0;
        $i = 0;
        $z = 0;
        $cantidad = 0;

        do {
            $encontrado = false;
            $dpedido = $em->getRepository('AppBundle:DetallePedido')->findBy(array('Dpedido' => $arrayPedidos[$j]));

            if (!$arrayDetallePedido) { //guardamos el primer pedido
                $arrayDetallePedido = $dpedido;
            } else {


                //recorremos los pedidos que tenga el cliente y comprobamos si el producto está en el array de detallepedido y en dpedido

                for ($z = 0; $z < count($dpedido); $z++) {
                    for ($i = 0; $i < count($arrayDetallePedido); $i++) {
                        $encontrado = false;

                        if ($arrayDetallePedido[$i]->getNombreProducto() == $dpedido[$z]->getNombreProducto()) {
                            $cantidad = $arrayDetallePedido[$i]->getCantidad();
                            $arrayDetallePedido[$i]->setCantidad($dpedido[0]->getCantidad() + $cantidad);
                            $encontrado = true;
                            break;
                        }

                    }
                    if (!$encontrado) {

                        array_push($arrayDetallePedido, $dpedido[$z]);


                    }
                }
            }


            $j++;
        } while ($j < count($arrayPedidos));


        return $this->render('default/VerCuenta.html.twig', [
            'factura' => $factura,
            'mesa' => $mesa,
            'dpedido' => $arrayDetallePedido

        ]);
    }

    /**
     * @Route("/ver_cuentaF/{id}", name="ver_cuentaF"), methods={'GET', 'POST'}
     */
    public function VerCuentaFAction(Usuario $id)
    {
        $em = $this->getDoctrine()->getManager();
        $factura = $em->getRepository('AppBundle:FacturaNoPagada')->findBy(array('usuario' => $id));
        $mesa = $em->getRepository('AppBundle:Mesa')->findOneById($this->getUser()->getMesaOcupada());

        //creamos un array con los pedidos que tiene el cliente en la factura
        $arrayPedidos = array();
        $i = 0;
        foreach ($factura as $f) {

            $arrayPedidos[$i] = $f->getIdPedido();

            $i++;
        }
        //creamos un array con los detalles delpedido para que no haya duplicados

        $arrayDetallePedido = 0;

        $j = 0;
        $i = 0;
        $z = 0;
        $cantidad = 0;

        do {
            $encontrado = false;
            $dpedido = $em->getRepository('AppBundle:DetallePedido')->findBy(array('Dpedido' => $arrayPedidos[$j]));

            if (!$arrayDetallePedido) { //guardamos el primer pedido
                $arrayDetallePedido = $dpedido;
            } else {


                //recorremos los pedidos que tenga el cliente y comprobamos si el producto está en el array de detallepedido y en dpedido

                for ($z = 0; $z < count($dpedido); $z++) {
                    for ($i = 0; $i < count($arrayDetallePedido); $i++) {
                        $encontrado = false;

                        if ($arrayDetallePedido[$i]->getNombreProducto() == $dpedido[$z]->getNombreProducto()) {
                            $cantidad = $arrayDetallePedido[$i]->getCantidad();
                            $arrayDetallePedido[$i]->setCantidad($dpedido[0]->getCantidad() + $cantidad);
                            $encontrado = true;
                            break;
                        }

                    }
                    if (!$encontrado) {

                        array_push($arrayDetallePedido, $dpedido[$z]);


                    }
                }
            }


            $j++;
        } while ($j < count($arrayPedidos));


        return $this->render('default/VerCuenta.html.twig', [
            'factura' => $factura,
            'mesa' => $mesa,
            'dpedido' => $arrayDetallePedido

        ]);
    }

    /**
     * @Route("/realizar_pedido", name="realizar_pedido")
     */
    public function realizarPedidoAction()
    {
        $total = 0;
        if (isset($_SESSION['pedido'])) {
            if ($_SESSION['pedido'] != '') {
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
                $factura = new FacturaNoPagada();
                $factura->setHora(new \DateTime());
                $factura->setIdPedido($pedidoRealizado->getId());
                $factura->setUsuario($this->getUser());
                $factura->setIdUsuario($this->getUser()->getId());
                $em->persist($factura);
                // Guardar los cambios
                $em->flush();

                //creamos los detalles del pedido


                for ($i = 0; $i < count($pedido); $i++) {
                    $producto = $em->getRepository('AppBundle:Producto')->findOneBy(array('id' => $pedido[$i][0]));


                    //guardamos los detalles del pedido
                    $newPedido = new DetallePedido();
                    $newPedido->setPrecio($producto->getPrecio());
                    $newPedido->setNombreProducto($pedido[$i][0]->getNombreProducto());
                    $newPedido->setCantidad($pedido[$i][1]);
                    $newPedido->setDpedido($pedidoRealizado);
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


                    //dentro del carrito buscamos los pedidos que tengan cantidad distinta de 0 para descontar los productos
                    if ($pedido[$i][1] != 0) {
                        $Ingredientes = $em->getRepository('AppBundle:Ingredientes')->findBy(array('nombreProducto' => $producto->getNombreProducto()));

                        if ($Ingredientes) {
                            for ($z = 0; $z < count($Ingredientes); $z++) {
                                $almacen = $em->getRepository('AppBundle:Almacen')->findBy(array('nombreIngrediente' => $Ingredientes[$z]->getNombreIngrediente()));
                                $stockActual = $almacen[0]->getStock();
                                $almacen[0]->setStock($stockActual - $Ingredientes[$z]->getCantidad());

                                $em->persist($almacen[0]);

                                // Guardar los cambios
                                $em->flush();
                            }
                        }
                    }

                    //guardamos la factura
                    $usuario1=$em->getRepository('AppBundle:Usuario')->findOneById($this->getUser()->getId());
                    $usuario1->setFactura($mesa->getCuenta());
                    $em->persist($usuario1);

                    // Guardar los cambios
                    $em->flush();

                }

                $this->addFlash(
                    'notice',
                    'Pedido realizado!'
                );

            }

            $em = $this->getDoctrine()->getManager();
            $mesa = $em->getRepository('AppBundle:Mesa')->findOneById($this->getUser()->getMesaOcupada());
            $producto = new Producto();
            $_SESSION['pedido'] = '';
            $pedido = null;


            return $this->render('default/cuenta.html.twig', [
                'producto' => $producto,
                'mesa' => $mesa,
                'total' => $total,
                'pedido' => $pedido
            ]);
        } else {    //Se muestra la cuenta sin pedidos.
            $em = $this->getDoctrine()->getManager();
            $mesa = $em->getRepository('AppBundle:Mesa')->findOneById($this->getUser()->getMesaOcupada());
            $producto = new Producto();
            $pedido = null;
            return $this->render('default/cuenta.html.twig', [
                'producto' => $producto,
                'mesa' => $mesa,
                'total' => $total,
                'pedido' => $pedido
            ]);
        }
    }

    /**
     * @Route("/pagar_cuenta", name="pagar")
     */
    public function PagarAction()
    {
        $total = 0;
        $em = $this->getDoctrine()->getManager();
        $mesa = $em->getRepository('AppBundle:Mesa')->findOneById($this->getUser()->getMesaOcupada());
        $mesa->setEstado("cuenta pedida");
        $em->persist($mesa);
        // Guardar los cambios
        $em->flush();

        $producto = new Producto();
        $pedido = null;


        return $this->render('default/cuenta.html.twig', [
            'producto' => $producto,
            'mesa' => $mesa,
            'total' => $total,
            'pedido' => $pedido
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
        $usu = $em->getRepository('AppBundle:Usuario')->findOneBy(array('id' => 1));
        if (!$usu) {
            $usuario = new Usuario();
            $usuario->setNombreUsuario('admin');
            $usuario->setPass('admin');
            $helper = $password = $this->container->get('security.password_encoder');
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


    /**
     * @Route("/pagarFacturas", name="pagar_facturas")
     * @Security("is_granted('ROLE_CAMARERO')")
     */
    public function pagarFacturas()
    {

        //mostrar los usuarios con facturas no pagadas

        $em = $this->getDoctrine()->getManager();

            $FacturasUsuarios = $em->getRepository('AppBundle:FacturaNoPagada')
                ->createQueryBuilder('v')
                ->select('v.idUsuario')
                ->distinct()
                ->getQuery()
                ->getResult();


            $arrayUsuarios = array();
            $j = 0;
            do {

                $usuarios = $em->getRepository('AppBundle:Usuario')->findBy(array("id" => $FacturasUsuarios[$j]));

                if (!$arrayUsuarios) {
                    $arrayUsuarios = $usuarios;
                } else {
                    for ($i = 0; $i < count($arrayUsuarios); $i++) {
                        $encontrado = false;
                        if ($arrayUsuarios[$i]->getId() == $usuarios[0]->getId()) {
                            $encontrado = true;
                            break;
                        }
                    }
                    if (!$encontrado) {

                        array_push($arrayUsuarios, $usuarios[0]);

                    }
                }
                $j++;
            } while ($j < count($FacturasUsuarios));


        return $this->render('default/pagarFacturas.html.twig', [
            'usuario' => $arrayUsuarios,
        ]);

    }


    /**
     * @Route("/pagarFacturasNP/{id}", name="pagar_facturasNP"), methods={'GET', 'POST'}
     * @Security("is_granted('ROLE_CAMARERO')")
     */
    public function pagarFacturasNP(Usuario $id)
    {

        //mostrar los usuarios con facturas no pagadas

        $em = $this->getDoctrine()->getManager();

        if(isset($_POST['pagarFacturasNP'])) {
            //poner las facturas en facturas pagadas

            $facturas = $em->getRepository('AppBundle:FacturaNoPagada')->findBy(array('usuario' => $id));

            if ($facturas) {

                while ($facturas) {
                    $facturaPagada = new FacturaPagada();
                    $facturaPagada->setUsuario($id);
                    $facturaPagada->setHora(new \DateTime());
                    $facturaPagada->setIdPedido($facturas[0]->getIdPedido());

                    $em->persist($facturaPagada);
                    $em->flush();

                    $em->remove($facturas[0]);
                    $em->flush();


                    $facturas = $em->getRepository('AppBundle:FacturaNoPagada')->findBy(array('usuario' => $id));
                };
            }
        }
        $FacturasUsuarios = $em->getRepository('AppBundle:FacturaNoPagada')
            ->createQueryBuilder('v')
            ->select('v.idUsuario')
            ->distinct()
            ->getQuery()
            ->getResult();


        $arrayUsuarios = array();
        $j = 0;
        do {

            $usuarios = $em->getRepository('AppBundle:Usuario')->findBy(array("id" => $FacturasUsuarios[$j]));

            if (!$arrayUsuarios) {
                $arrayUsuarios = $usuarios;
            } else {
                for ($i = 0; $i < count($arrayUsuarios); $i++) {
                    $encontrado = false;
                    if ($arrayUsuarios[$i]->getId() == $usuarios[0]->getId()) {
                        $encontrado = true;
                        break;
                    }
                }
                if (!$encontrado) {

                    array_push($arrayUsuarios, $usuarios[0]);

                }
            }
            $j++;
        } while ($j < count($FacturasUsuarios));


        return $this->render('default/pagarFacturas.html.twig', [
            'usuario' => $arrayUsuarios,
        ]);

    }
}