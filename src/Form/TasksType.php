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
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class TasksType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('month', DateType::class, [
                'widget' => 'single_text',   
                'required' => true,
            ])
            ->add('time', TimeType::class, [
                'attr'=> [
                    'label'=> 'Hours and Minutes',
                    'class'=>'col-time text-center',
                ],
                'required' => true,                  
            ])
            ->add('name', TextType::class, [
                'required' => false,
                'constraints' => [
                    new NotBlank([
                        'message' => '* Polje "name" je obavezno!',
                    ]),
                    new Length([
                        'min' => 10,
                        'minMessage' => 'Minimalan broj karaktera je 10!',
                        'max' => 4096,
                    ]),  
                ],              
            ])
            ->add('description', TextareaType::class, [
                'attr'=> [
                    'rows'=> '10',
                ],
                'required' => false,
                'constraints' => [
                    new NotBlank([
                        'message' => '* Polje "description" je obavezno!',
                    ]),
                    new Length([
                        'min' => 10,
                        'minMessage' => 'Minimalan broj karaktera je 10!',
                        'max' => 4096,
                    ]),
                ],                
            ])
            ->add('client', EntityType::class, [
                'class'=> Client::class,
                'choice_label'=> 'name',
                'required' => true,
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
