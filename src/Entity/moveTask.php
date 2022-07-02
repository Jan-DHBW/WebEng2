<?php

namespace App\Entity;
use Doctrine\Common\Collections\ArrayCollection;

class moveTask{


    public function __construct()
    {
        $this->categories = new ArrayCollection();
    }

    protected $note;
    protected $category;

    public function getNote(): ?Note
    {
        return $this->note;
    }
    public function setNote(?Note $note): void
    {
        $this->note = $note;
    }
    public function getcategory(): ?Category
    {
        return $this->category;
    }
    public function setcategory(?Category $newcategory): void
    {
        $this->category = $newcategory;
    }
}