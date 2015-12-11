<?php


namespace AppBundle\Form\Type;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;


class AlmacenType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('stock', null, [
                'label' => 'Stock del producto',
                'attr' => [
                    'class' => 'form-control'
                ],
                'required' => false
            ])
            ->add('stockMin', null, [
                'label' => 'Stock minimo del producto',
                'attr' => [
                    'class' => 'form-control'
                ],
                'required' => false
            ])
            ->add('nombreIngrediente', null, [
                'label' => 'Nombre Ingrediente',
                'attr' => [
                    'class' => 'form-control'
                ],
                'required' => false
            ])
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
        return 'almacen';
    }

}