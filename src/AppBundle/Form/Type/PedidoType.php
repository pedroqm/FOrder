<?php

namespace AppBundle\Form\Type;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class PedidoType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('estado', null, [
                'label' => 'Estado',
                'attr' => [
                    'class' => 'form-control'
                ],
                'required' => true
            ])
            ->add('incidencias', null, [
                'label' => 'Incidencias',
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
        return 'pedido';
    }
}