<?php 

$server= "localhost";
$user="root";
$password="";
$database="projectdb";

$conn = mysqli_connect($server, $user, $password, $database);

if(!$conn){
    die("<script>alert('Something went wrong, please try again! ')</script>");
}

?>