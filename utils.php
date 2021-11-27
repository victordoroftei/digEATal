<?php

    /**
     * function that returns the password of the corresponding email from the given table
     * @returns - null, if the email address doesn't correspond to any account from the table
     *            String(password), otherwise 
     */
    function find_in_table($connection, $email, $table){
        $sql = "select pass from " . $table . " where mail = ?";
        $statement = $connection->prepare($sql);
        $statement->bind_param("s", $email);
        $statement->execute();
        $result = $statement->get_result();
        if($result->num_rows === 0)
            return null;
        $row = $result->fetch_assoc();

        return $row["pass"];
    }

    function getEntity(){  // verifici dupa split("|") daca nr_arg = 4 => normal user ELSE manager

        if(isset($_COOKIE["mail"])){    // poate trb sa inchidem conexiunea

            $conn = new mysqli("localhost", "root", "", "site");

            if($conn->connect_error)

                die("Connect error".$conn->connect_error);

            $email = $_COOKIE["mail"];

            $sql = "SELECT * FROM users WHERE mail=?";
            $statement = $conn->prepare($sql);
            $statement->bind_param("s", $email);
            $statement->execute();
            $result = $statement->get_result();
            $row = $result->fetch_assoc();

            if($result->num_rows > 0)

                return $row['first_name']."|".$row['last_name']."|".$row['pass']."|".$row['phone_number']."|".$row['gender'];   

            $sql = "SELECT * FROM managers WHERE mail=?";
            $statement = $conn->prepare($sql);
            $statement->bind_param("s", $email);
            $statement->execute();
            $result = $statement->get_result();   

            $row = $result->fetch_assoc();   

            return $row['first_name']."|".$row['last_name']."|".$row['pass']."|".$row['phone_number']."|".$row['subscription']."|".$row['gender'];
        }    
    }

    function getRestaurantsByChance(){

        $conn = new mysqli("localhost", "root", "", "site");

        if($conn->connect_error)

            die('Connection error: '.$conn->connect_error);

        $chance = rand(1, 100); // generam valoarea

        echo '<script>console.log("Suntem pe chance")</script>';

        $sql = "SELECT * FROM restaurants R INNER JOIN managers M on R.mail = M.mail INNER JOIN subscriptions S on S.s_type = M.subscription;";

        $result = $conn->query($sql);

        if($result->num_rows > 0){

            $main_dict = array();

            while ($row = $result->fetch_assoc()){
                
                if($row['s_sansa'] >= $chance){

                    $dict['id'] = $row['id'];
                    $dict['name'] = $row['name'];
                    $dict['address'] = $row['address'];
                    $dict['rating'] = $row['rating'];
                    array_push($main_dict, $dict);
                } 
            }

            $conn->close();

            return json_encode($main_dict);
        }
    }

    function getRestaurantsAttributesForManager($email){
        $conn = new mysqli("localhost", "root", "", "site");
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
          }
        $sql = "select R.id, R.name, R.address, R.rating, R.description from managers M inner join restaurants R on M.mail = R.mail where M.mail = ?";
        $statement = $conn->prepare($sql);
        $statement->bind_param("s", $email);
        $statement->execute();
        $result = $statement->get_result();
        $id_managers = array();
        while($row = $result->fetch_assoc()){
            $dict['id'] = $row['id'];
            $dict['name'] = $row['name'];
            $dict['address'] = $row['address'];
            $dict['rating'] = $row['rating'];
            $dict['description'] = $row['description'];
            array_push($id_managers, $dict);
        }
        //  echo $id_managers;
        return $id_managers;
    }

?>