<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class UsersType extends AbstractType
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
            ->add('firstName', TextType::class, [
                'required' => false, 'constraints' => [
                    new NotBlank([
                        'message' => '* Please enter an First name.'
                    ])
                ],                 
            ])
            ->add('lastName', TextType::class, [
                'required' => false, 'constraints' => [
                    new NotBlank([
                        'message' => '* Please enter an Last name.'
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
            ->add('plainPassword', PasswordType::class, [
                'required' => false,
                'mapped' => false,
                'attr' => ['autocomplete' => 'new-password'],
                'constraints' => [
                    new NotBlank([
                        'message' => '* Please enter a password',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Your password should be at least {{ limit }} characters',
                        'max' => 4096,
                    ]),
                ],
            ])            
            ->add('street', TextType::class, [
                'required' => false, 'constraints' => [
                    new NotBlank([
                        'message' => '* Please enter an Street.'
                    ])
                ],                                  
            ])
            ->add('city', TextType::class, [
                'required' => false, 'constraints' => [
                    new NotBlank([
                        'message' => '* Please enter an City.'
                    ])
                ],                
            ])
            ->add('country', TextType::class, [
                'required' => false, 'constraints' => [
                    new NotBlank([
                        'message' => '* Please enter an Country.'
                    ])
                ],          
            ])         
            ->add('bankAcc', TextType::class, [
                'label'=>'Bank Account',
                'required' => false, 'constraints' => [
                    new NotBlank([
                        'message' => '* Please enter an Bank Acc.'
                    ])
                ],                     
            ])         
            ->add('roles', ChoiceType::class, [
                'choices'=>[
                    'Dev'=>'ROLE_DEV',
                    'Admin'=>'ROLE_ADMIN',
                ],
                'required'=>true,
                'multiple'=>false,
                'expanded'=>false,                
            ])
            ->add('status', ChoiceType::class, [
                'choices'=> [
                    'Inactive'=>0,
                    'Active'=>1,
                ],
                'required'=>true,
                'multiple'=>false,
                'expanded'=>false,                             
            ])
            ->add('hidden', HiddenType::class, [
                'required'=>true,
                'mapped'=>false,
            ]);

        $builder->get('roles')
            ->addModelTransformer(new CallbackTransformer(
                function ($rolesArray) {
                    // transform the array to a string
                    return count($rolesArray)? $rolesArray[0]: null;
                },
                function ($rolesString) {
                    // transform the string back to an array
                    return [$rolesString];
                }
            ));            
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            // 'is_admin' => false
        ]);
    }
}














// , [
//     'mapped'=>true,
//     'constraints'=>[
//         new Length([
//             'min'=>6,
//             'minMessage'=>'Your password should be at least {{ limit }} characters',
//             'max'=>4096,
//         ]),
//     ],
// ]
// ->add('submit', SubmitType::class, [
//     'label'=>'Update',
//     'attr'=> ['class'=>'btn btn-primary'],
// ])  
// ->add('button', ButtonType::class, [
//     'label'=>'Cancel',
//     'attr'=> [
//         'class'=>'btn btn-primary-outline',
//         'data-toggle-close'=>''
//     ],
// ])   
# Credits - https://stackoverflow.com/questions/51744484/symfony-form-choicetype-error-array-to-string-covnersion