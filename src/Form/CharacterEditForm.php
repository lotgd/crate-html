<?php
declare(strict_types=1);


namespace LotGD\Crate\WWW\Form;


use Entity\Category;
use LotGD\Crate\WWW\Form\FormEntity\CharacterFormEntity;
use LotGD\Crate\WWW\Form\FormEntity\UserFormEntity;
use LotGD\Crate\WWW\Model\Role;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * Class CharacterEditForm
 * @package LotGD\Crate\WWW\Form
 */
class CharacterEditForm extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add("name", TextType::class, [
                "empty_data" => "",
                "label" => "Display name",
                "constraints" => [
                    new NotBlank()
                ],
                "required" => true
            ])
            ->add("level", IntegerType::class, [
                "empty_data" => "",
                "label" => "Level",
                "constraints" => [
                    new NotBlank()
                ],
                "required" => true
            ])
            ->add("health", IntegerType::class, [
                "empty_data" => "",
                "label" => "Health",
                "constraints" => [
                    new NotBlank()
                ],
                "required" => true
            ])
            ->add("maxHealth", IntegerType::class, [
                "empty_data" => "",
                "label" => "Maximum Health",
                "constraints" => [
                    new NotBlank()
                ],
                "required" => true
            ])
            ->add("save", SubmitType::class, [
                "label" => "Save"
            ])
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            "data_class" => CharacterFormEntity::class
        ]);
    }
}