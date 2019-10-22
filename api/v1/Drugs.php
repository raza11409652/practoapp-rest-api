<?php
require_once '../../controller/Connection.php';
require_once '../../controller/Common.php';
$response = array("error"=>false);
// TODO: this is Get Request Api call for Medicine details
class Drugs{
  private $connect  , $userId = null ;// $otp=null;
  function __construct(){
      $con = new Connection();
      $this->connect = $con->getConnect();
  }
  function getDrugs(){
    $query = "select * from drugs order by drugs_name ASC";
    $res = mysqli_query($this->connect , $query);
    $count = mysqli_num_rows($res);
    if($count>0){
      $response['error']=false ;
      $response['count'] = $count ;
      $response['records'] = array();
      $remarks = null;
      while($data = mysqli_fetch_array($res)){
        if($data['drugs_remarks']==null || $data['drugs_remarks'] ==''){
          $remarks = 'N/A';
        }else{
          $remarks =ucwords($data['drugs_remarks'] );
        }
        $items  = array('id' => $data['drugs_id']  ,
                    'name'=>$data['drugs_name'] ,
                    'remarks'=>$remarks,
                    'ven'=>$data['drugs_ven'],
                    'grp'=>$data['drugs_grp_first'] ." " . $data['drugs_grp_second']
                  );
        array_push($response['records'] , $items);
      }
      echo json_encode($response);
    }

  }
}
if($_SERVER['REQUEST_METHOD'] == 'GET' ){
$drugs = new Drugs() ;
$drugs->getDrugs();

}else{

}

 ?>
