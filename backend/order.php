<style>
#header {
  background:#aaa;
  height:400px;
  margin-left:6px;
  margin-right:6px;
  min-height:100%;
  width:612px;
}

</style>

<?php
session_start();
include "koneksi.php";

function rupiah($angka)
{
  $hasil_rupiah = "Rp " . number_format($angka, 2, ",", ".");
  return $hasil_rupiah;
}

function fetch($url)
{
  $curl = curl_init($url);
  curl_setopt($curl, CURLOPT_URL, $url);
  curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

  $resp = curl_exec($curl);
  curl_close($curl);
  return $resp;
}

if (!isset($_SESSION["id"])) {
  header("Location:login.php");
} else {
  if (isset($_GET["id"])) {
    $user_id = $_SESSION["id"];

    $quer = "SELECT * FROM transaction WHERE user_id = '$user_id' ORDER BY id DESC LIMIT 1";
    $order_data = $koneksi->query($quer)->fetch_assoc();
    $order_id = $order_data["id"];
    $product_id = $order_data["product_id"];
    $quer = "SELECT * FROM product WHERE id = '$product_id'";
    $data = $koneksi->query($quer)->fetch_assoc();

    $explode_book = explode(" ", $order_data["book_date"]);
    $date = strtotime($explode_book[0]);
    $book_date = date("jS F Y", $date);

    $dateOrder = new DateTime($order_data["date_placed"]);
    $dateOrder->modify("+1 hour");
    $valid_until = $dateOrder->format("jS F Y H:i");

    $mutasi_bca = json_decode(
      fetch("http://localhost/fp-pemweb/backend/klikbca/run.php")
    );
    $is_success = false;
    $info_success = "";

    foreach ($mutasi_bca as $value) {
      $parse_money = preg_replace("/[^0-9]/", "", $value->amount);
      $parse_money = substr($parse_money, 0, -2);
      if ($parse_money == $order_data["total_amount"]) {
        $insert_paid = $koneksi->query(
          "UPDATE transaction SET is_paid = '1' WHERE id = '$order_id'"
        );

        if ($insert_paid) {
          $is_success = true;
          $info_success = $value->type . " " . $value->info;
        }
      }
    }

    echo '<div id="header">';
    echo "My Order<br>";
    echo "=============================<br>";
    echo "ID Order: " . $order_data["id"] . "<br>";
    echo "Name: " . $_SESSION["name"] . "<br>";
    echo "Product: " . $data["name"] . "<br>";
    echo "Price: " . rupiah($data["price"]) . "/pax<br>";
    echo "Pax: " . $order_data["pax"] . "<br>";
    echo "Total: " . rupiah($order_data["amount"]) . "<br>";
    echo "Book Date: " . $book_date . "<br><br>";
    echo "Amount to Transfer: " . rupiah($order_data["total_amount"]) . "<br>";
    echo "BCA 8240519721 a.n. Honestyan<br>";

    echo '<img src="https://upload.wikimedia.org/wikipedia/commons/thumb/5/5c/Bank_Central_Asia.svg/2560px-Bank_Central_Asia.svg.png" style="width:30%"><br><br>';

    if ($is_success) {
      echo "<b>Status: Paid<br>";
      echo "Info: " . $info_success . "<br>";
    } else {
      echo "<b>Status: Waiting for payment<br>";
      echo "Valid until: " . $valid_until . "<br>";
    }
    echo "</div>";
  }
}


?>
