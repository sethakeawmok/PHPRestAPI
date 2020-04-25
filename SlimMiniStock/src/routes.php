<?php
 
use \Firebase\JWT\JWT;
use PathClass\ProductClass;
use PathClass\UserClass;


    $app->add(function ($req, $res, $next) {
        $response = $next($req, $res);
        return $response
                ->withHeader('Access-Control-Allow-Origin', '*')
                ->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization')
                ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, PATCH, OPTIONS');
    });

    // Root Route
    $app->get('/', function ($request, $response)
    {
        return $this->response->withJson(['success' => true, 'message' => 'logged in']);
    });

    // Login และ รับ Token
    $app->post('/login', function ($request, $response) {

        $input = $request->getParsedBody();

        $password = sha1($input['password']);

        $sql = "SELECT * FROM users WHERE username=:username and password=:password";
        $sth = $this->db->prepare($sql);
        $sth->bindParam("username", $input['username']);
        $sth->bindParam("password", $password);
        $sth->execute();

        $count = $sth->rowCount();
        if($count){
            $user = $sth->fetchObject();
            $settings = $this->get('settings'); // get settings array.
            $token = JWT::encode(['id' => $user->id, 'username' => $user->username], $settings['jwt']['secret'], "HS256");
            $arr_respone = $input = [
                "success" => true,
                "user" => [
                    "id" => $user->id,
                    "first_name" => null,
                    "last_name" => null,
                    "email" => $user->username,
                    "auth_jwt" => $token
                ]
            ];
            
            return $this->response->withJson($arr_respone);
        }else{
            return $this->response->withJson(['error' => true, 'message' => 'These credentials do not match our records.']);
        }
    });

    
    $app->group('/api', function () use ( $app )
    {

        $app->get('/user/me', function ($request, $response) {

            $auth_header = $request->getHeader("Authorization"); 
            $token = substr($auth_header[0],6);
            $settings = $this->get('settings');
            $args = JWT::decode(trim($token), $settings['jwt']['secret'], array('HS256'));
            
            $getUserByID = new UserClass($this->db);
            return $getUserByID->getUserByID($request, $response, $args);
            
        });
       
        // Get All Products (Method GET)
        $app->get('/products', function ($request, $response)
        {
            $getProduct = new ProductClass($this->db);
            return $getProduct->getProduct($request, $response);
        });
         
        // Get  Product By ID (Method GET)
        $app->get('/products/{id}', function ($request, $response, $args)
        {
            $getProductByID = new ProductClass($this->db);
            return $getProductByID->getProductByID($request, $response, $args);
        });

        // Add new Product  (Method Post)
        $app->post('/products', function ($request, $response)
        {
            $addProduct = new ProductClass($this->db);
            return $addProduct->addProduct($request, $response);
        });

        // Edit Product  (Method Put)
        $app->put('/products/{id}', function ($request, $response, $args) {
            $editProduct = new ProductClass($this->db);
            return $editProduct->editProduct($request, $response, $args);
        });

        // Delete Product  (Method Delete)
        $app->delete('/products/{id}', function ($request, $response, $args) {
            $deleteProduct = new ProductClass($this->db);
            return $deleteProduct->deleteProduct($request, $response, $args);
        });
 
    }); 
