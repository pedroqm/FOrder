<?php


namespace AppBundle\Form\Type;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;


class IngredienteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('cantidad', null, [
                'label' => 'Cantidad:',
                'required' => true
            ])
            ->add('nombreIngrediente', null, [
                'label' => 'Nombre',
                'required' => true
            ])
            ->add('nombreProducto', null, [
                'label' => 'Nombre del producto',
                'required' => true
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
        return 'ingrediente';
    }

}