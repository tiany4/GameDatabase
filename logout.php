<?php
   session_start();
   if (session_destroy()) {
       unset($_SESSION['username']);
       $_SESSION['admin']=0;
       echo 'Logging out...';
       header('Refresh: 2; URL = index.php');
   }
?>