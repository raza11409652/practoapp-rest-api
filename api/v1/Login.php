<?php
require_once '../../controller/Connection.php';
require_once '../../controller/Common.php';
$response = array("error"=>false);
  #// TODO: this is Login handler
  /*
  // TODO: it accept mobile , password
  */
  class Login{
    private $connect  , $userId = null ;// $otp=null;
    function __construct(){
        $con = new Connection();
        $this->connect = $con->getConnect();
    }
    function isMobileExist($mobile){
      $query = "Select * from user where user_mobile='{$mobile}'";
      $res = mysqli_query($this->connect , $query);
      $count = mysqli_num_rows($res);
      if($count>0) return true;
      return false;
    }
    function getUser($mobile){
      $query = "Select * from user where user_mobile='{$mobile}'";
      $res = mysqli_query($this->connect , $query);
      $data = mysqli_fetch_array($res);
      return $data;
    }
    function verifyPassword($hash , $password){
      if(password_verify($password , $hash)){
        return true;
      }
      return false;
    }
  }
  if($_SERVER['REQUEST_METHOD'] == 'POST' ){
      //  var_dump($_POST);
    $mobile = $_POST['mobile'];
    $password = $_POST['password'];
    if(validMobile($mobile)){
      $obj = new Login();
      if($obj->isMobileExist($mobile)){
          $user = $obj->getUser($mobile);
        //  var_dump($user) ;
          $hash = $user['user_password'];
          $t = $obj->verifyPassword($hash , $password);
          if($t==true){
            $response['error'] = false ;
            $response['msg'] = "success" ;
            echo json_encode($response) ;
            return;
          }else{
            $response['error'] = true ;
            $response['error-code'] = 202 ;  // password  is not correct
            $response['msg'] = "Login failed";
            echo json_encode($response);
          }
      }else{
        $response['error'] = true ;
        $response['error-code'] = 201 ;  // Mobile number is not registered
        $response['msg'] = "Login failed";
        echo json_encode($response);
      }
    }else{
      $response['error'] = true ;
      $response['msg'] = "Error mobile number is invalid";
      echo json_encode($response);
    }
  }else{
    $response['error'] = true ;
    $response['msg'] = "Error ! Api call";
    echo json_encode($response);
  }
 ?>
