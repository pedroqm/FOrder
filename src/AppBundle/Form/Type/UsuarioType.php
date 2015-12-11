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
                'attr' => [
                    'class' => 'form-control'
                ],
                'required' => true
            ])
            ->add('pass', 'password', [
                'label' => 'Password',
                'attr' => [
                    'class' => 'form-control'
                ],
                'required' => true
            ])
            ->add('nombre', null, [
                'label' => 'Nombre',
                'attr' => [
                    'class' => 'form-control'
                ],
                'required' => false
            ])
            ->add('apellidos', null, [
                'label' => 'Apellidos',
                'attr' => [
                    'class' => 'form-control'
                ],
                'required' => false
            ])
            ->add('dni', null, [
                'label' => 'Dni',
                'attr' => [
                    'class' => 'form-control'
                ],
                'required' => true
            ])

            ->add('email', null, [
                'label' => 'email',
                'attr' => [
                    'class' => 'form-control'
                ],
                'required' => false
            ])
            ->add('telefono', null, [
                'label' => 'telefono',
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
        return 'usuario';
    }
}