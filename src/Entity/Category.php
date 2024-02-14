<?php

namespace App\Entity;

use App\Repository\CategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[ORM\Entity(repositoryClass: CategoryRepository::class)]
#[UniqueEntity(fields:['name'], message:"Ce nom de catégorie est déjà utilisé.")]
class Category
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50, unique: true)]
    private ?string $name = null;

    #[ORM\OneToMany(mappedBy: 'category', targetEntity: Wish::class)]
    private Collection $category;

    public function __construct()
    {
        $this->category = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): static
    {
        $this->id = $id;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection<int, Wish>
     */
    public function getCategory(): Collection
    {
        return $this->category;
    }

    public function addRelation(Wish $relation): static
    {
        if (!$this->category->contains($relation)) {
            $this->category->add($relation);
            $relation->setCategory($this);
        }

        return $this;
    }

    public function removeRelation(Wish $relation): static
    {
        if ($this->category->removeElement($relation)) {
            // set the owning side to null (unless already changed)
            if ($relation->getCategory() === $this) {
                $relation->setCategory(null);
            }
        }

        return $this;
    }
}
