<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=<device-width>, initial-scale=1.0">
    <title>Admin</title>
    <link rel="stylesheet" href="assets/style1.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@48,400,0,0" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
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
            <h3>Dashboard</h3>
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

            $quer = "SELECT COUNT(*) as total FROM user";
            $data = $koneksi->query($quer)->fetch_assoc();
            $user_total = $data["total"];
            $query_trx = "SELECT COUNT(*) as total FROM transaction";
            $data_trx = $koneksi->query($query_trx)->fetch_assoc();
            $trx_total = $data_trx["total"];
            $query_produk = "SELECT COUNT(*) as total FROM product";
            $data_produk = $koneksi->query($query_produk)->fetch_assoc();
            $produk_total = $data_produk["total"];
            ?>
        </header>
        
            <main>
            <h2 class="dash-title">Overview</h2>
            <div class="dash-cards">
            <div class="card-single">
                <div class="card-body">
                    <div>
                        
                        <h5> <span class="material-symbols-outlined">
                            person
                            
                            </span><br><br>User yang telah terdaftar : <?= $user_total ?></h5>
                    </div>
                </div>
                <div class="card-footer">
                    <a href="user.php">view all</a>
                </div>
            </div>
            <div class="card-single">
                <div class="card-body">
                    <div>
                        <h5><span class="material-symbols-outlined">
                            account_balance_wallet
                            </span><br><br>Data tranksaksi : <?= $trx_total ?></h5>
                    </div>
                </div>
                <div class="card-footer">
                    <a href="tranksaksi.php">view all</a>
                </div>
            </div>
            <div class="card-single">
                <div class="card-body">
                    <div>
                        <h5><span class="material-symbols-outlined">
                            shopping_cart_checkout
                            </span><br><br>Jumlah produk : <?= $produk_total ?></h5>
                    </div>
                </div>
                <div class="card-footer">
                    <a href="tranksaksi.php">view all</a>
                </div>
            </div>
            </div>
        </main>
    </div>
</body>
</html>