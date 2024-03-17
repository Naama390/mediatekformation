<?php
namespace App\Form;

use App\Entity\Categorie;
use App\Entity\Playlist;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\DateTime;


/**
 * Formulaire FormationType qui permet d'ajouter ou modifier une formation
 *
 * @author Naama Blum
 */
class FormationType extends AbstractType {
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
                ->add('title', TextType::class,[
                    'label' => 'Formation',
                    'required' => true
                ])
                ->add('description', TextareaType::class,[
                    'label' => 'Description',
                    'required' => false
                ])
                ->add('playlist', EntityType::class,[
                    'class' => Playlist::class,
                    'label' => 'Playlist',
                    'choice_label' => 'name',
                    'multiple' => false,
                    'required' => true
                ])
                ->add('categories', EntityType::class,[
                    'class' => Categorie::class,
                    'label' => 'Categorie',
                    'choice_label' => 'name',
                    'multiple' => true,
                    'required' => false
                ])
                ->add('publishedAt', DateType::class,[
                    'widget'=> 'single_text',
                    'data' => $options['data']->getPublishedAt() ?? new \DateTime(),
                    'label' => 'Date',
                    'required' => true
                ])
                ->add('videoId', TextType::class,[
                    'label' => 'Lien de la vidéo',
                    'required' => false,
                ])
                ->add('submit', SubmitType::class,[
                    'label' => 'Confirmer'
                ]);
    }
}
