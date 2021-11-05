<?php

declare(strict_types=1);

namespace App\Form;

use App\Entity\Message;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MessageType extends AbstractType
{
    /**
     * The field can be modified (edit or create message)
     * @var bool
     */
    private $status = false;

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {

            $form = $event->getForm();
            $message = $event->getData();

            if ($message->getId() !== null) {
                $this->status = true;
                $form
                    ->add('treated', CheckboxType::class, [
                        'required' => false,
                    ]);
            }
            $form
                ->add('name', TextType::class, [
                    'required' => true,
                    'disabled' => $this->status,
                    'attr' => [
                        'placeholder' => 'Votre nom'
                    ]
                ])
                ->add('email', EmailType::class, [
                    'required' => true,
                    'disabled' => $this->status,
                    'attr' => [
                        'placeholder' => 'Votre email'
                    ]
                ])
                ->add('content', TextareaType::class, [
                    'required' => true,
                    'disabled' => $this->status,
                    'attr' => [
                        'placeholder' => 'Votre message',
                        'rows' => 8
                    ]
                ]);
        });
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Message::class,
        ]);
    }
}
