<?php
declare(strict_types=1);


namespace LotGD\Crate\WWW\Form;


use LotGD\Crate\WWW\Model\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\IdenticalTo;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Mapping\ClassMetadata;

class UserCreationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add("displayName", TextType::class, [
                "empty_data" => "",
                "label" => "Display name",
                "constraints" => [
                    new NotBlank()
                ],
                "required" => True,
            ])
            ->add("email", RepeatedType::class, [
                "type" => EmailType::class,
                "invalid_message" => "The email addresses must match",
                "options" => ["attr" => ["class" => "email-field"]],
                "required" => True,
                "first_options"  => ["label" => "Email address"],
                "second_options" => ["label" => "Repeat the email address"],
            ])
            ->add("password", RepeatedType::class, [
                "type" => PasswordType::class,
                "invalid_message" => "The password fields must match",
                "options" => ["attr" => ["class" => "password-field"]],
                "required" => true,
                "first_options"  => ["label" => "Password"],
                "second_options" => ["label" => "Repeat the password"],
            ])
            ->add("save", SubmitType::class, [
                "label" => "Register"
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            "data_class" => User::class
        ]);
    }
}