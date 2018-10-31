<?php
    session_start();
    include 'conn.php';
    
    if ($_SESSION['admin'] == 0) {
        header("Location:index.php");
        die();
    } else if ($_SESSION['admin'] == 1) {
        $gameid = $_GET['gameid'];
        $statement = 'DELETE FROM GAMES WHERE GAMEID=' . $gameid;
        $stid = oci_parse($conn, $statement);
        oci_execute($stid);
        oci_close($conn);
        header("Location: index.php");
    }
?>