<?php
declare(strict_types=1);
session_start();
const DB_HOST='127.0.0.1'; const DB_NAME='volunteerhub'; const DB_USER='root'; const DB_PASS='';
function db(): PDO {
 static $pdo=null;
 if(!$pdo){ $pdo=new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME.';charset=utf8mb4',DB_USER,DB_PASS,[PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION,PDO::ATTR_DEFAULT_FETCH_MODE=>PDO::FETCH_ASSOC]); }
 return $pdo;
}
function e($v): string { return htmlspecialchars((string)$v,ENT_QUOTES,'UTF-8'); }
function user(): ?array { return $_SESSION['user']??null; }
function require_login(): void { if(!user()){ flash('error','Silakan masuk terlebih dahulu.'); redirect('login.php'); } }
function require_role(string ...$roles): void { require_login(); if(!in_array(user()['role'],$roles,true)){ http_response_code(403); exit('Akses ditolak.'); } }
function redirect(string $to): never { header('Location: '.$to); exit; }
function flash(string $type,string $message): void { $_SESSION['flash']=['type'=>$type,'message'=>$message]; }
function csrf(): string { if(empty($_SESSION['csrf'])) $_SESSION['csrf']=bin2hex(random_bytes(24)); return $_SESSION['csrf']; }
function check_csrf(): void { if($_SERVER['REQUEST_METHOD']==='POST' && !hash_equals($_SESSION['csrf']??'',$_POST['csrf']??'')){ http_response_code(419); exit('Permintaan tidak valid. Muat ulang halaman.'); } }
function layout_start(string $title='VolunteerHub'): void {
 $u=user(); $f=$_SESSION['flash']??null; unset($_SESSION['flash']); ?>
<!doctype html><html lang="id"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1"><title><?=e($title)?> · VolunteerHub</title><link rel="stylesheet" href="assets/style.css"></head><body>
<header class="topbar"><a class="brand" href="index.php"><span class="brand-mark">V</span> VolunteerHub</a><nav><a href="index.php">Jelajahi</a><?php if($u):?><a href="dashboard.php">Dashboard</a><?php if($u['role']==='volunteer'):?><a href="checkin.php">Check-in</a><a href="certificates.php">Sertifikat</a><?php endif;?><a href="profile.php">Profil</a><span class="nav-user"><?=e($u['nama'])?></span><a class="btn btn-outline small" href="logout.php">Keluar</a><?php else:?><a href="login.php">Masuk</a><a class="btn btn-primary small" href="register.php">Daftar</a><?php endif;?></nav></header><main class="container">
<?php if($f):?><div class="alert <?=e($f['type'])?>"><?=e($f['message'])?></div><?php endif;?>
<?php }
function layout_end(): void { ?></main><footer class="footer">© <?=date('Y')?> VolunteerHub · Hubungkan niat baik dengan aksi nyata.</footer></body></html><?php }
function badge(string $s): string { $map=['Menunggu Verifikasi'=>'warning','Diterima'=>'success','Ditolak'=>'danger','Hadir'=>'success','Tidak Hadir'=>'muted','Menunggu'=>'warning','Terverifikasi'=>'success','Ditangguhkan'=>'danger','Dipublikasikan'=>'success','Menunggu Moderasi'=>'warning','Selesai'=>'muted']; return '<span class="badge '.($map[$s]??'muted').'">'.e($s).'</span>'; }
function post(string $key,string $default=''): string { return trim((string)($_POST[$key]??$default)); }
