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
                'label' => 'Nombre de usuario para acceder a la aplicación',
                'attr' => [
                    'placeholder'=>'Nombre de usuario',
                    'class' => 'form-control'
                ],
                'required' => true
            ])
            ->add('pass', 'password', [
                'label' => 'Contraseña',
                'attr' => [
                    'placeholder'=>'contraseña',
                    'class' => 'form-control'
                ],
                'required' => true
            ])
            ->add('nombre', null, [
                'label' => 'Nombre',
                'attr' => [
                    'placeholder'=>'Nombre',
                    'class' => 'form-control'
                ],
                'required' => false
            ])
            ->add('apellidos', null, [
                'label' => 'Apellidos',
                'attr' => [
                    'placeholder'=>'Apellidos',
                    'class' => 'form-control'
                ],
                'required' => false
            ])
            ->add('dni', null, [
                'label' => 'Dni',
                'attr' => [
                    'placeholder'=>'Dni',
                    'class' => 'form-control'
                ],
                'required' => true
            ])

            ->add('email', null, [
                'label' => 'Email',
                'attr' => [
                    'placeholder'=>'Email',
                    'class' => 'form-control'
                ],
                'required' => false
            ])
            ->add('telefono', null, [
                'label' => 'Telefono',
                'attr' => [
                    'placeholder'=>'Telefono',
                    'class' => 'form-control'
                ],
                'required' => false
            ])

            ->add('enviar', 'submit', [
                'label' => 'Guardar',
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