<?php
/*
* Plugin Name : BlockInstaller
*/

namespace Plugin\BlockInstaller\Entity;

use Doctrine\ORM\Mapping as ORM;
use Eccube\Entity\AbstractEntity;

/**
 * BlockInstallerConfig
 *
 * @ORM\Table(name="plg_block_installer")
 * @ORM\Entity(repositoryClass="Plugin\BlockInstaller\Repository\BlockInstallerConfigRepository")
 */
class BlockInstallerConfig extends AbstractEntity
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", options={"unsigned":true})
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /** @var string
     *
     * @ORM\Column(name="block_name", type="text", nullable=true)
     */
    private $block_name;

    /** @var string
     *
     * @ORM\Column(name="file_name", type="text", nullable=true)
     */
    private $file_name;
    
    /**
     * @var string
     *
     * @ORM\Column(name="block_type", type="text", nullable=true)
     */
    private $block_type;


    /**
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set block_name.
     *
     * @param string $block_name
     *
     * @return BlockInstallerConfig
     */
    public function setBlockName($block_name)
    {
        $this->block_name = $block_name;

        return $this;
    }

    /**
     * Get block_name.
     *
     * @return string
     */
    public function getBlockName()
    {
        return $this->block_name;
    }

    /**
     * Set file_name.
     *
     * @param string $file_name
     *
     * @return BlockInstallerConfig
     */
    public function setFileName($file_name)
    {
        $this->file_name = $file_name;

        return $this;
    }

    /**
     * Get file_name.
     *
     * @return string
     */
    public function getFileName()
    {
        return $this->file_name;
    }

    /**
     * Set block_type.
     *
     * @param string $block_type
     *
     * @return BlockInstallerConfig
     */
    public function setBlockType($block_type)
    {
        $this->block_type = $block_type;

        return $this;
    }

    /**
     * Get block_type.
     *
     * @return string
     */
    public function getBlockType()
    {
        return $this->block_type;
    }
}
