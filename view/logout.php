<?php 
session_start();
session_unset();
session_destroy();

require_once("../design/header.php"); 

header("Location: ../index.php");


  

