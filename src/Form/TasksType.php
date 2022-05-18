<?php

namespace App\Form;

use App\Entity\Client;
use App\Entity\Task;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class TasksType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {

        // $yearNow = (int)date('Y');
        // $yearNext = (int)date('Y', strtotime($yearNow . ' + 1 year'));
        // 'years' => range($yearNow, $yearNext),

        $builder
            ->add('month', DateType::class, [
                'widget' => 'single_text',
            ])
            ->add('time', TimeType::class, [
                'attr'=> [
                    'label'=> 'Hours and Minutes',
                    'class'=>'col-time text-center',
                ]
            ])
            ->add('name', TextType::class)
            ->add('description', TextareaType::class, [
                'attr'=> [
                    'rows'=> '10'
                ]
            ])
            ->add('client', EntityType::class, [
                'class'=> Client::class,
                'choice_label'=> 'name'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Task::class,
        ]);
    }
}
