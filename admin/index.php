<?php 
require_once '../partials/admin_header.php';
require_once '../partials/admin_sidebar.php';
require_once 'auth.php';

girisKontrol();
if(adminYetkisi()){
    require_once 'admin_panel.php';
}

// Moderatör ise kendi istatistiklerini gösterelim
if (moderatorYetkisi()) {
    require_once 'moderator_panel.php';
}
?>

<?php require_once '../partials/admin_footer.php'; ?>