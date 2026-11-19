<?php require 'config.php';
$q=post('q'); $cat=post('kategori'); $loc=post('lokasi');
$sql="SELECT e.*,o.nama_organisasi FROM events e JOIN organizations o ON o.user_id=e.organisasi_id WHERE e.status='Dipublikasikan' AND e.tanggal>=NOW()";
$args=[]; if($q!==''){$sql.=" AND (e.nama_kegiatan LIKE ? OR e.deskripsi LIKE ?)";$args[]="%$q%";$args[]="%$q%";}
if($cat!==''){$sql.=" AND e.kategori=?";$args[]=$cat;} if($loc!==''){$sql.=" AND e.lokasi LIKE ?";$args[]="%$loc%";}
$sql.=" ORDER BY e.tanggal ASC"; $st=db()->prepare($sql);$st->execute($args);$events=$st->fetchAll();
layout_start('Temukan Kegiatan'); ?>
<section class="hero"><div><span class="eyebrow">VOLUNTEERHUB · BERGERAK BERSAMA</span><h1>Waktu dan kepedulianmu<br><em>bisa mengubah banyak hal.</em></h1><p>Temukan kegiatan sosial, bergabung dengan komunitas, dan catat setiap kontribusimu.</p><a class="btn btn-light" href="register.php">Mulai berkontribusi <span>→</span></a></div><div class="hero-art"><div class="orb orb1"></div><div class="orb orb2"></div><div class="hero-card"><div class="hero-icon">✦</div><b>Small actions.</b><b class="blue-text">Real impact.</b><small>Temukan ruang untuk berbuat baik.</small></div></div></section>
<div class="section-head"><div><span class="eyebrow">KEGIATAN TERBUKA</span><h2>Jelajahi peluang volunteer</h2><p class="muted">Pilih kegiatan yang sesuai dengan minat dan waktumu.</p></div><span class="count"><?=count($events)?> kegiatan</span></div>
<form class="filter" method="get"><input name="q" placeholder="Cari nama atau deskripsi kegiatan..." value="<?=e($q)?>"><input name="kategori" placeholder="Kategori" value="<?=e($cat)?>"><input name="lokasi" placeholder="Lokasi" value="<?=e($loc)?>"><button class="btn btn-primary">Cari kegiatan</button></form>
<div class="cards"><?php foreach($events as $ev):?><article class="event-card"><div class="event-top"><span class="category"><?=e($ev['kategori'])?></span><span class="muted small-text"><?=date('d M Y',strtotime($ev['tanggal']))?></span></div><h3><?=e($ev['nama_kegiatan'])?></h3><p class="muted clamp"><?=e($ev['deskripsi'])?></p><div class="event-meta"><span>⌖ <?=e($ev['lokasi'])?></span><span>◷ <?=e($ev['durasi_jam'])?> jam</span></div><div class="card-bottom"><small>Oleh <b><?=e($ev['nama_organisasi'])?></b></small><a href="event.php?id=<?=$ev['kegiatan_id']?>">Lihat detail →</a></div></article><?php endforeach;?></div>
<?php if(!$events):?><div class="empty"><div>⌕</div><h3>Belum ada kegiatan yang cocok</h3><p>Coba ubah kata kunci atau filter pencarian.</p></div><?php endif; ?>
<?php layout_end(); ?>
