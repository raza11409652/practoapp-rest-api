<?php
require_once '../../controller/Connection.php';
require_once '../../controller/Common.php';
$response = array("error"=>false);
// TODO: This is get Request Api for Doctor list
class DashTypes{
  private $connect  , $userId = null ;// $otp=null;
  function __construct(){
      $con = new Connection();
      $this->connect = $con->getConnect();
  }
  function listTypes(){
    $query = "select * from dr_types where dr_types_image!='null' order by dr_types_title ASC LIMIT 10";
  //  echo "{$query}";
  $res = mysqli_query($this->connect , $query);
  $count = mysqli_num_rows($res);
  if($count>0){
    $response['records'] = array();
    $response['error'] = false ;
    $response['count']=$count ;
    while ($data=mysqli_fetch_array($res)) {
      // code...
      $item = array('id'=>$data['dr_types_id'] ,
      'title'=>$data['dr_types_title'] ,
      'image'=>$data['dr_types_image']
      );
      array_push($response['records'] , $item);
    }
    echo json_encode($response);
  }else{
    $response['count'] =0;
    $response['error'] = true ;
    $response['msg'] = "No Dr found";
    echo json_encode($response);
    return;
  }
  }
}
if($_SERVER['REQUEST_METHOD'] == 'GET' ){
  $obj = new DashTypes();
  $obj->listTypes();
}else{

}
?>
