<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 18.09.17
 * Time: 13:38
 */

namespace BotBundle\Form;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;

class IndexType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('link', UrlType::class, [
            'attr' => [
                'class' => 'form-control'
            ],
            'required' => true,
            ])
            ->add('login', TextType::class, [
                'label' => 'Логин:',
                'attr' => [
                    'class' => 'form-control'
                ],
                'label_attr' => [
                    'class' => 'label-control col-sm-3'
                ],
                'required' => true,
            ])
            ->add('password', PasswordType::class, [
                'required' => true,
                'label' => 'Пароль:',
                'label_attr' => [
                    'class' => 'label-control col-sm-3'
                ],
            ])
            ->add('count_bet', MoneyType::class, [
                'required' => true,
                'label' => 'Сумма ставки:',
                'label_attr' => [
                    'class' => 'label-control col-sm-3'
                ],
                'scale' => 2,
                'currency' => false
            ])
            ->add('match_number', IntegerType::class,[
                'required' => true,
                'label' => 'Номер матча:',
                'label_attr' => [
                    'class' => 'label-control col-sm-3'
                ],
            ])
            ->add('submit', SubmitType::class, [
                'attr' => array('class' => 'btn btn-default'),
                'label' => 'Запустить бот'
            ]);
    }
}