<?php

namespace App\Entity;

use App\Repository\InvitaionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=InvitaionRepository::class)
 */
class Invitaion
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="invitaions")
     * @ORM\JoinColumn(nullable=false)
     */
    private $owner;

    /**
     * @ORM\OneToOne(targetEntity=Note::class, cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $note;

    /**
     * @ORM\ManyToMany(targetEntity=User::class, inversedBy="invitedto")
     */
    private $invitee;

    /**
     * @ORM\Column(type="boolean")
     */
    private $accepted;

    public function __construct()
    {
        $this->invitee = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getNote(): ?Note
    {
        return $this->note;
    }

    public function setNote(Note $note): self
    {
        $this->note = $note;

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getInvitee(): Collection
    {
        return $this->invitee;
    }

    public function addInvitee(User $invitee): self
    {
        if (!$this->invitee->contains($invitee)) {
            $this->invitee[] = $invitee;
        }

        return $this;
    }

    public function removeInvitee(User $invitee): self
    {
        $this->invitee->removeElement($invitee);

        return $this;
    }

    public function isAccepted(): ?bool
    {
        return $this->accepted;
    }

    public function setAccepted(bool $accepted): self
    {
        $this->accepted = $accepted;

        return $this;
    }
}
