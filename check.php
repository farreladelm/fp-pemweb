<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./pembayaran.css">
    <title>Cek Pembayaran</title>
</head>

<?php
include "koneksi.php";
session_start();

function fetch($url)
{
  $curl = curl_init($url);
  curl_setopt($curl, CURLOPT_URL, $url);
  curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

  $resp = curl_exec($curl);
  curl_close($curl);
  return $resp;
}

if (isset($_GET["id"])) {
  $id = $_GET["id"];
  $query = "SELECT * FROM transaction WHERE id = '$id'";
  $data_trx = $koneksi->query($query)->fetch_assoc();

  function rupiah($angka)
  {
    $hasil_rupiah = "Rp " . number_format($angka, 2, ",", ".");
    return $hasil_rupiah;
  }

  if (!isset($_SESSION["id"])) {
    echo "<script>alert('Silahkan login terlebih dahulu!'); window.location.href='index.php?action=login'</script>";
    exit();
  }

  $product_id = $data_trx["product_id"];
  $quer = "SELECT * FROM product WHERE id = '$product_id'";
  $data_product = $koneksi->query($quer)->fetch_assoc();

  $explode_book = explode(" ", $data_trx["book_date"]);
  $date = strtotime($explode_book[0]);
  $book_date = date("jS F Y", $date);

  $dateOrder = new DateTime($data_trx["date_placed"]);
  $dateOrder->modify("+1 hour");
  $valid_until = $dateOrder->format("jS F Y H:i");

  $mutasi_bca = json_decode(
    fetch("http://localhost/fp-pemweb/klikbca/run.php")
  );
  $is_success = false;
  $info_success = "";

  foreach ($mutasi_bca as $value) {
    $parse_money = preg_replace("/[^0-9]/", "", $value->amount);
    $parse_money = substr($parse_money, 0, -2);
    if ($parse_money == $data_trx["total_amount"]) {
      $insert_paid = $koneksi->query(
        "UPDATE transaction SET is_paid = '1' WHERE id = '$id'"
      );

      if ($insert_paid) {
        $is_success = true;
        $info_success = $value->type . " " . $value->info;
      }
    }
  }
}
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
                    <span class="label">ID Order</span> <span class="value"><?= $data_trx[
                      "id"
                    ] ?></span>
                </div>
                <div class="line">
                    <span class="label">Name</span> <span class="value"><?= $_SESSION[
                      "name"
                    ] ?></span>
                </div>
                <div class="line">
                    <span class="label">Product</span> <span class="value"><?= $data_product[
                      "name"
                    ] ?></span>
                </div>
                <div class="line">
                    <span class="label">Price</span> <span class="value"><?= rupiah(
                      $data_product["price"]
                    ) . "/pax" ?></span>
                </div>
                <div class="line">
                    <span class="label">Pax</span> <span class="value"><?= $data_trx[
                      "pax"
                    ] ?></span>
                    
                </div>
                <div class="line">
                    <span class="label">Total</span> <span class="value"><?= rupiah(
                      $data_trx["amount"]
                    ) ?></span>
                </div>
                <div class="line">
                    <span class="label">Book Date</span> <span class="value"><?= $book_date ?></span>
                </div>
                <br>    
                <div class="line">
                    <span class="label">Amount to transfer</span> <span class="value"><b><?= rupiah(
                      $data_trx["total_amount"]
                    ) ?></b></span>
                </div>
                <br>
                <br>
                <div><img src="https://upload.wikimedia.org/wikipedia/commons/thumb/5/5c/Bank_Central_Asia.svg/2560px-Bank_Central_Asia.svg.png" style="width:30%; margin-left:10px;"></div>
                    <div class="rekening">BCA 8240519721 a.n. Honestyan</div>
                    <div class="logo-bca"></div>

                    <?php if (!$is_success) { ?>
                    <div class="status">
                        <div class="line">
                            <span>Status</span> <span>Waiting for Payment</span>
                        </div>
                        <div class="line">
                            <span>Valid Until</span> <span><?= $valid_until ?></span>
                        </div>
                    </div>
                    <?php } else { ?>
                    <div class="status">
                        <div class="line">
                            <span>Status</span> <span>Paid</span>
                        </div>
                        <div class="line">
                            <span>Detail</span> <span><?= $info_success ?></span>
                        </div>
                    </div>
                    <?php } ?>
            </div>
            <div class="payment-finish">
                <div class="img-bg"></div>
            <a href="index.php">Back to Home</a>
        </div>
        </div>
    </div>
</body>
</html>