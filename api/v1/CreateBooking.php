<?php
require_once '../../controller/Connection.php';
require_once '../../controller/Common.php';
$response = array("error"=>false);
// TODO: required time,date user ,
class CreateBooking{
  private $connect  , $userId = null ;// $otp=null;
  function __construct(){
      $con = new Connection();
      $this->connect = $con->getConnect();
  }
  function getUser($mobile){
    $query = "select user_id from user where user_mobile='{$mobile}'";
    $res=mysqli_query($this->connect , $query);
    $data = mysqli_fetch_array($res);
    return $data['user_id'];
  }
  function insert($date , $user ,$time , $doctor){
    $insert = "insert into  booking (booking_date , booking_time , booking_user ,booking_doctor ) values
    ('{$date}','{$time}','{$this->getUser($user)}','{$doctor}')";
    $res = mysqli_query($this->connect , $insert);
    //echo $insert;
    if($res){
      $response['error'] = false ;
      $response['msg'] = "Booking has been scheduled" ;
      echo json_encode($response);
      return  ;
    }else{
      $response['error'] = true ;
      $response['msg'] = "Error" ;
      echo json_encode($response);
      return;
     }
  }
}
if($_SERVER['REQUEST_METHOD'] == 'POST' ){
//  var_dump($_POST);
$user = $_POST['user'];
$date = $_POST['date'];
$time = $_POST['time'];
$doctor = $_POST['doctor'];
$obj = new CreateBooking();
$obj->insert($date   , $user ,$time, $doctor);

}else{

}
 ?>
