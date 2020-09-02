<?php
/**
 * OrderType class.
 *
 * @Project : exo-alma
 * @File    : OrderType.php
 * @Author  : Nicolas BOULFROY
 * @Create  : 2020/09/02
 */

namespace App\Type;

use App\Entity\Order;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OrderType extends AbstractType
{
    /**
     * @inheritDoc
     *
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Order::class,
        ]);
    }

    /**
     * @inheritDoc
     *
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('streetName', TextType::class, [
                'label' => 'Rue :',
                'required' => true,
                'mapped' => false,
            ])
            ->add('zipCode', TextType::class, [
                'label' => 'Code postal :',
                'required' => true,
                'mapped' => false,
            ])
            ->add('city', TextType::class, [
                'label' => 'Ville :',
                'required' => true,
                'mapped' => false,
            ])
            ->add('firstName', TextType::class, [
                'label' => 'Prénom :',
                'required' => true,
            ])
            ->add('lastName', TextType::class, [
                'label' => 'Nom de famille :',
                'required' => true,
            ])
            ->add('email', TextType::class, [
                'label' => 'e-mail :',
                'required' => true,
            ])
            ->add('phoneNumber', TextType::class, [
                'label' => 'Numéro de téléphone :',
                'required' => true,
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Valider',
            ])
        ;

        $builder->addEventListener(FormEvents::SUBMIT, function (FormEvent $event) {
            $order = $event->getData();
            $form = $event->getForm();

            $order->setAddress($form->get('streetName')->getData()
                .', '.$form->get('zipCode')->getData()
                .', '.$form->get('city')->getData()
            );

            $form
                ->remove('streetName')
                ->remove('zipCode')
                ->remove('city')
                ->add('address', TextType::class, [
                    'required' => true,
                ])
            ;
        });
    }
}