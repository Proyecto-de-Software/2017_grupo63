<?php

namespace HospitalBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use HospitalBundle\Model\CurlClase;
use Symfony\Component\Form\Extension\Core\Type\DateType;

class PacienteType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $curlc = new CurlClase();
        $obras = $curlc->obtenerDatos("obra-social");
        $docs = $curlc->obtenerDatos("tipo-documento");
        $builder->add('apellido')
                ->add('nombre')
                ->add('nacimiento', DateType::class, array(
                                        'widget' => 'choice',
                                        'years' => range(1950, date('Y')),
                                        'label'  => 'Fecha de nacimiento*',
                                        'format' => 'dd/MM/yyyy',
                                        ))
                ->add('genero', 'choice', array(
                                            'choices' => ['MASCULINO' => 'MASCULINO',
                                                          'FEMENINO' => 'FEMENINO'],
                                        ))
                ->add('tipoDoc', 'choice', array(
                                            'label'  => 'Tipo de documento*',
                                            'choices' => $docs
                                        ))
                ->add('numDoc', null, array(
                                            'label'  => 'Numero de documento*',
                                        ))
                ->add('domicilio')
                ->add('telefono')
                ->add('obraSocial', 'choice', [ 'choices' => $obras]);
    }/**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'HospitalBundle\Entity\Paciente'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'hospitalbundle_paciente';
    }


}
