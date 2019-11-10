<?php

$dbConnection = mysqli_connect("localhost", "root", "", "renting");

if (mysqli_connect_errno()) {
    echo "Failed to connect to MySQL; " . mysqli_connect_error();
}