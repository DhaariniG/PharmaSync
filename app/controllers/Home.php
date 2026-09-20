<?php

class Home extends Controller 
{
    public function index() 
    {
        // Loads app/views/index.php
        $this->view('index');
    }
}