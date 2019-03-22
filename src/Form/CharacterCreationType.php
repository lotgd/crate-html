<?php
declare(strict_types=1);


namespace LotGD\Crate\WWW\Form;


use LotGD\Core\Models\Character;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\Validator\Mapping\ClassMetadata;

class CharacterCreationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add("name", TextType::class, [
                "empty_data" => "",
                "label" => "Character name",
                "constraints" => [
                    new NotBlank(),
                ]
            ])
            ->add("save", SubmitType::class, [
                "label" => "Create character"
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            "data_class" => Character::class
        ]);
    }
}