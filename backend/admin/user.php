<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=<device-width>, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="assets/style1.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons" />
</head>
<body>
    <div class="sidebar">
        <div class="sidebar-header">
            <h3>Menu</h3>
        </div>
        <nav class="side-menu">
            <ul>
                <a href="index.php"><li><img src="assets/grid-fill.svg" alt="">
                    
                        <span style="color: #000">Dashboard</span>
                    </li>
                </a>
                <a href="user.php">
                <li><img src="assets/person.svg" alt="">
                    
                        <span style="color: #000">User</span>
                    </li>
                </a>
                <a href="tranksaksi.php">
                <li><img src="assets/wallet.svg" alt="">
                    
                        <span style="color: #000">Tranksaksi</span>
                    </li>
                </a>
            </ul>
        </nav>
    </div>

    <div class="main-content">
        <header>
            <h3>User</h3>
             <?php
             session_start();
             include "../koneksi.php";

             if (!isset($_SESSION["id"])) {
               header("Location:../login.php");
             } else {
               $id = $_SESSION["id"];
               $result = mysqli_query(
                 $koneksi,
                 "SELECT * FROM user WHERE id='$id'"
               );

               $cek = mysqli_num_rows($result);

               if ($cek > 0) {
                 $data = mysqli_fetch_assoc($result);
                 if ($data["is_admin"] != 1) {
                   session_destroy();
                   echo "<script>alert('Anda bukan admin!'); window.location.href='login.php'</script>";
                 }
               }
             }

             echo "<b>" . $_SESSION["name"];

             echo "<a href='../logout.php'> Logout</a></b> ";
             $nomor = 1;
             ?>
        </header>
            <main>
                <div class="recent-orders">
                    <h2>User Terdata</h2>
                    <table>
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama</th>
                                <th>Email</th>
                                <th>Telepon</th>
                                <th>Alamat</th>
                                <th>Jenis Kelamin</th>
                                <th>Role</th>
                                <th>Terdaftar </th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $query = "SELECT * FROM user";
                            $result = $koneksi->query($query);
                            while ($data = $result->fetch_array()) {
                              $rows[] = $data;
                            }

                            foreach ($rows as $value) {

                              $address = $value["address"];
                              $address .= ", " . $value["village"];
                              $address .= ", " . $value["district"];
                              $address .= ", " . $value["city"];
                              $address .= ", " . $value["province"];
                              ?>
                            <tr>
                                <td><b><?= $nomor++ ?></b></td>
                                <th><?= $value["name"] ?></th>
                                <th><?= $value["email"] ?></th>
                                <th><?= $value["phone"] ?></th>
                                <th><?= $address ?></th>
                                <th><?php if ($value["gender"] == 1) {
                                  echo "Laki-laki";
                                } else {
                                  echo "Perempuat";
                                } ?></th>
                                <th><?php if ($value["is_admin"] == 1) {
                                  echo "Admin";
                                } else {
                                  echo "User";
                                } ?></th>
                                <th><?= $value["date_registered"] ?></th>
                            </tr>
                            <?php
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
        </main>
    </div>
</body>
</html>