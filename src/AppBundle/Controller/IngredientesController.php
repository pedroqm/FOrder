<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Almacen;
use AppBundle\Entity\Ingredientes;
use AppBundle\Form\Type\IngredienteType;
use AppBundle\Entity\Producto;
use AppBundle\Form\Type\ProductoType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Config\Tests\Definition\IntegerNodeTest;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;


/**
 * @Route("/ingredientes")
 */
class IngredientesController extends Controller
{

    /**
     * @Route("/receta/{id}", name="ver_receta"), methods={'GET', 'POST'}
     * @Security("is_granted('ROLE_ADMIN')")
     */
    public function verRecetaAction(Producto $id)
    {

        if(isset($_POST['ver_receta'])){

            $em = $this->getDoctrine()->getManager();

            $ingrediente = $em->getRepository('AppBundle:Ingredientes')
                ->findBy(array('nombreProducto' => $id->getNombreProducto()));



            $_SESSION['idprod']=$id->getId();


            return $this->render(':ingredientes:listar_ingredientes.html.twig', [
                'ingrediente' => $ingrediente,
                'producto'=>$id
            ]);
        }
    }
    /**
     * @Route("/listar", name="ingrediente_listar")
     * @Security("is_granted('ROLE_ADMIN')")
     */
    public function listarAction()
    {
        $em = $this->getDoctrine()->getManager();
        $producto=$em->getRepository('AppBundle:Producto')
            ->findOneBy(array('id' => $_SESSION['idprod']));
        $ingrediente = $em->getRepository('AppBundle:Ingredientes')
            ->findBy(array('nombreProducto' => $producto->getNombreProducto()));


        return $this->render(':ingredientes:listar_ingredientes.html.twig', [
            'ingrediente' => $ingrediente,
            'producto'=>$producto
        ]);
    }


    /**
     * @Route("/nuevo/{producto}", name="ingrediente_nuevo")
     * @Security("is_granted('ROLE_ADMIN')")
     */
    public function nuevoAction(Request $peticion, Producto $producto)
    {
        $ingrediente= new Ingredientes();

        // Crear el formulario a partir de la clase
        $formulario = $this->createForm(new IngredienteType(), $ingrediente);

        // Procesar el formulario si se ha enviado con un POST
        $formulario->handleRequest($peticion);

        // Si se ha enviado y el contenido es válido, guardar los cambios
       if ($formulario->isSubmitted() && $formulario->isValid()) {

           // Obtener el EntityManager
           $em = $this->getDoctrine()->getManager();

           $almacenn=$em->getRepository('AppBundle:Almacen')
               ->findOneBy(array('nombreIngrediente'=>$ingrediente->getNombreIngrediente()));

           if($almacenn){
               $ingrediente->setAlmacenado($almacenn);
           }else{
               $almacen= new Almacen();
               $almacen->setNombreIngrediente($ingrediente->getNombreIngrediente());
               $almacen->setStock(0);
               $almacen->setStockMin(0);
               $em->persist($almacen);
               $em->flush();
               $ingrediente->setAlmacenado($almacen);
           }


           $ingrediente->addProducto($producto);

           $ingrediente->setNombreProducto($producto->getNombreProducto());

           // Asegurarse de que se tiene en cuenta el nuevo pedido
           $em->persist($ingrediente);
           // Guardar los cambios
           $em->flush();

           // Redirigir al usuario a la lista
           return new RedirectResponse(
               $this->generateUrl('ingrediente_listar')
           );
       }
        return $this->render(':ingredientes:nuevo_ingredientes.html.twig',[
            'producto'=>$producto,
            'formulario' => $formulario->createView()
        ]);
    }
    /**
     * @Route("/modificar/{ingrediente}", name="ingrediente_modificar")
     * @Security("is_granted('ROLE_ADMIN')")
     */
    public function modificarAction(Request $peticion, Ingredientes $ingrediente)
    {

        // Crear el formulario a partir de la clase
        $formulario = $this->createForm(new IngredienteType(), $ingrediente);

        // Procesar el formulario si se ha enviado con un POST
        $formulario->handleRequest($peticion);

        // Si se ha enviado y el contenido es válido, guardar los cambios
        if ($formulario->isSubmitted() && $formulario->isValid()) {

            // Obtener el EntityManager
            $em = $this->getDoctrine()->getManager();

            // Asegurarse de que se tiene en cuenta el nuevo pedido
            $em->persist($ingrediente);
            // Guardar los cambios
            $em->flush();

            // Redirigir al usuario a la lista
            return new RedirectResponse(
                $this->generateUrl('ingrediente_listar')
            );
        }
        return $this->render(':ingredientes:modificar_ingredientes.html.twig', [
            'formulario' => $formulario->createView()
        ]);
    }

    /**
     * @Route("/eliminar/{ingrediente}", name="ingrediente_eliminar")
     * @Security("is_granted('ROLE_ADMIN')")
     */
    public function eliminarAction(Ingredientes $ingrediente)
    {
        //Eliminar producto

        if(isset($_POST['eliminar_ing'])){
            $em = $this->getDoctrine()->getManager();
            $em->remove($ingrediente);
            $em->flush();


        }

        return new RedirectResponse(
            $this->generateUrl('ingrediente_listar')
        );

    }

}
