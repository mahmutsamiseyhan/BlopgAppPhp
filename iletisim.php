<?php 
session_start();
require_once 'partials/header.php';
require_once 'admin/auth.php';
require_once 'function.php';

// CSRF token oluştur
$csrf_token = csrfTokenOlustur();
?>

<br><br><br>
<main id="main">
    <!-- Contact Section -->
    <section id="contact" class="contact">
        <div class="container" data-aos="fade-up">
            <div class="row mt-5">
                <div class="col-lg-4">
                    <div class="info">
                        <div class="address">
                            <i class="icofont-google-map"></i>
                            <h4>Adresimiz:</h4>
                            <p><?php echo guvenliCikti($ayarcek['ayar_adres']); ?></p>
                        </div>

                        <div class="email">
                            <i class="icofont-envelope"></i>
                            <h4>Email:</h4>
                            <p><?php echo guvenliCikti($ayarcek['ayar_email']); ?></p>
                        </div>

                        <div class="phone">
                            <i class="icofont-phone"></i>
                            <h4>Telefon numaramız:</h4>
                            <p><?php echo guvenliCikti($ayarcek['ayar_telefon']); ?></p>
                        </div>
                    </div>
                </div>

                <div class="col-lg-8 mt-5 mt-lg-0">
                    <?php
                    if (isset($_GET['durum'])) {
                        if ($_GET['durum'] == 'ok') {
                            echo '<div class="alert alert-success">Mesajınız başarıyla gönderildi.</div>';
                        } elseif ($_GET['durum'] == 'no') {
                            echo '<div class="alert alert-danger">Mesaj gönderilirken bir hata oluştu.</div>';
                        }
                    }
                    ?>
                    <form action="admin/islem.php" method="post" class="php-email-form">
                        <div class="form-row">
                            <div class="col-md-6 form-group">
                                <input type="text" name="name" class="form-control" id="name" placeholder="Adınız" required minlength="2" maxlength="50">
                            </div>
                            <div class="col-md-6 form-group">
                                <input type="email" class="form-control" name="email" id="email" placeholder="Email adresiniz" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <input type="text" class="form-control" name="subject" id="subject" placeholder="Konu" required minlength="4" maxlength="100">
                        </div>
                        <div class="form-group">
                            <textarea class="form-control" name="message" rows="5" placeholder="Mesajınız" required minlength="10" maxlength="1000"></textarea>
                        </div>
                        <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
                        <div class="text-center"><button type="submit" name="iletisim_gonder">Gönder</button></div>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <!-- Google Maps -->
    <div data-aos="fade-up">
        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3009.981173630286!2d28.974128666654074!3d41.02566782461609!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x14cab9e700764735%3A0xe01e13276edb60db!2zR2FsYXRhLCBCZXlvxJ9sdS_EsHN0YW5idWw!5e0!3m2!1str!2str!4v1609783464666!5m2!1str!2str" width="100%" height="450" frameborder="0" style="border:0;" allowfullscreen="" aria-hidden="false" tabindex="0"></iframe>
    </div>
</main>

<?php require_once 'partials/footer.php'; ?>