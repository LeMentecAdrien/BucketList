<?php

namespace App\Entity;


use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiResource;
use App\Repository\WishRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use phpDocumentor\Reflection\Types\Boolean;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: WishRepository::class)]
#[ORM\HasLifecycleCallbacks()]
#[ApiResource]
class Wish
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[ApiFilter(SearchFilter::class, properties: ['name' => 'partial'])]
    private ?int $id = null;

    #[ORM\Column(length: 250)]
    #[Assert\NotBlank(message: 'Title required')]
    private ?string $title = null;


    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(length: 50)]
    #[Assert\NotBlank(message: ' Sign up your wish')]
    private ?string $author = null;

    #[ORM\Column(nullable: true)]
    private ?bool $isPublished = true;


    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $dateCreated = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $dateUpdated = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $posterFile = null;

    #[Assert\NotNull(message: 'Category required')]
    #[ORM\ManyToOne(inversedBy: 'relation')]
    private ?Category $category = null;





    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

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


    public function getAuthor(): ?string
    {
        return $this->author;
    }

    public function setAuthor(string $author): static
    {
        $this->author = $author;

        return $this;
    }

    public function isIsPublished(): ?Bool
    {
        return $this->isPublished;
    }

    public function setIsPublished(?bool $isPublished): static
    {
        $this->isPublished = $isPublished;

        return $this;
    }

    public function getDateCreated(): ?\DateTimeInterface
    {
        return $this->dateCreated;
    }

    #[ORM\PrePersist]
    public function setDateCreated(): static
    {
        $this->dateCreated = new \DateTime();

        return $this;
    }


    public function getDateUpdated(): ?\DateTimeInterface
    {
        return $this->dateUpdated;
    }
    #[ORM\PreUpdate]
    public function setDateUpdated(): static
    {
        $this->dateUpdated = new \DateTime();

        return $this;
    }

    public function getPosterFile(): ?string
    {
        return $this->posterFile;
    }

    public function setPosterFile(?string $posterFile): static
    {
        $this->posterFile = $posterFile;

        return $this;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): static
    {
        $this->category = $category;

        return $this;
    }



}
