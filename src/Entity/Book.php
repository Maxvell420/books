<?php

namespace App\Entity;

use App\Repository\BookRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Attribute\SerializedName;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: BookRepository::class)]
class Book
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]

    private ?int $id = null;

    #[ORM\Column(length: 255)]

//    Группы для сериализации
    #[Groups('books')]

//    Для валидации
    #[Assert\NotBlank(groups: ['create'])]
    #[Assert\Length(min: 7,max: 255, groups: ['create'])]
    #[Assert\Type(['string'])]
    private ?string $name = null;

    #[ORM\Column]

    #[Groups('books')]

    #[Assert\NotBlank(groups: ['create'])]
    #[Assert\Type(['int'])]
    private ?int $year = null;

    #[ORM\ManyToOne(targetEntity: Publisher::class, inversedBy: 'books')]
    private Publisher|null $publisher = null;
    #[Assert\NotBlank(groups: ['create'])]
    #[ORM\ManyToMany(targetEntity: Author::class, mappedBy: 'books')]
    private Collection $authors;
    public function __construct()
    {
        $this->authors = new ArrayCollection();
    }
    public function setAuthors(ArrayCollection $authors): Book
    {
        foreach ($authors as $author) {
            if (!$this->authors->contains($author))
                $this->authors->add($author);
                $author->addBook($this);
        }
        return $this;
    }
    public function removeAuthor(Author $author): Book
    {
        $this->authors->removeElement($author);
        $author->removeBook($this);
        return $this;
    }
    public function removeAllAuthors(): Book
    {
        $authors = $this->authors;
        foreach ($authors as $author){
            $this->removeAuthor($author);
        }
        return $this;
    }
//    Так называемое виртуальное свойство которе позволяет при сериализации получать необоходимый формат данных
    #[SerializedName('Surnames')]
    #[Groups('books')]
    public function getAuthorsSurnames(): array
    {
        $authors = $this->getAuthors();
        $surnames = [];
        foreach ($authors as $author){
            $surnames[] = $author->getSurname();
        }
        return $surnames;
    }
    #[SerializedName('Publisher')]
    #[Groups('books')]
    public function getPublisherName()
    {
        $publisher = $this->getPublisher();
        if ($publisher){
            return $publisher->getName();
        }
        return 'Not determined';
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

    public function getYear(): ?int
    {
        return $this->year;
    }

    public function setYear(?int $year): static
    {
        $this->year = $year;

        return $this;
    }

    public function getPublisher(): ?Publisher
    {
        return $this->publisher;
    }
    public function addPublisher(Publisher $publisher): static
    {
        if (!$this->getPublisher()){
            $this->setPublisher($publisher);
        }
        return $this;
    }
    public function setPublisher(Publisher $publisher): static
    {
        $this->publisher = $publisher;

        return $this;
    }
    public function getAuthors(): ArrayCollection|Collection
    {
        return $this->authors;
    }

//    public function jsonSerialize(): mixed
//    {
//        $publisher = $this->getPublisher();
//        $authors = $this->getAuthors();
//        $surnames = [];
//        foreach ($authors as $author){
//            $surnames[] = $author->getSurname();
//        }
//        return ["Название книги" => $this->name, "Год выпуска" => $this->year,"Издатель" => $publisher ? $publisher->getName() : 'Не указан','Фамилии Авторов'=>$surnames];
//    }
}
