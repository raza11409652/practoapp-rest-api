<?php
require_once '../../controller/Connection.php';
require_once '../../controller/Common.php';
$response = array("error"=>false);
// TODO: This is get Request Api for Doctor list
# city is required and type is optional
class FetchDrByCity{
  private $connect  , $userId = null ;// $otp=null;
  function __construct(){
      $con = new Connection();
      $this->connect = $con->getConnect();
  }
  function getTypeName($id){
    $query = "select * from dr_types where dr_types_id='{$id}'" ;
    $res = mysqli_query($this->connect , $query) ;
    $data = mysqli_fetch_array($res);
    return $data['dr_types_title'];
  }
  function fetch($query){
    $res = mysqli_query($this->connect , $query) ;
    $count = mysqli_num_rows($res);
    if($count>0){
        $response['error']=false;
        $response['records']  =array();
        $response['count'] = $count;
        while ($data=mysqli_fetch_array($res)) {
          // code...
          $item = array('id'=>$data['doctor_id'] ,
            'name'=>$data['doctor_name'] ,
            'city' =>$data['doctor_city'] ,
            'type'=>$this->getTypeName($data['doctor_type']) ,
            'image' =>$data['doctor_image'] ,
            'address'=>$data ['doctor_address'],
            'state'=>$data['doctor_state'],
            'phone'=>$data['doctor_contact']
            );
          array_push($response['records'] , $item);

        }
        echo json_encode($response);
        return;
    }else{
      $response['error'] = true ;
      $response['msg'] = "No result Found";
      echo json_encode($response);
      return;
    }
  }
}
if($_SERVER['REQUEST_METHOD'] == 'POST' ){
  $city = null ;
  $type = null ;
  @$city = $_POST['city'] ;
  @$type = $_POST['type'] ;
  $query = null ;
  $obj = new FetchDrByCity();
  if(empty($type)&& !empty($city)){
    $query = "Select * from doctor where doctor_city='{$city}' order by doctor_name";
    $obj->fetch($query);
    return ;
  }else if(!empty($city) && !empty($type)){
    $query="select * from doctor where doctor_city='{$city}' && doctor_type='{$type}' order by doctor_name";
    $obj->fetch($query);
    return ;
  }else{
    $response['error'] = true ;
    $response['msg'] ="Not a Valid Request";
    echo json_encode($response);
    return ;
  }
}else{
  $response['error'] = true ;
  $response['msg'] ="Not a Valid Request";
  echo json_encode($response);
  return ;
}
 ?>
