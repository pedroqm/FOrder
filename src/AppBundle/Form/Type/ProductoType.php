<?php


namespace AppBundle\Form\Type;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;


class ProductoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nombreProducto', null, [
                'label' => 'Nombre',
                'attr' => [
                    'class' => 'form-control'
                ],
                'required' => false
            ])
            ->add('precio', null, [
                'label' => 'Precio',
                'attr' => [
                    'class' => 'form-control'
                ],
                'required' => false
            ])
            ->add('tipo', 'choice', array('label'=>'selecciona el tipo' ,
                'multiple'=>false,
                'choices'=>array("Bebidas"=> 'Bebidas',"Entrantes"=>"Entrantes","Carne"=>"Carne","Pescado"=>"Pescado","Postres"=>"Postres"),
                'attr' => [
                    'class' => 'form-control'

                ]
            ))
            ->add('enviar', 'submit', [
                'label' => 'Guardar cambios',
                'attr' => [
                    'class' => 'btn btn-success'
                ]
            ]);
    }


    /**
     * Returns the name of this type.
     *
     * @return string The name of this type
     */
    public function getName()
    {
        return 'producto';
    }

}