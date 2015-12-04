<?php

namespace AppBundle\Form\Type;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class UsuarioType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nombreUsuario', null, [
                'label' => 'Nombre de usuario',
                'required' => true
            ])
            ->add('pass', 'password', [
                'label' => 'Password',
                'required' => true
            ])
            ->add('dni', null, [
                'label' => 'Dni',
                'required' => true
            ])
            ->add('apellidos', null, [
                'label' => 'Apellidos',
                'required' => false
            ])
            ->add('email', null, [
                'label' => 'email',
                'required' => false
            ])
            ->add('telefono', null, [
                'label' => 'telefono',
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
        return 'usuario';
    }
}