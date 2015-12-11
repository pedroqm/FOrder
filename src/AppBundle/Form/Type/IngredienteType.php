<?php


namespace AppBundle\Form\Type;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;


class IngredienteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nombreIngrediente', null, [
                'label' => 'Nombre',
                'attr' => [
                    'class' => 'form-control'
                ],
                'required' => true
            ])
            ->add('cantidad', null, [
                'label' => 'Cantidad:',
                'attr' => [
                    'class' => 'form-control'
                ],
                'required' => true
            ])
          /*
            ->add('nombreProducto', null, [
                'label' => 'Nombre del producto',
                'attr' => [
                    'class' => 'form-control'
                ],
                'required' => true
            ])*/
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