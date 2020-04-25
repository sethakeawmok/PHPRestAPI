<?php
namespace PathClass;
final class ProductClass
{
    protected $db;

    public function __construct($db)
    {
        //$pdo = $this->get('pdo');
        $this->db = $db;
    }

    public function db_connect(){
        return $this->db;
    }

    // Get All Product
    public function getProduct($request, $response)
    {
        // Read product
        $db = $this->db_connect();
        $sql  = "SELECT * FROM products LIMIT 25";
        $stmt = $db->prepare($sql);
        $stmt->execute();
        $product = $stmt->fetchAll();

        if (count($product))
        {
            $input = [
                'status'  => true,
                'message' => 'Read Product Success',
                'data'    => $product,
            ];
        }
        else
        {
            $input = [
                'status'  => 'fail',
                'message' => 'Empty Product Data',
                'data'    => $product,
            ];
        }

        return $response->withJson($input);
    }

    // Get Product By ID
    public function getProductByID($request, $response, $args){

        $db = $this->db_connect();
        $sql  = "SELECT * FROM products WHERE id='$args[id]'";
            $stmt = $db->prepare($sql);
            $stmt->execute();
            $product = $stmt->fetchAll();
            if (count($product))
            {
                $input = [
                    'status'  => 'success',
                    'message' => 'Read Product Success',
                    'data'    => $product,
                ];
            }
            else
            {
                $input = [
                    'status'  => 'fail',
                    'message' => 'Empty Product Data',
                    'data'    => $product,
                ];
            }

            return $response->withJson($input);
    }


    // Add new product
    public function addProduct($request, $response)
    {
        $db = $this->db_connect();
         // รับจาก Client
         $body = $request->getParsedBody();
         // print_r($body);
         $img = "noimg.jpg";
         $sql = "INSERT INTO products(product_name,product_detail,product_barcode,product_price,product_qty,product_image) 
                    VALUES(:product_name,:product_detail,:product_barcode,:product_price,:product_qty,:product_image)";
        $sth = $db->prepare($sql);
        $sth->bindParam("product_name", $body['product_name']);
        $sth->bindParam("product_detail", $body['product_detail']);
        $sth->bindParam("product_barcode", $body['product_barcode']);
        $sth->bindParam("product_price", $body['product_price']);
        $sth->bindParam("product_qty", $body['product_qty']);
        $sth->bindParam("product_image", $img);

        if($sth->execute()){
            $data = $db->lastInsertId();
            $input = [
                'id' => $data,
                'status' => 'success'
            ];
        }else{
            $input = [
                'id' => '',
                'status' => 'fail'
            ];
        }

        return $response->withJson($input); 
    }


     // Edit product
     public function editProduct($request, $response,$args)
     {
         $db = $this->db_connect();

         // รับจาก Client
         $body = $request->getParsedBody();

         $sql = "UPDATE  products SET 
                        product_name=:product_name,
                        product_detail=:product_detail,
                        product_barcode=:product_barcode,
                        product_price=:product_price,
                        product_qty=:product_qty
                    WHERE id='$args[id]'";

        $sth = $db->prepare($sql);
        $sth->bindParam("product_name", $body['product_name']);
        $sth->bindParam("product_detail", $body['product_detail']);
        $sth->bindParam("product_barcode", $body['product_barcode']);
        $sth->bindParam("product_price", $body['product_price']);
        $sth->bindParam("product_qty", $body['product_qty']);
        

        if($sth->execute()){
            $data = $args['id'];
            $input = [
                'id' => $data,
                'status' => 'success'
            ];
        }else{
            $input = [
                'id' => '',
                'status' => 'fail'
            ];
        }

        return $response->withJson($input); 

     }


     // Delete product
     public function deleteProduct($request, $response,$args)
     {
         $db = $this->db_connect();

         // รับจาก Client
         $body = $request->getParsedBody();
         $sql = "DELETE FROM products WHERE id='$args[id]'";

         $sth = $db->prepare($sql);
         
         if($sth->execute()){
             $data = $args['id'];
             $input = [
                 'id' => $data,
                 'status' => 'success'
             ];
         }else{
             $input = [
                 'id' => '',
                 'status' => 'fail'
             ];
         }

         return $response->withJson($input); 

     }
    
}