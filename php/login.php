<?php
$dbhost= "127.0.0.1";
$dbuser = "root";
$dbpass = '';
$dbname = "login_system";
$conn = new mysqli($dbhost,$dbuser,$dbpass,$dbname);
if($conn->connect_error){
    die("could not connect to the database!".$conn->connect_error);
}
if((isset($_POST['action']) && $_POST['action'])=='login')
{
    session_start();
    $email = $_POST['email'];
    $password = sha1($_POST['password']);
    $stmt_l = $conn ->prepare("SELECT * FROM users WHERE email=? AND pass=?");
    $stmt_l -> bind_param("ss",$email,$password);
    $stmt_l ->execute();
    $user= $stmt_l->fetch();
    if($user!=null){
        $_SESSION['email'] = $email;
        $_SESSION['pass'] = $password;
    
        $myObj = new stdClass();
        $myObj->password = $password;
        $myObj->email = $email;
        
        $myJSON = json_encode($myObj);

  echo  $myJSON;
  
        if(!empty($_POST['rem'])){
            setcookie("email",$_POST['email'],time()+(10*365*24*60*60));
            setcookie("password",$_POST['password'],time()+(10*365*24*60*60));
        }else{
            if(isset($_COOKIE['email'])){
                setcookie("email","");
            }
            if(isset($_COOKIE['password'])){
                setcookie("password","");
            }
        }
     
    }else{
        echo "Login Failed check your email and password !";
    }

  
}


?>