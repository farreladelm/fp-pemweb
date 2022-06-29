<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="style.css" />
    <link
      rel="stylesheet"
      type="text/css"
      href="https://cdnjs.cloudflare.com/ajax/libs/Swiper/5.4.5/css/swiper.css"
    />
    <link rel="stylesheet" href="./slider.css" />
    <script
      src="https://kit.fontawesome.com/1cf4c7dee0.js"
      crossorigin="anonymous"
    ></script>
    <script src="./script.js"></script>
    <title>Final Project</title>
  </head>
  <body>
    <?php
    session_start();
    include "koneksi.php";

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
            header("Location: admin/index.php");
          } else {
            header("Location: index.php");
          }
        } else {
          session_destroy();
          echo "<script>alert('Akun belum aktif, silahkan cek email anda!'); window.location.href='index.php?action=login'</script>";
        }
      } else {
        echo "<script>alert('Email atau password salah!'); window.location.href='index.php?action=login'</script>";
      }
    }
    ?>
    <div class="center">
      <div class="form-container">
        <?php if (!isset($_SESSION["id"])) { ?>

        <div class="exit" id="exit"><i class="fa-solid fa-xmark"></i></div>
        <div class="text">Login Form</div>
        <form action="" method="post">
          <div class="data">
            <label for="email">Email</label>
            <input name="email" type="email" required/>
          </div>
          <div class="data">
            <label for="password">Password</label>
            <input type="password" name="password" required/>
          </div>
          <div class="forgot"><a href="#">Forgot Password</a></div>
          <input type="submit" name="login" value="LOGIN" class="submit-btn" />
        </form>
        <div class="register">
          Belum punya akun? <a href="./register.php" target="_blank">Daftar</a>
        </div>
        
        <?php } else {
          $user_id = $_SESSION["id"];
          $quer = "SELECT * FROM transaction WHERE user_id = '$user_id' ORDER BY id DESC LIMIT 1";
          $data_last = $koneksi->query($quer)->fetch_assoc();
          ?>
        <div class="exit" id="exit"><i class="fa-solid fa-xmark"></i></div>
        <div class="text">Hello, <?php echo $_SESSION["name"]; ?></div>
        <div class="forgot"><a href="check.php?id=<?php echo $data_last[
          "id"
        ]; ?>">My Order</a></div>
        <div class="forgot"><a href="logout.php">Logout</a></div>
        <?php } ?>

      </div>
    </div>

    <div id="header" class="logo fixed">
      <h1 class="animation">
        <span>Lo</span>
        <span>mbo</span>
        <span>k <span>&nbsp;</span> o</span>
        <span>n&nbsp;</span>
        <span>Tr</span>
        <span>av</span>
        <span>e</span>
        <span>l</span>
        <span>.</span>
      </h1>
      <div class="login" id="login">
        <i class="fa-solid fa-user-astronaut"></i>
      </div>
    </div>
    <section class="welcome-section">
      <div class="hero-bg darken"></div>
      <h1 class="animation">
        <span>Lo</span>
        <span>mbo</span>
        <span>k <span>&nbsp;</span> o</span>
        <span>n&nbsp;</span>
        <span>Tr</span>
        <span>av</span>
        <span>e</span>
        <span>l</span>
        <span>.</span>
      </h1>
      <a href="#nav-section" class="explore-btn">Explore</a>
      <h1 class="welcome-title">Travel With Us.</h1>
    </section>
    <section class="nav-section" id="nav-section">
      <div class="nav-list">
        <a href="#exp-page" class="explore" id="explore">
          <div class="exp-bg darken darken2"></div>
          <h2 class="exp-title">Explore</h2>
        </a>
        <a href="#book-page" class="book" id="book">
          <div class="book-bg darken darken2"></div>
          <h2 class="book-title">Book Now</h2>
        </a>
      </div>

      <div class="nav-text">
        <h2 class="nav-text-tittle">
          <span>What is</span>
          LokoTre
        </h2>
        <p>
          LokoTre (Lombok on Travel) is a travel agency based on Lombok Island,
          Nusa Tenggara Barat, Indonesia. We will serve the best services and
          give you an astonishing vacation spot to fill your wilderness.
        </p>
      </div>
    </section>

    <section id="exp-page" class="exp-page">
      <div class="exp__content-container">
        <section class="exp-content beach-sec" id="beach-sec">
          <div class="beach-bg darken darken2 blur"></div>
          <div class="blog-slider">
            <div class="blog-slider__wrp swiper-wrapper">
              <div class="blog-slider__item swiper-slide">
                <div class="blog-slider__img">
                  <img src="./assets/beach/pandanan beach 1.jpg" alt="" />
                </div>
                <div class="blog-slider__content">
                  <div class="blog-slider__title">Pandanan Beach</div>
                  <span class="blog-slider__code">North Lombok</span>
                  <div class="blog-slider__text">
                    Pandanan beach is a very beautiful destination for traveler
                    with its facilities and seafood culinary spread around the
                    seashore.
                  </div>
                </div>
              </div>
              <div class="blog-slider__item swiper-slide">
                <div class="blog-slider__img">
                  <img src="./assets/beach/gili trawangan 1.jpg" alt="" />
                </div>
                <div class="blog-slider__content">
                  <div class="blog-slider__title">Gili Trawangan</div>
                  <span class="blog-slider__code">Small Island</span>
                  <div class="blog-slider__text">
                    Gili Trawangan is a very beautiful small island located near
                    Lombok Island with a very beautiful scenery, you won't
                    regret visiting this place.
                  </div>
                </div>
              </div>
              <div class="blog-slider__item swiper-slide">
                <div class="blog-slider__img">
                  <img src="./assets/beach/senggigi beach 1.jpg" alt="" />
                </div>
                <div class="blog-slider__content">
                  <div class="blog-slider__title">Senggigi Beach</div>
                  <span class="blog-slider__code">West Lombok</span>
                  <div class="blog-slider__text">
                    Senggigi Beach is your best choice for admiring the gift
                    from earth. Senggigi has an extraordinary under water
                    scenery.
                  </div>
                </div>
              </div>
            </div>
            <div class="blog-slider__pagination"></div>
          </div>
        </section>

        <section class="exp-content mount-sec" id="mount-sec">
          <div class="mount-bg darken darken2 blur"></div>
          <div class="blog-slider">
            <div class="blog-slider__wrp swiper-wrapper">
              <div class="blog-slider__item swiper-slide">
                <div class="blog-slider__img">
                  <img src="./assets/mount/pergasingan 1.jpg" alt="" />
                </div>
                <div class="blog-slider__content">
                  <div class="blog-slider__title">Mount Pergasingan</div>
                  <span class="blog-slider__code">East Lombok</span>
                  <div class="blog-slider__text">
                    On Mount Pergasingan, you will feel like you are living
                    above the cloud.
                  </div>
                </div>
              </div>
              <div class="blog-slider__item swiper-slide">
                <div class="blog-slider__img">
                  <img src="./assets/mount/rinjani 1.jpg" alt="" />
                </div>
                <div class="blog-slider__content">
                  <div class="blog-slider__title">Mount Rinjani</div>
                  <span class="blog-slider__code">East Lombok</span>
                  <div class="blog-slider__text">
                    Mount Rinjani with 3.726 masl is the best choice for hike
                    enthusiast.
                  </div>
                </div>
              </div>
              <div class="blog-slider__item swiper-slide">
                <div class="blog-slider__img">
                  <img src="./assets/mount/sembalun 1.jpg" alt="" />
                </div>
                <div class="blog-slider__content">
                  <div class="blog-slider__title">Mount Sembalun</div>
                  <span class="blog-slider__code">East Lombok</span>
                  <div class="blog-slider__text">
                    Mount Sembalun located on Mount Rinjani slope with
                    staggering hills view. You can also do paragliding activity
                    in here.
                  </div>
                </div>
              </div>
            </div>
            <div class="blog-slider__pagination"></div>
          </div>
        </section>
        <section class="exp-content cult-sec" id="cult-sec">
          <div class="cult-bg darken darken2 blur"></div>
          <div class="blog-slider">
            <div class="blog-slider__wrp swiper-wrapper">
              <div class="blog-slider__item swiper-slide">
                <div class="blog-slider__img">
                  <img src="./assets/culture/desa banyumulek 1.jpg" alt="" />
                </div>
                <div class="blog-slider__content">
                  <div class="blog-slider__title">Banyumulek Village</div>
                  <span class="blog-slider__code">West Lombok</span>
                  <div class="blog-slider__text">
                    Banyumelek Village is the central business of earthenware.
                    The majority of this village is women.
                  </div>
                </div>
              </div>
              <div class="blog-slider__item swiper-slide">
                <div class="blog-slider__img">
                  <img src="./assets/culture/desa beleka 1.jpg" alt="" />
                </div>
                <div class="blog-slider__content">
                  <div class="blog-slider__title">Baleka Village</div>
                  <span class="blog-slider__code">Central Lombok</span>
                  <div class="blog-slider__text">
                    Baleka Village is the oldest and biggest business central in
                    Lombok Island. Lots of beautiful webbing craft in Baleka
                    Village
                  </div>
                </div>
              </div>
              <div class="blog-slider__item swiper-slide">
                <div class="blog-slider__img">
                  <img src="./assets/culture/desa sade 1.jpg" alt="" />
                </div>
                <div class="blog-slider__content">
                  <div class="blog-slider__title">Sade Village</div>
                  <span class="blog-slider__code">Central Lombok</span>
                  <div class="blog-slider__text">
                    You will got a special experience seeing the unique of Sade
                    Village and its ethnic group Sasak.
                  </div>
                </div>
              </div>
            </div>
            <div class="blog-slider__pagination"></div>
          </div>
        </section>
      </div>

      <div class="exp-page-nav">
        <ul class="exp-page-list">
          <li>
            <a href="#beach-sec"> Beaches </a>
          </li>
          <li>
            <a href="#mount-sec"> Mountains </a>
          </li>
          <li>
            <a href="#cult-sec"> Cultures </a>
          </li>
        </ul>
      </div>
    </section>
    <section class="book-page" id="book-page">
      <div class="book-page-container">
        <h2 class="book-title">Choose Your Trip</h2>
        <p class="title-desc">
          We Have 3 tipe of trip. Each trip has it own destination
        </p>
        <div class="book-card">
               <?php
               if (isset($_GET["action"]) && $_GET["action"] == "login") {
                 echo "<script>const login_form = document.querySelector('.center');
    login_form.classList.toggle('show');</script>";
               }
               $no = 1;
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
                <a href="detail.php?product_id=<?= $value[
                  "id"
                ] ?>" class="card trip-1">
                    <div class="darken darken2 trip-<?= $no ?>-bg"></div>
                    <div class="trip-content trip-content-<?= $no ?>">
                        <h3><?= $value["name"] ?></h3>
                        <p class="price"><?= rupiah($value["price"]) .
                          "/pax" ?></p>
                        <p class="detail"><?= $value["description"] ?></p>
                    </div>
                  </a>
<?php $no++;}
               ?>
            </div>
        </div>

    </section>
    <!-- <div class="chatbot">
      <iframe src="https://app.smojo.org/farreladelm/TudungSaji" title="Tudung Saji akan membantu kamu mencari makanan dari UMKM yang sangat beragam!" class="chatbot" ></iframe>
    </div> -->
    <script
      type="text/javascript"
      src="https://cdnjs.cloudflare.com/ajax/libs/Swiper/5.4.5/js/swiper.min.js"
    ></script>
    <script>
      var swiper = new Swiper(".blog-slider", {
        spaceBetween: 30,
        effect: "fade",
        loop: true,
        mousewheel: {
          invert: false,
        },
        // autoHeight: true,
        pagination: {
          el: ".blog-slider__pagination",
          clickable: true,
        },
      });
    </script>

    <script src="./script2.js"></script>
  </body>
</html>
