<?php

namespace model;
use \traits_\CacheTrait;
class ProductModel
{
    private $db;

    public function __construct(\PDO $db)
    {
        $this->db = $db;
    }
    public function getFilteredProducts($page, $idcat, $sortField = 't.tovar_id', $sortOrder = 'DESC', $filterParams = [])
    {
        $limit = 9;
        $offset = ($page - 1) * $limit;


        $allowedSortFields = ['t.tovar_id', 't.cena', 't.average_rating', 't.kolichestvo_na_sklade'];
        if (!in_array($sortField, $allowedSortFields)) {
            $sortField = 't.tovar_id';
        }

        $sortOrder = strtoupper($sortOrder) === 'DESC' ? 'DESC' : 'ASC';


        $sql = "SELECT t.*, b.brend_name, c.category_name, 
           GROUP_CONCAT(a.image_path SEPARATOR ', ') AS images
         FROM tovar t
         JOIN brend b ON t.brend_id = b.brend_id
         LEFT JOIN tovar_images a ON t.tovar_id = a.tovar_id 
         JOIN category c ON t.category_id = c.category_id
         WHERE t.category_id = :category_id";

        $params = [
            ':category_id' => $idcat,
            ':limit' => $limit,
            ':offset' => $offset
        ];


        if (!empty($filterParams)) {
            $filterConditions = [];
            $i = 0;

            foreach ($filterParams as $attrId => $filter) {
                $i++;
                $alias = "av{$i}";

                if ($filter['type'] === 'number') {
                    $filterConditions[] = "EXISTS (
                    SELECT 1 FROM attribute_values {$alias} 
                    WHERE {$alias}.product_id = t.tovar_id 
                    AND {$alias}.attribute_id = :attr_id{$i}
                    AND {$alias}.value_number BETWEEN :min{$i} AND :max{$i}
                )";
                    $params[":attr_id{$i}"] = $attrId;
                    $params[":min{$i}"] = $filter['min'];
                    $params[":max{$i}"] = $filter['max'];
                }
                elseif ($filter['type'] === 'text_multiple') {
                    $placeholders = [];
                    foreach ($filter['values'] as $j => $value) {
                        $key = ":value{$i}_{$j}";
                        $placeholders[] = $key;
                        $params[$key] = $value;
                    }
                    $filterConditions[] = "EXISTS (
                    SELECT 1 FROM attribute_values {$alias} 
                    WHERE {$alias}.product_id = t.tovar_id 
                    AND {$alias}.attribute_id = :attr_id{$i}
                    AND {$alias}.value_text IN (" . implode(',', $placeholders) . ")
                )";
                    $params[":attr_id{$i}"] = $attrId;
                }
                elseif ($filter['type'] === 'boolean') {
                    $filterConditions[] = "EXISTS (
                    SELECT 1 FROM attribute_values {$alias} 
                    WHERE {$alias}.product_id = t.tovar_id 
                    AND {$alias}.attribute_id = :attr_id{$i}
                    AND {$alias}.value_boolean = :bool{$i}
                )";
                    $params[":attr_id{$i}"] = $attrId;
                    $params[":bool{$i}"] = $filter['values'] == 'Да' ? 1 : 0;
                }
            }

            if (!empty($filterConditions)) {
                $sql .= " AND " . implode(" AND ", $filterConditions);
            }
        }

        $sql .= " GROUP BY t.tovar_id, b.brend_name, c.category_name
            ORDER BY $sortField $sortOrder
            LIMIT :limit OFFSET :offset";

        $stmt = $this->db->prepare($sql);


        foreach ($params as $key => $value) {
            if (is_int($value)) {
                $stmt->bindValue($key, $value, \PDO::PARAM_INT);
            } elseif (is_float($value)) {
                $stmt->bindValue($key, $value, \PDO::PARAM_STR);
            } elseif (is_bool($value)) {
                $stmt->bindValue($key, $value, \PDO::PARAM_BOOL);
            } else {
                $stmt->bindValue($key, $value, \PDO::PARAM_STR);
            }
        }

        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function countFilteredProducts($idcat, $filterParams = [])
    {

        $sql = "SELECT COUNT(DISTINCT t.tovar_id) as total_count
            FROM tovar t
            JOIN brend b ON t.brend_id = b.brend_id
            JOIN category c ON t.category_id = c.category_id
            WHERE t.category_id = :category_id";

        $params = [':category_id' => $idcat];

        if (!empty($filterParams)) {
            $filterConditions = [];
            $i = 0;

            foreach ($filterParams as $attrId => $filter) {
                $i++;
                $alias = "av{$i}";

                if ($filter['type'] === 'number') {
                    $filterConditions[] = "EXISTS (
                    SELECT 1 FROM attribute_values {$alias} 
                    WHERE {$alias}.product_id = t.tovar_id 
                    AND {$alias}.attribute_id = :attr_id{$i}
                    AND {$alias}.value_number BETWEEN :min{$i} AND :max{$i}
                )";
                    $params[":attr_id{$i}"] = $attrId;
                    $params[":min{$i}"] = $filter['min'];
                    $params[":max{$i}"] = $filter['max'];
                }
                elseif ($filter['type'] === 'text_multiple') {
                    $placeholders = [];
                    foreach ($filter['values'] as $j => $value) {
                        $key = ":value{$i}_{$j}";
                        $placeholders[] = $key;
                        $params[$key] = $value;
                    }
                    $filterConditions[] = "EXISTS (
                    SELECT 1 FROM attribute_values {$alias} 
                    WHERE {$alias}.product_id = t.tovar_id 
                    AND {$alias}.attribute_id = :attr_id{$i}
                    AND {$alias}.value_text IN (" . implode(',', $placeholders) . ")
                )";
                    $params[":attr_id{$i}"] = $attrId;
                }
                elseif ($filter['type'] === 'boolean') {
                    $filterConditions[] = "EXISTS (
                    SELECT 1 FROM attribute_values {$alias} 
                    WHERE {$alias}.product_id = t.tovar_id 
                    AND {$alias}.attribute_id = :attr_id{$i}
                    AND {$alias}.value_boolean = :bool{$i}
                )";
                    $params[":attr_id{$i}"] = $attrId;
                    $params[":bool{$i}"] = $filter['values'] == 'Да' ? 1 : 0;
                }
            }

            if (!empty($filterConditions)) {
                $sql .= " AND " . implode(" AND ", $filterConditions);
            }
        }

        $stmt = $this->db->prepare($sql);

        foreach ($params as $key => $value) {
            if (is_int($value)) {
                $stmt->bindValue($key, $value, \PDO::PARAM_INT);
            } elseif (is_float($value)) {
                $stmt->bindValue($key, $value, \PDO::PARAM_STR);
            } elseif (is_bool($value)) {
                $stmt->bindValue($key, $value, \PDO::PARAM_BOOL);
            } else {
                $stmt->bindValue($key, $value, \PDO::PARAM_STR);
            }
        }

        $stmt->execute();
        $result = $stmt->fetch(\PDO::FETCH_ASSOC);

        return (int) $result['total_count'];
    }
    public function getFilteredProductsCount($idcat, $filterParams = [])
    {

    }
    public function getAllProductswithsort($page,$typeOfcat, $sortField = 't.cena', $sortOrder = 'DESC')
    {
        $limit = 9;
        $offset = ($page - 1) * $limit;


        $allowedSortFields = ['t.tovar_id', 't.cena', 't.average_rating', 't.kolichestvo_na_sklade'];
        if (!in_array($sortField, $allowedSortFields)) {
            $sortField = 't.tovar_id';
        }


        $sortOrder = strtoupper($sortOrder) === 'DESC' ? 'DESC' : 'ASC';

        $sql = "SELECT t.*, b.brend_name, c.category_name, 
             GROUP_CONCAT(a.image_path SEPARATOR ', ') AS images
           FROM tovar t
           JOIN brend b ON t.brend_id = b.brend_id
           LEFT JOIN tovar_images a ON t.tovar_id = a.tovar_id 
           JOIN category c ON t.category_id = c.category_id
           WHERE t.category_id like :typeOfcat
           GROUP BY t.tovar_id, b.brend_name, c.category_name
           ORDER BY $sortField $sortOrder
           LIMIT :limit OFFSET :offset";

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':limit', $limit, \PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, \PDO::PARAM_INT);
        $stmt->bindValue(':typeOfcat', $typeOfcat, \PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
    public function getAllProducts($page, $sortField = 't.tovar_id', $sortOrder = 'DESC')
    {
        $limit = 9;
        $offset = ($page - 1) * $limit;

        $allowedSortFields = ['t.tovar_id', 't.cena', 't.average_rating', 't.kolichestvo_na_sklade'];
        if (!in_array($sortField, $allowedSortFields)) {
            $sortField = 't.tovar_id';
        }


        $sortOrder = strtoupper($sortOrder) === 'DESC' ? 'DESC' : 'ASC';

        $sql = "SELECT t.*, b.brend_name, c.category_name, 
             GROUP_CONCAT(a.image_path SEPARATOR ', ') AS images
           FROM tovar t
           JOIN brend b ON t.brend_id = b.brend_id
           LEFT JOIN tovar_images a ON t.tovar_id = a.tovar_id 
           JOIN category c ON t.category_id = c.category_id
           GROUP BY t.tovar_id, b.brend_name, c.category_name
           ORDER BY $sortField $sortOrder
           LIMIT :limit OFFSET :offset";

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':limit', $limit, \PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
    public function getProductByString($searchString, $sortField = 't.cena', $sortOrder = 'DESC')
    {

        $allowedSortFields = ['t.tovar_id', 't.cena', 't.average_rating', 't.kolichestvo_na_sklade', 't.nazvanie_tovara'];
        if (!in_array($sortField, $allowedSortFields)) {
            $sortField = 't.nazvanie_tovara';
        }
        $sortOrder = strtoupper($sortOrder) === 'DESC' ? 'DESC' : 'ASC';


        $sql = "SELECT t.*, b.brend_name, c.category_name, 
            GROUP_CONCAT(a.image_path SEPARATOR ', ') AS images
            FROM tovar t
            JOIN brend b ON t.brend_id = b.brend_id
            LEFT JOIN tovar_images a ON t.tovar_id = a.tovar_id 
            JOIN category c ON t.category_id = c.category_id
            WHERE t.nazvanie_tovara LIKE :search1
               OR b.brend_name LIKE :search2
               OR c.category_name LIKE :search3
            GROUP BY t.tovar_id, b.brend_name, c.category_name
            ORDER BY $sortField $sortOrder
           ";

        $stmt = $this->db->prepare($sql);
        $searchParam = '%' . $searchString . '%';
        $stmt->bindValue(':search1', $searchParam, \PDO::PARAM_STR);
        $stmt->bindValue(':search2', $searchParam, \PDO::PARAM_STR);
        $stmt->bindValue(':search3', $searchParam, \PDO::PARAM_STR);
        $stmt->execute();

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
    public function GetSpec()
    {
        $stmt = $this->db->prepare("
        SELECT * from category ");
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
    public function getParams($id)
    {
        $stmt = $this->db->prepare("
        SELECT 
            a.name AS attribute_name,
            a.type AS attribute_type,
            CASE a.type
                WHEN 'text' THEN av.value_text
                WHEN 'number' THEN av.value_number
                WHEN 'boolean' THEN av.value_boolean
            END AS attribute_value
        FROM 
            attribute_values av
        JOIN 
            attributes a ON av.attribute_id = a.id
        WHERE 
            av.product_id = ?
    ");

        $stmt->execute([$id]);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
    public function getProductById($id)
    {
        $stmt = $this->db->prepare("SELECT 
                               t.*, 
                               b.brend_name, 
                               c.category_name,
                               (SELECT GROUP_CONCAT(a.image_path SEPARATOR ', ') 
                                FROM tovar_images a 
                                WHERE a.tovar_id = t.tovar_id) AS images,
                               (SELECT GROUP_CONCAT(
                                   CONCAT(q.user_id, '|||', q.commentcontent, '|||', q.stars, '|||', u.first_name) 
                                   SEPARATOR '||||'
                                ) 
                                FROM comments q
                                LEFT JOIN users u ON q.user_id = u.id
                                WHERE q.tovar_id = t.tovar_id) AS comments_data
                           FROM tovar t
                           JOIN brend b ON t.brend_id = b.brend_id
                           JOIN category c ON t.category_id = c.category_id
                           WHERE t.tovar_id = :id
                           GROUP BY t.tovar_id");

        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }
    public function getTotalProductsCount($product=null)
    {
        return count($product);
    }
    public function getOrdersByUserId($userId) {

        $stmt = $this->db->prepare("
        SELECT o.id, o.total_price, o.created_at, o.status
        FROM orders o
        WHERE o.user_id = :user_id
        ORDER BY o.created_at DESC
    ");
        $stmt->bindParam(':user_id', $userId, \PDO::PARAM_INT);
        $stmt->execute();
        $orders = $stmt->fetchAll(\PDO::FETCH_ASSOC);


        foreach ($orders as &$order) {
            $stmt = $this->db->prepare("
            SELECT 
                oi.product_id, 
                oi.quantity, 
                t.cena as price,
                t.nazvanie_tovara
            FROM order_items oi
            JOIN tovar t ON oi.product_id = t.tovar_id
            WHERE oi.order_id = :order_id
        ");
            $stmt->bindParam(':order_id', $order['id'], \PDO::PARAM_INT);
            $stmt->execute();
            $order['items'] = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        }

        return $orders;
    }
    public function getOrderById($orderId) {
        $stmt = $this->db->prepare("
        SELECT o.* 
        FROM orders o
        WHERE o.id = :order_id
    ");
        $stmt->bindParam(':order_id', $orderId, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    public function GetAtributes(mixed $idcat)
    {
        $idcat=(int)$idcat;
        $stmt = $this->db->prepare( "
        SELECT a.name, a.id,a.type
        FROM attributes a
        JOIN attribute_categories ac ON a.id = ac.attribute_id
        WHERE ac.category_id = :idcat
    ");


        $stmt->bindParam(':idcat', $idcat, \PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
    public function getAttributeValues($attributeId, $categoryId) {
        $sql = "SELECT DISTINCT av.value 
            FROM attribute_values av
            JOIN tovar t ON av.tovar_id = t.tovar_id
            WHERE av.attribute_id = :attribute_id
            AND t.category_id = :category_id
            ORDER BY av.value";

        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':attribute_id', $attributeId, PDO::PARAM_INT);
        $stmt->bindParam(':category_id', $categoryId, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    use CacheTrait;

    public function GetPosibleData(int $idcat): array
    {
        $cacheKey = "attribute_data_{$idcat}";

        return $this->cacheGet($cacheKey, function() use ($idcat) {

            $attributes = $this->GetAtributes($idcat);
            if (empty($attributes)) {
                return [];
            }

            $result = [];
            $numericAttrIds = [];
            $textAttrIds = [];

            foreach ($attributes as $attribute) {
                $attrId = $attribute['id'];
                $result[$attrId] = [
                    'name' => $attribute['name'],
                    'type' => $attribute['type'],
                    'values' => null
                ];

                if ($attribute['type'] === 'number') {
                    $numericAttrIds[] = $attrId;
                } elseif ($attribute['type'] === 'text') {
                    $textAttrIds[] = $attrId;
                }

            }


            if (!empty($numericAttrIds)) {
                $placeholders = implode(',', array_fill(0, count($numericAttrIds), '?'));
                $sql = "SELECT 
                    attribute_id,
                    MIN(value_number) as min_value,
                    MAX(value_number) as max_value
                FROM attribute_values
                WHERE attribute_id IN ($placeholders)
                AND product_id IN (SELECT tovar_id FROM tovar WHERE category_id = ?)
                AND value_number IS NOT NULL
                GROUP BY attribute_id";

                $params = array_merge($numericAttrIds, [$idcat]);
                $stmt = $this->db->prepare($sql);
                $stmt->execute($params);

                while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
                    $result[$row['attribute_id']]['values'] = [
                        'min' => (float)$row['min_value'],
                        'max' => (float)$row['max_value']
                    ];
                }
            }


            if (!empty($textAttrIds)) {
                $placeholders = implode(',', array_fill(0, count($textAttrIds), '?'));
                $sql = "SELECT 
                    attribute_id,
                    value_text
                FROM attribute_values
                WHERE attribute_id IN ($placeholders)
                AND product_id IN (SELECT tovar_id FROM tovar WHERE category_id = ?)
                AND value_text IS NOT NULL
                GROUP BY attribute_id, value_text
                LIMIT 1000";

                $params = array_merge($textAttrIds, [$idcat]);
                $stmt = $this->db->prepare($sql);
                $stmt->execute($params);

                $textValues = [];
                while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
                    $textValues[$row['attribute_id']][] = $row['value_text'];
                }

                foreach ($textValues as $attrId => $values) {
                    $result[$attrId]['values'] = $values;
                }
            }


            foreach ($attributes as $attribute) {
                if ($attribute['type'] === 'boolean' && $result[$attribute['id']]['values'] === null) {
                    $result[$attribute['id']]['values'] = ['Да', 'Нет'];
                }
            }

            return $result;
        }, 1);
    }

    public function getAllProductcount($page, $sortField = 't.cena', $sortOrder = 'DESC')
    {


        $allowedSortFields = ['t.tovar_id', 't.cena', 't.average_rating', 't.kolichestvo_na_sklade'];
        if (!in_array($sortField, $allowedSortFields)) {
            $sortField = 't.tovar_id';
        }


        $sortOrder = strtoupper($sortOrder) === 'DESC' ? 'DESC' : 'ASC';

        $sql = "SELECT COUNT(*) as count
           FROM tovar t
           JOIN brend b ON t.brend_id = b.brend_id
           LEFT JOIN tovar_images a ON t.tovar_id = a.tovar_id 
           JOIN category c ON t.category_id = c.category_id
          ";

        $stmt = $this->db->prepare($sql);

        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function GetProductsAdvice(int $category_id,$id)
    {
        $limit = 3;
        $sql = "SELECT t.*, b.brend_name, c.category_name, 
            GROUP_CONCAT(a.image_path SEPARATOR ', ') AS images
        FROM tovar t
        JOIN brend b ON t.brend_id = b.brend_id
        LEFT JOIN tovar_images a ON t.tovar_id = a.tovar_id 
        JOIN category c ON t.category_id = c.category_id
        WHERE t.category_id = :category_id
        AND t.tovar_id != :current_product_id  
        GROUP BY t.tovar_id, b.brend_name, c.category_name
        ORDER BY RAND() 
        LIMIT $limit";

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':category_id', $category_id, \PDO::PARAM_INT);
        $stmt->bindValue(':current_product_id', $id, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function sendComment(int $stars, string $text, int $idtovar, int $iduser)
    {
        $stmt = $this->db->prepare("
        INSERT INTO comments (commentcontent, stars, user_id, tovar_id) 
        VALUES (:text, :stars, :user_id, :tovar_id)
    ");
        $stmt->bindValue(':text', $text, \PDO::PARAM_STR);
        $stmt->bindValue(':stars', $stars, \PDO::PARAM_INT);
        $stmt->bindValue(':tovar_id', $idtovar, \PDO::PARAM_INT);
        $stmt->bindValue(':user_id', $iduser, \PDO::PARAM_INT);
        $stmt->execute();
    }

    public function getCardData()
    {
        if (empty($_SESSION['card'])) {
            return [];
        }


        $productIds = array_keys($_SESSION['card']);


        $placeholders = implode(',', array_fill(0, count($productIds), '?'));

        $sql = "SELECT 
                tovar_id, 
                nazvanie_tovara, 
                cena as price, 
                kolichestvo_na_sklade as available_quantity
            FROM tovar
            WHERE tovar_id IN ($placeholders)";


        $stmt = $this->db->prepare($sql);
        $stmt->execute($productIds);


        $products = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        $result = [];
        foreach ($products as $product) {
            $tovarId = $product['tovar_id'];


            if (isset($_SESSION['card'][$tovarId])) {
                $quantity = $_SESSION['card'][$tovarId]['quantity'];
            } elseif (isset($_SESSION['card'][(string)$tovarId])) {

                $quantity = $_SESSION['card'][(string)$tovarId]['quantity'];
            } else {

                continue;
            }

            $result[] = [
                'tovar_id' => $tovarId,
                'nazvanie_tovara' => $product['nazvanie_tovara'],
                'price' => $product['price'],
                'quantity' => $quantity,
                'available_quantity' => $product['available_quantity'],
                'total_price' => $product['price'] * $quantity
            ];
        }

        return $result;
    }

    public function createzakaz()
    {

        $stmt = $this->db->prepare("
        INSERT INTO order_items (order_id, product_id, quantity) 
        VALUES (8,35,1);
    ");
        $stmt->execute();
         $stmt = $this->db->prepare("
        INSERT INTO order_items (order_id, product_id, quantity) 
        VALUES (8,31,1);
    ");
 $stmt->execute();
    }

}