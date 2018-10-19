<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Campagne
 *
 * @ORM\Table(name="campagne")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\CampagneRepository")
 */
class Campagne
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
     * @var string
     *
     * @ORM\Column(name="pays", type="string", length=255)
     */
    private $pays;

    /**
     * @var string
     *
     * @ORM\Column(name="ville", type="string", length=255)
     */
    private $ville;

    /**
     * @var string
     *
     * @ORM\Column(name="feedback", type="text", nullable=true)
     */
    private $feedback;

        /**
     * @var string
     *
     * @ORM\Column(name="concurrence", type="text", nullable=true)
     */
    private $concurrence;

         /**
     * @var string
     *
     * @ORM\Column(name="commentaire", type="text", nullable=true)
     */
    private $commentaire;
   
    /**
     * @var string
     *
     * @ORM\Column(name="statut", type="string", length=255)
     */
    private $statut;


     /**
     * @var string
     *
     * @ORM\Column(name="datedebut", type="datetime")
     */
    private $datedebut;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    private $description;

      /**
     * @var string
     *
     * @ORM\Column(name="mecanisme", type="text", nullable=true)
     */
    private $mecanisme;  
      /**
     * @var string
     *
     * @ORM\Column(name="principe", type="text", nullable=true)
     */
    private $principe;     
    /**
     * @var string
     *
     * @ORM\Column(name="dprospects", type="string", length=255, nullable=true)
     */
    private $dprospects;

    /**
     * @var string
     *
     * @ORM\Column(name="drapports", type="string", length=255, nullable=true)
     */
    private $drapports;


    /**
     * @var string
     *
     * @ORM\Column(name="folder", type="string", length=255, nullable=true)
     */
    private $folder;

     /**
   * @ORM\OneToMany(targetEntity="AppBundle\Entity\Etape", mappedBy="campagne", cascade={"persist","remove"})
   * @ORM\OrderBy({ "datedebut" = "ASC"})
   */
    private $etapes; 
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
     * @return Campagne
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

    /**
     * Set pays
     *
     * @param string $pays
     *
     * @return Campagne
     */
    public function setPays($pays)
    {
        $this->pays = $pays;

        return $this;
    }

    /**
     * Get pays
     *
     * @return string
     */
    public function getPays()
    {
        return $this->pays;
    }

    /**
     * Set ville
     *
     * @param string $ville
     *
     * @return Campagne
     */
    public function setVille($ville)
    {
        $this->ville = $ville;

        return $this;
    }

    /**
     * Get ville
     *
     * @return string
     */
    public function getVille()
    {
        return $this->ville;
    }

    /**
     * Set dprospects
     *
     * @param string $dprospects
     *
     * @return Campagne
     */
    public function setDprospects($dprospects)
    {
        $this->dprospects = $dprospects;

        return $this;
    }

    /**
     * Get dprospects
     *
     * @return string
     */
    public function getDprospects()
    {
        return $this->dprospects;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return Campagne
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    public function getPictures()
    {
        $folder=__DIR__ . '/../../../web/activations/'.$this->getPays().'/'.$this->getFolder().'/images';
        $images =array_diff( scandir( $folder), array('..', '.'));
        return $images;
    }

    public function getFirstPicture()
    {
        $folder=__DIR__ . '/../../../web/activations/'.$this->getPays().'/'.$this->getFolder().'/images';
        $images =array_diff( scandir( $folder), array('..', '.'));
        
        return count($images)>1?array_values($images)[0]:'';
    }    
    /**
     * Set feedback
     *
     * @param string $feedback
     *
     * @return Campagne
     */
    public function setFeedback($feedback)
    {
        $this->feedback = $feedback;

        return $this;
    }

    /**
     * Get feedback
     *
     * @return string
     */
    public function getFeedback()
    {
        return $this->feedback;
    }

    /**
     * Set concurrence
     *
     * @param string $concurrence
     *
     * @return Campagne
     */
    public function setConcurrence($concurrence)
    {
        $this->concurrence = $concurrence;

        return $this;
    }

    /**
     * Get concurrence
     *
     * @return string
     */
    public function getConcurrence()
    {
        return $this->concurrence;
    }

    /**
     * Set commentaire
     *
     * @param string $commentaire
     *
     * @return Campagne
     */
    public function setCommentaire($commentaire)
    {
        $this->commentaire = $commentaire;

        return $this;
    }

    /**
     * Get commentaire
     *
     * @return string
     */
    public function getCommentaire()
    {
        return $this->commentaire;
    }

    /**
     * Set statut
     *
     * @param string $statut
     *
     * @return Campagne
     */
    public function setStatut($statut)
    {
        $this->statut = $statut;

        return $this;
    }

    /**
     * Get statut
     *
     * @return string
     */
    public function getStatut()
    {
        return $this->statut;
    }

    /**
     * Set datedebut
     *
     * @param \DateTime $datedebut
     *
     * @return Campagne
     */
    public function setDatedebut($datedebut)
    {
        $this->datedebut = $datedebut;

        return $this;
    }

    /**
     * Get datedebut
     *
     * @return \DateTime
     */
    public function getDatedebut()
    {
        return $this->datedebut;
    }

    /**
     * Set drapports
     *
     * @param string $drapports
     *
     * @return Campagne
     */
    public function setDrapports($drapports)
    {
        $this->drapports = $drapports;

        return $this;
    }

    /**
     * Get drapports
     *
     * @return string
     */
    public function getDrapports()
    {
        return $this->drapports;
    }

    /**
     * Set mecanisme
     *
     * @param string $mecanisme
     *
     * @return Campagne
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
     * Set principe
     *
     * @param string $principe
     *
     * @return Campagne
     */
    public function setPrincipe($principe)
    {
        $this->principe = $principe;

        return $this;
    }

    /**
     * Get principe
     *
     * @return string
     */
    public function getPrincipe()
    {
        return $this->principe;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->etapes = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add etape
     *
     * @param \AppBundle\Entity\Etape $etape
     *
     * @return Campagne
     */
    public function addEtape(\AppBundle\Entity\Etape $etape)
    {
        $this->etapes[] = $etape;

        return $this;
    }

    /**
     * Remove etape
     *
     * @param \AppBundle\Entity\Etape $etape
     */
    public function removeEtape(\AppBundle\Entity\Etape $etape)
    {
        $this->etapes->removeElement($etape);
    }

    /**
     * Get etapes
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getEtapes()
    {
        return $this->etapes;
    }

    /**
     * Set folder
     *
     * @param string $folder
     *
     * @return Campagne
     */
    public function setFolder($folder)
    {
        $this->folder = $folder;

        return $this;
    }

    /**
     * Get folder
     *
     * @return string
     */
    public function getFolder()
    {
        return $this->folder;
    }
}