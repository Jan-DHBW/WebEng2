<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/** @ORM\Entity(repositoryClass= UserRepository::class) */
/** @ORM\Table(name= '`user`') */
class User
{
    /**
    * @ORM\Id
    * @ORM\GeneratedValue
    * @ORM\Column(type: 'integer')
    */
    private $id;

    /** @ORM\Column(type= 'string', length: 255) */
    private $mail;

    /** @ORM\Column(type= 'string', length: 255) */
    private $surname;

    /** @ORM\Column(type= 'string', length: 255) */
    private $lastname;

    /** @ORM\Column(type= 'string', length: 255) */
    private $passwordhash;

    /** @ORM\OneToMany(mappedBy= 'owner', targetEntity: Note::class) */
    private $notes;

    /** @ORM\OneToMany(mappedBy= 'owner', targetEntity: Invitations::class) */
    private $invitations;

    /** @ORM\OneToMany(mappedBy= 'owner', targetEntity: Category::class, orphanRemoval: true) */
    private $categories;

    public function __construct()
    {
        $this->notes = new ArrayCollection();
        $this->invitations = new ArrayCollection();
        $this->categories = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMail(): ?string
    {
        return $this->mail;
    }

    public function setMail(string $mail): self
    {
        $this->mail = $mail;

        return $this;
    }

    public function getSurname(): ?string
    {
        return $this->surname;
    }

    public function setSurname(string $surname): self
    {
        $this->surname = $surname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): self
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getPasswordhash(): ?string
    {
        return $this->passwordhash;
    }

    public function setPasswordhash(string $passwordhash): self
    {
        $this->passwordhash = $passwordhash;

        return $this;
    }

    /**
     * @return Collection<int, Note>
     */
    public function getNotes(): Collection
    {
        return $this->notes;
    }

    public function addNote(Note $note): self
    {
        if (!$this->notes->contains($note)) {
            $this->notes[] = $note;
            $note->setOwner($this);
        }

        return $this;
    }

    public function removeNote(Note $note): self
    {
        if ($this->notes->removeElement($note)) {
            // set the owning side to null (unless already changed)
            if ($note->getOwner() === $this) {
                $note->setOwner(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Invitations>
     */
    public function getInvitations(): Collection
    {
        return $this->invitations;
    }

    public function addInvitation(Invitations $invitation): self
    {
        if (!$this->invitations->contains($invitation)) {
            $this->invitations[] = $invitation;
            $invitation->setOwner($this);
        }

        return $this;
    }

    public function removeInvitation(Invitations $invitation): self
    {
        if ($this->invitations->removeElement($invitation)) {
            // set the owning side to null (unless already changed)
            if ($invitation->getOwner() === $this) {
                $invitation->setOwner(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Category>
     */
    public function getCategories(): Collection
    {
        return $this->categories;
    }

    public function addCategory(Category $category): self
    {
        if (!$this->categories->contains($category)) {
            $this->categories[] = $category;
            $category->setOwner($this);
        }

        return $this;
    }

    public function removeCategory(Category $category): self
    {
        if ($this->categories->removeElement($category)) {
            // set the owning side to null (unless already changed)
            if ($category->getOwner() === $this) {
                $category->setOwner(null);
            }
        }

        return $this;
    }
}
