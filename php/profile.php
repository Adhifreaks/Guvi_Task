<?php
session_start();
include("./db.php");
$manager = new MongoDB\Driver\Manager('mongodb://127.0.0.1:27017');
$redis->connect(REDIS_HOST, 6379);
$collection = 'mydb.people';
if ($_SERVER['REQUEST_METHOD'] === 'GET') {

  $session_key = $_GET['session_key'];
  $session_value = $redis->get('session:' . $session_key);

  if (!empty($session_value)) {

    $redis->expire('session:' . $session_key, 3600);
    $session_data = json_decode($session_value);
    $filter = ['username' => $session_data->username];
    $options = ['limit' => 1];
    $query = new MongoDB\Driver\Query($filter, $options);
    $cursor = $manager->executeQuery($collection , $query);
    $document = current($cursor->toArray());
    echo json_encode(array('success' => true, 'username' => $session_data->username,'dob'=>$document->dob,'contactAddress'=>$document->contactAddress));

    
  } else {

    echo json_encode(array('success' => false,'message' => 'Session expired.','session_key' => $session_key));
  }  
  
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

  $session_key = $_POST['session_key'];
  $username =$_POST['username'];
  $dob_str=$_POST['dob'];
  $contactAddress=$_POST['contactAddress'];
  $date_timestamp = strtotime($dob_str);
  $date_obj = date('Y-m-d H:i:s', $date_timestamp);
 

  $filter = ['username' => $username];
  $update = ['$set' => ['dob' => $dob_str,'contactAddress' => $contactAddress]];

  $options = [
    'multi' => false,   
    'upsert' => true   
  ];



  $query = new MongoDB\Driver\BulkWrite();
  $query->update($filter, $update, $options);

  $result = $manager->executeBulkWrite($collection, $query);
  echo json_encode(array('success' => false,'message' => 'Successfully Updated1...'));

}

?>