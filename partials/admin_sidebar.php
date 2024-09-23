<aside class="main-sidebar sidebar-dark-primary elevation-4">

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user panel (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <img src="dist/img/user2-160x160.jpg" class="img-circle elevation-2" alt="User Image">
            </div>
            <div class="info">
                <a href="#" class="d-block"><?php echo htmlspecialchars($kullanicicek['kullanici_adsoyad']); ?></a>
            </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <li class="nav-item">
                    <a href="index.php" class="nav-link">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>Dashboard</p>
                    </a>
                </li>
                <?php if (adminYetkisi()): ?>
                <li class="nav-item">
                    <a href="slider.php" class="nav-link">
                        <i class="nav-icon fas fa-images"></i>
                        <p>Slider Yönetimi</p>
                    </a>
                </li>
                <?php endif; ?>
                <li class="nav-item">
                    <a href="blog.php" class="nav-link">
                        <i class="nav-icon fas fa-blog"></i>
                        <p>Blog Yönetimi</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="kategori.php" class="nav-link">
                        <i class="nav-icon fas fa-list"></i>
                        <p>Kategori Yönetimi</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="yorumlar.php" class="nav-link">
                        <i class="nav-icon fas fa-comments"></i>
                        <p>Yorum Yönetimi</p>
                    </a>
                </li>
                <?php if (adminYetkisi()): ?>
                <li class="nav-item">
                    <a href="ekip.php" class="nav-link">
                        <i class="nav-icon fas fa-users"></i>
                        <p>Ekibimiz</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="hakkimizda.php" class="nav-link">
                        <i class="nav-icon fas fa-info-circle"></i>
                        <p>Hakkımızda</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="kullanicilar.php" class="nav-link">
                        <i class="nav-icon fas fa-user-cog"></i>
                        <p>Kullanıcı Yönetimi</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="iletisim.php" class="nav-link">
                        <i class="nav-icon fas fa-user-cog"></i>
                        <p>İletişim Yönetimi</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="ayarlar.php" class="nav-link">
                        <i class="nav-icon fas fa-cogs"></i>
                        <p>Site Ayarları</p>
                    </a>
                </li>
                <?php endif; ?>
                <li class="nav-item">
                    <a href="cikis.php" class="nav-link">
                        <i class="nav-icon fas fa-sign-out-alt"></i>
                        <p>Çıkış Yap</p>
                    </a>
                </li>
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>