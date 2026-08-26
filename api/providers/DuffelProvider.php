<?php
declare(strict_types=1);
require_once __DIR__.'/FlightProviderInterface.php';
final class DuffelProvider implements FlightProviderInterface {
    private string $apiKey;
    public function __construct(){ $this->apiKey=getenv('DUFFEL_API_KEY')?:''; }
    public function code(): string { return 'duffel'; }
    private function request(string $endpoint,string $method='GET',?array $payload=null): array {
        if($this->apiKey==='') return ['ok'=>false,'error'=>'DUFFEL_API_KEY is missing.','status'=>0,'data'=>[]];
        $ch=curl_init('https://api.duffel.com'.$endpoint);
        curl_setopt_array($ch,[CURLOPT_RETURNTRANSFER=>true,CURLOPT_CUSTOMREQUEST=>$method,CURLOPT_HTTPHEADER=>['Authorization: Bearer '.$this->apiKey,'Accept: application/json','Accept-Encoding: gzip','Content-Type: application/json','Duffel-Version: v2'],CURLOPT_ENCODING=>'',CURLOPT_CONNECTTIMEOUT=>15,CURLOPT_TIMEOUT=>55]);
        if($payload!==null) curl_setopt($ch,CURLOPT_POSTFIELDS,json_encode($payload,JSON_UNESCAPED_SLASHES));
        $raw=curl_exec($ch);$status=(int)curl_getinfo($ch,CURLINFO_HTTP_CODE);$err=curl_error($ch);curl_close($ch);
        if($raw===false) return ['ok'=>false,'error'=>$err?:'Could not connect to Duffel.','status'=>$status,'data'=>[]];
        $json=json_decode((string)$raw,true); if(!is_array($json))$json=[];
        if($status<200||$status>=300){$msg=$json['errors'][0]['message']??$json['errors'][0]['title']??('Duffel HTTP '.$status);return ['ok'=>false,'error'=>$msg,'status'=>$status,'data'=>$json];}
        return ['ok'=>true,'error'=>'','status'=>$status,'data'=>$json];
    }
    public function search(array $c): array {
        $s=[['origin'=>$c['origin'],'destination'=>$c['destination'],'departure_date'=>$c['departure']]];
        if(($c['trip_type']??'round')==='round'&&!empty($c['return_date']))$s[]=['origin'=>$c['destination'],'destination'=>$c['origin'],'departure_date'=>$c['return_date']];
        $p=[]; for($i=0;$i<(int)$c['adults'];$i++)$p[]=['age'=>30]; for($i=0;$i<(int)$c['children'];$i++)$p[]=['age'=>8]; for($i=0;$i<(int)$c['infants'];$i++)$p[]=['age'=>1];
        $api=$this->request('/air/offer_requests?return_offers=true&supplier_timeout=20000','POST',['data'=>['slices'=>$s,'passengers'=>$p,'cabin_class'=>$c['cabin']]]);
        if(!$api['ok'])return $api; return ['ok'=>true,'error'=>'','status'=>$api['status'],'offers'=>$api['data']['data']['offers']??[]];
    }
    public function getOffer(string $offerId): array {
        $api=$this->request('/air/offers/'.rawurlencode($offerId).'?return_available_services=true');
        if(!$api['ok'])return $api; return ['ok'=>true,'error'=>'','status'=>$api['status'],'offer'=>$api['data']['data']??[]];
    }
}
