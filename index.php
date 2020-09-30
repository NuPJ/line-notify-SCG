<?php
date_default_timezone_set("Asia/Bangkok");
$date = date("Y-m-d");
$time = date("H:i:s");
$json = file_get_contents('php://input');
$request = json_decode($json, true);
$queryText = $request["queryResult"]["queryText"];
$message = $queryText;
$action = $request["queryResult"]["action"];
$userId = $request['originalDetectIntentRequest']['payload']['data']['source']['userId'];
$opts = [
"http" =>[
"header" => "Content-Type: application/json\r\n".'Authorization: Bearer PCj876+Ugkisgdi1fNoNpnTPyETVWm7g/GQA9CQRRnoAnLI0StO6Bwz84uxfuXTuYr326Cldp7EvACnotimCI+Z+I4J3CuPMtPZPv1zOUkRykB1krLYd0z4Y4JYpLdf68T3tme/ZLzCjTyYfsCwOFwdB04t89/1O/w1cDnyilFU='
]
];
$context = stream_context_create($opts);
$profile_json = file_get_contents('https://api.line.me/v2/bot/profile/'.$userId,false,$context);
$profile_array = json_decode($profile_json,true);
$pic_ = $profile_array[pictureUrl];
$name_ = $profile_array[displayName];
//$message_all = "คุณ ".$name." ถามว่า ".$message;
$message_all = '.$name_.' ถามว่า '.$message.' '.'https://line-notify-scg.herokuapp.com/push1-1.php?uid='.$userId.'name='.$name_;
$date_ = date("Y-m-d");
$time_ = date("H:i:s");
$content = $date_.' '.$time_.' '.$name_.' '.$userId.' '.$pic_.' '.$message."\n";

/* $myfile = fopen("log_.txt", "a") or die("Unable to open file!");
fwrite($myfile,$content);
fclose($myfile);
*/
$chOne = curl_init();
curl_setopt( $chOne, CURLOPT_URL, "https://notify-api.line.me/api/notify");
// SSL USE
curl_setopt( $chOne, CURLOPT_SSL_VERIFYHOST, 0);
curl_setopt( $chOne, CURLOPT_SSL_VERIFYPEER, 0);
//POST
curl_setopt( $chOne, CURLOPT_POST, 1);
// Message
curl_setopt( $chOne, CURLOPT_POSTFIELDS, $message);
//ถ้าต้องการใส่รุป ให้ใส่ 2 parameter imageThumbnail และimageFullsize
curl_setopt( $chOne, CURLOPT_POSTFIELDS, "message=$message_all");
// follow redirects
curl_setopt( $chOne, CURLOPT_FOLLOWLOCATION, 1);
//ADD header array
$headers = array( 'Content-type: application/x-www-form-urlencoded', 'Authorization: Bearer pBoWLJ4Am5LR5hHr4onBFRMdwKMdxHN0HOAv6qsZV4w', );
curl_setopt($chOne, CURLOPT_HTTPHEADER, $headers);
//RETURN
curl_setopt( $chOne, CURLOPT_RETURNTRANSFER, 1);
$result = curl_exec( $chOne );
//Check error
if(curl_error($chOne)) { echo 'error:' . curl_error($chOne); }
else { $result_ = json_decode($result, true);
//echo "status : ".$result_['status'];
//echo "message : ". $result_['message'];
}
//Close connect
curl_close( $chOne );
?>
