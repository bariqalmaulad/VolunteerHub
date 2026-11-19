<?php require 'config.php'; check_csrf();
$mode=basename($_SERVER['PHP_SELF']);
if($_SERVER['REQUEST_METHOD']==='POST'){
$email=post('email');$pass=(string)($_POST['password']??'');
if($mode==='login.php'){
$s=db()->prepare("SELECT * FROM users WHERE email=?");$s->execute([$email]);$u=$s->fetch();
if($u && password_verify($pass,$u['password_hash'])){session_regenerate_id(true);$_SESSION['user']=$u;redirect('dashboard.php');}
flash('error','Email atau kata sandi tidak sesuai.');redirect('login.php');
}else{
$name=post('nama');$role=post('role','volunteer');$org=post('nama_organisasi');
if(!$name||!filter_var($email,FILTER_VALIDATE_EMAIL)||strlen($pass)<8||!in_array($role,['volunteer','organisasi'],true)||($role==='organisasi'&&!$org)){flash('error','Periksa kembali data. Kata sandi minimal 8 karakter.');redirect('register.php');}
try{db()->beginTransaction();$s=db()->prepare('INSERT INTO users(nama,email,password_hash,role) VALUES(?,?,?,?)');$s->execute([$name,$email,password_hash($pass,PASSWORD_DEFAULT),$role]);$id=(int)db()->lastInsertId();
if($role==='volunteer'){db()->prepare('INSERT INTO volunteers(user_id) VALUES(?)')->execute([$id]);db()->prepare('INSERT INTO contribution_history(volunteer_id) VALUES(?)')->execute([$id]);}
else{db()->prepare("INSERT INTO organizations(user_id,nama_organisasi) VALUES(?,?)")->execute([$id,$org]);}
db()->commit();$s=db()->prepare('SELECT * FROM users WHERE user_id=?');$s->execute([$id]);$_SESSION['user']=$s->fetch();flash('success',$role==='organisasi'?'Akun dibuat. Menunggu verifikasi admin sebelum mempublikasikan kegiatan.':'Akun berhasil dibuat. Selamat datang!');redirect('dashboard.php');
}catch(Throwable $e){if(db()->inTransaction())db()->rollBack();flash('error','Email sudah digunakan atau pendaftaran gagal.');redirect('register.php');}
}}
