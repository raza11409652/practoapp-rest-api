<?php
require_once '../../controller/Connection.php';
require_once '../../controller/Common.php';
$response = array("error"=>false);
class GetBookings{
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
  function getDoctor($id){
    $query="select * from doctor where doctor_id='{$id}'";
    $res =mysqli_query($this->connect , $query  );
    $data = mysqli_fetch_array($res);
    return $data;
  }
  function bookings($mobile){
    $id = $this->getUser($mobile) ;
    $query="select * from booking where booking_user='{$id}' order by booking_date DESC";
    $res = mysqli_query($this->connect , $query);
    $count = mysqli_num_rows($res) ;
    if($count>0){
      $response['error'] = false  ;
      $response['count'] = $count ;
      $response['records'] =array();
      while ($data=mysqli_fetch_array($res)) {
        // code...
        $id =$data['booking_doctor'] ;
        $doctor = $this->getDoctor($id);
        $name = $doctor['doctor_name'] ;
        $address = $doctor['doctor_address'] ;
        $item = array("id"=>$data['booking_id'] ,
              "date"=>$data['booking_date'] ,
              "time"=>$data['booking_time'],
              "doctor"=>$name  ,
              "address" =>$address
                    );
        array_push($response['records'] , $item);

      }
      echo json_encode($response);
      return;
    }else{
      $response['error'] = true ;
      $response['msg'] = "Nothing found";
      echo json_encode($response);
      return ;
    }
   }

}
if($_SERVER['REQUEST_METHOD'] == 'POST' ){
@  $mobile = $_POST['mobile'] ;
  $obj = new GetBookings();
  $obj->bookings($mobile);
}else{

}
?>
