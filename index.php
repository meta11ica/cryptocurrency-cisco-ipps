<?php header("Content-Type: text/xml");
echo '<?xml version ="1.0" encoding="utf-8"?>'; ?>

<CiscoIPPhoneText>
<?php
$base_url = "http://faucet.bitcoin.com.tn/ipps";
if (isset($_GET["coin"])) {
   $coin = $_GET["coin"];
}
else {
   $coin = "BTC";
}
$ctime = 60;
$purge_cache=false;
$request_limit=100;

        $cache_file = dirname(__FILE__) . '/'.$coin.'.json';
        $expires    = time() - $ctime;

        if (!file_exists($cache_file))
            die("Cache file is missing: $cache_file");

        if (filectime($cache_file) < $expires || file_get_contents($cache_file) == '' || $purge_cache && intval($_SESSION['views']) <= $request_limit) {
			$api_results = '[';
            $httpget = json_decode(file_get_contents('https://api.cryptonator.com/api/ticker/'.$coin.'-usd'),true);
			$from = $httpget['ticker']['base'];
			$to = $httpget['ticker']['target'];
			$value = $httpget['ticker']['price'];
			$api_results = json_encode(array("from" => $from, "to" => $to, "value" => $value));
			
            if ($api_results)
                file_put_contents($cache_file, $api_results);
            else
                unlink($cache_file);
        } else {

            $api_results = file_get_contents($cache_file);
            $request_type = 'JSON';
        }

$bvalues = json_decode($api_results,true);

$value = $bvalues['value'];


echo '<Title>'.$coin.' Exchange Rate:</Title>'."\r\n";
echo '<Text>1 '.$coin.' equals '.$value.' USD.</Text>'."\r\n";
echo '<SoftKeyItem>'."\r\n";
echo '<Name>'.$coin.'</Name>'."\r\n";
echo '<URL>'.$base_url.'/select.php</URL>'."\r\n";
echo '<Position>1</Position>'."\r\n";
echo '</SoftKeyItem>'."\r\n";


?>
<SoftKeyItem>
<Name>Exit</Name>
<URL>SoftKey:Exit</URL>
<Position>2</Position>
</SoftKeyItem>
</CiscoIPPhoneText>