<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./pembayaran.css">
    <title>Pembayaran</title>
</head>
<?php
session_start();
include "koneksi.php";

function rupiah($angka)
{
  $hasil_rupiah = "Rp " . number_format($angka, 2, ",", ".");
  return $hasil_rupiah;
}

if (!isset($_SESSION["id"])) {
  echo "<script>alert('Silahkan login terlebih dahulu!'); window.location.href='index.php?action=login'</script>";
  exit();
}

if (isset($_POST["order"])) {
  $product_id = $_POST["product_id"];
  $quer = "SELECT * FROM product WHERE id = '$product_id'";
  $data = $koneksi->query($quer)->fetch_assoc();
  $user_id = $_SESSION["id"];
  $pax = $_POST["pax"];
  $total = $data["price"] * $pax;
  $book_date = $_POST["book_date"];
  $total_unique = $total + rand(101, 999);
  $date_placed = date("Y-m-d H:i:s");
  $query = "INSERT INTO transaction VALUES ('', '$user_id', '$product_id', '$pax', '$total', '$total_unique', '$book_date', '$date_placed', '0')";
  $order = $koneksi->query($query);
  if ($order) {

    $quer = "SELECT * FROM transaction WHERE user_id = '$user_id' ORDER BY id DESC LIMIT 1";
    $order_data = $koneksi->query($quer)->fetch_assoc();

    $explode_book = explode(" ", $order_data["book_date"]);
    $date = strtotime($explode_book[0]);
    $book_date = date("jS F Y", $date);

    $dateOrder = new DateTime($order_data["date_placed"]);
    $dateOrder->modify("+1 hour");
    $valid_until = $dateOrder->format("jS F Y H:i");
    ?>

<body>
    <div class="container">
        <div class="img-bg hero"></div>

        <div class="payment">
            <div class="payment-title">
                    <div class="img-bg"></div>
                <h1>My Order</h1>
            </div>
            <div class="payment-detail">
                <div class="img-bg"></div>
                <div class="line">
                    <span class="label">ID Order</span> <span class="value"><?= $order_data[
                      "id"
                    ] ?></span>
                </div>
                <div class="line">
                    <span class="label">Name</span> <span class="value"><?= $_SESSION[
                      "name"
                    ] ?></span>
                </div>
                <div class="line">
                    <span class="label">Product</span> <span class="value"><?= $data[
                      "name"
                    ] ?></span>
                </div>
                <div class="line">
                    <span class="label">Price</span> <span class="value"><?= rupiah(
                      $data["price"]
                    ) . "/pax" ?></span>
                </div>
                <div class="line">
                    <span class="label">Pax</span> <span class="value"><?= $order_data[
                      "pax"
                    ] ?></span>
                    
                </div>
                <div class="line">
                    <span class="label">Total</span> <span class="value"><?= rupiah(
                      $order_data["amount"]
                    ) ?></span>
                </div>
                <div class="line">
                    <span class="label">Book Date</span> <span class="value"><?= $book_date ?></span>
                </div>
                <div class="line">
                    <span class="label">Amount to transfer</span> <span class="value"><?= rupiah(
                      $order_data["total_amount"]
                    ) ?></span>
                </div>
                <br>
                <br>
                <div><img src="https://upload.wikimedia.org/wikipedia/commons/thumb/5/5c/Bank_Central_Asia.svg/2560px-Bank_Central_Asia.svg.png" style="width:30%; margin-left:10px;"></div>
                    <div class="rekening">BCA 8240519721 a.n. Honestyan</div>
                    <div class="logo-bca"></div>
                    <div class="status">
                        <div class="line">
                            <span>Status</span> <span>Waiting for Payment</span>
                        </div>
                        <div class="line">
                            <span>Valid Until</span> <span><?= $valid_until ?></span>
                        </div>
                    </div>
            </div>
            <div class="payment-finish">
                <div class="img-bg"></div>
            <a href="check.php?id=<?= $order_data[
              "id"
            ] ?>">I have finish the transaction</a>
        </div>
        </div>
    </div>
</body>

<?php
  } else {
    echo "<script>alert('Gagal melakukan pemesanan!'); window.location.href='index.php'</script>";
  }
} else {
  echo "<script>window.location.href='index.php'</script>";
  exit();
}
?>
</html>