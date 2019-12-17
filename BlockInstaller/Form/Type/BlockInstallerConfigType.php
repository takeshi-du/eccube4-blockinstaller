<?php
/*
* Plugin Name : BlockInstaller
*/

namespace Plugin\BlockInstaller\Form\Type;

use Eccube\Common\EccubeConfig;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class BlockInstallerConfigType extends AbstractType
{
    /**
     * @var EccubeConfig
     */
    protected $eccubeConfig;

    /**
    * @var ContainerInterface
    */
    protected $containerInterface;

    /**
     * BlockInstallerConfigType constructor.
     *
     * @param EccubeConfig $eccubeConfig
     */
    public function __construct(EccubeConfig $eccubeConfig, ContainerInterface $container)
    {
        $this->eccubeConfig = $eccubeConfig;
        $this->containerInterface = $container;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
    $arrBlock = [
        'bst4_header' => 'ヘッダー',
        'bst4_news' => '新着情報',
    ];
    $builder
        ->add('block_name', TextType::class, [
            'label' => 'block_name',
            'attr' => array(
             'placeholder' => 'ブロック名',
            ),
            'constraints' => [
                new Assert\NotBlank(),
            ],
        ])
        ->add('file_name', TextType::class, [
            'label' => 'file_name',
            'attr' => array(
                'placeholder' => 'FileName',
            ),
            'constraints' => [
                new Assert\NotBlank(),
                new Assert\Regex(array(
                    'pattern' => '/^[0-9a-zA-Z\/_]+$/',
                )),
            ],
        ])
        ->add('block_type', ChoiceType::class, [
            'label' => 'ブロックタイプ',
            'choices' => array_flip($arrBlock),
            'expanded' => false,
            'multiple' => false,
            'placeholder' => false,
            'constraints' => [
                new Assert\NotBlank(),
            ],
        ]);
    }
}
