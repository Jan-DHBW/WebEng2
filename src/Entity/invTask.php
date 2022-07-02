<?php

namespace App\Entity;
use Doctrine\Common\Collections\ArrayCollection;
use App\Entity\Invitaion;

class invTask{


    public function __construct()
    {
        $this->categories = new ArrayCollection();
    }

    protected $add;
    protected $remove;

    public function getAdd()    
    {
        return $this->add;
    }
    public function getRemove()
    {
        return $this->remove;
    }
    public function setAdd($add)
    {
        $this->add = $add;
    }
    public function setRemove($remove)
    {
        $this->remove = $remove;
    }
}