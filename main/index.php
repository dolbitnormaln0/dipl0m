<?php
session_start();
require_once __DIR__ . '/vendor/autoload.php';
$dbConfig = require __DIR__ . '/config/database.php';
$dsn = "mysql:host={$dbConfig['host']};dbname={$dbConfig['dbname']};charset={$dbConfig['charset']}";
$db = new PDO($dsn, $dbConfig['username'], $dbConfig['password'], $dbConfig['options']);
$productModel = new model\ProductModel($db);
$productController = new controller\ProductController($productModel);
$homeController = new controller\HomeController();
$dispatcher = FastRoute\simpleDispatcher(function(FastRoute\RouteCollector $r) {
    $r->addRoute('GET', '/', 'home.index');
    $r->addRoute('GET', '/products', 'products.index');
    $r->addRoute('GET', '/search', 'search.find');
    $r->addRoute('GET', '/product', 'products.show');
    $r->addRoute('GET', '/login', 'products.login');
    $r->addRoute('GET', '/profile', 'products.profile');
    $r->addRoute('POST', '/order/qr', 'order.qr');
    $r->addRoute('GET', '/logout', 'logout.logout');
    $r->addRoute('GET', '/card', 'card');
    $r->addRoute('POST', '/order/create', 'create');
    $r->addRoute('POST', '/products/add-review', 'add_review');
});
$httpMethod = $_SERVER['REQUEST_METHOD'];
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$routeInfo = $dispatcher->dispatch($httpMethod, $uri);
switch ($routeInfo[0]) {
    case FastRoute\Dispatcher::NOT_FOUND:
        http_response_code(404);
        require __DIR__ . '/../view/errors/404.php';
        break;
    case FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
        http_response_code(405);
        echo '405 Method Not Allowed';
        break;
    case FastRoute\Dispatcher::FOUND:
        $handler = $routeInfo[1];
        $vars=$_GET;
        if ($handler === 'create') {
            $productController->createZak();
        }
        if ($handler === 'home.index') {
            $homeController->index();
        } elseif ($handler === 'products.index') {
            $filterParams = [];
            if (isset($_GET['attr_ids'])) {
                foreach ($_GET['attr_ids'] as $attrId) {
                    if (isset($_GET["attr_{$attrId}_min"]) && isset($_GET["attr_{$attrId}_max"])) {
                        $filterParams[$attrId] = [
                            'type' => 'number',
                            'min' => $_GET["attr_{$attrId}_min"],
                            'max' => $_GET["attr_{$attrId}_max"]
                        ];
                    } elseif (isset($_GET["attr_{$attrId}"])) {
                        $filterParams[$attrId] = [
                            'type' => is_array($_GET["attr_{$attrId}"]) ? 'text_multiple' : 'text_single',
                            'values' => $_GET["attr_{$attrId}"]
                        ];
                    }
                }
            }
            if (isset($vars['idCat'])) {
                $sortField = $vars['sortField'] ?? 't.tovar_id';
                $productController->index(
                    $vars['page'] ?? 1,
                    $sortField,
                    $vars['idCat'],
                    $filterParams
                );
            } elseif (isset($vars['page']) && isset($vars['sortField'])) {
                $productController->index($vars['page'], $vars['sortField']);
            } elseif (isset($vars['sortField'])) {
                $productController->index(1, $vars['sortField']);
            } elseif (isset($vars['page'])) {
                $productController->index($vars['page'], 't.average_rating');
            } else {
                $productController->index(1, 't.tovar_id');
            }
        }elseif ($handler === 'products.show') {
            if (isset($vars['id'])) {
                $productController->show($vars['id']);
            } else {
                http_response_code(400);
                echo 'ID продукта не указан';
            }
        }elseif ($handler === 'search.find') {
            if (isset($vars['string']) && isset($vars['sortField'])) {
                $productController->search($vars['string'],$vars['sortField']);
            }
            elseif (isset($vars['string'])) {
                $productController->search($vars['string'],'t.average_rating');
            }
            else {
                http_response_code(400);
                echo 'строка поиска  не указан';
            }
        }elseif ($handler === 'products.login') {
            $productController->login();
        } elseif ($handler === 'products.profile') {
            $productController->profile();
        }elseif ($handler === 'order.qr') {
            if (!empty($_POST['order_id'])) {
                $productController->generateQr((int)$_POST['order_id']);
            } else {
                http_response_code(400);
                echo 'ID заказа не указан в параметрах (order_id)';
            }
        }
        elseif ($handler === 'logout.logout') {
            session_unset();
            session_destroy();
            header('Location: /');
            exit;
        }
        elseif ($handler === 'add_review') {
            var_dump($_POST);
            $productController->AddRewiew((int)$_POST['rating'],(string)$_POST['review_text'],(int)$_POST['product_id'],(int)$_POST['userid']);
        }
        elseif ($handler === 'card') {
            var_dump($_POST);
            $productController->GetCard();
        }




        break;
}