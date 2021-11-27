<?php 
    
    require_once("xml/xml.php");
    require_once("utils.php");

    define("cookie_log_in", "mail");

    global $globalRestaurantId;
    $globalRestaurantId = null;

    //if(isset($_POST['restaurant']))
    {
        //echo "<script>alert('alo');</script>";
        if(isset($_POST['internalArr']))
        {
            $mainArray = explode("|", $_POST['internalArr']);

            //echo $mainArray[0];

            $restaurantId = $mainArray[0];

            $bigArray = explode(";", $mainArray[1]);

            $arr = array();
            for($i = 0; $i < count($bigArray); $i++)
            {
                $smallArray = explode(",", $bigArray[$i]);

                if(count($smallArray) == 3)
                {
                    $index = (int)$smallArray[0] * ROWNUM + (int)$smallArray[1];

                    $cell = new Cell($index, $smallArray[2]);
                    array_push($arr, $cell);
                    //echo "$cell\n";
                }
            }

            if(isset($_COOKIE[cookie_log_in]))
            {
                fromSingleArrayToXMLGrid($arr, $_COOKIE[cookie_log_in], $restaurantId);
            }

            else
                echo "<script> window.location.href = 'login.php'; </script>";
        }
    }

    {
        //echo "<script>window.location.href = 'index.php'; </script>";
    }

?>

<html>
<head>

    <link rel = "stylesheet" href = "css/table.css">
    <script src="js/jquery-3.6.0.js"></script>
  <link href="aboutme/css/backgge.css" rel="stylesheet">
  <link href="aboutme/css/aos.css" rel="stylesheet">
  <link href="aboutme/css/bootstrap.min.css" rel="stylesheet">
  <link href="aboutme/css/boxicons.min.css" rel="stylesheet">
  <link href="aboutme/css/style.css" rel="stylesheet">
  <link href="aboutme/css/restaurants.css" rel="stylesheet">
</head>
<body>
<!-- MENU -->
<!-- <div class="lines">
        <div class="line"></div>
        <div class="line"></div>
        <div class="line"></div>
      </div> -->
      <header id="header">
    <div class="d-flex flex-column">

      <div class="profile">
        <img src="images/AccountIcon2.png" alt="" class="img-fluid rounded-circle">
        <h1 class="text-light"><a href="index.php"><?php echo explode("|",getEntity())[0].' '.explode("|",getEntity())[1];?></a></h1>
       
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
  <footer id="footer">
  <img src="images/logo.png" style="width: 10vw; margin-left: 4vw" alt="">
    <div class="container">
      
      <div class="copyright" style=" margin-left: -2vw" >
      
        &copy; Copyright digEATal <strong><span></span></strong>
      </div>
    </div>
  </footer>
<!-- END MENU -->

<!-- RESTAURANT -->
<script type="text/javascript" src="/js/table.js"> </script>

<script>

    function blockCells()
    {
        extractData = "<?php echo getEntity(); ?>;"
        var arr = extractData.split("|");

        if(arr[4] == "bronze" || arr[4] == "silver")
        {
            var nonGreen = 0;

            for (row = 0; row < 10; row += 1)
            for (col = 0; col < 10; col += 1)    
            {
                var element = document.getElementById(row + " " + col);
                var color = getComputedStyle(element, "").backgroundColor;

                if (color != "rgb(0, 255, 0)")
                    nonGreen += 1;
                
            }

            if (nonGreen != 0)
            {
                for (row = 0; row < 10; row += 1)
                    for (col = 0; col < 10; col += 1)    
                    {
                        var element = document.getElementById(row + " " + col);
                        element.setAttribute("disabled", true);
                    }
            }
        }
    }

    function loadCell(row, column, color)
    {
        var createID = row + ' ' + column;
        document.getElementById(createID).style.background = color;
    }

    class Cell
    {
        constructor(xcoord, ycoord, status) 
        {
            this.xcoord = xcoord;
            this.ycoord = ycoord;
            this.status = status;
        }
    }

    Cell.prototype.toString = function() 
    {
        return "[" + this.xcoord + "," + this.ycoord + "," + this.status + "]";
    }

    globalVariable = "<?php if(isset($_POST['restaurant'])) echo $_POST['restaurant']; else echo ""; ?>";

    function loadAllCells()
    {
        var dict = 
        {
            "FREE": "rgb(0, 255, 0)",
            "OCCUPIED": "rgb(255, 0, 0)",
            "NONE": "rgb(104, 104, 104)"
        };

        var arr = <?php $arr = fromXMLToArrayGrid($_COOKIE[cookie_log_in], $_POST['restaurant']); echo json_encode($arr); ?>;

        for (index = 0; index < 100; index += 1)
        {
            var c = new Cell(arr[index]['xcoord'], arr[index]['ycoord'], arr[index]['status'][0]);

            loadCell(c.xcoord, c.ycoord, dict[c.status])
        }
    }

    loadAllCells();
    blockCells();

</script>

<div class="allGarbage">
    <!-- <button onclick="showHideYourRestaurant()">Show/Hide your Restaurant</button> -->
    <button id="submit" onclick="submit()">Submit Changes</button>
    <!-- <button onclick="loadCells(2,2,'rgb(255, 0, 0)')">Change</button> -->
    <button onclick="window.location.href = 'myrestaurant.php'">Back</button>
</div>


<script>

    function doLogic(dataString)
    {
        var form = document.createElement('form');
        form.setAttribute("method", "post");
        form.setAttribute("action", "manage.php");

        var input = document.createElement('input');
        input.setAttribute('name', 'internalArr');
        input.setAttribute('type', 'text');
        input.setAttribute('value', dataString);
        input.setAttribute('hidden', true);

        var input2 = document.createElement('input');
        input2.setAttribute('name', 'restaurant');
        input2.setAttribute('type', 'text');
        input2.setAttribute('value', '<?php echo $_POST['restaurant']; ?>');
        input2.setAttribute('hidden', true);

        var s = document.createElement("input");
        s.setAttribute("id", "sub");
        s.setAttribute("type", "submit");
        s.setAttribute("value", "Submit");
        s.setAttribute('hidden', true);

        form.appendChild(input);
        form.appendChild(input2);
        form.appendChild(s);

        document.getElementsByTagName('body')[0].appendChild(form);

        $(document).ready(function(){
                    
            $("#sub").click();
        });
    }

    var dict = 
    {
        "rgb(0, 255, 0)": "FREE",
        "rgb(255, 0, 0)": "OCCUPIED",
        "rgb(104, 104, 104)": "NONE"
    };

    function submit()
    {
        var array = [];

        for (row = 0; row < 10; row += 1)
            for (col = 0; col < 10; col += 1)
            {
                // rgb(255, 0, 0) - red
                // rgb(0, 255, 0) - green
                // rgb(104, 104, 104) - gray

                var myDivObjBgColor = getComputedStyle( document.getElementById(row + ' ' + col), "").backgroundColor;
                
                var element = document.getElementById(row + ' ' + col);
                var value = element.getAttribute('value');
                var status = dict[myDivObjBgColor];

                var c = new Cell(row, col, status);
                array.push(c);

                console.log(value);

            }

        //alert(array.toString());
        bigString = "<?php echo $_POST['restaurant']; ?>";
        bigString += "|";

        for(i = 0; i < array.length; i++)
        {
            bigString += array[i].xcoord + "," + array[i].ycoord + "," + array[i].status + ";";
        }

        console.log(bigString);
        doLogic(bigString);
        
        loadAllCells();

    }

</script>

</body>


</html>