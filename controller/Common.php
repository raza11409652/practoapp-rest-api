<?php
      function validMobile($mobile){
          $mobileRegex="/^[6789]{1}\d{9}$/";
          if(preg_match($mobileRegex,$mobile)){
              return true;
          }
          return false;
      }
      function validEmail($email){
          $email = filter_var($email, FILTER_SANITIZE_EMAIL);
          if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
              return true;
            }
            return false;
      }
      function pureText($data){
          $data = trim($data);
          $data = stripslashes($data);
          $data = htmlspecialchars($data);
          return $data;
      }
      function validName($name){
          $name = pureText($name);
          if (preg_match("/^[a-zA-Z ]*$/",$name)) {
              //$nameErr = "Only letters and white space allowed";
              return true;
            }
            return false;
      }
?>
