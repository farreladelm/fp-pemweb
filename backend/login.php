<?php
session_start();
include "koneksi.php";

if (isset($_SESSION["use"])) {
  header("Location:index.php");
}

if (isset($_POST["login"])) {
  $email = $_POST["email"];
  $password = $_POST["password"];

  $result = mysqli_query(
    $koneksi,
    "SELECT * FROM user WHERE email='$email' AND password='$password'"
  );

  $cek = mysqli_num_rows($result);

  if ($cek > 0) {
    $data = mysqli_fetch_assoc($result);
    $_SESSION["email"] = $email;
    $_SESSION["id"] = $data["id"];
    $_SESSION["name"] = $data["name"];

    if ($data["is_active"] == 1) {
      if ($data["is_admin"] == 1) {
        echo "<script>alert('admin login');";
        header("Location: admin/index.php");
      } else {
        echo "<script>alert('user login');";
        header("Location: index.php");
      }
    } else {
      echo "<script>alert('Akun belum aktif, silahkan cek email anda!'); window.location.href='login.php'</script>";
    }
  } else {
    echo "<script>alert('Email atau password salah!'); window.location.href='login.php'</script>";
  }
}
?>
<html>
<head>

<title> Login Page   </title>

</head>

<body>

<form action="" method="post">

    <table width="200" border="0">
  <tr>
    <td>  Email</td>
    <td> <input type="text" name="email" required> </td>
  </tr>
  <tr>
    <td> Password  </td>
    <td><input type="password" name="password" required></td>
  </tr>
  <tr>
    <td> <input type="submit" name="login" value="LOGIN"></td>
    <td></td>
  </tr>
</table>
</form>

</body>
</html>