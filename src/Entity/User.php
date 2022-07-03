<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 */
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     * @Assert\Email(
     *     message = "The email '{{ value }}' is not a valid email."
     * )
     */
    private $email;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $surname;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $lastname;

    /**
     * @ORM\OneToMany(targetEntity=Note::class, mappedBy="owner")
     */
    private $notes;

    /**
     * @ORM\OneToMany(targetEntity=Category::class, mappedBy="owner",cascade={"persist"})
     */
    private $categories;

    /**
     * @ORM\OneToMany(targetEntity=Invitaion::class, mappedBy="owner")
     */
    private $invitaions;

    /**
     * @ORM\ManyToMany(targetEntity=Invitaion::class, mappedBy="invitee")
     */
    private $invitedto;

    public function __construct()
    {
        $this->notes = new ArrayCollection();
        $this->categories = new ArrayCollection();
        $this->invitaions = new ArrayCollection();
        $this->invitedto = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @deprecated since Symfony 5.3, use getUserIdentifier instead
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
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

    /**
     * @return Collection<int, Invitaion>
     */
    public function getInvitaions(): Collection
    {
        return $this->invitaions;
    }

    public function addInvitaion(Invitaion $invitaion): self
    {
        if (!$this->invitaions->contains($invitaion)) {
            $this->invitaions[] = $invitaion;
            $invitaion->setOwner($this);
        }

        return $this;
    }

    public function removeInvitaion(Invitaion $invitaion): self
    {
        if ($this->invitaions->removeElement($invitaion)) {
            // set the owning side to null (unless already changed)
            if ($invitaion->getOwner() === $this) {
                $invitaion->setOwner(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Invitaion>
     */
    public function getInvitedto(): Collection
    {
        return $this->invitedto;
    }

    public function addInvitedto(Invitaion $invitedto): self
    {
        if (!$this->invitedto->contains($invitedto)) {
            $this->invitedto[] = $invitedto;
            $invitedto->addInvitee($this);
        }

        return $this;
    }

    public function removeInvitedto(Invitaion $invitedto): self
    {
        if ($this->invitedto->removeElement($invitedto)) {
            $invitedto->removeInvitee($this);
        }

        return $this;
    }
}
