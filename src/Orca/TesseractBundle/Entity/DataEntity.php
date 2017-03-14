<?php

namespace Orca\TesseractBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * DataEnity
 *
 * @ORM\Table(name="data_entity")
 * @ORM\Entity(repositoryClass="Orca\TesseractBundle\Repository\DataEnityRepository")
 */
class DataEntity
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
     * @var string
     *
     * @ORM\Column(name="file", type="string", length=255)
     */
    private $file;


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
     * Set file
     *
     * @param string $file
     *
     * @return DataEnity
     */
    public function setFile($file)
    {
        $this->file = $file;

        return $this;
    }

    /**
     * Get file
     *
     * @return string
     */
    public function getFile()
    {
        return $this->file;
    }


    /**
     * @ORM\OneToMany(targetEntity="Orca\GedBundle\Entity\metadata",mappedBy="creditcase",cascade={"remove"})
     * @ORM\JoinColumn(nullable=true)
     */
    private $metadata;

    /**
     * @return mixed
     */
    public function getMetadata()
    {
        return $this->metadata;
    }

    /**
     * @param mixed $metadata
     */
    public function setMetadata($metadata)
    {
        $this->metadata = $metadata;
    }

    /**
     * @ORM\OneToMany(targetEntity="Orca\TesseractBundle\Entity\metadatadoc",mappedBy="dataEntity",cascade={"remove"})
     * @ORM\JoinColumn(nullable=true)
     */
    private $metadatadoc;

    /**
     * @return mixed
     */
    public function getMetadatadoc()
    {
        return $this->metadatadoc;
    }

    /**
     * @param mixed $metadatadoc
     */
    public function setMetadatadoc($metadatadoc)
    {
        $this->metadatadoc = $metadatadoc;
    }
}

