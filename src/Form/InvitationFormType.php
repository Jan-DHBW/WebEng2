<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\Note;
use App\Entity\moveTask;
use App\Entity\Category;
use App\Entity\Invitaion;
use App\Entity\invTask;
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


class InvitationFormType extends AbstractType{

private $security;
public function __construct(Security $security)
{
    $this->security = $security;
}
public function buildForm(FormBuilderInterface $builder, array $options){
    $user = $this->security->getUser();
    $currentInvitations = $user->getInvitaions();
    $builder
        ->add('add', TextType::class, array(
            'label' => 'Email zum Hinzufügen',
            'attr' => array('class' => 'form-control'),
            'required' => false,
        ))
        ->add('remove', ChoiceType::class, array(
            'label' => 'Test',
            'attr' => array('class' => 'form-select'),
            'choices' => $options['invitees'],
            'choice_value' => 'id',
            'placeholder' => 'Hier den zu entfrenden Eintrag auswählen',
            'required' => false,
        ))
        ->add('save', SubmitType::class, array(
            'label' => 'Erstellen',
            'attr' => array('class' => 'btn btn-success')
        ))
        ->getForm();
}
public function configureOptions(OptionsResolver $resolver): void
{
    $resolver->setDefaults([
        'data_class' => invTask::class,
        'invitees' => array(),
    ]);
}



}