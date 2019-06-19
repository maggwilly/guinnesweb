<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Ligne
 *
 * @ORM\Table(name="ligne")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\LigneRepository")
 */
class Ligne
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
     * @var int
     * @ORM\Column(name="quantite", type="integer", nullable=true)
     */
    private $quantite;
    /**
     * @var int
     * @ORM\Column(name="share", type="integer", nullable=true)
     */
    private $share;
    /**
     * @var int
     * @ORM\Column(name="price", type="integer", nullable=true)
     */
    private $price;

    /**
     * @var int
     * @ORM\Column(name="frigo", type="string", length=55, nullable=true)
     */
    private $frigo;

    /**
     * @var int
     * @ORM\Column(name="affiche", type="string", length=55, nullable=true)
     */
    private $affiche;

    /**
     * @var int
     * @ORM\Column(name="autre", type="string", length=55, nullable=true)
     */
    private $autre;

    /**
     * @var int
     * @ORM\Column(name="gratuite", type="integer", nullable=true)
     */
    private $gratuite;

    /**
     * @var int
     * @ORM\Column(name="invalide", type="integer", nullable=true)
     */
    private $invalide;

    /**
     * @var int
     * @ORM\Column(name="nombre_ressources", type="integer", nullable=true)
     */
    private $nombreRessources;


    /**
     * @var int
     *
     * @ORM\Column(name="mecanisme", type="string", length=255, nullable=true)
     */
    private $mecanisme;

    /**
   * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Produit",inversedBy="lignes")
   */
    private $produit;
    
    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Commende",inversedBy="lignes")
   */
    private $commende;

    /**
     * Get id
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set quantite
     *
     * @param integer $quantite
     *
     * @return Ligne
     */
    public function setQuantite($quantite)
    {
        $this->quantite = $quantite;

        return $this;
    }

    /**
     * Get quantite
     *
     * @return int
     */
    public function getQuantite()
    {
        return $this->quantite;
    }

    /**
     * Set produit
     *
     * @param \AppBundle\Entity\Produit $produit
     *
     * @return Ligne
     */
    public function setProduit(\AppBundle\Entity\Produit $produit = null)
    {
        $this->produit = $produit;

        return $this;
    }

    /**
     * Get produit
     *
     * @return \AppBundle\Entity\Produit
     */
    public function getProduit()
    {
        return $this->produit;
    }

    /**
     * Set commende
     *
     * @param \AppBundle\Entity\Commende $commende
     *
     * @return Ligne
     */
    public function setCommende(\AppBundle\Entity\Commende $commende = null)
    {
        $this->commende = $commende;

        return $this;
    }

    /**
     * Get commende
     *
     * @return \AppBundle\Entity\Commende
     */
    public function getCommende()
    {
        return $this->commende;
    }



    /**
     * Set price
     *
     * @param integer $price
     *
     * @return Ligne
     */
    public function setPrice($price)
    {
        $this->price = $price;

        return $this;
    }

    /**
     * Get price
     *
     * @return integer
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * Set nombreRessources
     *
     * @param integer $nombreRessources
     *
     * @return Ligne
     */
    public function setNombreRessources($nombreRessources)
    {
        $this->nombreRessources = $nombreRessources;

        return $this;
    }

    /**
     * Get nombreRessources
     *
     * @return integer
     */
    public function getNombreRessources()
    {
        return $this->nombreRessources;
    }

    /**
     * Set mecanisme
     *
     * @param string $mecanisme
     *
     * @return Ligne
     */
    public function setMecanisme($mecanisme)
    {
        $this->mecanisme = $mecanisme;

        return $this;
    }

    /**
     * Get mecanisme
     *
     * @return string
     */
    public function getMecanisme()
    {
        return $this->mecanisme;
    }

    /**
     * Set share
     *
     * @param integer $share
     *
     * @return Ligne
     */
    public function setShare($share)
    {
        $this->share = $share;

        return $this;
    }

    /**
     * Get share
     *
     * @return integer
     */
    public function getShare()
    {
        return $this->share;
    }

    /**
     * Set frigo
     *
     * @param string $frigo
     *
     * @return Ligne
     */
    public function setFrigo($frigo)
    {
        $this->frigo = $frigo;

        return $this;
    }

    /**
     * Get frigo
     *
     * @return string
     */
    public function getFrigo()
    {
        return $this->frigo;
    }

    /**
     * Set affiche
     *
     * @param string $affiche
     *
     * @return Ligne
     */
    public function setAffiche($affiche)
    {
        $this->affiche = $affiche;

        return $this;
    }

    /**
     * Get affiche
     *
     * @return string
     */
    public function getAffiche()
    {
        return $this->affiche;
    }

    /**
     * Set autre
     *
     * @param string $autre
     *
     * @return Ligne
     */
    public function setAutre($autre)
    {
        $this->autre = $autre;

        return $this;
    }

    /**
     * Get autre
     *
     * @return string
     */
    public function getAutre()
    {
        return $this->autre;
    }

    /**
     * Set gratuite
     *
     * @param integer $gratuite
     *
     * @return Ligne
     */
    public function setGratuite($gratuite)
    {
        $this->gratuite = $gratuite;

        return $this;
    }

    /**
     * Get gratuite
     *
     * @return integer
     */
    public function getGratuite()
    {
        return $this->gratuite;
    }

    /**
     * Set invalide
     *
     * @param integer $invalide
     *
     * @return Ligne
     */
    public function setInvalide($invalide)
    {
        $this->invalide = $invalide;

        return $this;
    }

    /**
     * Get invalide
     *
     * @return integer
     */
    public function getInvalide()
    {
        return $this->invalide;
    }
}
