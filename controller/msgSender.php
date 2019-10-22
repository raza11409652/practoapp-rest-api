<?php
function sendTextMsg($mobile , $msg)
{
    $key ="YOUR_AUTH_KEY";

    $curl = curl_init();
    
    curl_setopt_array($curl, array(
    CURLOPT_URL => "http://api.msg91.com/api/sendhttp.php?country=91&sender=DROIDH&route=4&mobiles=$mobile&authkey=$key8&encrypt=1&message=$msg&unicode=1",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 30,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "GET",
    CURLOPT_SSL_VERIFYHOST => 0,
    CURLOPT_SSL_VERIFYPEER => 0,
    ));

    $response = curl_exec($curl);
    $err = curl_error($curl);

    curl_close($curl);

    if ($err) {
        //echo "cURL Error #:" . $err;
        return false;
      } else {
        #echo $response;
        return true;
      }
      return false;
}
?>
