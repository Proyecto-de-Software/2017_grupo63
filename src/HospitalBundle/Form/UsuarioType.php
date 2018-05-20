<?php

namespace HospitalBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type;

class UsuarioType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('username', null, array(
                                            'label'  => 'Nombre de usuario*',
                                        ))
                ->add('email', null, array(
                                            'label'  => 'Correo Electronico*',
                                        ))
                ->add('firstName', null, array(
                                            'label'  => 'Nombre*',
                                        ))
                ->add('lastName', null, array(
                                            'label'  => 'Apellido*',
                                        ))
                ->add('password', Type\PasswordType::class, array(
                                             
                                            'label'  => 'Contraseña',
                                        ))
                ->add('enabled', null, array(
                                            'label'  => 'Activo',
                                        ))
                 ->add('roles', 'choice', [
                                            'choices' => ['ROLE_ADMIN' => 'Administrador',
                                                          'ROLE_PED' => 'Pediatra',
                                                          'ROLE_REC' => 'Recepcionista'],
                                            'expanded' => true,
                                            'multiple' => true,
                                        ]
                                    );
    }/**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'HospitalBundle\Entity\Usuario'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'hospitalbundle_usuario';
    }


}
