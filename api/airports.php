<?php
declare(strict_types=1);
header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: public, max-age=3600');
$q=trim((string)($_GET['q']??''));
if(mb_strlen($q)<2){echo json_encode(['results'=>[]]);exit;}
$rows=json_decode((string)file_get_contents(__DIR__.'/data/airports.json'),true);if(!is_array($rows))$rows=[];
$needle=mb_strtolower($q);$matches=[];
foreach($rows as $r){[$code,$city,$airport,$country]=$r;$hay=mb_strtolower($code.' '.$city.' '.$airport.' '.$country);if(mb_strpos($hay,$needle)!==false){$matches[]=['code'=>$code,'city'=>$city,'airport'=>$airport,'country'=>$country,'label'=>$city.' ('.$code.')'];}if(count($matches)>=10)break;}
echo json_encode(['results'=>$matches],JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);
