<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;

class moveTask{


    public function __construct()
    {
        $this->categories = new ArrayCollection();
    }

    protected $confirmation;

    public function getConfirmation(): ?bool
    {
        return $this->confirmation;
    }
    public function setConfirmation(?bool $confirmation): void
    {
        $this->confirmation = $confirmation;
    }
}