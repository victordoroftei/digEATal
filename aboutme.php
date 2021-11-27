<!DOCTYPE html>
<html lang="en">

<?php

include_once "utils.php";

if(isset($_COOKIE["mail"])){

  $conn = new mysqli('localhost', 'root', '', 'site');

  if($conn->connect_error){

      die("Connection failed: " . $conn->connect_error);
  }

  $result = getEntity($conn);  

  echo '<script> console.log("'.$result.'") </script>';
}
else

  echo '<script>window.location.href = "login.php";</script>';

if($_SERVER["REQUEST_METHOD"] == "POST"){

    $email = $_COOKIE['mail'];

    $conn = new mysqli('localhost', 'root', '', 'site');

    if($conn->connect_error)

      die("Connect error".$conn->connect_error);

    $new_sub = $_POST['subscription'];    

    $sql = "UPDATE managers SET subscription=? WHERE mail=?";

    $statement = $conn->prepare($sql);
    $statement->bind_param("ss", $new_sub, $email);

    $statement->execute();

    $conn->close();
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
        <h1 class="text-light" style="font-size: 3vh;"><a href="index.php"><?php echo explode("|",getEntity())[0].' '.explode("|",getEntity())[1];?></a></h1>
       
      </div>

      <nav id="navbar" class="nav-menu navbar">
        <ul>
          <li><a href="index.php" class="nav-link scrollto active"> <span>Home</span></a></li>
          <li><a href="aboutme.php" class="nav-link scrollto"> <span>About</span></a></li>
         <li><a href="#contact" class="nav-link scrollto"> <span>Contact</span></a></li>
         <li><a href="logout.php" class="nav-link scrollto"  style="position: fixed; top:0;left:0 ;margin:1vh;"> <span>Logout</span></a></li>
         <li><a href="myrestaurant.php" class="nav-link scrollto "><span>My restaurants</span></a></li>
          <li style ="margin-top: -15%;"><a class="nav-link scrollto"></a>
        </ul>
      </nav>
    </div>
  </header>
  <main id="main">
    <section id="about" class="about">
      <div class="container">

        <div class="section-title">
          <h2>About</h2>
          <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. A, dolore, exercitationem dicta nihil est officia perspiciatis maxime hic libero dolorem culpa sunt velit fuga repellendus laudantium delectus ducimus iusto! Culpa laboriosam tenetur, amet totam, obcaecati autem aliquam iure ea suscipit consequuntur nobis est nemo quibusdam. At sed culpa porro eos.</p>
        </div>

        <div class="row">
          <div class="col-lg-4" data-aos="fade-right">
            <img src="images/AccountIcon2.png" class="img-fluid" alt="">
          </div>
          <div class="col-lg-8 pt-4 pt-lg-0 content" data-aos="fade-left">
            <h3><?php echo explode("|",getEntity())[0].' '.explode("|",getEntity())[1];?></h3>
            <p class="fst-italic">
              Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore
              magna aliqua.
            </p>
            <div class="row">
              <div class="col-lg-6">
                <ul>
                <li><i class="bi bi-chevron-right"></i> <strong>Nume:</strong> <span><?php echo explode("|",getEntity())[1];?></span></li>
                  <li><i class="bi bi-chevron-right"></i> <strong>Prenume:</strong> <span><?php echo explode("|",getEntity())[0];?></span></li>
                  <li><i class="bi bi-chevron-right"></i> <strong>Email:</strong> <span><?php echo $_COOKIE['mail'];?></span></li>
                  
                 
                
                <li><i class="bi bi-chevron-right"></i> <strong>Subscription:</strong>
                <form action="aboutme.php" method="post">
                <input type="submit" id="bronze" name="subscription" value ="bronze"<?php if (explode("|",getEntity())[4] == "bronze") echo 'disabled';?>/>
                </form>
                <form action="aboutme.php" method="post">
                <input type="submit" id="silver" name="subscription" value ="silver"<?php if (explode("|",getEntity())[4] == "silver") echo 'disabled';?>/>
                </form>
                <form action="aboutme.php" method="post">
                <input type="submit" id="gold" name="subscription" value ="gold"<?php if (explode("|",getEntity())[4] == "gold") echo 'disabled';?>/>
                </form>
                <form action="aboutme.php" method="post">
                <input type="submit" id="platinum" name="subscription" value ="platinum"<?php if (explode("|",getEntity())[4] == "platinum") echo 'disabled';?>/>
                </form>
                </ul>
              </div>
              <div class="col-lg-6">
                <ul>
                  <li><i class="bi bi-chevron-right"></i> <strong>Nr. de telefon:</strong> <span><?php echo explode("|",getEntity())[3];?></span></li>
                  <li><i class="bi bi-chevron-right"></i> <strong>Gender:</strong> <span><?php echo explode("|",getEntity())[5];?></span></li>

                </ul>
              </div>
            </div>
  
          </div>
        </div>

      </div>
    </section>

  </main>

  <footer id="footer">
  <img src="images/logo.png" style="width: 10vw; margin-left: 4vw" alt="">
    <div class="container">
      <div class="copyright" style=" margin-left: -2vw">
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