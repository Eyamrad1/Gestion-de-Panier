<?php

namespace App\Entity;

use App\Repository\LivraisonRepository;
use Doctrine\ORM\Mapping as ORM;
use http\Message;
use Symfony\Component\Validator\Constraint as Assert;

#[ORM\Entity(repositoryClass: LivraisonRepository::class)]
class Livraison
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Assert\NotBlank(Message: "champ obligatoire")]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(Message: "champ obligatoire")]
    private ?string $nom = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(Message: "champ obligatoire")]
    private ?string $prenom = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(Message: "champ obligatoire")]
    private ?string $adresse = null;

    #[ORM\Column]
    #[Assert\NotBlank(Message: "champ obligatoire")]
    #[Assert\GreaterThanOrEqual(value: 0, message: "Le prix doit être un nombre positif.")]

    private ?float $prix = null;

    #[ORM\Column]
    #[Assert\NotBlank(Message: "champ obligatoire")]
    private ?int $nbrC = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): static
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(string $adresse): static
    {
        $this->adresse = $adresse;

        return $this;
    }

    public function getPrix(): ?float
    {
        return $this->prix;
    }

    public function setPrix(float $prix): static
    {
        $this->prix = $prix;

        return $this;
    }

    public function getNbrC(): ?int
    {
        return $this->nbrC;
    }

    public function setNbrC(int $nbrC): static
    {
        $this->nbrC = $nbrC;

        return $this;
    }
}
