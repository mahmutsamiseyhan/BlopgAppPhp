<?php
session_start();
require_once 'baglanti.php';
require_once 'auth.php';

// CSRF token kontrolü
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!isset($_POST['csrf_token']) || !csrfTokenKontrol($_POST['csrf_token'])) {
        die("CSRF token doğrulaması başarısız!");
    }
}

// Dosya yükleme fonksiyonu
function dosyaYukle($dosya, $hedefDizin) {
    $hedefYol = $hedefDizin . basename($dosya["name"]);
    $dosyaTipi = strtolower(pathinfo($hedefYol, PATHINFO_EXTENSION));
    
    // Sadece belirli dosya türlerine izin ver
    $izinliTurler = array("jpg", "jpeg", "png", "gif");
    if (!in_array($dosyaTipi, $izinliTurler)) {
        return false;
    }
    
    // Dosya adını benzersiz yap
    $yeniDosyaAdi = uniqid() . '.' . $dosyaTipi;
    $hedefYol = $hedefDizin . $yeniDosyaAdi;
    
    if (move_uploaded_file($dosya["tmp_name"], $hedefYol)) {
        return $yeniDosyaAdi;
    }
    return false;
}

// Eski dosyayı silme fonksiyonu
function eskiDosyaSil($dosyaYolu) {
    if (file_exists($dosyaYolu)) {
        unlink($dosyaYolu);
    }
}

// Kayıt işlemi
if (isset($_POST['kayitol'])) {
    $adsoyad = filter_var($_POST['adsoyad'], FILTER_SANITIZE_STRING);
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $sifre = $_POST['sifre'];
    $sifre_tekrar = $_POST['sifre_tekrar'];

    // Şifre kontrolü
    if ($sifre !== $sifre_tekrar) {
        header("Location: kayit.php?durum=sifreler_eslesmiyor");
        exit;
    }

    // E-posta kontrolü
    $emailKontrol = $baglanti->prepare("SELECT * FROM kullanici WHERE kullanici_email = :email");
    $emailKontrol->execute(['email' => $email]);
    if ($emailKontrol->rowCount() > 0) {
        header("Location: kayit.php?durum=email_mevcut");
        exit;
    }

    // Şifre karmaşıklık kontrolü
    if (strlen($sifre) < 8 || !preg_match("#[0-9]+#", $sifre) || !preg_match("#[a-zA-Z]+#", $sifre)) {
        header("Location: kayit.php?durum=zayif_sifre");
        exit;
    }

    // Şifreyi hashle
    $sifre_hash = password_hash($sifre, PASSWORD_DEFAULT);

    // Kullanıcıyı veritabanına ekle
    $kaydet = $baglanti->prepare("INSERT INTO kullanici SET 
        kullanici_adsoyad = :adsoyad,
        kullanici_email = :email,
        kullanici_sifre = :sifre,
        kullanici_rol = :rol
    ");

    $insert = $kaydet->execute([
        'adsoyad' => $adsoyad,
        'email' => $email,
        'sifre' => $sifre_hash,
        'rol' => 'moderator' // Varsayılan olarak normal moderator rolü
    ]);

    if ($insert) {
        header("Location: giris.php?durum=kayit_basarili");
    } else {
        header("Location: kayit.php?durum=hata");
    }
    exit;
}

// Giriş işlemi
if (isset($_POST['girisyap'])) {
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $sifre = $_POST['sifre'];

    $kullanicisor = $baglanti->prepare("SELECT * FROM kullanici WHERE kullanici_email = :email");
    $kullanicisor->execute(['email' => $email]);
    $kullanicicek = $kullanicisor->fetch(PDO::FETCH_ASSOC);

    if ($kullanicicek && password_verify($sifre, $kullanicicek['kullanici_sifre'])) {
        $_SESSION['kullanici_email'] = $kullanicicek['kullanici_email'];
        $_SESSION['kullanici_id'] = $kullanicicek['kullanici_id'];
        $_SESSION['kullanici_rol'] = $kullanicicek['kullanici_rol'];
        header("Location:index.php");
    } else {
        header("Location:giris.php?durum=no");
    }
}

// Ekip üyesi ekleme işlemi
if (isset($_POST['ekipekle'])) {
    $isim = filter_var($_POST['isim'], FILTER_SANITIZE_STRING);
    $aciklama = filter_var($_POST['aciklama'], FILTER_SANITIZE_STRING);
    $twitter = filter_var($_POST['twitter'], FILTER_SANITIZE_URL);
    $instagram = filter_var($_POST['instagram'], FILTER_SANITIZE_URL);
    $linkedin = filter_var($_POST['linkedin'], FILTER_SANITIZE_URL);

    $resim = $_FILES['resim'];
    $resimAdi = dosyaYukle($resim, "resimler/ekip/");

    if ($resimAdi) {
        $kaydet = $baglanti->prepare("INSERT INTO ekip SET 
            ekip_isim = :isim,
            ekip_aciklama = :aciklama,
            ekip_twitter = :twitter,
            ekip_instagram = :instagram,
            ekip_linkedin = :linkedin,
            ekip_resim = :resim
        ");

        $insert = $kaydet->execute([
            'isim' => $isim,
            'aciklama' => $aciklama,
            'twitter' => $twitter,
            'instagram' => $instagram,
            'linkedin' => $linkedin,
            'resim' => $resimAdi
        ]);

        if ($insert) {
            header("Location: ekip.php?durum=ok");
        } else {
            header("Location: ekip.php?durum=no");
        }
    } else {
        header("Location: ekip.php?durum=no");
    }
    exit;
}

// Ekip üyesi düzenleme işlemi
if (isset($_POST['ekipduzenle'])) {
    $id = filter_var($_POST['id'], FILTER_SANITIZE_NUMBER_INT);
    $isim = filter_var($_POST['isim'], FILTER_SANITIZE_STRING);
    $aciklama = filter_var($_POST['aciklama'], FILTER_SANITIZE_STRING);
    $twitter = filter_var($_POST['twitter'], FILTER_SANITIZE_URL);
    $instagram = filter_var($_POST['instagram'], FILTER_SANITIZE_URL);
    $linkedin = filter_var($_POST['linkedin'], FILTER_SANITIZE_URL);

    if ($_FILES['resim']['size'] > 0) {
        $resim = $_FILES['resim'];
        $resimAdi = dosyaYukle($resim, "resimler/ekip/");
        if ($resimAdi) {
            eskiDosyaSil("resimler/ekip/" . $_POST['eskiresim']);
        } else {
            $resimAdi = $_POST['eskiresim'];
        }
    } else {
        $resimAdi = $_POST['eskiresim'];
    }

    $guncelle = $baglanti->prepare("UPDATE ekip SET 
        ekip_isim = :isim,
        ekip_aciklama = :aciklama,
        ekip_twitter = :twitter,
        ekip_instagram = :instagram,
        ekip_linkedin = :linkedin,
        ekip_resim = :resim
    WHERE ekip_id = :id");

    $update = $guncelle->execute([
        'isim' => $isim,
        'aciklama' => $aciklama,
        'twitter' => $twitter,
        'instagram' => $instagram,
        'linkedin' => $linkedin,
        'resim' => $resimAdi,
        'id' => $id
    ]);

    if ($update) {
        header("Location: ekip.php?durum=ok");
    } else {
        header("Location: ekip.php?durum=no");
    }
    exit;
}

// Ekip üyesi silme işlemi
if (isset($_POST['ekipsil'])) {
    $id = filter_var($_POST['id'], FILTER_SANITIZE_NUMBER_INT);
    $eskiresim = $_POST['eskiresim'];

    eskiDosyaSil("resimler/ekip/" . $eskiresim);

    $sil = $baglanti->prepare("DELETE FROM ekip WHERE ekip_id = :id");
    $delete = $sil->execute(['id' => $id]);

    if ($delete) {
        header("Location: ekip.php?durum=ok");
    } else {
        header("Location: ekip.php?durum=no");
    }
    exit;
}

// Kullanıcı ekleme işlemi
if (isset($_POST['kullaniciekle'])) {
    $adsoyad = filter_var($_POST['adsoyad'], FILTER_SANITIZE_STRING);
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $sifre = password_hash($_POST['sifre'], PASSWORD_DEFAULT);
    $rol = filter_var($_POST['rol'], FILTER_SANITIZE_STRING);

    $kaydet = $baglanti->prepare("INSERT INTO kullanici SET 
        kullanici_adsoyad = :adsoyad,
        kullanici_email = :email,
        kullanici_sifre = :sifre,
        kullanici_rol = :rol
    ");

    $insert = $kaydet->execute([
        'adsoyad' => $adsoyad,
        'email' => $email,
        'sifre' => $sifre,
        'rol' => $rol
    ]);

    if ($insert) {
        header("Location: kullanicilar.php?durum=ok");
    } else {
        header("Location: kullanicilar.php?durum=no");
    }
    exit;
}

// Kullanıcı düzenleme işlemi
if (isset($_POST['kullaniciduzenle'])) {
    $id = filter_var($_POST['id'], FILTER_SANITIZE_NUMBER_INT);
    $adsoyad = filter_var($_POST['adsoyad'], FILTER_SANITIZE_STRING);
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $rol = filter_var($_POST['rol'], FILTER_SANITIZE_STRING);

    $guncelle = $baglanti->prepare("UPDATE kullanici SET 
        kullanici_adsoyad = :adsoyad,
        kullanici_email = :email,
        kullanici_rol = :rol
    WHERE kullanici_id = :id");

    $update = $guncelle->execute([
        'adsoyad' => $adsoyad,
        'email' => $email,
        'rol' => $rol,
        'id' => $id
    ]);

    if ($update) {
        header("Location: kullanicilar.php?durum=ok");
    } else {
        header("Location: kullanicilar.php?durum=no");
    }
    exit;
}

// Kullanıcı silme işlemi
if (isset($_POST['kullanicisil'])) {
    girisKontrol();
    
    $id = filter_var($_POST['id'], FILTER_SANITIZE_NUMBER_INT);

    $sil = $baglanti->prepare("DELETE FROM kullanici WHERE kullanici_id = :id");
    $delete = $sil->execute(['id' => $id]);

    if ($delete) {
        header("Location: kullanicilar.php?durum=ok");
    } else {
        header("Location: kullanicilar.php?durum=no");
    }
    exit;
}

// Blog ekleme işlemi
if (isset($_POST['blogekle'])) {
    girisKontrol();
    
    if (!yetkiKontrol('blog')) {
        header("Location: yetkisiz.php");
        exit;
    }

    $baslik = filter_var($_POST['baslik'], FILTER_SANITIZE_STRING);
    $aciklama = $_POST['aciklama']; // CKEditor kullanıldığı için filtreleme yapmıyoruz
    $sira = filter_var($_POST['sira'], FILTER_SANITIZE_NUMBER_INT);
    $anahtarkelime = filter_var($_POST['anahtarkelime'], FILTER_SANITIZE_STRING);
    $yazar_id = $_SESSION['kullanici_id'];

    $resim = $_FILES['resim'];
    $resimAdi = dosyaYukle($resim, "resimler/blog/");

    if ($resimAdi) {
        $kaydet = $baglanti->prepare("INSERT INTO blog SET 
            blog_baslik = :baslik,
            blog_aciklama = :aciklama,
            blog_resim = :resim,
            blog_sira = :sira,
            blog_anahtarkelime = :anahtarkelime,
            kullanici_id = :yazar_id
        ");

        $insert = $kaydet->execute([
            'baslik' => $baslik,
            'aciklama' => $aciklama,
            'resim' => $resimAdi,
            'sira' => $sira,
            'anahtarkelime' => $anahtarkelime,
            'yazar_id' => $yazar_id
        ]);

        if ($insert) {
            header("Location: blog.php?durum=ok");
        } else {
            header("Location: blog.php?durum=no");
        }
    } else {
        header("Location: blog.php?durum=no");
    }
    exit;
}

// Blog ekleme işlemi
if (isset($_POST['blogekle'])) {
    girisKontrol();
    
    if (!yetkiKontrol('blog')) {
        header("Location: yetkisiz.php");
        exit;
    }

    $baslik = filter_var($_POST['baslik'], FILTER_SANITIZE_STRING);
    $aciklama = $_POST['aciklama']; // CKEditor kullanıldığı için filtreleme yapmıyoruz
    $sira = filter_var($_POST['sira'], FILTER_SANITIZE_NUMBER_INT);
    $anahtarkelime = filter_var($_POST['anahtarkelime'], FILTER_SANITIZE_STRING);
    $yazar_id = $_SESSION['kullanici_id'];

    $resim = $_FILES['resim'];
    $resimAdi = dosyaYukle($resim, "resimler/blog/");

    if ($resimAdi) {
        $kaydet = $baglanti->prepare("INSERT INTO blog SET 
            blog_baslik = :baslik,
            blog_aciklama = :aciklama,
            blog_resim = :resim,
            blog_sira = :sira,
            blog_anahtarkelime = :anahtarkelime,
            kullanici_id = :yazar_id
        ");

        $insert = $kaydet->execute([
            'baslik' => $baslik,
            'aciklama' => $aciklama,
            'resim' => $resimAdi,
            'sira' => $sira,
            'anahtarkelime' => $anahtarkelime,
            'yazar_id' => $yazar_id
        ]);

        if ($insert) {
            header("Location: blog.php?durum=ok");
        } else {
            header("Location: blog.php?durum=no");
        }
    } else {
        header("Location: blog.php?durum=no");
    }
    exit;
}

// Blog düzenleme işlemi
if (isset($_POST['blogduzenle'])) {
    girisKontrol();
    
    if (!yetkiKontrol('blog')) {
        header("Location: yetkisiz.php");
        exit;
    }

    $id = filter_var($_POST['id'], FILTER_SANITIZE_NUMBER_INT);
    $baslik = filter_var($_POST['baslik'], FILTER_SANITIZE_STRING);
    $aciklama = $_POST['aciklama']; // CKEditor kullanıldığı için filtreleme yapmıyoruz
    $sira = filter_var($_POST['sira'], FILTER_SANITIZE_NUMBER_INT);
    $anahtarkelime = filter_var($_POST['anahtarkelime'], FILTER_SANITIZE_STRING);

    if ($_FILES['resim']['size'] > 0) {
        $resim = $_FILES['resim'];
        $resimAdi = dosyaYukle($resim, "resimler/blog/");
        if ($resimAdi) {
            eskiDosyaSil("resimler/blog/" . $_POST['eskiresim']);
        } else {
            $resimAdi = $_POST['eskiresim'];
        }
    } else {
        $resimAdi = $_POST['eskiresim'];
    }

    $guncelle = $baglanti->prepare("UPDATE blog SET 
        blog_baslik = :baslik,
        blog_aciklama = :aciklama,
        blog_resim = :resim,
        blog_sira = :sira,
        blog_anahtarkelime = :anahtarkelime
    WHERE blog_id = :id");

    $update = $guncelle->execute([
        'baslik' => $baslik,
        'aciklama' => $aciklama,
        'resim' => $resimAdi,
        'sira' => $sira,
        'anahtarkelime' => $anahtarkelime,
        'id' => $id
    ]);

    if ($update) {
        header("Location: blog.php?durum=ok");
    } else {
        header("Location: blog.php?durum=no");
    }
    exit;
}

// Blog silme işlemi
if (isset($_POST['blogsil'])) {
    girisKontrol();
    
    if (!yetkiKontrol('blog')) {
        header("Location: yetkisiz.php");
        exit;
    }

    $id = filter_var($_POST['id'], FILTER_SANITIZE_NUMBER_INT);
    $eskiresim = $_POST['eskiresim'];

    eskiDosyaSil("resimler/blog/" . $eskiresim);

    $sil = $baglanti->prepare("DELETE FROM blog WHERE blog_id = :id");
    $delete = $sil->execute(['id' => $id]);

    if ($delete) {
        header("Location: blog.php?durum=ok");
    } else {
        header("Location: blog.php?durum=no");
    }
    exit;
}

// Kategori işlemleri
if (isset($_POST['kategorikaydet'])) {
    girisKontrol();
    if (!adminYetkisi()) {
        header("Location: yetkisiz.php");
        exit;
    }

    

    $baslik = filter_var($_POST['baslik'], FILTER_SANITIZE_STRING);
    $sira = filter_var($_POST['sira'], FILTER_SANITIZE_NUMBER_INT);
    $durum = filter_var($_POST['durum'], FILTER_SANITIZE_NUMBER_INT);

    $kaydet = $baglanti->prepare("INSERT INTO kategori SET 
        kategori_baslik = :baslik,
        kategori_sira = :sira,
        kategori_durum = :durum
    ");

    $insert = $kaydet->execute([
        'baslik' => $baslik,
        'sira' => $sira,
        'durum' => $durum
    ]);

    if ($insert) {
        header("Location:kategori.php?durum=ok");
    } else {
        header("Location:kategori.php?durum=no");
    }
}

// // İçerik ekleme işlemi
// if (isset($_POST['icerikkaydet'])) {
//     $baslik = filter_var($_POST['baslik'], FILTER_SANITIZE_STRING);
//     $aciklama = $_POST['aciklama']; // CKEditor kullanıldığı için filtreleme yapmıyoruz
//     $katid = filter_var($_POST['katid'], FILTER_SANITIZE_NUMBER_INT);
//     $sira = filter_var($_POST['sira'], FILTER_SANITIZE_NUMBER_INT);
//     $icerikanahtar = filter_var($_POST['icerikanahtar'], FILTER_SANITIZE_STRING);
//     $yazar_id = $_SESSION['kullanici_id'];

//     $resim = $_FILES['resim'];
//     $resimAdi = dosyaYukle($resim, "resimler/icerik/");

//     if ($resimAdi) {
//         $kaydet = $baglanti->prepare("INSERT INTO icerik SET 
//             icerik_baslik = :baslik,
//             icerik_aciklama = :aciklama,
//             kategori_id = :katid,
//             icerik_resim = :resim,
//             icerik_sira = :sira,
//             icerik_anahtarkelime = :icerikanahtar,
//             kullanici_id = :yazar_id
//         ");

//         $insert = $kaydet->execute([
//             'baslik' => $baslik,
//             'aciklama' => $aciklama,
//             'katid' => $katid,
//             'resim' => $resimAdi,
//             'sira' => $sira,
//             'icerikanahtar' => $icerikanahtar,
//             'yazar_id' => $yazar_id
//         ]);

//         if ($insert) {
//             header("Location: icerik.php?katid=$katid&durum=ok");
//         } else {
//             header("Location: icerik.php?katid=$katid&durum=no");
//         }
//     } else {
//         header("Location: icerik.php?katid=$katid&durum=no");
//     }
//     exit;
// }

// // İçerik düzenleme işlemi
// if (isset($_POST['icerikduzenle'])) {
//     $id = filter_var($_POST['id'], FILTER_SANITIZE_NUMBER_INT);
//     $baslik = filter_var($_POST['baslik'], FILTER_SANITIZE_STRING);
//     $aciklama = $_POST['aciklama']; // CKEditor kullanıldığı için filtreleme yapmıyoruz
//     $katid = filter_var($_POST['katid'], FILTER_SANITIZE_NUMBER_INT);
//     $sira = filter_var($_POST['sira'], FILTER_SANITIZE_NUMBER_INT);
//     $icerikanahtar = filter_var($_POST['icerikanahtar'], FILTER_SANITIZE_STRING);

//     if ($_FILES['resim']['size'] > 0) {
//         $resim = $_FILES['resim'];
//         $resimAdi = dosyaYukle($resim, "resimler/icerik/");
//         if ($resimAdi) {
//             eskiDosyaSil("resimler/icerik/" . $_POST['eskiresim']);
//         } else {
//             $resimAdi = $_POST['eskiresim'];
//         }
//     } else {
//         $resimAdi = $_POST['eskiresim'];
//     }

//     $guncelle = $baglanti->prepare("UPDATE icerik SET 
//         icerik_baslik = :baslik,
//         icerik_aciklama = :aciklama,
//         kategori_id = :katid,
//         icerik_resim = :resim,
//         icerik_sira = :sira,
//         icerik_anahtarkelime = :icerikanahtar
//     WHERE icerik_id = :id");

//     $update = $guncelle->execute([
//         'baslik' => $baslik,
//         'aciklama' => $aciklama,
//         'katid' => $katid,
//         'resim' => $resimAdi,
//         'sira' => $sira,
//         'icerikanahtar' => $icerikanahtar,
//         'id' => $id
//     ]);

//     if ($update) {
//         header("Location: icerik.php?katid=$katid&durum=ok");
//     } else {
//         header("Location: icerik.php?katid=$katid&durum=no");
//     }
//     exit;
// }

// // İçerik silme işlemi
// if (isset($_POST['iceriksil'])) {
//     $id = filter_var($_POST['id'], FILTER_SANITIZE_NUMBER_INT);
//     $katid = filter_var($_POST['katid'], FILTER_SANITIZE_NUMBER_INT);
//     $eskiresim = $_POST['eskiresim'];

//     eskiDosyaSil("resimler/icerik/" . $eskiresim);

//     $sil = $baglanti->prepare("DELETE FROM icerik WHERE icerik_id = :id");
//     $delete = $sil->execute(['id' => $id]);

//     if ($delete) {
//         header("Location: icerik.php?katid=$katid&durum=ok");
//     } else {
//         header("Location: icerik.php?katid=$katid&durum=no");
//     }
//     exit;
// }

// // İçerik kaydetme işlemi
// if (isset($_POST['icerikkaydet'])) {

//     $baslik = filter_var($_POST['baslik'], FILTER_SANITIZE_STRING);
//     $aciklama = $_POST['aciklama']; // CKEditor kullanıldığı için filtreleme yapmıyoruz
//     $katid = filter_var($_POST['katid'], FILTER_SANITIZE_NUMBER_INT);
//     $sira = filter_var($_POST['sira'], FILTER_SANITIZE_NUMBER_INT);
//     $icerikanahtar = filter_var($_POST['icerikanahtar'], FILTER_SANITIZE_STRING);
//     $yazar_id = $_SESSION['kullanici_id'];

//     // Resim yükleme işlemi
//     $resimAdi = "";
//     if(isset($_FILES['resim']) && $_FILES['resim']['error'] == 0) {
//         $resim = $_FILES['resim'];
//         $resimAdi = dosyaYukle($resim, "resimler/icerik/");
//         if ($resimAdi === false) {
//             header("Location: icerik-ekle.php?katid=$katid&durum=no");
//             exit;
//         }
//     }

//     $kaydet = $baglanti->prepare("INSERT INTO icerik SET 
//         icerik_baslik = :baslik,
//         icerik_aciklama = :aciklama,
//         kategori_id = :katid,
//         icerik_resim = :resim,
//         icerik_sira = :sira,
//         icerik_anahtarkelime = :icerikanahtar,
//         kullanici_id = :yazar_id
//     ");

//     $insert = $kaydet->execute([
//         'baslik' => $baslik,
//         'aciklama' => $aciklama,
//         'katid' => $katid,
//         'resim' => $resimAdi,
//         'sira' => $sira,
//         'icerikanahtar' => $icerikanahtar,
//         'yazar_id' => $yazar_id
//     ]);

//     if ($insert) {
//         header("Location: icerik.php?katid=$katid&durum=ok");
//     } else {
//         header("Location: icerik-ekle.php?katid=$katid&durum=no");
//     }
//     exit;
// }

// Kategori ekleme işlemi
if (isset($_POST['kategoriekle'])) {
    $baslik = filter_var($_POST['baslik'], FILTER_SANITIZE_STRING);
    $sira = filter_var($_POST['sira'], FILTER_SANITIZE_NUMBER_INT);
    $durum = isset($_POST['durum']) ? 1 : 0;

    $kaydet = $baglanti->prepare("INSERT INTO kategori SET 
        kategori_baslik = :baslik,
        kategori_sira = :sira,
        kategori_durum = :durum
    ");

    $insert = $kaydet->execute([
        'baslik' => $baslik,
        'sira' => $sira,
        'durum' => $durum
    ]);

    if ($insert) {
        header("Location: kategori.php?durum=ok");
    } else {
        header("Location: kategori.php?durum=no");
    }
    exit;
}

// Kategori düzenleme işlemi
if (isset($_POST['kategoriduzenle'])) {
    $id = filter_var($_POST['id'], FILTER_SANITIZE_NUMBER_INT);
    $baslik = filter_var($_POST['baslik'], FILTER_SANITIZE_STRING);
    $sira = filter_var($_POST['sira'], FILTER_SANITIZE_NUMBER_INT);
    $durum = isset($_POST['durum']) ? 1 : 0;

    $guncelle = $baglanti->prepare("UPDATE kategori SET 
        kategori_baslik = :baslik,
        kategori_sira = :sira,
        kategori_durum = :durum
    WHERE kategori_id = :id");

    $update = $guncelle->execute([
        'baslik' => $baslik,
        'sira' => $sira,
        'durum' => $durum,
        'id' => $id
    ]);

    if ($update) {
        header("Location: kategori.php?durum=ok");
    } else {
        header("Location: kategori.php?durum=no");
    }
    exit;
}

// Kategori silme işlemi
if (isset($_POST['kategorisil'])) {
    $id = filter_var($_POST['id'], FILTER_SANITIZE_NUMBER_INT);

    // Önce bu kategoriye ait blog yazılarını kontrol edelim
    $blogKontrol = $baglanti->prepare("SELECT COUNT(*) FROM blog WHERE kategori_id = :id");
    $blogKontrol->execute(['id' => $id]);
    $blogSayisi = $blogKontrol->fetchColumn();

    if ($blogSayisi > 0) {
        // Eğer bu kategoriye ait blog yazıları varsa, silme işlemini gerçekleştirmeyelim
        header("Location: kategori.php?durum=bagli_icerik");
        exit;
    }

    $sil = $baglanti->prepare("DELETE FROM kategori WHERE kategori_id = :id");
    $delete = $sil->execute(['id' => $id]);

    if ($delete) {
        header("Location: kategori.php?durum=ok");
    } else {
        header("Location: kategori.php?durum=no");
    }
    exit;
}

// Blog yorumu kaydetme işlemi
if (isset($_POST['blogyorumkaydet'])) {
    
    $adsoyad = filter_var($_POST['adsoyad'], FILTER_SANITIZE_STRING);
    $detay = filter_var($_POST['detay'], FILTER_SANITIZE_STRING);
    $blog_id = filter_var($_POST['id'], FILTER_SANITIZE_NUMBER_INT);
    $kategori = filter_var($_POST['kategori'], FILTER_SANITIZE_NUMBER_INT);

    $kaydet = $baglanti->prepare("INSERT INTO yorumlar SET 
        yorum_kategori = :kategori,
        blog_id = :blog_id,
        yorum_adsoyad = :adsoyad,
        yorum_detay = :detay,
        yorum_onay = :onay
    ");

    $insert = $kaydet->execute([
        'kategori' => $kategori,
        'blog_id' => $blog_id,
        'adsoyad' => $adsoyad,
        'detay' => $detay,
        'onay' => 0  // Yorumlar varsayılan olarak onaysız
    ]);

    if ($insert) {
        header("Location: ../blog-detay.php?blog_id=$blog_id&durum=ok");
    } else {
        header("Location: ../blog-detay.php?blog_id=$blog_id&durum=no");
    }
    exit;
}

// // İçerik yorumu kaydetme işlemi
// if (isset($_POST['icerikyorumkaydet'])) {
    
//     $adsoyad = filter_var($_POST['adsoyad'], FILTER_SANITIZE_STRING);
//     $detay = filter_var($_POST['detay'], FILTER_SANITIZE_STRING);
//     $icerik_id = filter_var($_POST['id'], FILTER_SANITIZE_NUMBER_INT);
//     $kategori = filter_var($_POST['kategori'], FILTER_SANITIZE_NUMBER_INT);

//     $kaydet = $baglanti->prepare("INSERT INTO yorumlar SET 
//         yorum_kategori = :kategori,
//         icerik_id = :icerik_id,
//         yorum_adsoyad = :adsoyad,
//         yorum_detay = :detay,
//         yorum_onay = :onay
//     ");

//     $insert = $kaydet->execute([
//         'kategori' => $kategori,
//         'icerik_id' => $icerik_id,
//         'adsoyad' => $adsoyad,
//         'detay' => $detay,
//         'onay' => 0  // Yorumlar varsayılan olarak onaysız
//     ]);

//     if ($insert) {
//         header("Location: ../icerik-detay.php?icerik_id=$icerik_id&durum=ok");
//     } else {
//         header("Location: ../icerik-detay.php?icerik_id=$icerik_id&durum=no");
//     }
//     exit;
// }

// Yorum onaylama işlemi
if (isset($_POST['yorumonayla'])) {
    girisKontrol();
    if (!adminYetkisi() && !moderatorYetkisi()) {
        header("Location: yetkisiz.php");
        exit;
    }

    $yorum_id = filter_var($_POST['id'], FILTER_SANITIZE_NUMBER_INT);

    $guncelle = $baglanti->prepare("UPDATE yorumlar SET yorum_onay = 1 WHERE yorum_id = :id");
    $update = $guncelle->execute(['id' => $yorum_id]);

    if ($update) {
        header("Location: yorumlar.php?durum=ok");
    } else {
        header("Location: yorumlar.php?durum=no");
    }
    exit;
}

// Yorum onayını kaldırma işlemi
if (isset($_POST['yorumonaykaldır'])) {
    girisKontrol();
    if (!adminYetkisi() && !moderatorYetkisi()) {
        header("Location: yetkisiz.php");
        exit;
    }

    $yorum_id = filter_var($_POST['id'], FILTER_SANITIZE_NUMBER_INT);

    $guncelle = $baglanti->prepare("UPDATE yorumlar SET yorum_onay = 0 WHERE yorum_id = :id");
    $update = $guncelle->execute(['id' => $yorum_id]);

    if ($update) {
        header("Location: yorumlar.php?durum=ok");
    } else {
        header("Location: yorumlar.php?durum=no");
    }
    exit;
}

// Yorum silme işlemi
if (isset($_GET['yorumsil'])) {
    if (!adminYetkisi() && !moderatorYetkisi()) {
        header("Location: yetkisiz.php");
        exit;
    }

    $yorum_id = filter_var($_GET['yorumsil'], FILTER_SANITIZE_NUMBER_INT);

    // Eğer moderatörse, sadece kendi bloglarına ait yorumları silebilmeli
    if (moderatorYetkisi()) {
        $kontrolsor = $baglanti->prepare("
            SELECT yorumlar.yorum_id 
            FROM yorumlar 
            JOIN blog ON yorumlar.blog_id = blog.blog_id 
            WHERE yorumlar.yorum_id = :yorum_id AND blog.kullanici_id = :kullanici_id
        ");
        $kontrolsor->execute([
            'yorum_id' => $yorum_id,
            'kullanici_id' => $_SESSION['kullanici_id']
        ]);
        if ($kontrolsor->rowCount() == 0) {
            header("Location: yetkisiz.php");
            exit;
        }
    }

    $sil = $baglanti->prepare("DELETE FROM yorumlar WHERE yorum_id = :id");
    $delete = $sil->execute(['id' => $yorum_id]);

    if ($delete) {
        header("Location: yorumlar.php?durum=ok");
    } else {
        header("Location: yorumlar.php?durum=no");
    }
    exit;
}

// Ayarlar
if (isset($_POST['ayarkaydet'])) {
    girisKontrol();
    if (!adminYetkisi()) {
        header("Location: yetkisiz.php");
        exit;
    }

    $baslik = filter_var($_POST['baslik'], FILTER_SANITIZE_STRING);
    $aciklama = filter_var($_POST['aciklama'], FILTER_SANITIZE_STRING);
    $anahtar = filter_var($_POST['anahtar'], FILTER_SANITIZE_STRING);

    $guncelle = $baglanti->prepare("UPDATE ayarlar SET 
        ayar_baslik = :baslik,
        ayar_aciklama = :aciklama,
        ayar_anahtar = :anahtar
    WHERE ayar_id = 1");

    $update = $guncelle->execute([
        'baslik' => $baslik,
        'aciklama' => $aciklama,
        'anahtar' => $anahtar
    ]);

    if ($update) {
        header("Location:ayarlar.php?durum=ok");
    } else {
        header("Location:ayarlar.php?durum=no");
    }
}

// Kullanıcı işlemleri
if (isset($_POST['kullanicikaydet'])) {
    girisKontrol();
    if (!adminYetkisi()) {
        header("Location: yetkisiz.php");
        exit;
    }

    $adsoyad = filter_var($_POST['adsoyad'], FILTER_SANITIZE_STRING);
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $sifre = password_hash($_POST['sifre'], PASSWORD_DEFAULT);
    $rol = filter_var($_POST['rol'], FILTER_SANITIZE_STRING);

    $kaydet = $baglanti->prepare("INSERT INTO kullanici SET 
        kullanici_adsoyad = :adsoyad,
        kullanici_email = :email,
        kullanici_sifre = :sifre,
        kullanici_rol = :rol
    ");

    $insert = $kaydet->execute([
        'adsoyad' => $adsoyad,
        'email' => $email,
        'sifre' => $sifre,
        'rol' => $rol
    ]);

    if ($insert) {
        header("Location:kullanicilar.php?durum=ok");
    } else {
        header("Location:kullanicilar.php?durum=no");
    }
}

if (isset($_POST['kullaniciduzenle'])) {
    girisKontrol();
    if (!adminYetkisi()) {
        header("Location: yetkisiz.php");
        exit;
    }

    $id = filter_var($_POST['id'], FILTER_SANITIZE_NUMBER_INT);
    $adsoyad = filter_var($_POST['adsoyad'], FILTER_SANITIZE_STRING);
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $rol = filter_var($_POST['rol'], FILTER_SANITIZE_STRING);

    // Şifre değiştirilmek isteniyorsa
    if (!empty($_POST['sifre'])) {
        $sifre = password_hash($_POST['sifre'], PASSWORD_DEFAULT);
        $guncelle = $baglanti->prepare("UPDATE kullanici SET 
            kullanici_adsoyad = :adsoyad,
            kullanici_email = :email,
            kullanici_sifre = :sifre,
            kullanici_rol = :rol
        WHERE kullanici_id = :id");

        $update = $guncelle->execute([
            'adsoyad' => $adsoyad,
            'email' => $email,
            'sifre' => $sifre,
            'rol' => $rol,
            'id' => $id
        ]);
    } else {
        $guncelle = $baglanti->prepare("UPDATE kullanici SET 
            kullanici_adsoyad = :adsoyad,
            kullanici_email = :email,
            kullanici_rol = :rol
        WHERE kullanici_id = :id");

        $update = $guncelle->execute([
            'adsoyad' => $adsoyad,
            'email' => $email,
            'rol' => $rol,
            'id' => $id
        ]);
    }

    if ($update) {
        header("Location:kullanicilar.php?durum=ok");
    } else {
        header("Location:kullanicilar.php?durum=no");
    }
}

if (isset($_POST['kullanicisil'])) {
    girisKontrol();
    if (!adminYetkisi()) {
        header("Location: yetkisiz.php");
        exit;
    }

    $id = filter_var($_POST['id'], FILTER_SANITIZE_NUMBER_INT);

    $sil = $baglanti->prepare("DELETE FROM kullanici WHERE kullanici_id = :id");
    $delete = $sil->execute(['id' => $id]);

    if ($delete) {
        header("Location:kullanicilar.php?durum=ok");
    } else {
        header("Location:kullanicilar.php?durum=no");
    }
}

// İletişim formu işlemi
if (isset($_POST['iletisim_gonder'])) {
    girisKontrol();

    $ad = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $konu = filter_var($_POST['subject'], FILTER_SANITIZE_STRING);
    $mesaj = filter_var($_POST['message'], FILTER_SANITIZE_STRING);

    $kaydet = $baglanti->prepare("INSERT INTO iletisim SET 
        iletisim_ad = :ad,
        iletisim_email = :email,
        iletisim_konu = :konu,
        iletisim_mesaj = :mesaj
    ");

    $insert = $kaydet->execute([
        'ad' => $ad,
        'email' => $email,
        'konu' => $konu,
        'mesaj' => $mesaj
    ]);

    if ($insert) {
        header("Location: ../iletisim.php?durum=ok");
    } else {
        header("Location: ../iletisim.php?durum=no");
    }
    exit;
}

// İletişim mesajı silme işlemi
if (isset($_GET['iletisimsil'])) {
    if (!adminYetkisi()) {
        header("Location: yetkisiz.php");
        exit;
    }

    $id = filter_var($_GET['iletisimsil'], FILTER_SANITIZE_NUMBER_INT);

    $sil = $baglanti->prepare("DELETE FROM iletisim WHERE iletisim_id = :id");
    $delete = $sil->execute(['id' => $id]);

    if ($delete) {
        header("Location: ../iletisim.php?durum=ok");
    } else {
        header("Location: ../iletisim.php?durum=no");
    }
    exit;
}
// Abone olma işlemi
if (isset($_POST['abone'])) {
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);

    $kaydet = $baglanti->prepare("INSERT INTO abone SET abone_email = :email");
    $insert = $kaydet->execute(['email' => $email]);

    if ($insert) {
        header("Location:index.php?durum=ok");
    } else {
        header("Location:index.php?durum=no");
    }
}

// Hakkımızda sayfası güncelleme
if (isset($_POST['hakkimizdakaydet'])) {
    girisKontrol();
    if (!adminYetkisi()) {
        header("Location: yetkisiz.php");
        exit;
    }

    $baslik = filter_var($_POST['baslik'], FILTER_SANITIZE_STRING);
    $aciklama = $_POST['aciklama'];  // HTML içerik olduğu için filtreleme yapmıyoruz

    if ($_FILES['resim']['size'] > 0) {
        $resim = $_FILES['resim'];
        $resimAdi = dosyaYukle($resim, "resimler/hakkimizda/");
        if ($resimAdi) {
            eskiDosyaSil("resimler/hakkimizda/" . $_POST['eskiresim']);
        } else {
            $resimAdi = $_POST['eskiresim'];
        }
    } else {
        $resimAdi = $_POST['eskiresim'];
    }

    $guncelle = $baglanti->prepare("UPDATE hakkimizda SET 
        hakkimizda_baslik = :baslik,
        hakkimizda_aciklama = :aciklama,
        hakkimizda_resim = :resim
    WHERE hakkimizda_id = 1");

    $update = $guncelle->execute([
        'baslik' => $baslik,
        'aciklama' => $aciklama,
        'resim' => $resimAdi
    ]);

    if ($update) {
        header("Location:hakkimizda.php?durum=ok");
    } else {
        header("Location:hakkimizda.php?durum=no");
    }
}

// Slider güncelleme
if (isset($_POST['sliderkaydet'])) {
    girisKontrol();
    if (!adminYetkisi()) {
        header("Location: yetkisiz.php");
        exit;
    }

    $baslik = filter_var($_POST['baslik'], FILTER_SANITIZE_STRING);
    $aciklama = filter_var($_POST['aciklama'], FILTER_SANITIZE_STRING);
    $buton = filter_var($_POST['buton'], FILTER_SANITIZE_STRING);
    $link = filter_var($_POST['link'], FILTER_SANITIZE_URL);

    if ($_FILES['resim']['size'] > 0) {
        $resim = $_FILES['resim'];
        $resimAdi = dosyaYukle($resim, "resimler/slider/");
        if ($resimAdi) {
            eskiDosyaSil("resimler/slider/" . $_POST['eskiresim']);
        } else {
            $resimAdi = $_POST['eskiresim'];
        }
    } else {
        $resimAdi = $_POST['eskiresim'];
    }

    $guncelle = $baglanti->prepare("UPDATE slider SET 
        slider_baslik = :baslik,
        slider_aciklama = :aciklama,
        slider_buton = :buton,
        slider_link = :link,
        slider_resim = :resim
    WHERE slider_id = 1");

    $update = $guncelle->execute([
        'baslik' => $baslik,
        'aciklama' => $aciklama,
        'buton' => $buton,
        'link' => $link,
        'resim' => $resimAdi
    ]);

    if ($update) {
        header("Location:slider.php?durum=ok");
    } else {
        header("Location:slider.php?durum=no");
    }
}

// Eğer hiçbir işlem yapılmadıysa
header("Location:index.php");
exit;
?>