<?php

namespace HospitalBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class HistoriaType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('fecha', DateType::class, array(
                                        'widget' => 'choice',
                                        'years' => range(2000, date('Y')),
                                        'label'  => 'Fecha de reporte*',
                                        'format' => 'dd/MM/yyyy',
                                        ))
                ->add('peso')
                ->add('vacunas')
                ->add('vacunasOb', TextareaType::class, array(
                                            'label'  => 'Vacunas observacion',
                                        ))
                ->add('maduracion', null, array(
                                            'label'  => 'Maduracion Acorde',
                                        ))
                ->add('maduracionOb', TextareaType::class, array(
                                            'label'  => 'Maduracion observacion',
                                        ))
                ->add('fisico', null, array(
                                            'label'  => 'Examen fisico normal',
                                        ))
                ->add('fisicoOb', TextareaType::class, array(
                                            'label'  => 'Examen fisico observacion',
                                        ))
                ->add('observacion', TextareaType::class, array(
                                            'label'  => 'Observaciones generales',
                                        ))
                ->add('alimentacion', TextareaType::class, array(
                                            'label'  => 'Alimentacion',
                                        ))
                ->add('pc')
                ->add('ppc')
                ->add('talla');
    }/**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'HospitalBundle\Entity\Historia'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'hospitalbundle_historia';
    }


}
