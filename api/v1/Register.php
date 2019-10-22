<?php
require_once '../../controller/Connection.php';
require_once '../../controller/Common.php';
require_once '../../controller/msgSender.php';
$response = array("error"=>false);
// TODO: Register script name , mobile  , email , password
 class Register{
   private $connect  , $userId = null ;// $otp=null;
   function __construct(){
       $con = new Connection();
       $this->connect = $con->getConnect();
   }
   function isUserEmailExist($email){
     $query = "select * from user where user_email='{$email}'";
     $res = mysqli_query($this->connect , $query);
     $count = mysqli_num_rows($res);
     if($count>0) return true;
     return false;
   }
   function isMobileExist($mobile){
     $query = "Select * from user where user_mobile='{$mobile}'";
     $res = mysqli_query($this->connect , $query);
     $count = mysqli_num_rows($res);
     if($count>0) return true;
     return false;
   }
   function generateOTP(){
     return mt_rand(10000 , 99999);
   }
   function insert($name , $email , $mobile , $password){
      $query = "Insert into user (user_name ,user_email , user_mobile , user_password) values
      ('{$name}'  , '{$email}' ,'{$mobile}' ,'{$password}')" ;
      $res = mysqli_query($this->connect , $query);
      if($res){
        return true;
      }
      return false;
   }
   function getUserId($email , $mobile){
     $query = "select user_id from user where user_email='{$email}' && user_mobile='{$mobile}'";
     $res = mysqli_query($this->connect , $query);
     $data = mysqli_fetch_array($res);
     return $data['user_id'];
   }
   function isTokenExist($id){
     $query = "select * from user_token where user_token_ref='{$id}'" ;
     $res = mysqli_query($this->connect , $query);
     $count = mysqli_num_rows($res);
     if($count>0) return true;
     return false;
   }
   function insertToken($id , $token){
     $query = null ;
     if($this->isTokenExist($id)){
       $query = "Update user_token set user_token_val='{$token}' where user_token_ref='{$id}'";
       // $res = mysqli_query($this->connect , $q)
     }else{
       $query = "Insert into user_token (user_token_val , user_token_ref) values('{$token}' ,'{$id}')";
     }
     $res = mysqli_query($this->connect  , $query);
     if($res) return true ;
     return false;
   }
 }
if($_SERVER['REQUEST_METHOD'] == 'POST' ){
  @$name = $_POST['name'];
  @$email = $_POST['email'];
  @$password = $_POST['password'] ;
  @$mobile = $_POST['mobile'] ;
  $options = [
    'cost' => 12,
];
  $hashPassword= password_hash($password , PASSWORD_BCRYPT , $options);
  //var_dump($_POST);
  //object Register class
  $register = new Register() ;
  $name = pureText($name);
  if (!validName($name)) {
    // code...
    $response['error'] = true ;
    $response['msg'] = "Not valid name" ;
    echo json_encode($response);
    return ;
  }else if(!validEmail($email)) {
    $response['error'] = true ;
    $response['msg'] = "Not valid email" ;
    echo json_encode($response);
    return ;
  }else if (!validMobile($mobile)) {
    $response['error'] = true ;
    $response['msg'] = "Not valid indian mobile" ;
    echo json_encode($response);
    return ;
  }else if($register->isUserEmailExist($email)) {
    $response['error'] = true ;
    $response['msg'] = "{$email} is already used" ;
    echo json_encode($response);
    return ;
  }else if($register->isMobileExist($mobile)){
    $response['error'] = true ;
    $response['msg'] = "{$email} is already used" ;
    echo json_encode($response);
    return ;
  }else{
    //start reegister
    //echo $password;
    // if(password_verify($password , $hashPassword)){
    //   echo "verified";
    // }else {
    //   echo "not";
    // }
    if($register->insert($name , $email , $mobile , $hashPassword)){
      // $response['error'] = false;
      // $response['msg'] = "OTP has been sent to your mobile";
      // echo json_encode($response);
      // return;
      $userId = $register->getUserId($email  , $mobile);
      $otp = $register->generateOTP();
      $otpHash=md5($otp);
      if($register->insertToken($userId , $otp)){
        //send $otp
      //  sendTextMsg($mobile , $otp);
        $response['error'] = false;
        $response['msg'] = "OTP has been sent to your mobile";
        echo json_encode($response);
        return;
      }

    }else{
      $response['error'] = true;
      $response['msg'] = "Server error";
      echo json_encode($response);
      return;
    }
  }

}else{
  $response['error'] = true ;
  $response['msg'] = "Error ! Api call";
  echo json_encode($response);
}
 ?>
