<?php

class Product
{
    private $mysqli;

    public function __construct($mysqli)
    {
        $this->mysqli = $mysqli;
    }

     /**
     * Search products by name.
     *
     * @param string $productName
     * @return array Result of the search (success, products or message)
     */

    public function searchProductsByName($productName)
    {
        $query = "SELECT * FROM product WHERE Name LIKE ?";
        $stmt = $this->mysqli->prepare($query);

        if ($stmt) {
            $productName = "%" . $productName . "%";
            $stmt->bind_param('s', $productName);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result) {
                $products = $result->fetch_all(MYSQLI_ASSOC);
                return ["success" => true, "products" => $products];
            } else {
                return ["success" => false, "message" => "Failed to retrieve products"];
            }
        } else {
            return ["success" => false, "message" => "Database error"];
        }
    }


    
    /**
     * Search products by category.
     *
     * @param int $categoryID
     * @return array Result of the search (success, products or message)
     */

    public function searchProductsByCategory($categoryID)
    {
        $query = "SELECT * FROM product WHERE CategoryID = ?";
        $stmt = $this->mysqli->prepare($query);

        if ($stmt) {
            $stmt->bind_param('i', $categoryID);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result) {
                $products = $result->fetch_all(MYSQLI_ASSOC);
                return ["success" => true, "products" => $products];
            } else {
                return ["success" => false, "message" => "Failed to retrieve products"];
            }
        } else {
            return ["success" => false, "message" => "Database error"];
        }
    }


    /**
     * Search products within a price range.
     *
     * @param float $minPrice
     * @param float $maxPrice
     * @return array Result of the search (success, products or message)
     */
    
    public function searchProductsByPriceRange($minPrice, $maxPrice)
    {
        $query = "SELECT * FROM product WHERE Price >= ? AND Price <= ?";
        $stmt = $this->mysqli->prepare($query);

        if ($stmt) {
            $stmt->bind_param('dd', $minPrice, $maxPrice);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result) {
                $products = $result->fetch_all(MYSQLI_ASSOC);
                return ["success" => true, "products" => $products];
            } else {
                return ["success" => false, "message" => "Failed to retrieve products"];
            }
        } else {
            return ["success" => false, "message" => "Database error"];
        }
    }

   

}
?>
