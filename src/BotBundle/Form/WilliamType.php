<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 13.09.17
 * Time: 15:46
 */

namespace BotBundle\Form;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\FormBuilderInterface;

class WilliamType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('link', null, array(
                'required' => true,
                'label' => 'Всавьте id или ссылку матча:',
                'attr' => ['class' => 'form-control']
            ))
            ->add('watch', ButtonType::class, array(
                'attr' => ['class' => 'btn btn-primary watch_button']
            ));
    }
}