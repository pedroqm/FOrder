<?php

namespace AppBundle\Controller;

use AppBundle\Entity\DetallePedido;
use AppBundle\Entity\FacturaPagada;
use AppBundle\Entity\Mesa;
use AppBundle\Entity\Pedido;
use AppBundle\Entity\Usuario;
use AppBundle\Form\Type\MesaType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;


/**
 * @Route("/mesa")
 */
class MesaController extends Controller
{

    /**
     * @Route("/listar", name="mesa_listar")
     */
    public function listarAction()
    {
        $em = $this->getDoctrine()->getManager();
        $usuarios=$this->getUser();

        $mesa = $em->getRepository('AppBundle:Mesa')
            ->findAll();

        $pedidoRealizado=$em->getRepository('AppBundle:Pedido')->findBy(array('estado'=>array('pendiente','preparado')));

        return $this->render(':mesa:listar_mesa.html.twig', [
            'mesa' => $mesa,
            'pedido'=>$pedidoRealizado,
            'usuarios'=>$usuarios
        ]);
    }


    /**
     * @Route("/nuevo", name="mesa_nuevo")
     */
    public function nuevoAction()
    {
        $mesa = new mesa();

        $mesa->setCuenta(0)
            ->setEstado("libre");

        // Obtener el EntityManager
        $em = $this->getDoctrine()->getManager();

        $em->persist($mesa);
        // Guardar los cambios
        $em->flush();

           // Redirigir al usuario a la lista
           return new RedirectResponse(
               $this->generateUrl('mesa_listar')
           );

    }

    /**
     * @Route("/detallePedido/{pedido}", name="detallePedido")
     */
    public function detallePedidoAction(Pedido $pedido)
    {
        $em = $this->getDoctrine()->getManager();

        $Dpedido=$em->getRepository('AppBundle:DetallePedido')->findBy(array('Dpedido'=>$pedido));

        return $this->render(':mesa:detalle_pedido.html.twig', [
            'detalle'=>$Dpedido,
            'pedido'=>$pedido
        ]);
    }
    /**
     * @Route("/pagado/{id}", name="pagado"), methods={'GET', 'POST'}
     */
    public function pagadoAction(Mesa $id)
    {

        if(isset($_POST['pagar'])){

            $em = $this->getDoctrine()->getManager();
            $cliente=$id->getUser();

            //sumamos la cuenta a la factura de la mesa
            $id->setCuenta(0);

            //ponemos el estado de la mesa a "libre"
            $id->setEstado("libre");

            $em->persist($id);
            //guardamos los cambios
            $em->flush();

            if($cliente) {
                //ponemos la mesa del usuario libre
                $usuario = $em->getRepository('AppBundle:Usuario')->findById($cliente);
                $usuario[0]->setMesaOcupada(0);
                $usuario[0]->setFactura(0);

                $em->persist($usuario[0]);
                $em->flush();


                //poner las facturas en facturas pagadas
                $facturas = $em->getRepository('AppBundle:FacturaNoPagada')->findBy(array('usuario' => $cliente));

                if ($facturas) {

                    while ($facturas) {
                        $facturaPagada = new FacturaPagada();
                        $facturaPagada->setUsuario($cliente);
                        $facturaPagada->setHora(new \DateTime());
                        $facturaPagada->setIdPedido($facturas[0]->getIdPedido());

                        $em->persist($facturaPagada);
                        $em->flush();

                        $em->remove($facturas[0]);
                        $em->flush();


                        $facturas = $em->getRepository('AppBundle:FacturaNoPagada')->findBy(array('usuario' => $cliente));
                    };
                }


            }

            //quitamos el usuario de la mesa
            $id->setUser(null);
            $em->persist($id);
            //guardamos los cambios
            $em->flush();

            return new RedirectResponse(
                $this->generateUrl('mesa_listar')
            );
        }
    }
    /**
     * @Route("/NOpagado/{id}", name="NOpagado"), methods={'GET', 'POST'}
     */
    public function NOpagadoAction(Mesa $id)
    {

        if(isset($_POST['SinPagar'])){
            $em = $this->getDoctrine()->getManager();
            $cliente=$id->getUser();


            //sumamos la cuenta a la factura de la mesa
            $id->setCuenta(0);


            //ponemos la mesa del usuario libre
            $usuario = $em->getRepository('AppBundle:Usuario')->findById($cliente);
            $usuario[0]->setMesaOcupada(0);

            $em->persist($usuario[0]);
            $em->flush();

            //ponemos el estado de la mesa a "libre"
            $id->setEstado("libre");
            $id->setUser(null);
            $em->persist($id);
            //guardamos los cambios
            $em->flush();


            return new RedirectResponse(
                $this->generateUrl('mesa_listar')
            );
        }
    }
    /**
     * @Route("/modificar/{mesa}", name="mesa_modificar")
     */
    public function modificarAction(Request $peticion, Mesa $mesa)
    {
        // Crear el formulario a partir de la clase
        $formulario = $this->createForm(new MesaType(), $mesa);

        // Procesar el formulario si se ha enviado con un POST
        $formulario->handleRequest($peticion);

        // Si se ha enviado y el contenido es válido, guardar los cambios
        if ($formulario->isSubmitted() && $formulario->isValid()) {

            // Obtener el EntityManager
            $em = $this->getDoctrine()->getManager();


            // Asegurarse de que se tiene en cuenta el nuevo usuario
            $em->persist($mesa);
            // Guardar los cambios
            $em->flush();
            // Redirigir al usuario a la lista
            return new RedirectResponse(
                $this->generateUrl('mesa_listar')
            );
        }


        return $this->render(':mesa:modificar_mesa.html.twig', [
            'mesa' => $mesa,
            'formulario' => $formulario->createView()
        ]);
    }
    /**
     * @Route("/ver/{mesa}", name="mesa_cuenta")
     */
    public function pagarAction(Request $peticion, Mesa $mesa)
    {
        if(isset($_POST['pagar'])){
            $em = $this->getDoctrine()->getManager();
           //cambiar estado de la cuenta
            $em->flush();


            // Redirigir al usuario a la lista
            return new RedirectResponse(
                $this->generateUrl('mesa_listar')
            );
        }

            // Obtener el EntityManager
            $em = $this->getDoctrine()->getManager();

            // Asegurarse de que se tiene en cuenta el nuevo pedido
            $em->persist($mesa);
            // Guardar los cambios
            $em->flush();

            // Redirigir al usuario a la lista
            return new RedirectResponse(
                $this->generateUrl('mesa_listar')
            );


    }
    /**
     * @Route("/eliminar/{mesa}", name="mesa_eliminar")
     */
    public function eliminarAction(Request $peticion, Mesa $mesa)
    {

        //Eliminar mesa

        if(isset($_POST['eliminar_mesa'])){
            $em = $this->getDoctrine()->getManager();
            $em->remove($mesa);
            $em->flush();
            $this->addFlash('success', 'Mesa eliminada de forma correcta');



        }
        // Redirigir al usuario a la lista
        return new RedirectResponse(
            $this->generateUrl('mesa_listar')
        );

    }

}
