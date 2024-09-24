<footer style="background-color:black" id="footer">

    <div style="background-color:black" class="footer-top">
        <div class="container">
            <div class="row">

                <div class="col-lg-3 col-md-6 footer-contact">
                    <h3 style="color:white">Yazılım Yolcusu</h3>
                    <p style="color:white">
                        Adresimiz:<?php echo $ayarcek['ayar_adres'] ?><br>
                        <strong>Telefon:</strong> <?php echo $ayarcek['ayar_telefon'] ?><br>
                        <strong>Email:</strong> <?php echo $ayarcek['ayar_email'] ?><br>
                    </p>
                </div>

                <div class="col-lg-2 col-md-6 footer-links">
                    <h4 style="color:white">Sayfalarımız</h4>
                    <ul>
                        <li><i class="bx bx-chevron-right"></i> <a style="color:white" href="index">Anasayfa</a></li>
                        <li><i class="bx bx-chevron-right"></i> <a style="color:white" href="hakkimizda">Hakkımzıda</a>
                        </li>
                        <li><i class="bx bx-chevron-right"></i> <a style="color:white" href="ekip">Ekibimiz</a></li>
                        <li><i class="bx bx-chevron-right"></i> <a style="color:white" href="blog">Blog</a></li>
                        <!-- <li><i class="bx bx-chevron-right"></i> <a style="color:white" href="iletisim">İletişim</a></li> -->
                    </ul>
                </div>

                <div class="col-lg-3 col-md-6 footer-links">
                    <h4 style="color:white">Hizmetlerimiz</h4>
                    <ul>
                        <?php 

$kategorisor=$baglanti->prepare("SELECT * FROM kategori   order by kategori_sira ASC");
$kategorisor->execute(array());
while ($kategoricek=$kategorisor->fetch(PDO::FETCH_ASSOC)) {
                 ?>
                        <li style="color:white"><i class="bx bx-chevron-right"></i><a style="color:white"
                                href="kategori-detay-<?=seo($kategoricek['kategori_baslik']).'-'.$kategoricek['kategori_id'] ?>"><?php echo $kategoricek['kategori_baslik'] ?></a>
                        </li>
                        <?php } ?>
                    </ul>
                </div>

                <div class="col-lg-4 col-md-6 footer-newsletter">
                    <!-- <h4 style="color:white">E-bültene abone olun</h4>
                    <p style="color:white">E-bültene abone olarak anında içeriklerden haberdar olabilirsiniz.</p>
                    <form action="admin/islem.php" method="post">
                        <input placeholder="Lütfen email giriniz." type="email" name="email">
                        <input name="abone" type="submit" value="Abone Ol">
                    </form> -->
                </div>

            </div>
        </div>
    </div>
<div>
<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3047.967581625265!2d29.057522!3d40.187534899999996!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x14ca3de2cae40b37%3A0x9bf6a2c5985be498!2sTophane%20Saat%20Kulesi!5e0!3m2!1str!2str!4v1727174994858!5m2!1str!2str" width="100%" height="450" frameborder="0" style="border:0;" allowfullscreen="" aria-hidden="false"
    tabindex="0"></iframe>
</div>
    <div class="container d-md-flex py-4">

        <div class="mr-md-auto text-center text-md-left">
            <div style="color: white" class="copyright">
                &copy; Tüm Hakları Saklıdır<strong><span> </span></strong> <?php echo date("Y") ?>
            </div>
            <div style="color:white" class="credits">
            </div>
        </div>
        <!-- <div class="social-links text-center text-md-right pt-3 pt-md-0">
            <a href="#" class="twitter"><i class="bx bxl-twitter"></i></a>
            <a href="#" class="facebook"><i class="bx bxl-facebook"></i></a>
            <a href="#" class="instagram"><i class="bx bxl-instagram"></i></a>
            <a href="#" class="google-plus"><i class="bx bxl-skype"></i></a>
            <a href="#" class="linkedin"><i class="bx bxl-linkedin"></i></a>
        </div> -->
    </div>
</footer><!-- End Footer -->

<a href="#" class="back-to-top"><i class="bx bx-up-arrow-alt"></i></a>
<div id="preloader"></div>

<!-- Vendor JS Files -->
<script src="assets/vendor/jquery/jquery.min.js"></script>
<script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="assets/vendor/jquery.easing/jquery.easing.min.js"></script>
<script src="assets/vendor/php-email-form/validate.js"></script>
<script src="assets/vendor/waypoints/jquery.waypoints.min.js"></script>
<script src="assets/vendor/counterup/counterup.min.js"></script>
<script src="assets/vendor/owl.carousel/owl.carousel.min.js"></script>
<script src="assets/vendor/aos/aos.js"></script>

<!-- Template Main JS File -->
<script src="assets/js/main.js"></script>

</body>

</html>