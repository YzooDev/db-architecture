<?php

namespace App\Controller;

use App\Controller\AbstractController;

class ContactController extends AbstractController
{
    public function index(): mixed 
    {
        return $this->render("contact", "Contact");
    }
}