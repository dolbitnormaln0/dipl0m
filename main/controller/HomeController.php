<?php

namespace controller;
use \PDO;
class HomeController
{
    public function index()
    {
        $title = "Главная страница";
        $content = "Добро пожаловать в наш интернет-магазин!";
        $view='products/homepage';
        require __DIR__."/../view/layout/template.php";
    }
}