<?php

namespace App\Entity;

use App\Repository\AuthorRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Attribute\SerializedName;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: AuthorRepository::class)]
class Author
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(groups: ['create'])]
    #[Assert\Length(max: 255)]
    #[Assert\Type(['string'])]
    #[Groups('author')]
    private ?string $name = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\NotBlank(groups: ['create'])]
    #[Assert\Length(max: 255)]
    #[Assert\Type(['string'])]
    #[Groups('author')]
    private ?string $surname = null;
    #[ORM\ManyToMany(targetEntity: Book::class, inversedBy: 'authors')]
    private $books;
    public function __construct()
    {
        $this->books = new ArrayCollection();
    }
    #[Groups('author')]
    #[SerializedName('books')]
    public function getBooksData()
    {
        $booksData = [];
        foreach ($this->books as $book) {
            $booksData[] = [$book->getName().' '.$book->getYear()];
        }
        return $booksData;
    }
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getSurname(): ?string
    {
        return $this->surname;
    }

    public function setSurname(?string $surname): static
    {
        $this->surname = $surname;

        return $this;
    }
    public function getBooks()
    {
        return $this->books;
    }
    public function addBook(Book $book)
    {
        if (!$this->books->contains($book)) {
            $this->books[] = $book;
        }
    }
    public function removeBook(Book $book)
    {
        if ($this->books->contains($book)) {
            $this->books->removeElement($book);
        }
    }
}
