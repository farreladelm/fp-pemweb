<?php
require_once "koneksi.php";

$token = $_GET["token"];

$sql = "SELECT * FROM user WHERE token = '$token'";
$result = $koneksi->query($sql);
$row = $result->fetch_assoc();

if ($token == $row["token"]) {
  $q = $koneksi->query("UPDATE user
            SET is_active = 1
            WHERE token = '$token'");

  if ($q) {
    $InvalidMSG = [
      "verify_status" =>
        "Success verifying email! Login to get start on LokoTre.",
    ];
    $InvalidMSGJSon = json_encode($InvalidMSG);
    echo $InvalidMSGJSon;
    header("Refresh: 3;URL=index.php?action=login");
    // header("Location:login.php");
  } else {
    $InvalidMSG = ["error" => "Verification invalid!"];
    $InvalidMSGJSon = json_encode($InvalidMSG);
    echo $InvalidMSGJSon;
  }
} else {
  $InvalidMSG = ["error" => "Token or email invalid!"];
  $InvalidMSGJSon = json_encode($InvalidMSG);
  echo $InvalidMSGJSon;
}

?>
