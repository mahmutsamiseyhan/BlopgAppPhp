
<div class="content-wrapper">
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">Yeni Blog Ekle</h3>
                        </div>
                        <form action="islem.php" method="post" enctype="multipart/form-data">
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="resim">Blog Resim</label>
                                    <input type="file" name="resim" class="form-control" required>
                                </div>
                                <div class="form-group">
                                    <label for="baslik">Blog Başlık</label>
                                    <input type="text" name="baslik" class="form-control" placeholder="Blog başlığını giriniz" required>
                                </div>
                                <div class="form-group">
                                    <label for="icerik">Blog İçerik</label>
                                    <textarea name="aciklama" id="editor1" class="ckeditor" required></textarea>
                                </div>
                                <div class="form-group">
                                    <label for="sira">Sıra</label>
                                    <input type="number" name="sira" class="form-control" required>
                                </div>
                                <div class="form-group">
                                    <label for="kategori">Kategori</label>
                                    <select name="kategori" class="form-control" required>
                                        <?php
                                        $kategorisor = $baglanti->prepare("SELECT * FROM kategori WHERE kategori_durum = 1 ORDER BY kategori_sira ASC");
                                        $kategorisor->execute();
                                        while($kategoricek = $kategorisor->fetch(PDO::FETCH_ASSOC)) {
                                            echo '<option value="'.$kategoricek['kategori_id'].'">'.htmlspecialchars($kategoricek['kategori_baslik']).'</option>';
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="card-footer">
                                <button type="submit" name="blogekle" class="btn btn-primary">Ekle</button>
                            </div>
                            <input type="hidden" name="csrf_token" value="<?php echo csrfTokenOlustur(); ?>">
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
