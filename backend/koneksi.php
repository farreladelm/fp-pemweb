<?php
date_default_timezone_set("Asia/Bangkok");
$host = "localhost";
$user = "root";
$pass = "";
$db = "lokotre";

$koneksi = mysqli_connect($host, $user, $pass, $db);

if (!$koneksi) {
  die("Koneksi gagal : " . mysql_connect_error());
}
?>
