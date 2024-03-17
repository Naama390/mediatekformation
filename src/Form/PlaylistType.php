<?php
namespace App\Form;

use App\Entity\Formation;
use App\Entity\Playlist;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


/**
 * Formulaire PlaylistType qui permet d'ajouter ou modifier une playlist
 *
 * @author Naama Blum
 */
class PlaylistType extends AbstractType {
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
                ->add('name', TextType::class,[
                    'label' => 'Playlist',
                    'required' => true
                ])
                ->add('description', TextareaType::class,[
                    'label' => 'Description',
                    'required' => false
                ])
                ->add('submit', SubmitType::class,[
                    'label' => 'Ajouter'
                ]);
        // Si le formulaire est utilisé pour l'édition, ajoutez le champ des formations
        if ($options['editMode']) {
            $builder->add('formations', EntityType::class, [
                'class' => Formation::class,
                'label' => false,
                'choice_label' => 'title',
                'multiple' => true,
                'attr' => [
                    'style' => 'display: none;',
                ],
                'disabled' => true,
                'required' => false
            ]);
        }
    }
    
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'editMode' => false
        ]);
    }
}
