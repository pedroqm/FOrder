<?php

namespace AppBundle\Controller;

use AppBundle\Entity\DetallePedido;
use AppBundle\Entity\Mesa;
use AppBundle\Entity\Pedido;
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

        $mesa = $em->getRepository('AppBundle:Mesa')
            ->findAll();

        $pedidoRealizado=$em->getRepository('AppBundle:Pedido')->findBy(array('estado'=>array('pendiente','preparado')));

        return $this->render(':mesa:listar_mesa.html.twig', [
            'mesa' => $mesa,
            'pedido'=>$pedidoRealizado
        ]);
    }


    /**
     * @Route("/nuevo", name="mesa_nuevo")
     */
    public function nuevoAction()
    {
        $mesa = new mesa();

        $mesa->setCuenta(0)
            ->setFactura(0);

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

        $Dpedido=$em->getRepository('AppBundle:DetallePedido')->findBy(array('idPedido'=>$pedido->getId()));

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
            $factura=$id->getFactura();
            $cuenta=$id->getCuenta();

            //sumamos la cuenta a la factura de la mesa
            $id->setCuenta(0);
            $id->setFactura($factura+$cuenta);

            //ponemos el estado de la mesa a "libre"
            $id->setEstado("libre");
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

            /*$helper =  $password = $this->container->get('security.password_encoder');
            $usuario->setPassword($helper->encodePassword($usuario, $usuario->getPassword()));*/

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
            $this->addFlash('success', 'Mesa eliminada de forma correcta');


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

        return $this->render(':mesa:modificar_mesa.html.twig', [
            'formulario' => $formulario->createView()
        ]);
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


            // Redirigir al usuario a la lista
            return new RedirectResponse(
                $this->generateUrl('mesa_listar')
            );
        }

    }

}
