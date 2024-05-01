<?php

namespace App\Entity;

use App\Repository\PublisherRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: PublisherRepository::class)]
class Publisher
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups('publisher')]
    #[Assert\NotBlank(groups: ['create','update'])]
    #[Assert\Length(min: 7,max: 255, groups: ['create','update'])]
    #[Assert\Type(['string'])]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    #[Groups('publisher')]
    #[Assert\NotBlank(groups: ['create','update'])]
    #[Assert\Length(min: 7,max: 255, groups: ['create','update'])]
    #[Assert\Type(['string'])]
    private ?string $address = null;
    #[ORM\OneToMany(targetEntity: Book::class,mappedBy: 'publisher')]
    private Collection|null $books;

    public function __construct()
    {
        $this->books= new ArrayCollection();
    }
    public function getId(): ?int
    {
        return $this->id;
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

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(string $address): static
    {
        $this->address = $address;

        return $this;
    }
    public function getBooks(): Collection
    {
        return $this->books;
    }
}
