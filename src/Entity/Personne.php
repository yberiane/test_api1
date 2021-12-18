<?php

namespace App\Entity;

use App\Repository\PersonneRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints\DateTime;

/**
 * @ORM\Entity(repositoryClass=PersonneRepository::class)
 */
class Personne
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nom;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $prenom;

    private $age;

    /**
     * @ORM\Column(type="string")
     */
    private $date_naissance;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): self
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getDateNaissance(): ?string
    {
        return $this->date_naissance;
    }

    public function setDateNaissance(string $date_naissance): self
    {
        $this->date_naissance = $date_naissance;

        $age = date('Y') - date('Y', strtotime($this->date_naissance));
        if (date('md') < date('md', strtotime($this->date_naissance))){
            $age -= 1;
        }
        $this->setAge($age);

        return $this;
    }

    public function getAge(): ?int
    {
        if($this->age === NULL){
            $age = date('Y') - date('Y', strtotime($this->date_naissance));
            if (date('md') < date('md', strtotime($this->date_naissance))){
                $age -= 1;
            }
            $this->setAge($age);
        }

        return $this->age;
    }

    public function setAge(int $age): self 
    {
        $this->age = $age;

        return $this;
    }
}
