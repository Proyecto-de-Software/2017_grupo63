<?php

namespace HospitalBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use HospitalBundle\Model\CurlClase;

class DatosDemograficosType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $curlc = new CurlClase();
        $viviendas = $curlc->obtenerDatos("tipo-vivienda");
        $calefacciones = $curlc->obtenerDatos("tipo-calefaccion");
        $aguas = $curlc->obtenerDatos("tipo-agua");
        $builder->add('heladera', 'choice', array(
                                            'choices' => ['1' => 'SI',
                                                          '0' => 'NO'],
                                        ))
        ->add('electricidad', 'choice', array(
                                            'choices' => ['1' => 'SI',
                                                          '0' => 'NO'],
                                        ))
        ->add('mascota', 'choice', array(
                                            'choices' => ['1' => 'SI',
                                                          '0' => 'NO'],
                                        ))
        ->add('tipoViviendaId', 'choice', [ 'choices' => $viviendas, 'label' => 'Tipo de vivienda'])
        ->add('tipoCalefaccionId', 'choice', [ 'choices' => $calefacciones, 'label' => 'Tipo de calefaccion'])
        ->add('tipoAguaId', 'choice', [ 'choices' => $aguas, 'label' => 'Tipo de agua']);
    }/**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'HospitalBundle\Entity\DatosDemograficos'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'hospitalbundle_datosdemograficos';
    }


}
