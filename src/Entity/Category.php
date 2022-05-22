<?php

namespace App\Entity;

use App\Repository\CategoryRepository;
use Doctrine\ORM\Mapping as ORM;

/** @ORM\Entity(repositoryClass: CategoryRepository::class) */
class Category
{
    /**
    * @ORM\Id
    * @ORM\GeneratedValue
    * @ORM\Column(type: 'integer')
    */
    private $id;

    /** @ORM\Column(type: 'string', length: 255) */
    private $name;

    /** @ORM\ManyToOne(targetEntity: User::class, inversedBy: 'categories') */
    /** @ORM\JoinColumn(nullable: false) */
    private $owner;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

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
}
