<?php
declare(strict_types=1);


namespace LotGD\Crate\WWW\Form;


use Entity\Category;
use LotGD\Crate\WWW\Form\FormEntity\SceneFormEntity;
use LotGD\Crate\WWW\Form\FormEntity\UserFormEntity;
use LotGD\Crate\WWW\Model\Role;
use LotGD\Core\Models\SceneTemplate;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * Administration edit form to edit scenes.
 */
class SceneEditForm extends AbstractForm
{
    /** {@inheritdoc} */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add("title", TextType::class, [
                "empty_data" => "",
                "label" => "Scene title",
                "constraints" => [
                    new NotBlank()
                ],
                "required" => true
            ])
            ->add("description", TextareaType::class, [
                "empty_data" => "",
                "label" => "description",
                "constraints" => [
                    new NotBlank(),
                ],
                "required" => true
            ])
            ->add("template", ChoiceType::class, [
                "choices" => [null, ...$options["templates"]],
                "choice_label" => function(?SceneTemplate $template) {
                    if ($template) {
                        return "{$template->getClass()}";
                    } else {
                        return "no template";
                    }
                },
                "multiple" => false,
                "expanded" => false,
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
            "data_class" => SceneFormEntity::class
        ]);

        $resolver->setRequired([
            "templates",
        ]);
    }
}