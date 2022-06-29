<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=<device-width>, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="assets/style1.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/lykmapipo/themify-icons@0.1.2/css/themify-icons.css">
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
            <h3>Tranksaksi</h3>
             <?php
             session_start();
             include "../koneksi.php";

             function rupiah($angka)
             {
               $hasil_rupiah = "Rp " . number_format($angka, 2, ",", ".");
               return $hasil_rupiah;
             }

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
             ?>
        </header>
            <main>
                <div class="recent-orders">
                    <h2>Daftar Transaksi</h2>
                    <div class="date">
                        <input type="date">
                    </div>
                    <table>
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Name</th>
                                <th>Produk</th>
                                <th>Pax</th>
                                <th>Book Date</th>
                                <th>Amount</th>
                                <th>Total</th>
                                <th>Placed on</th>
                                <th>Payment</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $no = 1;
                            $query = "SELECT * FROM transaction";
                            $result = $koneksi->query($query);
                            while ($data = $result->fetch_array()) {
                              $rows[] = $data;
                            }

                            foreach ($rows as $value) {

                              $date_temp = strtotime($value["book_date"]);
                              $book_date = date("jS F Y", $date_temp);
                              $user_id = $value["user_id"];
                              $date_placed = date(
                                "jS F Y H:i",
                                strtotime($value["date_placed"])
                              );
                              $user_id = $value["user_id"];
                              $query_user = "SELECT * FROM user WHERE id = '$user_id'";
                              $data_user = $koneksi
                                ->query($query_user)
                                ->fetch_assoc();
                              $nama = $data_user["name"];

                              $product_id = $value["product_id"];
                              $query_product = "SELECT * FROM product WHERE id = '$product_id'";
                              $data_product = $koneksi
                                ->query($query_product)
                                ->fetch_assoc();
                              $product = $data_product["name"];
                              ?>
                            <tr>
                                <th><?= $no++ ?></th>
                                <th><?= $nama ?></th>
                                <th><?= $product ?></th>
                                <th><?= $value["pax"] ?></th>
                                <th><?= $book_date ?></th>
                                <th><?= rupiah($value["amount"]) ?></th>
                                <th><?= rupiah($value["total_amount"]) ?></th>
                                <th><?= $date_placed ?></th>
                                <th><?php if ($value["is_paid"] == 1) {
                                  echo '<img width="40" height="40" src="assets/paid.png">';
                                } else {
                                  echo '<img width="40" height="40" src="assets/unpaid.png">';
                                } ?></th>
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