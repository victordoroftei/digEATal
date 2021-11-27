<!DOCTYPE html>
<html lang="en" >
<head>
  <meta charset="UTF-8">
  <title>login modal</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/5.0.0/normalize.min.css">
<link rel="stylesheet" href="./css/login.css">
<?php
try{
  include_once "utils.php";
require_once("XML/xml.php");

$firstName = $lastName = $mail = $phoneNumber = $password = $gender = $role = "";

function translateInput($data){

    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

function validateInputData($firstName, $lastName, $mail, $password, $phone){

    $errors = "";

    $regexForNames = "/^[A-Z][a-z]{0,63}$/i";

    $regexForPhoneNumber = "/^0[0-9]{9}$/i";

    $regexForPassword = "/^[a-zA-Z0-9]{8,64}$/i";

    if(!preg_match($regexForNames, $firstName))

        $errors .= "Invalid first name!";

    if(!preg_match($regexForNames, $lastName))
    
        $errors .= "Invalid last name!";

    if(!filter_var($mail, FILTER_VALIDATE_EMAIL))
    
        $errors .= "Invalid mail address!";

    else if(strlen($mail) > 256)

        $errors .= "E-mail address is to big!";

    if(!preg_match($regexForPassword, $password))
    
        $errors .= "Invalid password!";

    if(!preg_match($regexForPhoneNumber, $phone))

        $errors .= "Invalid phone number!";

    if(!empty($errors))
    
        throw new Exception($errors);

}

if($_SERVER["REQUEST_METHOD"] == "POST"){

    $firstName = translateInput($_POST["firstName"]);
    $lastName = translateInput($_POST["lastName"]);
    $mail = translateInput($_POST["mail"]);
    $password = translateInput($_POST["password"]);
    $phone = translateInput($_POST["phone"]);
    $gender = translateInput($_POST["gender"]);
    $role = translateInput($_POST["role"]);

    validateInputData($firstName, $lastName, $mail, $password, $phone);

    // ar trb sa incepem sa inseram in baza de date

    $servername = "localhost";
    $username = "root";
    $pass = "";
    $db = "site";

    $conn = new mysqli($servername, $username, $pass, $db);

    // Check connection
    if ($conn->connect_error){

        die("Connetion failed: ".$conn->connect_error);
    }

    if(find_in_table($conn, $mail, "users") !== null || find_in_table($conn, $mail, "managers") !== null)

        throw new Exception("There is already an instance with this e-mail in our database!");

    // S-a conectat cu succes

    $sql = "";

    if(strcmp($role, "Manager") === 0)

        $sql = 'INSERT INTO `managers`(`mail`, `first_name`, `last_name`, `pass`, `phone_number`, `gender`) VALUES (?, ?, ?, ?, ?, ?)';
    
    else
    
        $sql = "INSERT INTO users(mail, first_name, last_name, `pass`, phone_number, gender) VALUES (?, ?, ?, ?, ?, ?)";

    $statement = $conn->prepare($sql);

    if($statement === false)

        throw new Exception("Error occured!\n");
    
    else{

        $parola = md5($password);

        $statement->bind_param("ssssss", $mail, $firstName, $lastName, $parola, $phone, $gender);

        $statement->execute();

        if(strcmp($role, "Manager") === 0)
          createNewGrid($mail);

        else
          createNewBookings($mail);

        echo '<script>console.log("Login successfull!")</script>';
        echo '<script>window.location="login.php"</script>';
    }

    $conn->close();
}}catch(Exception $err){
    // echo '<div style="position: absolute; z-index: 999; left: 10vh; font-size: 10vh; top: 10vh;">Eroare:'.$err->getMessage().'</div>';
    echo '<script>console.log("'.$err->getMessage().'")</script>';
    
    echo '<script>alert("'.$err->getMessage().'");</script>';
   }

?>
</head>
<body>

<!-- partial:index.partial.html -->
<div class="scroll-down">SCROLL DOWN
  <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 32 32">
  <path d="M16 3C8.832031 3 3 8.832031 3 16s5.832031 13 13 13 13-5.832031 13-13S23.167969 3 16 3zm0 2c6.085938 0 11 4.914063 11 11 0 6.085938-4.914062 11-11 11-6.085937 0-11-4.914062-11-11C5 9.914063 9.914063 5 16 5zm-1 4v10.28125l-4-4-1.40625 1.4375L16 23.125l6.40625-6.40625L21 15.28125l-4 4V9z"/> 
</svg></div>
<div class="container"></div>
<div class="modal">
  <div class="modal-container">
    <div class="modal-left">
      <h1 class="modal-title">Welcome!</h1>

      <form action="register.php" method="post">
      <div class="input-block">
        <label for="email" class="input-label">Nume</label>
        <input type="text" name="firstName" id="firstName" placeholder="Nume" required>
      </div>
      <div class="input-block">
        <label for="email" class="input-label">Prenume</label>
        <input type="text" name="lastName" id="lastName" placeholder="Prenume" required>
      </div>
      <div class="input-block">
        <label for="email" class="input-label">Email</label>
        <input type="text" name="mail" id="email" placeholder="Email" required>
      </div>
      <div class="input-block">
        <label for="password" class="input-label">Password</label>
        <input type="password" name="password" id="password" placeholder="Password" required>
      </div>
      <div class="input-block">
        <label for="email" class="input-label">Phone</label>
        <input type="text" name="phone" id="phone" placeholder="phone" required>
      </div>
<!-- Nume: <input type="text" name="firstName" required><br> -->
<!-- Prenume: <input type="text" name="lastName" required><br> -->
<!-- Mail: <input type="text" name="mail" required><br> -->
<!-- Parola: <input type="text" name="password" required><br> -->
<!-- Telefon: <input type="text" name="phone" required><br> -->

      <label for="email" class="input-label">Gender:</label>
      <input type="radio" name="gender" value="female">Female
    <input type="radio" name="gender" value="male">Male
    <input type="radio" name="gender" value="other">Other
    <br>
    
    <label for="email" class="input-label">Role:</label>
    <input type="radio" name="role" value="Manager">Manager
    <input type="radio" name="role" value="User">User<br>
    <div class="modal-buttons">
        <a href="login.php">
        <input class="input-button" type="submit">
        <!-- <button class="input-button">Signup</button> -->
      </div>
    <!-- <input type="submit"> -->
</form>
      <!-- <div class="input-block">
        <label for="email" class="input-label">Nume</label>
        <input type="email" name="email" id="email" placeholder="Email">
      </div> -->
      <!-- <div class="input-block">
        <label for="email" class="input-label">Prenume</label>
        <input type="email" name="email" id="email" placeholder="Email">
      </div> -->
      <!-- <div class="input-block">
        <label for="email" class="input-label">Email</label>
        <input type="email" name="email" id="email" placeholder="Email">
      </div> -->
      <!-- <div class="input-block">
        <label for="password" class="input-label">Password</label>
        <input type="password" name="password" id="password" placeholder="Password">
      </div> -->
      <!-- <div class="input-block">
        <label for="email" class="input-label">Email</label>
        <input type="email" name="email" id="email" placeholder="Email">
      </div> -->
      <!-- <div class="modal-buttons">
        <button class="input-button">Signup</button>
      </div> -->
      <p class="sign-up">Already have an account? <a href="login.php">Log in here</a></p>
    </div>
    <div class="modal-right">
    <img src="images/rightside.jpg" alt="" class="rightimage">
    </div>
    <button class="icon-button close-button">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 50 50">
    <path d="M 25 3 C 12.86158 3 3 12.86158 3 25 C 3 37.13842 12.86158 47 25 47 C 37.13842 47 47 37.13842 47 25 C 47 12.86158 37.13842 3 25 3 z M 25 5 C 36.05754 5 45 13.94246 45 25 C 45 36.05754 36.05754 45 25 45 C 13.94246 45 5 36.05754 5 25 C 5 13.94246 13.94246 5 25 5 z M 16.990234 15.990234 A 1.0001 1.0001 0 0 0 16.292969 17.707031 L 23.585938 25 L 16.292969 32.292969 A 1.0001 1.0001 0 1 0 17.707031 33.707031 L 25 26.414062 L 32.292969 33.707031 A 1.0001 1.0001 0 1 0 33.707031 32.292969 L 26.414062 25 L 33.707031 17.707031 A 1.0001 1.0001 0 0 0 32.980469 15.990234 A 1.0001 1.0001 0 0 0 32.292969 16.292969 L 25 23.585938 L 17.707031 16.292969 A 1.0001 1.0001 0 0 0 16.990234 15.990234 z"></path>
</svg>
      </button>
  </div>
  <button class="modal-button">Click here to sign up</button>
</div>
<!-- partial -->
<script  src="./js/login.js"></script>





</body>
</html>