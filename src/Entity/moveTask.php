<?php

namespace App\Entity;

class moveTask{
    protected $note;
    protected $newcategory;

    public function getNote(): ?Note
    {
        return $this->note;
    }
    public function setNote(?Note $note): void
    {
        $this->note = $note;
        return $this;
    }
    public function getNewcategory(): ?Category
    {
        return $this->newcategory;
    }
    public function setNewcategory(?Category $newcategory): void
    {
        $this->newcategory = $newcategory;
        return $this;
    }
}