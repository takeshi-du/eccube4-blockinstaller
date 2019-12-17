<?php
/*
* Plugin Name : BlockInstaller
*/

namespace Plugin\BlockInstaller\Controller;

use Eccube\Controller\AbstractController;
use Plugin\BlockInstaller\Form\Type\BlockInstallerConfigType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Eccube\Entity\Block;
use Eccube\Entity\BlockPosition;
use Eccube\Repository\BlockPositionRepository;
use Eccube\Repository\BlockRepository;
use Symfony\Component\Filesystem\Filesystem;
use Eccube\Entity\Master\DeviceType;
use Eccube\Repository\Master\DeviceTypeRepository;

/**
 * Class ConfigController.
 */
class ConfigController extends AbstractController
{
    /**
     * @Route("/%eccube_admin_route%/block_installer/config", name="block_installer_admin_config")
     * @Template("@BlockInstaller/admin/config.twig")
     *
     * @param Request $request
     * @param ContainerInterface $container
     *
     * @return array
     */
	public function index(Request $request, ContainerInterface $container)
	{
		$form = $this->createForm(BlockInstallerConfigType::class);
		$form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $request->request->get('block_installer_config');
            $block_name = $data['block_name'];
            $file_name = $data['file_name'];
            $block_type = $data['block_type'];

            // // ブロックをテンプレートにコピー
            $this->copyBlock($container, $file_name, $block_type);
            // // ファイル名でデータベースを検索
            $Block = $container->get(BlockRepository::class)->findOneBy(['file_name' => $file_name]);
            // // ファイル名がなければブロックをデータベースに登録、テンプレートの内容が置き換わるだけ
            if (is_null($Block)) {
                // pagelayoutの作成
                $this->createDataBlock($container, $block_name, $file_name);
            }

            log_info('config', ['status' => 'Success']);
            $this->addSuccess('block_installer.admin.config.save.complete', 'admin');

            return $this->redirectToRoute('block_installer_admin_config');
        }

		return [
            'form' => $form->createView(),
        ];
	}

    /**
     * Copy block template.
     *
     * @param ContainerInterface $container
     * @param block_type int
     */
    private function copyBlock(ContainerInterface $container, $file_name, $block_type)
    {
        $templateDir = $container->getParameter('eccube_theme_front_dir');
        $originBlock = __DIR__.'/../Resource/template/Block/'.$block_type.'.twig';

        $file = new Filesystem();
        $file->copy($originBlock, $templateDir.'/Block/'.$file_name.'.twig', true);
    }

    /**
     * ブロックを登録.
     *
     * @param ContainerInterface $container
     *
     * @throws \Exception
     */
    private function createDataBlock(ContainerInterface $container, $block_name, $file_name)
    {
        $em = $container->get('doctrine.orm.entity_manager');
        $DeviceType = $container->get(DeviceTypeRepository::class)->find(DeviceType::DEVICE_TYPE_PC);

        try {
            /** @var Block $Block */
            $Block = $container->get(BlockRepository::class)->newBlock($DeviceType);

            // Blockの登録
            $Block->setName($block_name)
                ->setFileName($file_name)
                ->setUseController(false)
                ->setDeletable(true);
            $em->persist($Block);
            $em->flush($Block);
        } catch (\Exception $e) {
            throw $e;
        }
    }
}
