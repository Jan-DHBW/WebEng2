<?php

namespace App\Entity;

class moveTask{
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