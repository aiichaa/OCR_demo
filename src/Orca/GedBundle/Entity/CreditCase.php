<?php

namespace Orca\GedBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CreditCase
 *
 * @ORM\Table(name="credit_case")
 * @ORM\Entity(repositoryClass="Orca\GedBundle\Repository\CreditCaseRepository")
 */
class CreditCase
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
     * @ORM\Column(name="person", type="string", length=255)
     */
    private $person;

    /**
     * @var float
     *
     * @ORM\Column(name="sum", type="float")
     */
    private $sum;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="date")
     */
    private $date;

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
     * Set person
     *
     * @param string $person
     *
     * @return CreditCase
     */
    public function setPerson($person)
    {
        $this->person = $person;

        return $this;
    }

    /**
     * Get person
     *
     * @return string
     */
    public function getPerson()
    {
        return $this->person;
    }

    /**
     * Set sum
     *
     * @param float $sum
     *
     * @return CreditCase
     */
    public function setSum($sum)
    {
        $this->sum = $sum;

        return $this;
    }

    /**
     * Get sum
     *
     * @return float
     */
    public function getSum()
    {
        return $this->sum;
    }

    /**
     * Set date
     *
     * @param \DateTime $date
     *
     * @return CreditCase
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set file
     *
     * @param string $file
     *
     * @return CreditCase
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



}

