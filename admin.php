<?php
require_once __DIR__.'/config.php';
$error='';
if(isset($_GET['logout'])){session_destroy();header('Location: admin.php');exit;}
if($_SERVER['REQUEST_METHOD']==='POST' && isset($_POST['login'])){
 if(($_POST['username']??'')===ADMIN_USER && ($_POST['password']??'')===ADMIN_PASS){$_SESSION['admin']=true;header('Location: admin.php');exit;}
 $error='Incorrect username or password.';
}
if(empty($_SESSION['admin'])): ?>
<!doctype html><html><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><link rel="stylesheet" href="assets/css/style.css"><title>Admin Login</title></head><body class="admin-login"><form class="login-card" method="post"><div class="brand-mark large">✈</div><h2>Mustafa Travels Admin</h2><?php if($error):?><div class="error"><?=h($error)?></div><?php endif;?><input name="username" placeholder="Username" required><input type="password" name="password" placeholder="Password" required><button class="btn btn-primary" name="login">Login</button><small>Default: admin / ChangeMe123! — change in config.php</small></form></body></html>
<?php exit; endif;

if(isset($_GET['delete_offer'])){$pdo->prepare("DELETE FROM offers WHERE id=?")->execute([(int)$_GET['delete_offer']]);header('Location: admin.php');exit;}
if(isset($_GET['delete_cert'])){$pdo->prepare("DELETE FROM certificates WHERE id=?")->execute([(int)$_GET['delete_cert']]);header('Location: admin.php?tab=certs');exit;}

if($_SERVER['REQUEST_METHOD']==='POST' && isset($_POST['save_offer'])){
 $img=upload_image('image');
 $stmt=$pdo->prepare("INSERT INTO offers(title,subtitle,origin,destination,airline,price,currency,travel_dates,baggage,badge,image,featured,active) VALUES(?,?,?,?,?,?,?,?,?,?,?,?,1)");
 $stmt->execute([$_POST['title'],$_POST['subtitle'],$_POST['origin'],$_POST['destination'],$_POST['airline'],(float)$_POST['price'],$_POST['currency'],$_POST['travel_dates'],$_POST['baggage'],$_POST['badge'],$img,!empty($_POST['featured'])?1:0]);
 header('Location: admin.php');exit;
}
if($_SERVER['REQUEST_METHOD']==='POST' && isset($_POST['save_cert'])){
 $img=upload_image('cert_image');
 $pdo->prepare("INSERT INTO certificates(title,issuer,image,sort_order,active) VALUES(?,?,?,?,1)")->execute([$_POST['title'],$_POST['issuer'],$img,(int)$_POST['sort_order']]);
 header('Location: admin.php?tab=certs');exit;
}
$tab=$_GET['tab']??'offers';
$offers=$pdo->query("SELECT * FROM offers ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC);
$certs=$pdo->query("SELECT * FROM certificates ORDER BY sort_order,id")->fetchAll(PDO::FETCH_ASSOC);
$inq=$pdo->query("SELECT * FROM inquiries ORDER BY id DESC LIMIT 100")->fetchAll(PDO::FETCH_ASSOC);
?>
<!doctype html><html><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><link rel="stylesheet" href="assets/css/style.css"><title>Admin</title></head><body class="admin-body">
<div class="admin-shell">
<aside><h2>Mustafa Admin</h2><a class="<?=$tab==='offers'?'active':''?>" href="admin.php">Offers</a><a class="<?=$tab==='certs'?'active':''?>" href="admin.php?tab=certs">Certificates</a><a class="<?=$tab==='inq'?'active':''?>" href="admin.php?tab=inq">Inquiries</a><a href="index.php" target="_blank">View Website</a><a href="admin.php?logout=1">Logout</a></aside>
<main>
<?php if($tab==='offers'): ?>
<h1>Daily Offers</h1><div class="admin-grid"><form class="admin-card" method="post" enctype="multipart/form-data"><h3>Add New Offer</h3><input name="title" placeholder="Barcelona → Islamabad" required><input name="subtitle" placeholder="Limited dates / special fare"><div class="form-2"><input name="origin" placeholder="BCN"><input name="destination" placeholder="ISB"></div><input name="airline" placeholder="Airline"><div class="form-2"><input name="price" type="number" step="0.01" placeholder="640"><select name="currency"><option>EUR</option><option>GBP</option><option>USD</option></select></div><input name="travel_dates" placeholder="30 Sep – 27 Oct"><input name="baggage" placeholder="40kg + 7kg"><input name="badge" placeholder="Today Only / Best Seller"><input type="file" name="image" accept="image/*"><label class="check"><input type="checkbox" name="featured" checked> Show on homepage</label><button class="btn btn-primary" name="save_offer">Publish Offer</button></form>
<div class="admin-card"><h3>Published Offers</h3><?php foreach($offers as $o):?><div class="admin-row"><div><b><?=h($o['title'])?></b><small><?=h($o['airline'])?> · <?=h($o['currency'])?> <?=h((string)$o['price'])?></small></div><a class="delete" href="?delete_offer=<?=$o['id']?>" onclick="return confirm('Delete?')">Delete</a></div><?php endforeach;?></div></div>
<?php elseif($tab==='certs'): ?><h1>Certificates</h1><div class="admin-grid"><form class="admin-card" method="post" enctype="multipart/form-data"><h3>Add Certificate</h3><input name="title" placeholder="Expedia TAAP Certificate" required><input name="issuer" placeholder="Expedia TAAP / TBO Academy"><input name="sort_order" type="number" value="0"><input type="file" name="cert_image" accept="image/*"><button class="btn btn-primary" name="save_cert">Upload Certificate</button></form><div class="admin-card"><?php foreach($certs as $c):?><div class="admin-row"><div><b><?=h($c['title'])?></b><small><?=h($c['issuer'])?></small></div><a class="delete" href="?delete_cert=<?=$c['id']?>">Delete</a></div><?php endforeach;?></div></div>
<?php else: ?><h1>Website Inquiries</h1><div class="admin-card table-wrap"><table><tr><th>Date</th><th>Name</th><th>Phone</th><th>Service</th><th>Message</th></tr><?php foreach($inq as $x):?><tr><td><?=h($x['created_at'])?></td><td><?=h($x['name'])?></td><td><?=h($x['phone'])?></td><td><?=h($x['service'])?></td><td><?=h($x['message'])?></td></tr><?php endforeach;?></table></div><?php endif;?>
</main></div></body></html>