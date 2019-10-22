<?php
require_once '../../controller/Connection.php';
require_once '../../controller/Common.php';
$response = array("error"=>false);
// TODO: This is get Request Api for City list
class City{
  private $connect  , $userId = null ;// $otp=null;
  function __construct(){
      $con = new Connection();
      $this->connect = $con->getConnect();
  }
  function getCity(){
    $query = "Select * from city order by city_name ASC";
    $res = mysqli_query($this->connect , $query);
    $response['records'] = array() ;
    $response['error'] = false ;
    $response['count'] = mysqli_num_rows($res);
    while($data = mysqli_fetch_array($res) ){
      $item =  array('id' => $data['city_id']  ,
      'city' => $data['city_name']
    );
    array_push($response['records'] , $item);
    }
    echo json_encode($response);
  }
}
if($_SERVER['REQUEST_METHOD'] == 'GET' ){
  $obj = new City ();
  $obj->getCity();
}
 ?>
