<?php
include "koneksi.php";

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require "PHPMailer/src/Exception.php";
require "PHPMailer/src/PHPMailer.php";
require "PHPMailer/src/SMTP.php";

$mail = new PHPMailer();
$mail->IsSMTP();
$mail->Mailer = "smtp";

$mail->SMTPDebug = 0;
$mail->SMTPAuth = true;
$mail->SMTPSecure = "tls";
$mail->Port = 465;
$mail->Host = "ssl://smtp.gmail.com";
$mail->Username = "";
$mail->Password = "";

session_start();
session_destroy();

$url_province = "https://dev.farizdotid.com/api/daerahindonesia/provinsi";

function fetch($url)
{
  $curl = curl_init($url);
  curl_setopt($curl, CURLOPT_URL, $url);
  curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

  $resp = curl_exec($curl);
  curl_close($curl);
  return $resp;
}

function generateToken($length = 40)
{
  $characters =
    "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
  $charactersLength = strlen($characters);
  $randomString = "";
  for ($i = 0; $i < $length; $i++) {
    $randomString .= $characters[rand(0, $charactersLength - 1)];
  }
  return $randomString;
}

if (isset($_POST["register"])) {
  $email = $_POST["email"];

  $query_duplicate = "SELECT * FROM user WHERE email = '$email'";
  $res_duplicate = $koneksi->query($query_duplicate);
  $row_duplicate = $res_duplicate->fetch_assoc();
  if ($row_duplicate) {
    echo "<script>alert('Email telah terdaftar, silahkan masuk ke akun anda.'); window.location.href='index.php?action=login'</script>";
    exit();
  }

  $password = $_POST["password"];
  $token = generateToken();
  $name = $_POST["name"];
  $phone = $_POST["phone"];
  $province = $_POST["province"];
  $city = $_POST["city"];
  $district = $_POST["district"];
  $village = $_POST["village"];
  $address = $_POST["address"];
  $gender = $_POST["gender"];
  $date_register = date("Y-m-d H:i:s");

  $selectedAll = $_POST["selectedAll"];
  $selectedAll = explode("|", $selectedAll);
  $province = $selectedAll[0];
  $city = $selectedAll[1];
  $district = $selectedAll[2];
  $village = $selectedAll[3];

  if (
    empty($email) ||
    empty($password) ||
    empty($token) ||
    empty($name) ||
    empty($phone) ||
    empty($province) ||
    empty($city) ||
    empty($district) ||
    empty($village) ||
    empty($address) ||
    empty($gender)
  ) {
    echo "<script>alert('Data harus diisi!'); window.location.href='register.php'</script>";
  } else {
    $q = $koneksi->query(
      "INSERT INTO user VALUES ('', '$email', '$password', '$token', '', '', '$name', '$phone', '$province', '$city', '$district', '$village', '$address', '$gender', '$date_register')"
    );

    if ($q) {
      $mail->IsHTML(true);
      $mail->AddAddress($email, $name);
      $mail->SetFrom("20081010064@student.upnjatim.ac.id", "LokoTre");
      $mail->Subject = "Email verification LokoTre";
      $content =
        "Hello " .
        $name .
        ".<br><b>Click below to activate your LokoTre account.</b><br><a href='localhost/fp-pemweb/verify.php?token=" .
        $token .
        "'>Verify now</a>";

      $mail->MsgHTML($content);
      if ($mail->Send()) {
        echo "Email sent to" . $email;
      } else {
        echo "Send email failed";
      }
      echo "<script>alert('Registrasi sukses, silahkan cek email anda untuk verifikasi.'); window.location.href='index.php?action=login'</script>";
    } else {
      echo "<script>alert('Registrasi gagal.'); window.location.href='register.php'</script>";
    }
  }
}

$provinsi_json = json_decode(fetch($url_province));
$provinsi = $provinsi_json->provinsi;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/select2-4.0.6-rc.1/dist/css/select2.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="assets/select2-4.0.6-rc.1/dist/js/select2.min.js"></script>   
    <script src="assets/select2-4.0.6-rc.1/dist/js/i18n/id.js"></script>  
    <script src="assets/js/address.js"></script>  
    <script src="https://kit.fontawesome.com/1cf4c7dee0.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="./regis.css">
    <title>Sign Up</title>
</head>
<body>
    <div class="container">
        <div class="form-container">
          <div class="text">Registration Form</div>
          <form action="" method="post">
            <div class="data">
              <label for="name">Name</label>
              <input type="text" name="name" required>
            </div>
            <div class="data">
              <label for="email">Email</label>
              <input name="email" type="email" required>
            </div>
            <div class="data">
              <label for="password">Password</label>
              <input type="password" name="password" required>
            </div>
            
            <div class="data">
              <label for="phone">Phone Number</label>
              <input type="text" name="phone" required>
            </div>
            <div class="data">
              <label for="province">Province</label>
              <select name="province" id="province">
                    <option></option>
                    <?php foreach ($provinsi as $key => $value) {
                      echo "<option value='$value->id'>$value->nama</option>";
                    } ?>
                  </select>
            </div>
            <div class="data">
              <label for="city">City</label>
             <select name="city" id="city">
                    <option></option>
                  </select>
            </div>
            <div class="data">
              <label for="disctrict">District</label>
              <select name="district" id="district">
                    <option></option>
                  </select>
            </div>
            <div class="data">
              <label for="village">Village</label>
              <select name="village" id="village">
                    <option></option>
                  </select>
            </div>
            <div class="data">
              <label for="address">Address</label>
              <input type="text" name="address" required>
            </div>
            <div class="data">
              <label for="gender">Gender</label >
        <select name="gender" id="gender">
            <option value="1">Laki-laki</option>
            <option value="2">Perempuan</option>
        </select>
            </div>
            <input type="hidden" name="selectedAll" id="selectedAll" value="">
            <input type="submit" name="register" value="submit" class="submit-btn">
          </form>
        </div>
      </div>
</body>
</html>