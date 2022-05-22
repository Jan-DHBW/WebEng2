<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 */
class User
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
    private $mail;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $surname;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $lasname;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $pwhash;

    /**
     * @ORM\OneToMany(targetEntity=Note::class, mappedBy="owner")
     */
    private $privnotes;

    /**
     * @ORM\OneToMany(targetEntity=Invitations::class, mappedBy="invitee")
     */
    private $invitedToNotes;

    /**
     * @ORM\OneToMany(targetEntity=Category::class, mappedBy="owner")
     */
    private $categories;

    public function __construct()
    {
        $this->privnotes = new ArrayCollection();
        $this->invitedToNotes = new ArrayCollection();
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

    public function getLasname(): ?string
    {
        return $this->lasname;
    }

    public function setLasname(string $lasname): self
    {
        $this->lasname = $lasname;

        return $this;
    }

    public function getPwhash(): ?string
    {
        return $this->pwhash;
    }

    public function setPwhash(string $pwhash): self
    {
        $this->pwhash = $pwhash;

        return $this;
    }

    /**
     * @return Collection<int, Note>
     */
    public function getPrivnotes(): Collection
    {
        return $this->privnotes;
    }

    public function addPrivnote(Note $privnote): self
    {
        if (!$this->privnotes->contains($privnote)) {
            $this->privnotes[] = $privnote;
            $privnote->setOwner($this);
        }

        return $this;
    }

    public function removePrivnote(Note $privnote): self
    {
        if ($this->privnotes->removeElement($privnote)) {
            // set the owning side to null (unless already changed)
            if ($privnote->getOwner() === $this) {
                $privnote->setOwner(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Invitations>
     */
    public function getInvitedToNotes(): Collection
    {
        return $this->invitedToNotes;
    }

    public function addInvitedToNote(Invitations $invitedToNote): self
    {
        if (!$this->invitedToNotes->contains($invitedToNote)) {
            $this->invitedToNotes[] = $invitedToNote;
            $invitedToNote->setInvitee($this);
        }

        return $this;
    }

    public function removeInvitedToNote(Invitations $invitedToNote): self
    {
        if ($this->invitedToNotes->removeElement($invitedToNote)) {
            // set the owning side to null (unless already changed)
            if ($invitedToNote->getInvitee() === $this) {
                $invitedToNote->setInvitee(null);
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
