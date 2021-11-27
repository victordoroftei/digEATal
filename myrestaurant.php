<!DOCTYPE html>
<html lang="en">
 <?php

//require_once("XML/xml.php");
include_once 'utils.php';
if(!isset($_COOKIE["mail"]))


  echo '<script>window.location.href = "login.php";</script>';

    $servername = "localhost";
    $username  = "root";
    $password = "";
    $database = "site";
    $conn = new mysqli($servername, $username, $password, $database);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $sql = "select * from restaurants where mail=?";
    $statement = $conn->prepare($sql);
    $statement->bind_param("s", $_COOKIE['mail']);
    $statement->execute();
    $result = $statement->get_result();
    $managerRestaurants = array();
    while($row = $result->fetch_assoc()) {
      array_push($managerRestaurants, array('name' => $row['name'], 'address' => $row['address'], 'rating' => $row['rating'], 'description' => $row['description']));
    }

?> 
<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>index</title>
  <meta content="" name="description">
  <meta content="" name="keywords">


  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Raleway:300,300i,400,400i,500,500i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="aboutme/css/aos.css" rel="stylesheet">
  <link href="aboutme/css/bootstrap.min.css" rel="stylesheet">
  <link href="aboutme/css/boxicons.min.css" rel="stylesheet">
  <link href="aboutme/css/style.css" rel="stylesheet">
  <link href="aboutme/css/restaurants.css" rel="stylesheet">

</head>

<body>

  <!-- ======= Mobile nav toggle button ======= -->
  <i class="bi bi-list mobile-nav-toggle d-xl-none"></i>

  <!-- ======= Header ======= -->
  <header id="header">
    <div class="d-flex flex-column">

      <div class="profile">
        <img src="images/AccountIcon2.png" alt="" class="img-fluid rounded-circle">
        <h1 class="text-light"><a href="index.php"><?php echo explode("|",getEntity())[0].' '.explode("|",getEntity())[1];?></a></h1>
       
      </div>

      <nav id="navbar" class="nav-menu navbar">
        <ul>
          <li><a href="index.php" class="nav-link scrollto active"> <span>Home</span></a></li>
          <li><a href="aboutme.php" class="nav-link scrollto"><span>About</span></a></li>
         <li><a href="#contact" class="nav-link scrollto"> <span>Contact</span></a></li>
         <li><a href="myrestaurant.php" class="nav-link scrollto "> <span>My restaurants</span></a></li>
       <li style ="margin-top: -15%;"><a class="nav-link scrollto"></a>
         
          </li>
          <li><a href="logout.php" class="nav-link scrollto active"  style="position: fixed; top:0;left:0 ;margin:1vh;"></a> <span>Logout</span></a></li>
        </ul>
      </nav>
    </div>
  </header>
  <main id="main">
    
  <h3 style = "margin: 5vh;margin-bottom: -5vh;">Your Restaurants:</h2>

<?php

$rez = getRestaurantsAttributesForManager($_COOKIE['mail']);
// echo gettype($rez[0]);
echo "<div class='rowaf'></div>";
for($i = 0; $i < count($rez); $i++)
{
  if($i % 2 == 0){
  echo "<div class='columntype'>";
  echo "<form class='formRes' method='POST' action='manage.php' >";
  echo "<button name='restaurant' class='box' style='height: 10vw;width: 10vw; grid-column: span 2;float: left;' value=" . $rez[$i]['id'] . " type='submit'>" . $rez[$i]['name'] . "</button>";
  echo "</form>";
  echo "</div>";
}else{
  echo "<div class='columntype'>";
  echo "<form class='formRes' method='POST' action='manage.php' >";
  echo "<button name='restaurant' class='box' style='height: 10vw;width: 10vw; grid-column: span 2;float: left;' value=" . $rez[$i]['id'] . " type='submit'>" . $rez[$i]['name'] . "</button>";
  echo "</form>";
  echo "</div>";
  // if($i % 2 == 0)
  // echo "<span></span>";
}
}
echo "</div>";

?>


<div class="containerRest">
  <form class="form" method = "post" action = "myrestaurant.php">
    <p>Add a new restaurant</p>
    <input type = "text" name = "name" placeholder="Name" required><br>
    <input type = "text" name = "address" placeholder="Address" required><br>
    <input type = "text" name = "description" placeholder="Description" required><br>
    <input type="submit" value="Submit"><br>
    
  </form>

  <div class="drops">
    <div class="drop drop-1"></div>
    <div class="drop drop-2"></div>
    <div class="drop drop-3"></div>
    <div class="drop drop-4"></div>
    <div class="drop drop-5"></div>
  </div>
</div>

<?php

  function translate_data1($data){
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
  }

  function validateRestaurant($nume, $adresa, $descriere){
    $regexForName = "/^[A-Za-z\s]{1,64}$/i";
    $errors = "";
    if(!preg_match($regexForName, $nume))
      $errors .= "Invalid restaurant name!";
    $regexForAdress = "/^[A-Za-z0-9\s\-,\.]{1,64}$/i";
    
    if(!preg_match($regexForAdress, $adresa))
      $errors .= "Invalid adress name!";
      
    if(strlen($descriere) > 1024)
      $errors .= "Description is too big!";

    if(strlen($errors) > 0)
      throw new Exception($errors);
  }

  $nume = $adresa = $descriere = "";

  if(isset($_POST['name']) and isset($_POST['address']) and isset($_POST['description'])){

      $nume = translate_data1($_POST["name"]);
      $adresa = translate_data1($_POST["address"]);
      $descriere = translate_data1($_POST["description"]);
      validateRestaurant($nume, $adresa, $descriere);

      $connection = new mysqli("localhost", "root", "", "site");
      if($connection->connect_error)

        die("Error occured".$connection->connect_error);

      $sql = "INSERT INTO restaurants(`name`, `address`, rating, mail, `description`) VALUES (?, ?, ?, ?, ?)"; 
      
      $statement = $connection->prepare($sql);

      $rating = rand(300, 500) / 100;

      $statement->bind_param("ssdss", $nume, $adresa, $rating, $_COOKIE['mail'], $descriere);

      $statement->execute();

      unset($_POST);

      echo '<script>window.location.href = "myrestaurant.php";</script>';

      echo '<script>console.log("Restaurant added!")</script>';
  }

?>

  </main>

  <footer id="footer">
  <img src="images/logo.png" style="width: 10vw; margin-left: 4vw" alt="">
    <div class="container">
      
      <div class="copyright" style=" margin-left: -2vw" >
      
        &copy; Copyright digEATal <strong><span></span></strong>
      </div>
    </div>
  </footer>

  <script src="aboutme/js/aos.js"></script>
  <script src="aboutme/js/bootstrap.bundle.min.js"></script>
  <script src="aboutme/js/glightbox.min.js"></script>
  <script src="aboutme/js/swiper-bundle.min.js"></script>

  <script src="aboutme/js/main.js"></script>
</body>

</html>