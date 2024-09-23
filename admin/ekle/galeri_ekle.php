
<div class="content-wrapper">
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">Yeni Galeri Resmi Ekle</h3>
                        </div>
                        <form action="islem.php" method="post" enctype="multipart/form-data">
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="resim">Galeri Resim</label>
                                    <input type="file" name="resim" class="form-control" required>
                                </div>
                                <div class="form-group">
                                    <label for="baslik">Başlık</label>
                                    <input type="text" name="baslik" class="form-control" placeholder="Resim başlığını giriniz">
                                </div>
                                <div class="form-group">
                                    <label for="sira">Sıra</label>
                                    <input type="number" name="sira" class="form-control" required>
                                </div>
                            </div>
                            <div class="card-footer">
                                <button type="submit" name="galeriekle" class="btn btn-primary">Ekle</button>
                            </div>
                            <input type="hidden" name="csrf_token" value="<?php echo csrfTokenOlustur(); ?>">
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
