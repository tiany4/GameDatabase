<?php
    include 'session.php';
    if (!isset($_SESSION['username']) || $_SESSION['username']==$_GET['name']) {
        header("Location:manage.php");
        die();
    }
    include 'conn.php';
    if ($_GET['banned']==0) {
        $ban = 1;
    } else {
        $ban = 0;
    }
    $statement = "UPDATE ACCOUNTS SET ban = :ban_b WHERE USERNAME = :name_b";
    $stid = oci_parse($conn, $statement);
    oci_bind_by_name($stid, ':ban_b', $ban);
    oci_bind_by_name($stid, ':name_b', $_GET['name']);
    if (oci_execute($stid)) {
        oci_close($conn);
        header("Location: manage.php");
    } else {
        $e = oci_error($stid); 
        echo $e['message']; 
    }
?>