<?php

namespace Orca\TesseractBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * metadatadoc
 *
 * @ORM\Table(name="metadatadoc")
 * @ORM\Entity(repositoryClass="Orca\TesseractBundle\Repository\metadatadocRepository")
 */
class metadatadoc
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }


    /**
     * @var string
     *
     * @ORM\Column(name="content", type="text", nullable=true)
     */
    private $content;

    /**
     * @var int
     *
     * @ORM\Column(name="fileSize", type="integer", nullable=true)
     */
    private $fileSize;


    /**
     * @var string
     *
     * @ORM\Column(name="contentType", type="string", length=255, nullable=true)
     */
    private $contentType;

    /**
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @param string $content
     */
    public function setContent($content)
    {
        $this->content = $content;
    }

    /**
     * @return int
     */
    public function getFileSize()
    {
        return $this->fileSize;
    }

    /**
     * @param int $fileSize
     */
    public function setFileSize($fileSize)
    {
        $this->fileSize = $fileSize;
    }

    /**
     * @return string
     */
    public function getContentType()
    {
        return $this->contentType;
    }

    /**
     * @param string $contentType
     */
    public function setContentType($contentType)
    {
        $this->contentType = $contentType;
    }


    /**
     * @ORM\ManyToOne(targetEntity="Orca\TesseractBundle\Entity\DataEntity",cascade={"persist"})
     * @ORM\JoinColumn(nullable=true)
     */
    private $dataEntity;

    /**
     * @return mixed
     */
    public function getDataEntity()
    {
        return $this->dataEntity;
    }

    /**
     * @param mixed $dataEntity
     */
    public function setDataEntity($dataEntity)
    {
        $this->dataEntity = $dataEntity;
    }
}

