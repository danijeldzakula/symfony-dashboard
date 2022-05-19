<?php

namespace App\Form;

use App\Entity\Client;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\NotBlank;

class ClientsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('avatar', FileType::class, [
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '4096k',
                        'maxSizeMessage' => 'File is over {{ maxSize }}KB',
                        'mimeTypes' => [ 'image/gif', 'image/jpeg', 'image/png' ],
                        'mimeTypesMessage' => 'Please upload a valid image file',
                    ])
                ],
                'empty_data' => 'img/blank_avatar.png',
                'attr'=> [
                    'class'=> 'sidebar-avatar',
                    'html5' => false,
                ],
                'label_html' => true,
                'label' => 'Upload Photo',
                'label_attr' => [
                'class' => ''
                ],
            ])
            ->add('name', TextType::class, [
                'label'=> 'Client Name',
                'required' => false, 'constraints' => [
                    new NotBlank([
                        'message' => '* Please enter an Client Name.'
                    ])
                ],                   
            ])
            ->add('email', EmailType::class, [
                'required' => false, 'constraints' => [
                    new NotBlank([
                        'message' => '* Please enter an email.'
                    ])
                ],                  
            ])
            ->add('payment', ChoiceType::class, [
                'choices'=> [
                    'Dinacard'=>'dinacard',
                    'Mastercard'=>'mastercard',
                    'Visacard'=>'visacard',
                ],
                'required'=>true,
                'multiple'=>false,
                'expanded'=>false,                 
            ])
            ->add('bankAcc', TextType::class, [
                'label'=> 'Bank Account',
                'required'=>true,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Client::class,
        ]);
    }
}
