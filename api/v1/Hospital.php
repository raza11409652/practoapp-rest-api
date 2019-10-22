<?php
require_once '../../controller/Connection.php';
require_once '../../controller/Common.php';
$response = array("error"=>false);
// TODO: this is post request api for Hospital listing it accept one parameter location=""
class Hospital{
  private $connect  , $userId = null ;// $otp=null;
  function __construct(){
      $con = new Connection();
      $this->connect = $con->getConnect();
  }
  function listHospital($location){
    $query = "select * from hospital where hospital_city ='{$location}' order by hospital_name ASC" ;
    $res = mysqli_query($this->connect , $query);
    $count = mysqli_num_rows($res);
    if($count>0){

      $response['error'] = false ;
      $response['count'] = $count ;
        $response['records'] = array();
      while ($data=mysqli_fetch_array($res)) {
        // code...
        $item  = array('id' => $data['hospital_id']  ,
            'name'=>$data['hospital_name'] ,
            'address'=>$data['hospital_address'] . " Pin {$data['hospital_pin_code']} " ,
            'phone' =>$data['hospital_phone'] ,
            'city' =>$data['hospital_city'] . "-" .$data['hospital_state']
            );
           array_push($response['records'] , $item);
      }
      echo json_encode($response);
      return;
    }else{
      $response['count'] = $count;
      $response['error'] = true ;
      $response['msg'] = "No Hospital found";
      echo json_encode($response);
      return;
    }
  }
}
if($_SERVER['REQUEST_METHOD'] == 'POST' ){
  $location = $_POST['location'];
  $obj = new Hospital();
  $obj  ->listHospital($location);
}else{

}
 ?>
