<?php
require_once __DIR__.'/config.php';
$error=''; $notice='';
if(isset($_GET['logout'])){session_destroy();header('Location: admin.php');exit;}
if($_SERVER['REQUEST_METHOD']==='POST' && isset($_POST['login'])){
 if(($_POST['username']??'')===ADMIN_USER && ($_POST['password']??'')===ADMIN_PASS){$_SESSION['admin']=true;header('Location: admin.php');exit;}
 $error='Incorrect username or password.';
}
if(empty($_SESSION['admin'])): ?>
<!doctype html><html><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><link rel="stylesheet" href="assets/css/style.css"><title>Admin Login</title></head><body class="admin-login">
<form class="login-card" method="post"><div class="brand-mark large">✈</div><h2>Mustafa Travels Admin</h2><?php if($error):?><div class="error"><?=h($error)?></div><?php endif;?><input name="username" placeholder="Username" required><input type="password" name="password" placeholder="Password" required><button class="btn btn-primary" name="login">Login</button><small>Supabase connected</small></form></body></html>
<?php exit; endif;

$tab=$_GET['tab']??'offers';
if(isset($_GET['delete_offer'])){sb_delete('offers','id=eq.'.(int)$_GET['delete_offer']);header('Location: admin.php');exit;}
if(isset($_GET['delete_umrah'])){sb_delete('umrah_packages','id=eq.'.(int)$_GET['delete_umrah']);header('Location: admin.php?tab=umrah');exit;}
if(isset($_GET['delete_hotel'])){sb_delete('hotel_offers','id=eq.'.(int)$_GET['delete_hotel']);header('Location: admin.php?tab=hotels');exit;}
if(isset($_GET['delete_cert'])){sb_delete('certificates','id=eq.'.(int)$_GET['delete_cert']);header('Location: admin.php?tab=certs');exit;}

if($_SERVER['REQUEST_METHOD']==='POST' && isset($_POST['save_offer'])){
 $notice=sb_insert('offers',[
  'title'=>$_POST['title'],'subtitle'=>$_POST['subtitle'],'offer_type'=>$_POST['offer_type'],'origin'=>$_POST['origin'],
  'destination'=>$_POST['destination'],'airline'=>$_POST['airline'],'price'=>(float)$_POST['price'],'currency'=>$_POST['currency'],
  'travel_dates'=>$_POST['travel_dates'],'baggage'=>$_POST['baggage'],'badge'=>$_POST['badge'],'image_url'=>upload_image('image'),
  'featured'=>!empty($_POST['featured']),'active'=>true
 ])?'Offer published successfully.':'Offer could not be published.';
}
if($_SERVER['REQUEST_METHOD']==='POST' && isset($_POST['save_umrah'])){
 $notice=sb_insert('umrah_packages',[
  'title'=>$_POST['title'],'package_code'=>$_POST['package_code'],'departure_city'=>$_POST['departure_city'],
  'travel_date'=>$_POST['travel_date'],'return_date'=>$_POST['return_date'],'duration'=>$_POST['duration'],'makkah_hotel'=>$_POST['makkah_hotel'],
  'makkah_nights'=>(int)$_POST['makkah_nights'],'makkah_distance'=>$_POST['makkah_distance'],'madinah_hotel'=>$_POST['madinah_hotel'],
  'madinah_nights'=>(int)$_POST['madinah_nights'],'madinah_distance'=>$_POST['madinah_distance'],'airline'=>$_POST['airline'],'baggage'=>$_POST['baggage'],
  'quad_price'=>(float)($_POST['quad_price']?:0),'triple_price'=>(float)($_POST['triple_price']?:0),'double_price'=>(float)($_POST['double_price']?:0),
  'single_price'=>(float)($_POST['single_price']?:0),'currency'=>$_POST['currency'],'description'=>$_POST['description'],'included'=>$_POST['included'],
  'not_included'=>$_POST['not_included'],'image_url'=>upload_image('umrah_image'),'featured'=>!empty($_POST['featured']),'active'=>true
 ])?'Umrah package published.':'Umrah package could not be published.';
}
if($_SERVER['REQUEST_METHOD']==='POST' && isset($_POST['save_hotel'])){
 $notice=sb_insert('hotel_offers',[
  'hotel_name'=>$_POST['hotel_name'],'city'=>$_POST['city'],'country'=>$_POST['country'],'category'=>$_POST['category'],
  'distance_from_haram'=>$_POST['distance_from_haram'],'room_type'=>$_POST['room_type'],'board_basis'=>$_POST['board_basis'],
  'check_in'=>$_POST['check_in'],'check_out'=>$_POST['check_out'],'price'=>(float)$_POST['price'],'currency'=>$_POST['currency'],
  'description'=>$_POST['description'],'image_url'=>upload_image('hotel_image'),'featured'=>!empty($_POST['featured']),'active'=>true
 ])?'Hotel offer published.':'Hotel offer could not be published.';
}
if($_SERVER['REQUEST_METHOD']==='POST' && isset($_POST['save_cert'])){
 $notice=sb_insert('certificates',[
  'title'=>$_POST['title'],'issuer'=>$_POST['issuer'],'description'=>$_POST['description'],'image_url'=>upload_image('cert_image'),
  'sort_order'=>(int)$_POST['sort_order'],'active'=>true
 ])?'Certificate saved.':'Certificate could not be saved.';
}
if($_SERVER['REQUEST_METHOD']==='POST' && isset($_POST['save_settings'])){
 $existing=sb_select('site_settings','select=id&limit=1');
 $row=['company_name'=>$_POST['company_name'],'owner_name'=>$_POST['owner_name'],'phone_1'=>$_POST['phone_1'],'phone_2'=>$_POST['phone_2'],
 'phone_3'=>$_POST['phone_3'],'whatsapp'=>$_POST['whatsapp'],'email'=>$_POST['email'],'website'=>$_POST['website'],'address'=>$_POST['address'],
 'experience_years'=>(int)$_POST['experience_years'],'happy_clients'=>(int)$_POST['happy_clients'],'umrah_packages_sold'=>(int)$_POST['umrah_packages_sold'],
 'about_text'=>$_POST['about_text']];
 $notice=( $existing ? sb_update('site_settings','id=eq.'.(int)$existing[0]['id'],$row) : sb_insert('site_settings',$row) )?'Website settings updated.':'Settings could not be updated.';
}

$offers=sb_select('offers','select=*&order=id.desc');
$umrah=sb_select('umrah_packages','select=*&order=id.desc');
$hotels=sb_select('hotel_offers','select=*&order=id.desc');
$certs=sb_select('certificates','select=*&order=sort_order.asc,id.desc');
$inq=sb_select('inquiries','select=*&order=id.desc&limit=100');
$settings=sb_select('site_settings','select=*&limit=1'); $s=$settings[0]??[];
?>
<!doctype html><html><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><link rel="stylesheet" href="assets/css/style.css"><title>Mustafa Admin</title></head><body class="admin-body">
<div class="admin-shell"><aside><h2>Mustafa Admin</h2>
<a class="<?=$tab==='offers'?'active':''?>" href="admin.php">Daily Offers</a><a class="<?=$tab==='umrah'?'active':''?>" href="?tab=umrah">Umrah Packages</a>
<a class="<?=$tab==='hotels'?'active':''?>" href="?tab=hotels">Hotel Offers</a><a class="<?=$tab==='certs'?'active':''?>" href="?tab=certs">Certificates</a>
<a class="<?=$tab==='inq'?'active':''?>" href="?tab=inq">Inquiries</a><a class="<?=$tab==='settings'?'active':''?>" href="?tab=settings">Website Settings</a>
<a href="index.php" target="_blank">View Website</a><a href="?logout=1">Logout</a></aside><main>
<?php if($notice):?><div class="success"><?=h($notice)?></div><?php endif;?>

<?php if($tab==='offers'): ?>
<h1>Daily Offers</h1><div class="admin-grid"><form class="admin-card" method="post" enctype="multipart/form-data"><h3>Publish New Offer</h3>
<select name="offer_type"><option>Flight</option><option>Hotel</option><option>Umrah</option><option>Visa</option><option>Other</option></select>
<input name="title" placeholder="Barcelona → Islamabad" required><input name="subtitle" placeholder="Limited dates / special fare"><div class="form-2"><input name="origin" placeholder="BCN"><input name="destination" placeholder="ISB"></div>
<input name="airline" placeholder="Airline"><div class="form-2"><input name="price" type="number" step="0.01" placeholder="640"><select name="currency"><option>EUR</option><option>GBP</option><option>USD</option><option>SAR</option></select></div>
<input name="travel_dates" placeholder="30 Sep – 27 Oct"><input name="baggage" placeholder="40kg + 7kg"><input name="badge" placeholder="Today Only / Best Seller"><input type="file" name="image" accept="image/*">
<label class="check"><input type="checkbox" name="featured" checked> Show on homepage</label><button class="btn btn-primary" name="save_offer">Publish Offer</button></form>
<div class="admin-card"><h3>Published Offers</h3><?php foreach($offers as $o):?><div class="admin-row"><div><b><?=h($o['title'])?></b><small><?=h($o['airline'])?> · <?=h($o['currency'])?> <?=h((string)$o['price'])?></small></div><a class="delete" href="?delete_offer=<?=$o['id']?>" onclick="return confirm('Delete?')">Delete</a></div><?php endforeach;?></div></div>

<?php elseif($tab==='umrah'): ?>
<h1>Umrah Packages</h1><div class="admin-grid"><form class="admin-card" method="post" enctype="multipart/form-data"><h3>Add Umrah Package</h3>
<input name="title" placeholder="Economy Umrah Package" required><div class="form-2"><input name="package_code" placeholder="MTU-001"><input name="departure_city" placeholder="Barcelona"></div>
<div class="form-2"><input name="travel_date" placeholder="Departure date"><input name="return_date" placeholder="Return date"></div><input name="duration" placeholder="10 Days / 8 Nights">
<input name="airline" placeholder="Airline"><input name="baggage" placeholder="1x23kg + 7kg"><input name="makkah_hotel" placeholder="Makkah hotel"><div class="form-2"><input name="makkah_nights" type="number" placeholder="Makkah nights"><input name="makkah_distance" placeholder="750m / Shuttle"></div>
<input name="madinah_hotel" placeholder="Madinah hotel"><div class="form-2"><input name="madinah_nights" type="number" placeholder="Madinah nights"><input name="madinah_distance" placeholder="700m"></div>
<div class="form-2"><input name="quad_price" type="number" step="0.01" placeholder="Quad price"><input name="triple_price" type="number" step="0.01" placeholder="Triple price"></div><div class="form-2"><input name="double_price" type="number" step="0.01" placeholder="Double price"><input name="single_price" type="number" step="0.01" placeholder="Single price"></div>
<select name="currency"><option>EUR</option><option>GBP</option><option>USD</option></select><textarea name="description" placeholder="Package description"></textarea><textarea name="included" placeholder="Included - one item per line"></textarea><textarea name="not_included" placeholder="Not included - one item per line"></textarea>
<input type="file" name="umrah_image" accept="image/*"><label class="check"><input type="checkbox" name="featured"> Featured package</label><button class="btn btn-primary" name="save_umrah">Publish Umrah Package</button></form>
<div class="admin-card"><h3>Current Packages</h3><?php foreach($umrah as $u):?><div class="admin-row"><div><b><?=h($u['title'])?></b><small><?=h($u['travel_date'])?> · Quad <?=h((string)$u['quad_price'])?></small></div><a class="delete" href="?tab=umrah&delete_umrah=<?=$u['id']?>">Delete</a></div><?php endforeach;?></div></div>

<?php elseif($tab==='hotels'): ?>
<h1>Hotel Offers</h1><div class="admin-grid"><form class="admin-card" method="post" enctype="multipart/form-data"><h3>Add Hotel Deal</h3>
<input name="hotel_name" placeholder="Hotel name" required><div class="form-2"><input name="city" placeholder="Makkah"><input name="country" placeholder="Saudi Arabia"></div><div class="form-2"><input name="category" placeholder="3 Star"><input name="distance_from_haram" placeholder="750m / Shuttle"></div>
<div class="form-2"><input name="room_type" placeholder="Double / Triple"><input name="board_basis" placeholder="Room Only / Breakfast"></div><div class="form-2"><input name="check_in" placeholder="Check-in"><input name="check_out" placeholder="Check-out"></div>
<div class="form-2"><input name="price" type="number" step="0.01" placeholder="Price"><select name="currency"><option>EUR</option><option>SAR</option><option>USD</option></select></div><textarea name="description" placeholder="Hotel offer details"></textarea>
<input type="file" name="hotel_image" accept="image/*"><label class="check"><input type="checkbox" name="featured"> Featured</label><button class="btn btn-primary" name="save_hotel">Publish Hotel</button></form>
<div class="admin-card"><h3>Hotel Offers</h3><?php foreach($hotels as $x):?><div class="admin-row"><div><b><?=h($x['hotel_name'])?></b><small><?=h($x['city'])?> · <?=h($x['currency'])?> <?=h((string)$x['price'])?></small></div><a class="delete" href="?tab=hotels&delete_hotel=<?=$x['id']?>">Delete</a></div><?php endforeach;?></div></div>

<?php elseif($tab==='certs'): ?>
<h1>Certificates</h1><div class="admin-grid"><form class="admin-card" method="post" enctype="multipart/form-data"><h3>Add Certificate</h3>
<input name="title" placeholder="Expedia TAAP Certificate" required><input name="issuer" placeholder="Expedia TAAP / TBO Academy"><textarea name="description" placeholder="Certificate description"></textarea><input name="sort_order" type="number" value="0"><input type="file" name="cert_image" accept="image/*"><button class="btn btn-primary" name="save_cert">Save Certificate</button></form>
<div class="admin-card"><?php foreach($certs as $c):?><div class="admin-row"><div><b><?=h($c['title'])?></b><small><?=h($c['issuer'])?></small></div><a class="delete" href="?tab=certs&delete_cert=<?=$c['id']?>">Delete</a></div><?php endforeach;?></div></div>

<?php elseif($tab==='inq'): ?>
<h1>Website Inquiries</h1><div class="admin-card table-wrap"><table><tr><th>Date</th><th>Name</th><th>Phone</th><th>Service</th><th>Message</th></tr><?php foreach($inq as $x):?><tr><td><?=h($x['created_at']??'')?></td><td><?=h($x['name']??'')?></td><td><?=h($x['phone']??'')?></td><td><?=h($x['service']??'')?></td><td><?=h($x['message']??'')?></td></tr><?php endforeach;?></table></div>

<?php else: ?>
<h1>Website Settings</h1><form class="admin-card" method="post"><div class="form-2"><input name="company_name" value="<?=h($s['company_name']??SITE_NAME)?>" placeholder="Company Name"><input name="owner_name" value="<?=h($s['owner_name']??'Ghulam Mustafa Haidry')?>" placeholder="Owner"></div>
<div class="form-2"><input name="phone_1" value="<?=h($s['phone_1']??PHONE1)?>"><input name="phone_2" value="<?=h($s['phone_2']??PHONE2)?>"></div><div class="form-2"><input name="phone_3" value="<?=h($s['phone_3']??PHONE3)?>"><input name="whatsapp" value="<?=h($s['whatsapp']??PHONE2)?>" placeholder="WhatsApp"></div>
<div class="form-2"><input name="email" value="<?=h($s['email']??EMAIL)?>"><input name="website" value="<?=h($s['website']??WEBSITE)?>"></div><input name="address" value="<?=h($s['address']??ADDRESS)?>">
<div class="form-2"><input name="experience_years" type="number" value="<?=h((string)($s['experience_years']??8))?>"><input name="happy_clients" type="number" value="<?=h((string)($s['happy_clients']??10000))?>"></div>
<input name="umrah_packages_sold" type="number" value="<?=h((string)($s['umrah_packages_sold']??500))?>"><textarea name="about_text" rows="6"><?=h($s['about_text']??'')?></textarea><button class="btn btn-primary" name="save_settings">Save Website Settings</button></form>
<?php endif;?>
</main></div></body></html>