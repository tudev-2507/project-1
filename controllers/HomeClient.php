<?php

class HomeClient
{
    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }
    public function checkAdmin()
    {
        return isset($_SESSION['user']) && $_SESSION['user']['role'] == 0;
    }
    public function index()
    {
        require_once './models/client/shop.php';
        $view = 'home';
        $title = 'home';
        $productModel = new Shop();
        $productHome = $productModel->getAll();
        $productBestSelling = $productModel->getProdHot();
        $categoryModel = new Shop();
        $categoryHome = $categoryModel->getCategoryHome();
        $categoryHome2 = $categoryModel->getCategoryHome2();
        require_once './views/client/main.php';
    }
}    