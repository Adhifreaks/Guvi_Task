<?php
$dbhost= "127.0.0.1";
$dbuser = "root";
$dbpass = '';
$dbname = "login_system";
$conn = new mysqli($dbhost,$dbuser,$dbpass,$dbname);
if(isset($_POST['action'])&&$_POST['action']=='register'){
  $name = check_input($_POST['name']);
  $uname = check_input($_POST['uname']) ;
  $email = check_input($_POST['email']);
  $pass = check_input($_POST['pass']);
  $cpass = check_input($_POST['cpass']);
  $pass = sha1($pass);
  $cpass  = sha1($cpass);
  $created = date('Y-m-d');
  if($pass!=$cpass){
    echo "password did not matched !";
    exit();
  }else{
    $sql = $conn->prepare("SELECT username,email from users WHERE username =? OR email=?");
    $sql->bind_param("ss",$uname,$email);
    $sql->execute();
    $result = $sql->get_result();
    $row  = $result->fetch_array(MYSQLI_ASSOC);
   
    if(isset($row['username'])== $uname)
    {
        echo 'username not available try different';

    }else if(isset($row['email']) == $email)
    {
        echo 'email is already registered try different ';
    }
    else
    {
        $stmt = $conn->prepare("INSERT INTO users (name,username,email,pass,created) VALUES (?,?,?,?,?)");
        $stmt->bind_param('sssss',$name,$uname,$email,$pass,$created);
        
        if($stmt->execute()){
            echo "registerd sucessfully . Login now!";
            echo "<script> location.href='http://localhost:3000/login.html'; </script>";
            exit;
            
        }else{
            echo "some thing went wrong. please again !";
        }
    }

  }
}else{
  session_destroy(); 
  
}
function check_input($data){ 
    $data = trim($data);  
    $data = stripslashes($data); 
    $data = htmlspecialchars($data); 
   
    return $data;
}



?>