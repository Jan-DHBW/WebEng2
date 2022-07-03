<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\Note;
use App\Entity\moveTask;
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
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Doctrine\Persistence\ManagerRegistry;


class MoveNoteFormType extends AbstractType{

private $security;
private $manager;

public function __construct(Security $security, EntityManagerInterface $entityManager)
{
    $this->security = $security;
    $this->manager = $entityManager;
}
public function buildForm(FormBuilderInterface $builder, array $options){
    $user = $this->security->getUser();
    
    $categories = $user->getCategories();
    //$uncat = $this->$manager->getRepository(Category::class)->findOneBy(array('name' => 'Uncategorized'));
   // $categories->add($uncat);
    //unset($uncat);
    $builder
        ->add('category', ChoiceType::class, [
            'label' => false,
            'attr' => array('class' => 'form-select'),
            'choices' => $categories,
            'choice_label' => 'name',
            'choice_value' => 'id',
            'placeholder' => 'Wähle eine Kategorie',
            'required' => true,
        ])
        ->add('save', SubmitType::class, array(
            'label' => 'Verschieben',
            'attr' => array('class' => 'btn btn-primary')
        ))
        ->getForm();
}
public function configureOptions(OptionsResolver $resolver): void
{
    $resolver->setDefaults([
        'data_class' => moveTask::class,
    ]);
}



}