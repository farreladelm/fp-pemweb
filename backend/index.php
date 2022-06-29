<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1">
<style>
.card {
  box-shadow: 0 4px 8px 0 rgba(0,0,0,0.2);
  transition: 0.3s;
  width: 80%;
  border-radius: 5px;
}

.card:hover {
  box-shadow: 0 8px 16px 0 rgba(0,0,0,0.2);
}

img {
  border-radius: 5px 5px 0 0;
}

.container {
  padding: 2px 16px;
}

.column {
  float: left;
  width: 25%;
}

.row:after {
  content: "";
  display: table;
  clear: both;
}

.button {
  border: none;
  color: white;
  padding: 15px 32px;
  text-align: center;
  text-decoration: none;
  display: inline-block;
  font-size: 16px;
  margin: 4px 2px;
  cursor: pointer;
  background-color: blue;
}

</style>
</head>
<body>

<div class="row">

<?php
include "koneksi.php";

function rupiah($angka)
{
  $hasil_rupiah = "Rp " . number_format($angka, 2, ",", ".");
  return $hasil_rupiah;
}

$query = "SELECT * FROM product";
$result = $koneksi->query($query);

while ($data = $result->fetch_array()) {
  $rows[] = $data;
}

foreach ($rows as $value) { ?>
  <div class="column">
  <div class="card">
    <img src="assets/<?= $value["slug"] ?>.jpg" alt="Avatar" style="width:100%">
    <div class="container">
      <h2><b><?= $value["name"] ?></b></h2> 
      <h4><b><?= rupiah($value["price"]) . "/pax" ?></b></h4> 
      <p><?= $value["description"] ?></p> 
      <a href="detail.php?product_id=<?= $value[
        "id"
      ] ?>" class="button">Detail</a>
    </div>
  </div>
</div>
<?php }
?>
  
</div>


</body>
</html> 
