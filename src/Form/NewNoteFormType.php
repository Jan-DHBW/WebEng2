<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\Note;
use App\Entity\Category;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Security\Core\Security;
use Doctrine\ORM\EntityManagerInterface;

class NewNoteFormType extends AbstractType{

    private $security;
    public function __construct(Security $security)
    {
        $this->security = $security;
    }
    public function buildForm(FormBuilderInterface $builder, array $options){
        $user = $this->security->getUser();
        $categories = $user->getCategories();
        $builder
            ->add('title', TextType::class, array(
                'attr' => array('class' => 'form-control')
            ))
            ->add('category', ChoiceType::class, [
                'choices' => $categories,
                'choice_label' => 'name',
                'choice_value' => 'id',
                'placeholder' => 'Wähle eine Kategorie',
                'required' => false,
            ])
            ->add('save', SubmitType::class, array(
                'label' => 'Erstellen',
                'attr' => array('class' => 'btn btn-success')
            ))
            ->getForm();
        ;
    }
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Note::class,
        ]);
    }



}
