<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * PointVente
 * @ORM\Table(name="point_vente")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\PointVenteRepository")
 */
class PointVente
{
    /**
     * @var int
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     * @ORM\Column(name="nom", type="string", length=255)
     */
    private $nom;

    /**
     * @var string
     *
     * @ORM\Column(name="ba1", type="string", length=255,nullable=true)
     */
    private $ba1;

        /**
     * @var string
     *
     * @ORM\Column(name="ba2", type="string", length=255,nullable=true)
     */
     private $ba2;
        /**
     * @var string
     *
     * @ORM\Column(name="type", type="string", length=255)
     */
     private $type;

    /**
     * @var string
     *
     * @ORM\Column(name="telephone", type="string", length=255, nullable=true)
     */
    private $telephone;
   
   /**
     * @var string
     * @ORM\Column(name="ville", type="string", length=255,nullable=true)
     */
    private $ville;

    /**
     * @var string
     *
     * @ORM\Column(name="nomGerant", type="string", length=255, nullable=true)
     */
    private $nomGerant;

    /**
     * @var string
     *
     * @ORM\Column(name="telGerant", type="string", length=255, nullable=true)
     */
    private $telGerant;
   /**
     * @var string
     * @ORM\Column(name="secteur", type="string", length=255,nullable=true)
     */
    private $secteur;
    /**
     * @var string
     *
     * @ORM\Column(name="quartier", type="string", length=255, nullable=true)
     */
    private $quartier;
       /**
     * @var string
     * @ORM\Column(name="nom_secteur", type="string", length=255,nullable=true)
     */
    private $nomSecteur;

        
        /**
     * @var string
     * @ORM\Column(name="latitude",  type="decimal", precision=10, scale=6, nullable=true)
     */
    private $latitude;

    /**
     * @var string
     *
     * @ORM\Column(name="longitude", type="decimal", precision=10, scale=6, nullable=true)
     */
    private $longitude;

    /**
     * @var string
     * @ORM\Column(name="description", type="string", length=255, nullable=true)
     */
    private $description;

    
    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User",inversedBy="pointVentes")
     * @var User
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Campagne",inversedBy="pointVentes")
     * @var User
     */
    private $campagne;

    /**
   * @ORM\OneToMany(targetEntity="AppBundle\Entity\Commende", mappedBy="pointVente", cascade={"persist","remove"})
   */
    private $commendes;

    public function __construct(\AppBundle\Entity\User $user = null)
    {
        $this->user = $user;
    }
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
     * @return PointVente
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
     * Set telephone
     *
     * @param string $telephone
     *
     * @return PointVente
     */
    public function setTelephone($telephone)
    {
        $this->telephone = $telephone;

        return $this;
    }

    /**
     * Get telephone
     *
     * @return string
     */
    public function getTelephone()
    {
        return $this->telephone;
    }

    /**
     * Set user
     *
     * @param \AppBundle\Entity\User $user
     *
     * @return PointVente
     */
    public function setUser(\AppBundle\Entity\User $user = null)
    {
        $this->user = $user;
        if($user!=null)
            $this->setVille($user->getVille());
        return $this;
    }

    /**
     * Get user
     *
     * @return \AppBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set secteur
     *
     * @param string $secteur
     *
     * @return User
     */
    public function setSecteur($secteur)
    {
        $this->secteur = $secteur;

        return $this;
    }

    /**
     * Get secteur
     *
     * @return string
     */
    public function getSecteur()
    {
        return $this->secteur;
    }

    /**
     * Set nomSecteur
     *
     * @param string $nomSecteur
     *
     * @return PointVente
     */
    public function setNomSecteur($nomSecteur)
    {
        $this->nomSecteur = $nomSecteur;

        return $this;
    }

    /**
     * Get nomSecteur
     *
     * @return string
     */
    public function getNomSecteur()
    {
        return $this->nomSecteur;
    }

    /**
     * Add commende
     *
     * @param \AppBundle\Entity\Commende $commende
     *
     * @return PointVente
     */
    public function addCommende(\AppBundle\Entity\Commende $commende)
    {
        $this->commendes[] = $commende;

        return $this;
    }

    /**
     * Remove commende
     *
     * @param \AppBundle\Entity\Commende $commende
     */
    public function removeCommende(\AppBundle\Entity\Commende $commende)
    {
        $this->commendes->removeElement($commende);
    }

    /**
     * Get commendes
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCommendes()
    {
        return $this->commendes;
    }
        /**
     * Set ville
     *
     * @param string $ville
     * @return PointVente
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
     * Set nomGerant
     *
     * @param string $nomGerant
     *
     * @return PointVente
     */
    public function setNomGerant($nomGerant)
    {
        $this->nomGerant = $nomGerant;

        return $this;
    }

    /**
     * Get nomGerant
     *
     * @return string
     */
    public function getNomGerant()
    {
        return $this->nomGerant;
    }

    /**
     * Set telGerant
     *
     * @param string $telGerant
     *
     * @return PointVente
     */
    public function setTelGerant($telGerant)
    {
        $this->telGerant = $telGerant;

        return $this;
    }

    /**
     * Get telGerant
     *
     * @return string
     */
    public function getTelGerant()
    {
        return $this->telGerant;
    }

    /**
     * Set quartier
     *
     * @param string $quartier
     *
     * @return PointVente
     */
    public function setQuartier($quartier)
    {
        $this->quartier = $quartier;

        return $this;
    }

    /**
     * Get quartier
     *
     * @return string
     */
    public function getQuartier()
    {
        return $this->quartier;
    }

    /**
     * Set latitude
     *
     * @param string $latitude
     *
     * @return PointVente
     */
    public function setLatitude($latitude)
    {
        $this->latitude = $latitude;

        return $this;
    }

    /**
     * Get latitude
     *
     * @return string
     */
    public function getLatitude()
    {
        return $this->latitude;
    }

    /**
     * Set longitude
     *
     * @param string $longitude
     *
     * @return PointVente
     */
    public function setLongitude($longitude)
    {
        $this->longitude = $longitude;

        return $this;
    }

    /**
     * Get longitude
     *
     * @return string
     */
    public function getLongitude()
    {
        return $this->longitude;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return PointVente
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

    /**
     * Set campagne
     *
     * @param \AppBundle\Entity\Campagne $campagne
     *
     * @return PointVente
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

    /**
     * Set ba1
     *
     * @param string $ba1
     *
     * @return PointVente
     */
    public function setBa1($ba1)
    {
        $this->ba1 = $ba1;

        return $this;
    }

    /**
     * Get ba1
     *
     * @return string
     */
    public function getBa1()
    {
        return $this->ba1;
    }

    /**
     * Set ba2
     *
     * @param string $ba2
     *
     * @return PointVente
     */
    public function setBa2($ba2)
    {
        $this->ba2 = $ba2;

        return $this;
    }

    /**
     * Get ba2
     *
     * @return string
     */
    public function getBa2()
    {
        return $this->ba2;
    }
}
