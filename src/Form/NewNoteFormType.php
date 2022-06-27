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
use Doctrine\ORM\EntityManagerInterface;

class NewNoteFormType extends AbstractType{
    public function buildForm(FormBuilderInterface $builder, array $options){
        $note = array_shift($options);
        $builder
            ->add('title')
         //   ->add('Catgory', ChoiceType::class, ['choices' => $options])
        ;
    }
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Note::class,
        ]);
    }



}
