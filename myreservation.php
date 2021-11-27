<!DOCTYPE html>
<html lang="en">
<head>
    <?php
    
        require_once 'xml/xml.php';

    ?>
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

<?php

    if(isset($_COOKIE["mail"])){

        $connection = new mysqli("localhost", "root", "", "site");

        if ($connection->connect_error){

            die("Connetion failed: ".$connection->connect_error);
        }

        $array = fromXMLToArrayBooking($_COOKIE["mail"]);

        echo "<script> alert('" . $array[0]->idRestaurant." ".$array[0]->indexMasa . "');</script>";

        echo "<script> alert('" . $array[1]->idRestaurant." ".$array[1]->indexMasa . "');</script>";

        echo "<script> alert('" . count($array) . "');</script>";

        $mail = $_COOKIE["mail"];
        
        $sql = 'INSERT INTO bookings(mail, id_restaurant, index_masa) VALUES (?, ?, ?)';

        $statement = $connection->prepare($sql);

        for($i = 0; $i < count($array); $i++){


            $id_restaurant = (int) $array[$i]->idRestaurant;
            $index_masa = (int) $array[$i]->indexMasa;

            echo "<script> alert('" . $i . " " . $id_restaurant . " " . $index_masa . "');</script>";

            if($statement === false)
            {
              $connection->close();
              throw new Exception("Error occured!\n");
            }

            else{

                $statement->bind_param("sii", $mail, $id_restaurant, $index_masa);

                echo "<script> alert('" . count($array) . "');</script>";

                $statement->execute();
            } 
        }

        echo '<script>console.log("Reservation successfull!");</script>';
        echo '<script>window.location.href = "index.php";</script>';
    }

    else
    
        echo '<script>window.location="login.php"</script>';

?>

  <!-- ======= Mobile nav toggle button ======= -->
  <i class="bi bi-list mobile-nav-toggle d-xl-none"></i>

  <!-- ======= Header ======= -->
  <header id="header">
    <div class="d-flex flex-column">

      <div class="profile">
        <img src="images/AccountIcon2.png" alt="" class="img-fluid rounded-circle">;
        <h1 class="text-light"><a href="index.php"><?php echo explode("|",getEntity())/*[0].' '.explode("|",getEntity())[1]*/;?></a></h1>
       
      </div>

      <nav id="navbar" class="nav-menu navbar">
        <ul>
          <li><a href="index.php" class="nav-link scrollto active"><i class="bx bx-home"></i> <span>Home</span></a></li>
          <li><a href="aboutme.php" class="nav-link scrollto"><i class="bx bx-user"></i> <span>About</span></a></li>
         <li><a href="#contact" class="nav-link scrollto"><i class="bx bx-envelope"></i> <span>Contact</span></a></li>
         <li><a href="myrestaurant.php" class="nav-link scrollto "><i class="bx bx-home"></i> <span>My restaurants</span></a></li>
       <li style ="margin-top: -15%;"><a class="nav-link scrollto"></a>
         
          </li>
          <li><a href="logout.php" class="nav-link scrollto active"  style="position: fixed; top:0;left:0 ;margin:1vh;"></a><i class="bx bx-home"></i> <span>Logout</span></a></li>
        </ul>
      </nav>
    </div>
  </header>
  <main id="main"></main>

<?php

function getAttributesOfRestaurant($id){
    $conn = new mysqli("localhost", "root", "", "site");
    if ($conn->connect_error){

        die("Connetion failed: ".$conn->connect_error);
    }
    $sql = 'SELECT * from restaurants where id = ?';
    $statement = $conn->prepare($sql);
    $statement->bind_param("i", $id);
    $statement->execute();
    $result = $statement->get_result();
    $dict = array();
    if($row = $result->fetch_assoc()){
        $dict['name'] = $row['name'];
        $dict['address'] = $row['address'];
        $dict['rating'] = $row['rating'];
        $dict['description'] = $row['description'];
        return $dict;
    }
    return $dict;
}

$rez = getRestaurantsAttributesForManager($_COOKIE['mail']);
// echo gettype($rez[0]);
for($i = 0; $i < count($rez); $i++)
{
  echo "<form class='formRes' method='POST' action='manage.php'>";
  echo "<button name='restaurant' class='box' style='height: 6vw;' value=" . $rez[$i]['id'] . " type='submit'>" . $rez[$i]['name'] . "</button>";
  echo "</form>";
}

?>


<div class="containerRest">
  

  <div class="drops">
    <div class="drop drop-1"></div>
    <div class="drop drop-2"></div>
    <div class="drop drop-3"></div>
    <div class="drop drop-4"></div>
    <div class="drop drop-5"></div>
  </div>
</div>

<?php
/* 
  if(!isset($_COOKIE['mail']))

    echo '<script>window.location="login.php"</script>';

  else{

    if($_SERVER["REQUEST_METHOD"] == "POST"){

        $mail = $_COOKIE["mail"];
        $id_restaurant = $_POST["id_restaurant"];
        $index_masa = $_POST["index_masa"];

        $connection = new mysqli("localhost", "root", "", "site");

        if ($connection->connect_error){

            die("Connetion failed: ".$connection->connect_error);
        }

        $sql = 'INSERT INTO bookings(mail, id_restaurant, index_masa) VALUES (?, ?, ?)';

        $statement = $connection->prepare($sql);

        if($statement === false)

            throw new Exception("Error occured!\n");

        else{

            $statement->bind_param("sii", $mail, $id_restaurant, $index_masa);

            $statement->execute();

            echo '<script>console.log("Reservation successfull!")</script>';
            echo '<script>window.location = "myreservation.php"</script>';
        }    
    }
  }
  */
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