<?php
    include("../../config.php");

    if(isset($_POST['name']) && isset($_POST['username'])) {
        $name = $_POST['name'];
        $username = $_POST['username'];
        $date = date("Y-m-d");

        $query = mysqli_query($con, "INSERT INTO playlists VALUES (0, '$name', '$username', '$date')");

    }
?>