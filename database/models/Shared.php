<?php

class Shared {
    private $id;
    private $postit_id;
    private $user_id;

    public function __construct($postit_id, $user_id, $id = 0)
    {
        $this->postit_id = $postit_id;
        $this->user_id = $user_id;
        $this->id = $id;
    }

    

    /**
     * Get the value of id
     */ 
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set the value of id
     *
     * @return  self
     */ 
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Get the value of postit_id
     */ 
    public function getPostit_id()
    {
        return $this->postit_id;
    }

    /**
     * Set the value of postit_id
     *
     * @return  self
     */ 
    public function setPostit_id($postit_id)
    {
        $this->postit_id = $postit_id;

        return $this;
    }

    /**
     * Get the value of user_id
     */ 
    public function getUser_id()
    {
        return $this->user_id;
    }

    /**
     * Set the value of user_id
     *
     * @return  self
     */ 
    public function setUser_id($user_id)
    {
        $this->user_id = $user_id;

        return $this;
    }
}