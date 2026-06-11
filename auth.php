
<?php

require_once 'db.php';

if (!isset($_SESSION['landlord_id'])) {

    header("Location: landlord_login.php");
    exit();

}
?>

