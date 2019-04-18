<?php
declare(strict_types=1);


namespace LotGD\Crate\WWW\Form;


use Entity\Category;
use LotGD\Crate\WWW\Form\FormEntity\UserFormEntity;
use LotGD\Crate\WWW\Model\Role;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * Administration edit form to edit users.
 */
class UserEditForm extends AbstractForm
{
    /** {@inheritdoc} */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add("displayName", TextType::class, [
                "empty_data" => "",
                "label" => "Display name",
                "constraints" => [
                    new NotBlank()
                ],
                "required" => true
            ])
            ->add("email", EmailType::class, [
                "empty_data" => "",
                "label" => "Email address",
                "constraints" => [
                    new NotBlank(),
                    new Email()
                ],
                "required" => true
            ])
            ->add("roles", ChoiceType::class, [
                "choices" => $options["roles"],
                "choice_label" => function(Role $role) {
                    return $role->getRole();
                },
                "multiple" => true,
                "expanded" => true,
            ])
        ;

        parent::buildForm($builder, $options);

        $builder
            ->add("save", SubmitType::class, [
                "label" => "Save"
            ])
        ;
    }

    /** {@inheritdoc} */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            "data_class" => UserFormEntity::class
        ]);
        $resolver->setRequired([
            "roles",
        ]);
    }
}