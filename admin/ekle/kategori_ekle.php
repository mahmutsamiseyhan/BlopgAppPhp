
<div class="content-wrapper">
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">Yeni Kategori Ekle</h3>
                        </div>
                        <form action="islem.php" method="post">
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="baslik">Kategori Başlık</label>
                                    <input type="text" name="baslik" class="form-control" placeholder="Kategori başlığını giriniz" required>
                                </div>
                                <div class="form-group">
                                    <label for="sira">Sıra</label>
                                    <input type="number" name="sira" class="form-control" required>
                                </div>
                                <div class="form-group">
                                    <label for="durum">Durum</label>
                                    <select name="durum" class="form-control">
                                        <option value="1">Aktif</option>
                                        <option value="0">Pasif</option>
                                    </select>
                                </div>
                            </div>
                            <div class="card-footer">
                                <button type="submit" name="kategoriekle" class="btn btn-primary">Ekle</button>
                            </div>
                            <input type="hidden" name="csrf_token" value="<?php echo csrfTokenOlustur(); ?>">
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
