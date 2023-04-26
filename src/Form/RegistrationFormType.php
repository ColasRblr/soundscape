<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            // ->add('first_name')
            // ->add('last_name')
            // ->add('email')
            
            ->add('lastname', TextType::class,[
                "label" =>"Nom: ",
                "attr" =>[
                    "placeholder" =>"Entrez votre prenom",
                    "class" => "form-group firstName-input",
                    "row_attr" => "form-group"
                ],
                "row_attr" => [
                    "class" => "form-group"
                ]
        
            ])
            ->add('firstname', TextType::class,[
                "label" =>"Prénom : ",
                "attr" =>[
                    "placeholder" =>"Entrez votre nom",
                    "class" => "form-group firstName-input",
                    "row_attr" => "form-group"
                ],
                "row_attr" => [
                    "class" => "form-group"
                ]
            ])
            ->add('email', EmailType::class,[
                "label" =>"Adresse mail : ",
                "attr" =>[
                    "placeholder" =>"Entrez votre email",
                    "class" => "form-group email-input",
                    "row_attr" => "form-group"
                ],
                "row_attr" => [
                    "class" => "form-group"
                ]
            
            ])
            ->add('plainPassword', PasswordType::class, [
                // instead of being set onto the object directly,
                // this is read and encoded in the controller
                'mapped' => false,
                'attr' => ['autocomplete' =>
                
                 'new-password'],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter a password',
                        
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Your password should be at least {{ limit }} characters',
                        // max length allowed by Symfony for security reasons
                        'max' => 4096,
                    ]),
                ],
            ])
            
            // ->add('Valider', SubmitType::class, [
            //     'row_attr' => [
            //         'class' => 'form-group'
            //     ],
            //     'attr' => [
            //         'class' => 'btn btn-left'
            //     ]
            // ])
            
            // ->add('Annuler', SubmitType::class, [
            //     'row_attr' => [
            //         'class' => 'form-group'
            //     ],
            //     'attr' => [
            //         'class' => ''
            //     ]
            // ]);
            
        ;

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
