<?php

namespace App\Entity;

use App\Repository\NoteRepository;
use Doctrine\ORM\Mapping as ORM;

/** @ORM\Entity(repositoryClass)= NoteRepository::class) */
class Note
{
    /** @ORM\Id
    * @ORM\GeneratedValue
    * @ORM\Column(type= 'integer')
    */
    private $id;

  /** @ORM\Column(type= 'string', length: 255) */
    private $title;

   /** @ORM\ManyToOne(targetEntity= User::class, inversedBy: 'notes') */
    private $owner;

   /** @ORM\Column(type= 'text', nullable: true) */
    private $content;

    /** @ORM\ManyToOne(targetEntity= Category::class) */
    private $category;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getOwner(): ?User
    {
        return $this->owner;
    }

    public function setOwner(?User $owner): self
    {
        $this->owner = $owner;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(?string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): self
    {
        $this->category = $category;

        return $this;
    }
}
