<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Produit
 *
 * @ORM\Table(name="produit")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ProduitRepository")
 */
class Produit
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
     * @ORM\Column(name="nom", type="string", length=255)
     */
    private $nom;

    /**
     * @var int
     *
     * @ORM\Column(name="cout", type="integer", nullable=true)
     */
    private $cout;
        
        /**
     * @var string
     *
     * @ORM\Column(name="type", type="string", length=255, nullable=true)
     */
     private $type;

    /**
   * @ORM\OneToMany(targetEntity="AppBundle\Entity\Ligne", mappedBy="produit", cascade={"persist","remove"})
   */
    private $lignes;
    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Campagne")
     * @var User
     */
    private $campagne;
        /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Produit", cascade={"persist","remove"})
     * @var User
     */
    protected $concurent;
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
     * Set nom
     *
     * @param string $nom
     *
     * @return Produit
     */
    public function setNom($nom)
    {
        $this->nom = $nom;

        return $this;
    }

    /**
     * Get nom
     *
     * @return string
     */
    public function getNom()
    {
        return $this->nom;
    }

    public function getShortNom()
    {
        $whatIWant = substr($this->nom, strpos($this->nom, "->") + 1)
        return $whatIWant;
    }
    /**
     * Set type
     *
     * @param string $type
     *
     * @return PointVente
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set cout
     *
     * @param integer $cout
     *
     * @return Produit
     */
    public function setCout($cout)
    {
        $this->cout = $cout;

        return $this;
    }

    /**
     * Get cout
     *
     * @return int
     */
    public function getCout()
    {
        return $this->cout;
    }

    /**
     * Add ligne
     *
     * @param \AppBundle\Entity\Ligne $ligne
     *
     * @return Commende
     */
    public function addLigne(\AppBundle\Entity\Ligne $ligne)
    {
        $this->lignes[] = $ligne;

        return $this;
    }

    /**
     * Remove ligne
     *
     * @param \AppBundle\Entity\Ligne $ligne
     */
    public function removeLigne(\AppBundle\Entity\Ligne $ligne)
    {
        $this->lignes->removeElement($ligne);
    }

    /**
     * Get lignes
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getLignes()
    {
        return $this->lignes;
    }

    /**
     * Set concurent
     *
     * @param \AppBundle\Entity\Produit $concurent
     * @return Produit
     */
    public function setConcurent(\AppBundle\Entity\Produit $concurent = null)
    {
        $this->concurent = $concurent;

        return $this;
    }

    /**
     * Get concurent
     *
     * @return \AppBundle\Entity\Produit 
     */
    public function getConcurent()
    {
        return $this->concurent;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->lignes = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Set campagne
     *
     * @param \AppBundle\Entity\Campagne $campagne
     *
     * @return Produit
     */
    public function setCampagne(\AppBundle\Entity\Campagne $campagne = null)
    {
        $this->campagne = $campagne;

        return $this;
    }

    /**
     * Get campagne
     *
     * @return \AppBundle\Entity\Campagne
     */
    public function getCampagne()
    {
        return $this->campagne;
    }
}
