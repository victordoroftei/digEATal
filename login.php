<?php

define("cookie_log_in", "mail");

/*if(isset($_COOKIE[cookie_log_in]))

    echo '<script> window.location.href = "index.php"; </script>';*/

function setCookieUser($mail){
      
  echo '<script>console.log("Am ajuns aici 1!");</script>';

  $cookie_value = $mail;

  if(!isset($_COOKIE[cookie_log_in])){
    setcookie(cookie_log_in, $cookie_value, time() + (86400 * 30), "/");
    $_COOKIE[cookie_log_in] = cookie_log_in;
  }

  echo '<script>console.log("Am ajuns aici 3!");</script>';

  /**if(!isset($_COOKIE[cookie_log_in])){
    echo '<script>console.log("Am ajuns la exceptie, ceau!");</script>';
    throw new Exception("Cookie not set!\n");
  }

  else {
  
    echo '<script>console.log("Am ajuns aici!");</script>';
    echo '<script> window.location.href = "index.php"; </script>';
  }*/
}

function deleteCookie() {
  
  setcookie(cookie_log_in, "", time() - 3600 * 1000 * 24);
}

?>

<!DOCTYPE html>
<html lang="en" >
<head>
  <meta charset="UTF-8">
  <title>login modal</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/5.0.0/normalize.min.css">
<link rel="stylesheet" href="./css/login.css">
<?php
try{
  include_once 'utils.php'; 
    function validate_input($email, $inputPassword){
        $errors = "";
        $regexPassword = "/^[a-zA-Z0-9]{8,64}$/i"; //password with at least 8 character, at least 1 uppercasem, at least 1 lowercase and at least 1 digit

        if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors .= "The email is invalid!";
        }
        
        else if(strlen($email) > 256){
            $errors .= "Email is too big";
        }

        if(!preg_match($regexPassword, $inputPassword)){
            $errors .= "Parola este invalida!";
        }
        
        if(strlen($errors) > 0){
            throw new Exception($errors);
        }
    }

    function translate_data($data){
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }

    $email = $userPassword = "";
    $flag = false; // to see if we are on users table or managers table
    if($_SERVER["REQUEST_METHOD"] == "POST"){
        $email = translate_data($_POST["mail"]);
        $userPassword = translate_data($_POST["password"]);
        validate_input($email, $userPassword);

        $servername = "localhost";
        $username = "root";
        $pass = "";
        $dbName = "site";

        $conn = new mysqli($servername, $username, $pass, $dbName);
        if($conn->connect_error){
            die("Connection failed: " . $conn->connect_error);
        }
        $fromMemoryPassword = find_in_table($conn, $email, "users");
        if($fromMemoryPassword === null){
            //we check administrators table as well
            $flag = true;
            $fromMemoryPassword = find_in_table($conn, $email, "managers");
            if($fromMemoryPassword === null){
                throw new Exception("The account doesn't exist!");
            }
        }
        
        if($fromMemoryPassword != md5($userPassword)){
            throw new Exception("Incorrect password!");
        }
        else{
            echo '<script>console.log("Login successful!")</script>';

            $conn->close();

            setCookieUser($email);

            if(!isset($_COOKIE[cookie_log_in])){
              throw new Exception("Cookie not set!\n");
            }
          
            else {
              $GLOBALS['eror_login'] = null;
              if(!$flag)
                echo '<script> window.location.href = "index.php"; </script>';
              else
                echo '<script> window.location.href = "myrestaurant.php"; </script>';
            }
        }

    }}catch(Exception $err){
      //  echo '<div style="position: absolute; z-index: 999; left: 10vh; font-size: 10vh; top: 10vh;">Eroare:'.$err->getMessage().'</div>';
       echo '<script>console.log("'.$err->getMessage().'")</script>';
      // $GLOBALS['eror_login'] =$err->getMessage();
        echo '<script>alert("'.$err->getMessage().'");</script>';
      }
?>
</head>
<body>

<?php 

if(isset($_COOKIE[cookie_log_in]))

  echo '<script> window.location.href = "index.php"; </script>';

?>

<div class="scroll-down">SCROLL DOWN
  <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 32 32">
  <path d="M16 3C8.832031 3 3 8.832031 3 16s5.832031 13 13 13 13-5.832031 13-13S23.167969 3 16 3zm0 2c6.085938 0 11 4.914063 11 11 0 6.085938-4.914062 11-11 11-6.085937 0-11-4.914062-11-11C5 9.914063 9.914063 5 16 5zm-1 4v10.28125l-4-4-1.40625 1.4375L16 23.125l6.40625-6.40625L21 15.28125l-4 4V9z"/> 
</svg></div>
<div class="container"></div>
<div class="modal">
  <div class="modal-container">
    <div class="modal-left">
      <h1 class="modal-title" style = "margin-bottom: 5vh;">Welcome!</h1>
      <form action="login.php" method="post">
      <div class="input-block">
        <label for="email" class="input-label">Email</label>
        <input type="text" name="mail" id="email" placeholder="Email" required>
      </div>
      <div class="input-block">
        <label for="password" class="input-label">Password</label>
        <input type="password" name="password" id="password" placeholder="Password" required>
      </div>
<!-- Mail: <input type="text" name="mail"><br> -->
<!-- Parola: <input type="text" name="password"><br> -->
<!-- <input type="submit"><br> -->
<div class="modal-buttons">
<!-- <div style='color:"red; position: fixed;"'><?php echo "test";?> </div> -->
  <a href="manage.php">
   
<input class="input-button"style = "margin-top: 5vh;" type="submit"></a>
        <!-- <button class="input-button"style = "margin-top: 5vh;">Log in</button> -->
      </div>


      <p class="sign-up">Nu ai un cont <a href="register.php">Inregistreaza-te aici</a></p>
    </div>
    
    <div class="modal-right">
      <img src="images/rightside.jpg" alt="">
    </div>
    <button class="icon-button close-button">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 50 50">
    <path d="M 25 3 C 12.86158 3 3 12.86158 3 25 C 3 37.13842 12.86158 47 25 47 C 37.13842 47 47 37.13842 47 25 C 47 12.86158 37.13842 3 25 3 z M 25 5 C 36.05754 5 45 13.94246 45 25 C 45 36.05754 36.05754 45 25 45 C 13.94246 45 5 36.05754 5 25 C 5 13.94246 13.94246 5 25 5 z M 16.990234 15.990234 A 1.0001 1.0001 0 0 0 16.292969 17.707031 L 23.585938 25 L 16.292969 32.292969 A 1.0001 1.0001 0 1 0 17.707031 33.707031 L 25 26.414062 L 32.292969 33.707031 A 1.0001 1.0001 0 1 0 33.707031 32.292969 L 26.414062 25 L 33.707031 17.707031 A 1.0001 1.0001 0 0 0 32.980469 15.990234 A 1.0001 1.0001 0 0 0 32.292969 16.292969 L 25 23.585938 L 17.707031 16.292969 A 1.0001 1.0001 0 0 0 16.990234 15.990234 z"></path>
</svg>
      </button>
  </div>
  <button class="modal-button">Click here to login</button>
</div>

<!-- partial -->
  <script  src="./js/login.js"></script>




</body>
</html>