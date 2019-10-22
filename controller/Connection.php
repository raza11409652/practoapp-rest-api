<?php
  require_once 'Config.php';
  class Connection{
    protected $connect;
    private $ip, $userAgent;
    function __construct(){
        $this->userAgent=$_SERVER['HTTP_USER_AGENT'];
    }
    function getConnect(){
        $this->connect=new mysqli(host , user,password , dbName );
        if($this->connect)
        {
           return $this->connect;

        }else{
            var_dump ("Error in Connection");
        }
    }
  }
  // $obj = new Connection();
  // $obj->getConnect();
?>
