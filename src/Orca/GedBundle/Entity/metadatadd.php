<?php

namespace Orca\GedBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * metadatadd
 *
 * @ORM\Table(name="metadatadd")
 * @ORM\Entity(repositoryClass="Orca\GedBundle\Repository\metadataddRepository")
 */
class metadatadd
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
     * @ORM\Column(name="author", type="string", length=255, nullable=true)
     */
    private $author;

    /**
     * @var string
     *
     * @ORM\Column(name="contentType", type="string", length=255, nullable=true)
     */
    private $contentType;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="createdDate", type="date", nullable=true)
     */
    private $createdDate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="modifiedDate", type="date", nullable=true)
     */
    private $modifiedDate;

    /**
     * @var int
     *
     * @ORM\Column(name="pageCount", type="integer", nullable=true)
     */
    private $pageCount;


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
     * Set content
     *
     * @param string $content
     *
     * @return metadata
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Get content
     *
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Set fileSize
     *
     * @param integer $fileSize
     *
     * @return metadata
     */
    public function setFileSize($fileSize)
    {
        $this->fileSize = $fileSize;

        return $this;
    }

    /**
     * Get fileSize
     *
     * @return int
     */
    public function getFileSize()
    {
        return $this->fileSize;
    }

    /**
     * Set author
     *
     * @param string $author
     *
     * @return metadata
     */
    public function setAuthor($author)
    {
        $this->author = $author;

        return $this;
    }

    /**
     * Get author
     *
     * @return string
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * Set contentType
     *
     * @param string $contentType
     *
     * @return metadata
     */
    public function setContentType($contentType)
    {
        $this->contentType = $contentType;

        return $this;
    }

    /**
     * Get contentType
     *
     * @return string
     */
    public function getContentType()
    {
        return $this->contentType;
    }

    /**
     * Set createdDate
     *
     * @param \DateTime $createdDate
     *
     * @return metadata
     */
    public function setCreatedDate($createdDate)
    {
        $this->createdDate = $createdDate;

        return $this;
    }

    /**
     * Get createdDate
     *
     * @return \DateTime
     */
    public function getCreatedDate()
    {
        return $this->createdDate;
    }

    /**
     * Set modifiedDate
     *
     * @param \DateTime $modifiedDate
     *
     * @return metadata
     */
    public function setModifiedDate($modifiedDate)
    {
        $this->modifiedDate = $modifiedDate;

        return $this;
    }

    /**
     * Get modifiedDate
     *
     * @return \DateTime
     */
    public function getModifiedDate()
    {
        return $this->modifiedDate;
    }

    /**
     * Set pageCount
     *
     * @param integer $pageCount
     *
     * @return metadata
     */
    public function setPageCount($pageCount)
    {
        $this->pageCount = $pageCount;

        return $this;
    }

    /**
     * Get pageCount
     *
     * @return int
     */
    public function getPageCount()
    {
        return $this->pageCount;
    }

    /**
     * @ORM\ManyToOne(targetEntity="Orca\GedBundle\Entity\CreditCaseDD")
     * @ORM\JoinColumn(nullable=true)
     */
    private $creditcasedd;

    /**
     * @return mixed
     */
    public function getCreditcasedd()
    {
        return $this->creditcasedd;
    }

    /**
     * @param mixed $creditcasedd
     */
    public function setCreditcasedd($creditcasedd)
    {
        $this->creditcasedd = $creditcasedd;
    }


}

