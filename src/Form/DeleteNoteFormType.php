<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\Note;
use App\Entity\Category;
use App\Entity\deleteTask;
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

class DeleteNoteFormType extends AbstractType{

private $security;
public function __construct(Security $security)
{
    $this->security = $security;
}
public function buildForm(FormBuilderInterface $builder, array $options){
    $user = $this->security->getUser();
    $builder
        ->add('save', SubmitType::class, array(
            'label' => 'Löschen',
            'attr' => array('class' => 'btn btn-danger')
        ))
        ->getForm();
    ;
}
public function configureOptions(OptionsResolver $resolver): void
{
    $resolver->setDefaults([
        'data_class' => deleteTask::class,
    ]);
}



}