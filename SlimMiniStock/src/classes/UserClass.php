<?php 
namespace PathClass;
final class UserClass
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

    // Get All  Users
    public function getUser($request, $response)
    {
        // Read product
        $db = $this->db_connect();
        $sql  = "SELECT * FROM users";
        $stmt = $db->prepare($sql);
        $stmt->execute();
        $user = $stmt->fetchAll();

        if (count($product))
        {
            $input = array(
                'status'  => true,
                'message' => 'Read User Success',
                'data'    => $user,
            );
        }
        else
        {
            $input = array(
                'status'  => false,
                'message' => 'Empty User Data',
                'data'    => $user,
            );
        }

        return $response->withJson($input);
    }


    // Get Users By ID
    public function getUserByID($request, $response, $args){ 
        
        $db = $this->db_connect();
        $sql  = "SELECT id,email,fullname,username FROM users WHERE id=".$args->id;
        $stmt = $db->prepare($sql);
        $stmt->execute();
        $user_info = $stmt->fetchAll();
        
        if (count($user_info))
        {
            $input = array(
                'status'  => true,
                'message' => 'Read User Success',
                'data'    => $user_info[0],
            );
        }
        else
        {
            $input = array(
                'status'  => 'fail',
                'message' => 'Empty User Data',
                'data'    => $user_info,
            );
        }

        return $response->withJson($input);
    }

    // Get UpdateProfile
    public function UpdateProfile($request, $response, $args){

        $db = $this->db_connect();

        $body = $request->getParsedBody();
        $email = $body['email']; 
        $fullname = $body['fullname']; 

        $arr_field_err = array();
        if (empty($email)){
            $arr_field_err['email'] = ["The name field is required."]; 
        }
        if (empty($fullname)){
            $arr_field_err['fullname'] = ["The fullname field is required."];
        }
        if (sizeof($arr_field_err) > 0){
            $input = array(
                'message' => 'The given data was invalid.',
                'errors' => $arr_field_err
            );
            return $response->withJson($input, 422);
        }
        

        $sql = "UPDATE  users SET 
                        email=:email,
                        fullname=:fullname 
                    WHERE id=".$args->id;

        $sth = $db->prepare($sql);
        $sth->bindParam("email", $body['email']); 
        $sth->bindParam("fullname", $body['fullname']); 
        
        if($sth->execute()){
            $data = $args->id;
            $input = [
                'id' => $data,
                'status' => true
            ];
        }else{
            $input = [
                'id' => '',
                'status' => false
            ];
        }

        return $response->withJson($input); 
    }

    // Other Method
        // Something like this guy...
        // ...
        // ...

}