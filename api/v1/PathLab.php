<?php
require_once '../../controller/Connection.php';
require_once '../../controller/Common.php';
$response = array("error"=>false);
// TODO: this is post request api for lab it accept one parameter location=""
class Pathlab{
  private $connect  , $userId = null ;// $otp=null;
  function __construct(){
      $con = new Connection();
      $this->connect = $con->getConnect();
  }
  function getLab($location){
    $query = "select * from labs where labs_address LIKE '%{$location}%'";
  //  echo $query;
  $res = mysqli_query($this->connect , $query) ;
  $count = mysqli_num_rows($res)  ;
  if($count>0){
    $response['error'] = false ;
    $response['count'] = $count;
    $response['records'] = array();
    while($data=mysqli_fetch_array($res)){
      $item = array('id'=>$data['labs_id'] ,
          'name'=>$data['labs_name'] ,
          'address'=>$data['labs_address'],
          'type'=>$data['labs_type']
        );
        array_push($response['records'] , $item);
    }
    echo json_encode($response);
    return ;
  }else{
    $response['error']  =true ;
    $response['count'] = 0;
    $response['msg'] = "No Labs found";
    echo json_encode($response);
    return;
  }
  }
}
if($_SERVER['REQUEST_METHOD'] == 'POST' ){
  $obj = new Pathlab ();
  $location = $_POST['location'];
  $location = pureText($location);
  $obj->getLab($location);
}
 ?>
