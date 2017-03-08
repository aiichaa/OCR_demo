<?php

namespace Orca\GedBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * CreditCaseDD
 *
 * @ORM\Table(name="credit_case_d_d")
 * @ORM\Entity(repositoryClass="Orca\GedBundle\Repository\CreditCaseDDRepository")
 */
class CreditCaseDD
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
     * @ORM\Column(type="string")
     *
     * @Assert\File(
     *     mimeTypes={ "application/pdf", "image/png", "image/jpeg", "image/gif"},
     *     mimeTypesMessage = "Please upload a valid file"
     * )
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



    public function setFile($file)
    {
        $this->file = $file;

        return $this;
    }



    public function getFile()
    {
        return $this->file;
    }

    /**
     * @ORM\OneToMany(targetEntity="Orca\GedBundle\Entity\metadatadd",mappedBy="creditcasedd",cascade={"remove"})
     * @ORM\JoinColumn(nullable=true)
     */
    private $metadatadd;

    /**
     * @return mixed
     */
    public function getMetadatadd()
    {
        return $this->metadatadd;
    }

    /**
     * @param mixed $metadatadd
     */
    public function setMetadatadd($metadatadd)
    {
        $this->metadatadd = $metadatadd;
    }
}

