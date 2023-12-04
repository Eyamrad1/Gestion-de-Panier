<?php

namespace App\Entity;

use App\Repository\ProduitRepository;
use Doctrine\ORM\Mapping as ORM;
use phpDocumentor\Reflection\File;
use phpDocumentor\Reflection\PseudoTypes\FloatValue;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\DBAL\Types\Types;
use Vich\UploaderBundle\Mapping\Annotation as Vich;



#[ORM\Entity(repositoryClass: ProduitRepository::class)]
#[Vich\Uploadable]

class Produit
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    /**
     * @Assert\Type(type="float", message="Le prix doit être un nombre à virgule flottante")
     * @Assert\GreaterThan(value=0, message="Le prix doit être supérieur à zéro")
     */

    #[ORM\Column(type: "float")]
    #[Assert\GreaterThanOrEqual(
        value: 0,
        message: "Le prix doit être supérieur à zéro"
    )]
    #[Assert\Type(
        type: "numeric",
        message: "Le prix doit être un nombre."
    )]
    #[Assert\NotBlank(message: "champ obligatoire")]
    #[Assert\Positive(message: "doit être supérieur à zéro")]
    private $prix = null;

    #[ORM\Column(type: "string", length: 255)]
    #[Assert\NotBlank(message: "champ obligatoire")]
    #[Assert\Length(max: 255, maxMessage: "Le nom du produit ne peut pas dépasser {{ limit }} caractères.")]
    private $nom_produit = null;

    #[ORM\Column(length: 255)]
    private ?string $description = null;

    #[ORM\Column(length: 255)]
    private ?string $image;

    #[Vich\UploadableField(mapping: "produit",fileNameProperty: "image")]
    private ?File $imageFile;


    #[ORM\Column]
    private ?int $nombre_produit = null;


    #[ORM\ManyToOne( targetEntity: Typeproduit::class,cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(name: "id_t_id",referencedColumnName: "id" )]
    private ?Typeproduit $idT ;

//    #[ORM\Column]
//
//    private ?\DateTime $updateAt;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getNomProduit(): ?string
    {
        return $this->nom_produit;
    }

    public function setNomProduit(string $nom_produit): static
    {
        $this->nom_produit = $nom_produit;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): static
    {
        $this->image = $image;

        return $this;
    }




    public function getNombreProduit(): ?int
    {
        return $this->nombre_produit;
    }

    public function setNombreProduit(int $nombre_produit): static
    {
        $this->nombre_produit = $nombre_produit;

        return $this;
    }

    public function getIdT(): ?Typeproduit
    {
        return $this->idT;
    }

    public function setIdT(?Typeproduit $idT):static
    {
        $this->idT = $idT;

        return $this;
    }



}