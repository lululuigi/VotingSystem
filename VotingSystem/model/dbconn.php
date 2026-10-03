<?php

    $conn = new mysqli("localhost", "root", "", "votingsystem");

    if($conn->connect_error){
        echo "error";
    }

?>