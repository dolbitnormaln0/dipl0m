<?php

namespace controller;

use \PDO;
use model\ProductModel;
use \traits_\CacheTrait;
use Endroid\QrCode\Color\Color;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\SvgWriter;
class ProductController
{
    use CacheTrait;
    private static $pageCache = [];
    private $model;

    public function __construct(ProductModel $model)
    {
        $this->model = $model;
    }

    public function index($page, $sortField, $idcat = 0) {
        $page = max(1, (int)$page);
        $cacheKey = "products_page_{$page}_" . ($idcat ? "cat_{$idcat}" : "all");
        $filterParams = [];
        if (isset($_GET['attr_ids'])) {
            foreach ($_GET['attr_ids'] as $attrId) {
                if (isset($_GET["attr_{$attrId}_min"]) && isset($_GET["attr_{$attrId}_max"])) {
                    $filterParams[$attrId] = [
                        'type' => 'number',
                        'min' => (float)$_GET["attr_{$attrId}_min"],
                        'max' => (float)$_GET["attr_{$attrId}_max"]
                    ];
                }
                elseif (isset($_GET["attr_{$attrId}"]) && is_array($_GET["attr_{$attrId}"])) {
                    $values = array_filter(array_map('trim', $_GET["attr_{$attrId}"]));
                    if (!empty($values)) {
                        $filterParams[$attrId] = [
                            'type' => 'text_multiple',
                            'values' => $values
                        ];
                    }
                }
                elseif (isset($_GET["attr_{$attrId}_bool"])) {
                    $filterParams[$attrId] = [
                        'type' => 'boolean',
                        'values' => $_GET["attr_{$attrId}_bool"] === '1' ? 'Да' : 'Нет'
                    ];
                }
            }
        }
        if (isset($_GET['brand']) && !empty($_GET['brand'])) {
            $filterParams['brand'] = [
                'type' => 'brand',
                'values' => (int)$_GET['brand']
            ];
        }

        if($idcat == 0){
            $products = $this->cacheGet(
                $cacheKey,
                function () use ($page, $sortField) {
                    return $this->model->getAllProducts($page, $sortField);
                },
                1
            );
        }
        else {
            $products = $this->cacheGet(
                $cacheKey . "_" . md5(json_encode($filterParams)),
                function () use ($page, $idcat, $sortField, $filterParams) {
                    return empty($filterParams)
                        ? $this->model->getAllProductswithsort($page, $idcat, $sortField)
                        : $this->model->getFilteredProducts($page, $idcat, $sortField, 'DESC', $filterParams);
                },
                1
            );

            $categoryAtributs = $this->model->GetAtributes($idcat);
            $attributeFiltersData = $this->model->GetPosibleData($idcat);
        }
        var_dump($products);
        $spec = $this->model->GetSpec();

        foreach ($products as &$product) {
            $product['images_array'] = isset($product['images']) && !empty($product['images'])
                ? array_map('trim', explode(',', $product['images']))
                : [];
            unset($product['images']);
        }
        unset($product);

        $total =$this->model->getTotalProductsCount($products);


        $title = "Каталог товаров";
        $view = 'products/index';
        $productcount=0;
        if($idcat == 0){
            $productcount = $this->model->getAllProductcount($page, $sortField);

            $productcount=$productcount[0]['count'];
        }
        else {
            $productcount=$this->model->countFilteredProducts($idcat, $filterParams);

        }


        require __DIR__ . '/../view/layout/template.php';
    }

    public function show($id) {
        $product = $this->cacheGet(
            "product_{$id}",
            function() use ($id) {
                echo "Loading item from DB...<br>";
                return $this->model->getProductById($id);
            },
            1
        );
        $category_id=(int)$product['category_id'];

        $productAdvice = $this->cacheGet(
            "product_{$category_id}_{$id}",
            function() use ($category_id, $id) {
                echo "Loading advice from DB...<br>";
                return $this->model->GetProductsAdvice($category_id, $id);
            },
            1
        );
        var_dump($productAdvice);
        if (!$product) {
            header("HTTP/1.0 404 Not Found");
            require __DIR__ . '/../view/errors/404.php';
            exit;
        }
        $params= $this->cacheGet(
            "params_{$id}",
            function() use ($id) {
                echo "Loading item from DB...<br>";
                return $this->model->getParams($id);
            },
            1
        );

        $title = $product['nazvanie_tovara'];
        $view = 'products/show';
        $pathtoData = explode(',', $product['images']);

        $filePath = './app/pictures/' . $pathtoData[0];
        if (!file_exists($filePath)) {
            echo "Файл не найден.";
        }
        if (!empty($product['comments_data'])) {
            $comments = array_map(function($comment) {
                $parts = explode('|||', $comment);
                return [
                    'userid' => $parts[0],
                    'commentcontent' => $parts[1],
                    'stars' => $parts[2],
                    'user_name' => $parts[3]
                ];
            }, explode('||||', $product['comments_data']));
            $product['comments'] = $comments;
        } else {
            $product['comments'] = [];
        }
        foreach ($productAdvice as &$productAdvicee) {
            $productAdvicee['images_array'] = isset($productAdvicee['images']) && !empty($productAdvicee['images'])
                ? array_map('trim', explode(',', $productAdvicee['images']))
                : [];
            unset($productAdvicee['images']);
        }
        unset($productAdvicee);
        var_dump($productAdvice);
        require __DIR__ . '/../view/layout/template.php';
    }
    public function search($string,$sortField) {
        $cacheKey = "search_" . md5($string);

        $products = $this->cacheGet(
            $cacheKey,
            function() use ($string,$sortField) {
                echo "Loading item from DB...<br>";
                return $this->model->getProductByString($string,$sortField);
            },
            1
        );


        $view = 'products/index';
        foreach ($products as &$product) {

            $product['images_array'] = isset($product['images']) && !empty($product['images'])
                ? array_map('trim', explode(',', $product['images']))
                : [];


            unset($product['images']);
        }
        unset($product);

        $productcount=$this->model->getTotalProductsCount($products);
        require __DIR__ . '/../view/layout/template.php';
    }

    public function login()
    {
        $view = '/products/login';
        require __DIR__ . '/../view/products/login.php';
    }

    public function profile()
    {
        $userId = $_SESSION['user_id'] ?? 0;
        $cacheKey = "user_orders_{$userId}";

        $orders = $this->cacheGet(
            $cacheKey,
            function() use ($userId) {
                return $this->model->getOrdersByUserId($userId);
            },
            10
        );

        $title = "Мой профиль";
        $view = 'products/profile';
        require __DIR__ . '/../view/layout/template.php';
    }
    public function generateQr(int $orderId)
    {
        $userId = $_SESSION['user_id'] ?? 0;


        $order = $this->model->getOrderById($orderId);
        if (!$order || $order['user_id'] != $userId) {
            header("HTTP/1.0 404 Not Found");
            die("Заказ не найден или недоступен");
        }


        $qrUrl = "https://api.qrserver.com/v1/create-qr-code/?" . http_build_query([
                'size' => '300x300',
                'data' => $orderId,
                'format' => 'png',
                'color' => '000000',
                'bgcolor' => 'FFFFFF'
            ]);

        $title = "QR-код заказа #$orderId";
        $view = 'orders/qr';
        require __DIR__ . '/../view/layout/template.php';
    }

    public function AddRewiew(int $stars, string $text, int $idtovar,int $iduser)
    {
        $this->model->sendComment($stars, $text, $idtovar,$iduser);
        $this->show($idtovar);


    }

    public function GetCard()
    {

        $cartItems=$this->model->getCardData();

        $title = "Мой профиль";
        $view = 'products/shopper';
        require __DIR__ . '/../view/layout/template.php';
    }

    public function createZak()
    {
        $this->model->createzakaz();
        $this->profile();
    }
}

