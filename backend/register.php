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
$mail->Username = "20081010064@student.upnjatim.ac.id";
$mail->Password = "sauthil0708";

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
    echo "<script>alert('Email telah terdaftar, silahkan masuk ke akun anda.'); window.location.href='login.php'</script>";
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
        ".<br><b>Click below to activate your LokoTre account.</b><br><a href='localhost/fp-pemweb/backend/verify.php?token=" .
        $token .
        "'>Verify now</a>";

      $mail->MsgHTML($content);
      if ($mail->Send()) {
        echo "Email sent to" . $email;
      } else {
        echo "Send email failed";
      }
      echo "<script>alert('Registrasi sukses, silahkan cek email anda untuk verifikasi.'); window.location.href='index.php'</script>";
    } else {
      echo "<script>alert('Registrasi gagal.'); window.location.href='register.php'</script>";
    }
  }
}

$provinsi_json = json_decode(fetch($url_province));
$provinsi = $provinsi_json->provinsi;
?>



<html>
<head>

<title> Register Page   </title>
<!-- Latest compiled and minified CSS -->
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
  <link rel="stylesheet" href="assets/select2-4.0.6-rc.1/dist/css/select2.min.css">
  <script src="assets/select2-4.0.6-rc.1/dist/js/select2.min.js"></script>   
  <script src="assets/select2-4.0.6-rc.1/dist/js/i18n/id.js"></script>  
  <script src="assets/js/address.js"></script>  
  <style>
    input, select {
  width: 100%;
  padding: 12px 20px;
  margin: 8px 0;
  display: inline-block;
  border: 1px solid #ccc;
  border-radius: 4px;
  box-sizing: border-box;
}
  </style>

  
</head>

<body>
    <div class="form">
<form action="" method="post">

    <table border="0">
  <tr>
    <td>  Email</td>
    <td> <input type="text" name="email"required></td>
  </tr>
  <tr>
    <td> Password  </td>
    <td><input type="password" name="password" required></td>
  </tr>
  <tr>
    <td>  Nama</td>
    <td> <input type="text" name="name"required></td>
  </tr>
  <tr>
    <td>  Phone</td>
    <td> <input type="number" name="phone"required></td>
  </tr>
  <tr>
    <td>  Province</td>
    <td> <select name="province" id="province">
                    <option></option>
                    <?php foreach ($provinsi as $key => $value) {
                      echo "<option value='$value->id'>$value->nama</option>";
                    } ?>
                  </select></td>
  </tr>
  <tr>
    <td>  City</td>
    <td> <select name="city" id="city">
                    <option></option>
                  </select></td>
  </tr>
  <tr>
    <td>  District</td>
    <td></div><select name="district" id="district">
                    <option></option>
                  </select></td>
  </tr>
    <tr>
    <td>  Village</td>
    <td><select name="village" id="village">
                    <option></option>
                  </select></td>
  </tr>
  <tr>
    <td>  Address</td>
    <td> <input type="text" name="address" required></td>
  </tr>
  <tr>
    <td>  Gender</td>
    <td> 
        <select name="gender">
            <option value="1">Laki-laki</option>
            <option value="1">Perempuan</option>
        </select>
    </td>
  </tr>
  <tr>
    <td> <input type="hidden" name="selectedAll" id="selectedAll" value="">
    <input type="submit" name="register" value="DAFTAR"></td>
    <td></td>
  </tr>
</table>
</form>
</div>
</body>
</html>