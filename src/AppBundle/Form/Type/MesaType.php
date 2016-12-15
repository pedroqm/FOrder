<?php


namespace AppBundle\Form\Type;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;


class MesaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('cuenta', null, [
                'label' => 'cuenta',
                'attr' => [
                    'class' => 'form-control'
                ],
                'required' => false
            ])
            ->add('estado', null,[
                'label' => 'estado',
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
        return 'mesa';
    }

}