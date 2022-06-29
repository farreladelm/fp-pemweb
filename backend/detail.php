<!DOCTYPE html>
<html>
<title>Detail</title>
<meta name="viewport" content="width=device-width, initial-scale=1">

<head>
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script>
        	$(document).ready(function() {
			$('.minus').click(function () {
				var $input = $(this).parent().find('#pax');
				var count = parseInt($input.val()) - 1;
				count = count < 1 ? 1 : count;
				$input.val(count);
				$input.change();
				return false;
			});
			$('.plus').click(function () {
				var $input = $(this).parent().find('#pax');
				$input.val(parseInt($input.val()) + 1);
				$input.change();
				return false;
			});
		});


</script>
</head>
<style>
   	span {cursor:pointer; }
		.number{
			margin:100px;
		}
		.minus, .plus{
			width:20px;
			height:20px;
			background:#f2f2f2;
			border-radius:4px;
			padding:8px 5px 8px 5px;
			border:1px solid #ddd;
      display: inline-block;
      vertical-align: middle;
      text-align: center;
		}
		input{
			height:34px;
      width: 100px;
      text-align: center;
      font-size: 26px;
			border:1px solid #ddd;
			border-radius:4px;
      display: inline-block;
      vertical-align: middle;
}
#header {
  background:#aaa;
  height:400px;
  margin-left:6px;
  margin-right:6px;
  min-height:100%;
  width:612px;
}

</style>
<body>


<?php if (isset($_GET["product_id"])) {
  session_start();
  include "koneksi.php";
  $product_id = $_GET["product_id"];

  $quer = "SELECT * FROM product WHERE id = '$product_id'";
  $data = $koneksi->query($quer)->fetch_assoc();

  function rupiah($angka)
  {
    $hasil_rupiah = "Rp " . number_format($angka, 2, ",", ".");
    return $hasil_rupiah;
  }

  if (isset($_POST["order"])) {
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

      echo '<div id="header">';
      echo "SUKSES<br>";
      echo "ID Order: " . $order_data["id"] . "<br>";
      echo "Name: " . $_SESSION["name"] . "<br>";
      echo "Product: " . $data["name"] . "<br>";
      echo "Price: " . rupiah($data["price"]) . "/pax<br>";
      echo "Pax: " . $order_data["pax"] . "<br>";
      echo "Total: " . rupiah($order_data["amount"]) . "<br>";
      echo "Book Date: " . $book_date . "<br><br>";
      echo "Amount to Transfer: " .
        rupiah($order_data["total_amount"]) .
        "<br>";
      echo "BCA 8240519721 a.n. Honestyan<br>";

      echo '<img src="https://upload.wikimedia.org/wikipedia/commons/thumb/5/5c/Bank_Central_Asia.svg/2560px-Bank_Central_Asia.svg.png" style="width:30%"><br><br>';
      echo "Status: Waiting for payment<br>";
      echo "Valid until: " . $valid_until . "<br>";
      echo '<a href="order.php?id=' .
        $order_data["id"] .
        '">I have transferred</a>';
      echo "</div>";
    }
  }
} else {
  header("Location:index.php");
} ?>


<div class="w3-container">
  <h3>Detail</h3>
  <p><?= $data["description"] ?></p>

  <div class="w3-card-4" style="width:50%;">
    <header class="w3-container w3-blue">
      <h2><?= $data["name"] ?></h2>
    </header>

    <div class="w3-container">
    <br>
	<img src="assets/<?= $data["slug"] ?>.jpg" style="width:100%">
      <p><?= nl2br($data["long_description"]) ?></p>
    </div>

    <footer class="w3-container w3-blue">
      <h5><?= rupiah($data["price"]) . "/pax" ?></h5>
      
    </footer>
  </div>
  <br>
  
<?php if (!isset($_SESSION["id"])) {
  echo "login untuk memesan <br>";
  echo '<a href="login.php" class="button">Login</a>';
} else {
   ?>

<form action="" method="post">
<input type="hidden" name="product_id" value="<?= $_GET["product_id"] ?>"/>
<input type="hidden" name="user_id" value="<?php echo $_SESSION["id"]; ?>"/>
    <div class="number">
	<span class="minus">-</span>
	<input type="text" id="pax" name="pax" value="1"/>
	<span class="plus">+</span>
    <input type="date" id="book_date" name="book_date">
<input type="submit" name="order" value="ORDER NOW">
</div>
</form>


<?php
} ?>



</div>
</body>
</html>
