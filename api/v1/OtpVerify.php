<?php
require_once '../../controller/Connection.php';
require_once '../../controller/Common.php';
$response = array("error"=>false);
#// TODO: this is OTP Handler it will verify OTP for registration process
// it requires OTP and userMobile number
class OtpVerify{
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
  function getUserId($mobile){
    $query = "select user_id from user where  user_mobile='{$mobile}'";
    $res = mysqli_query($this->connect , $query);
    $data = mysqli_fetch_array($res);
    return $data['user_id'];
  }
  function verifyOtp($otp , $userId){
    $query = "Select * from user_token where user_token_val='{$otp}' &&  user_token_ref='{$userId}'";
    $res = mysqli_query($this->connect , $query);
    $count = mysqli_num_rows($res);
    if($count==1) return true;
    return false;
  }
  function activateUser($userId){
    $query = "update user set user_status='1' where user_id ='{$userId}'";
    $res = mysqli_query($this->connect ,  $query);
    if($res) return true;
    return false;
  }
  function deleteToken($userId){
    $query = "Delete from user_token where user_token_ref='{$userId}'";
    $res = mysqli_query($this->connect , $query);

  }

}
if($_SERVER['REQUEST_METHOD'] == 'POST' ){
  $otp = $_POST['token'];
  $mobile = $_POST['mobile'];
  # NOTE:check whether the mobile exist or
//  var_dump($_POST);
  $obj = new OtpVerify();
  if($obj->isMobileExist($mobile)){
    $userId = $obj->getUserId($mobile) ;
    if($obj->verifyOtp($otp , $userId)){
      $obj->activateUser($userId);
      $obj->deleteToken($userId);
      $response['error'] = false ;
      $response['msg'] = "Verification done";
      echo json_encode($response) ;
      return ;
    }else{
      $response['error'] = true ;
      $response['msg'] = "Verification failed";
      echo json_encode($response) ;
      return ;
    }
  }else{
    $response['error'] = true ;
    $response['msg'] = "Verification failed";
    echo json_encode($response) ;
    return ;
  }
}else{

}
?>
