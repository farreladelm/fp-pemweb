<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://kit.fontawesome.com/1cf4c7dee0.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="./detail_trip_A.css">
    <link rel="stylesheet" href="./style.css">
    <title>Detail Trip</title>
</head>
<body>

  <div class="user-input" id="user-input">
    <span class="exit" id="exit"><i class="fa-solid fa-xmark"></i></span>
    <h2>Order Now</h2>
    <div class="wrapper">
      <span class="minus">-</span>
      <span class="num">1</span>
      <span class="plus">+</span>  
      
    </div>
    <form action="pembayaran.php" method="post">
      <input type="hidden" name="product_id" value="<?= $_GET[
        "product_id"
      ] ?>"/>
      <input type="hidden" name="user_id" value="<?php echo $_SESSION[
        "id"
      ]; ?>"/>
      <input type="hidden" name="pax" id="pax" value="">
      <input type="date" name="book_date" id="date">
      <input type="submit" name="order" value="ORDER NOW" class="submit-btn">
  </form>
  </div>
  
  <?php if (isset($_GET["product_id"])) {
    include "koneksi.php";
    session_start();
    function rupiah($angka)
    {
      $hasil_rupiah = "Rp " . number_format($angka, 2, ",", ".");
      return $hasil_rupiah;
    }

    if (!isset($_SESSION["id"])) {
      echo "<script>alert('Silahkan login terlebih dahulu!'); window.location.href='index.php?action=login'</script>";
      exit();
    }

    $product_id = $_GET["product_id"];
    $quer = "SELECT * FROM product WHERE id = '$product_id'";
    $data = $koneksi->query($quer)->fetch_assoc();
  } else {
    header("Location:index.php");
  } ?>


  <div class="container">
    <div class="trip-title">
      <h1><?= $data["name"] ?></h1>
    </div>
    <div class="img-trip  "><img src="assets/<?= $data[
      "slug"
    ] ?>.jpg" class="img-trip" style="width:100%"></div>
      
    <div class="detail-trip">
      <p class="destination"><?= $data["description"] ?></p>
      <p class="price">
        <?= rupiah($data["price"]) . "/pax" ?>
      </p>
      <?= nl2br($data["long_description"]) ?>


  
    </div>

    
  </div>
  <script>
   const plus = document.querySelector(".plus"),
    minus = document.querySelector(".minus"),
    num = document.querySelector(".num");
    input = document.getElementById("pax");
    user_input = document.getElementById("user-input");
    exit = document.getElementById("exit");
    let a = 1;
    pax.value = a;
    plus.addEventListener("click", ()=>{
      a++;
      num.innerText = a;
      pax.value = a;
    });

    minus.addEventListener("click", ()=>{
      if(a > 1){
        a--;
        num.innerText = a;
        pax.value = a;
      }
    });

    user_input.addEventListener("click", ()=>{
      user_input.classList.add("order");
    })
    
    exit.addEventListener("click", (e)=>{
      e.stopPropagation();
      user_input.classList.remove("order");
    })

  </script>
</body>
</html>