<?php 
session_destroy();
//setcookie('username', 'logout', time()+86400);//this line is only for testing do not uncomment
header("location:index.html");
?>