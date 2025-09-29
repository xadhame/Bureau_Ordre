<?php 
session_start();

if(isset($_SESSION)){
    session_destroy() ;
    header('location:connecter.php');
}
else{
    header('location:index.php');
}

?>