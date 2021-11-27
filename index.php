<!DOCTYPE html>
<html lang="en">
 <?php

include_once "utils.php";

function getAllRestaurants($search_tokens){
  $array_tokens = explode(" ", $search_tokens);

  $servername = "localhost";
  $username  = "root";
  $password = "";
  $database = "site";
  $conn = new mysqli($servername, $username, $password, $database);

  if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
  }

  $sql = "select * from restaurants";
  $result = $conn->query($sql);
  $matchingRestaurants = array();
  while($row = $result->fetch_assoc()) {
      $restaurantName = strtolower($row["name"]) ;
      $matching = true;
      foreach ($array_tokens as $token) {
          $lowercaseToken = strtolower($token);
          $matching = $matching && strstr($restaurantName, $lowercaseToken);
          if($matching == false){
            break;
          }
      }
  
      if($matching){
          $foundRestaurant['id'] = $row['id'];
          $foundRestaurant['name'] = $row['name'];
          $foundRestaurant['address'] = $row['address'];
          $foundRestaurant['rating'] = $row['rating'];
          array_push($matchingRestaurants, $foundRestaurant);
      }
    }
    return $matchingRestaurants;
}

  if(!isset($GLOBALS['connection_global_var'])){
    $GLOBALS['connection_global_var'] = new mysqli('localhost', 'root', '', 'site');
  }

  if(isset($_COOKIE["mail"])){

    $conn = new mysqli('localhost', 'root', '', 'site');

    if($conn->connect_error){

        die("Connection failed: " . $conn->connect_error);
    }

    $result = getEntity();  
    echo '<script>console.log("'.$result.'");</script>';
  }
  
else

  echo '<script>window.location.href = "login.php";</script>';
 
if($_SERVER["REQUEST_METHOD"] == "GET"){

  $search_tokens = "";

  if(array_key_exists( "search", $_GET))
    $search_tokens = $_GET["search"];
    
  $GLOBALS['var_globala_search'] = json_encode(getAllRestaurants($search_tokens));
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

</head>

<body>

  <!-- ======= Mobile nav toggle button ======= -->
  <i class="bi bi-list mobile-nav-toggle d-xl-none"></i>

  <!-- ======= Header ======= -->
  <header id="header">
    <div class="d-flex flex-column">

      <div class="profile">
        <img src="images/AccountIcon2.png" alt="" class="img-fluid rounded-circle">
        <h1 class="text-light" style="font-size: 3vh;"><a href="index.php">
          <?php echo explode("|",getEntity())[0].' '.explode("|",getEntity())[1];?>
        </a></h1>
       
      </div>

      <nav id="navbar" class="nav-menu navbar">
        <ul>
          <li><a href="index.php" class="nav-link scrollto active"><div class="bxicons"><img src="https://www.google.com/imgres?imgurl=https%3A%2F%2Flookaside.fbsbx.com%2Flookaside%2Fcrawler%2Fmedia%2F%3Fmedia_id%3D528565310990101&imgrefurl=https%3A%2F%2Fm.facebook.com%2Ffoodicon11%2Fphotos%2Fa.528561437657155%2F528565310990101%2F%3Ftype%3D3%26m_entstream_source%3Dtimeline&tbnid=7y15F78KGwgzOM&vet=12ahUKEwi8-6_fmKn0AhWP-6QKHTJ7Dc8QMygJegUIARC-AQ..i&docid=7OeXsRRt-zaVDM&w=981&h=862&q=food%20icon&ved=2ahUKEwi8-6_fmKn0AhWP-6QKHTJ7Dc8QMygJegUIARC-AQ" alt=""></div> <span>Home</span></a></li>
          <li><a href="aboutme.php" class="nav-link scrollto"> <span>About</span></a></li>
         <li><a href="#contact" class="nav-link scrollto"> <span>Contact</span></a></li>
         <?php 
         if(sizeof(explode("|",getEntity())) == 6)
          echo '<li><a href="myrestaurant.php" class="nav-link scrollto "> <span>My restaurants</span></a></li>
          <li style ="margin-top: -15%;"><a class="nav-link scrollto"></a>';
          else {
            echo '<li><a href="myreservation.php" class="nav-link scrollto "><<span>My reservations</span></a></li>
          <li style ="margin-top: -15%;"><a class="nav-link scrollto"></a>';
          }
         ?>
         
          <form action="index.php">
      <input type="text" placeholder="Search.." name="search">
      <button type="submit"></button></a>
    </form>
          </li>
          <li> <a href="logout.php" class="nav-link scrollto" style="position: fixed; top:0;left:0 ;margin:1vh;"><span>
          Logout
        </span></a></li>
        </ul>
      </nav>
    </div>
  </header>
  <main id="main">
  <script>
function showSearch(){
var varSearch = <?php
echo $GLOBALS['var_globala_search'];
?>;
tableString = document.createElement('div');
tableString.setAttribute("id", "gridrestaurants");
// tableString.setAttribute("class", "bordereffect");

body = document.getElementsByTagName('main')[0];
var inner = '';
var position = 0;
for (row = 0; row < varSearch.length; row += 1) {

      inner += "<div class='box'>";
      inner += '<span></span>';inner += '<span></span>';inner += '<span></span>';inner += '<span></span>';
      inner += '<form method ="post" action="booking.php">'
      inner += '<button class="content" style="background: transparent;" name ="restaurant" value="'+varSearch[position]['id']+'" type ="submit">';
      inner += 'Nume:'+varSearch[position]['name']+' <br> Adresa:'+varSearch[position]['address']+'<br>Rating: '+varSearch[position]['rating']+'';
      inner += '</button></form>';
      // console.log(mysvar[position]['name']);
      inner += "</div>";
      console.log(position);
      position += 1;
      
      
}

tableString.innerHTML = inner;
// tableString += "</div>";
body.appendChild(tableString);
console.log(varSearch);
}
showSearch();
  </script>
    <script>
      console.log("FUNCTION");
      function showRestaurant(){
      console.log("FUNCTION2");

      }
      showRestaurant();

    </script>
<section>
 
   
      
    
 
</section>
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