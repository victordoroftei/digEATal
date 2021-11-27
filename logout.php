<?php

    define("cookie_log_in", "mail");

    function deleteCookie() {
  
        setcookie(cookie_log_in, "", time() - 3600 * 1000 * 24);
    
    }

    deleteCookie();

    echo "<script> window.location.href = 'login.php'; </script>";

?>