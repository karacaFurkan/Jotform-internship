<?php

function syncQueryAccess($query){
        
        
        sleep(3);
        return "ID: 1, Username: , Phone: 
ID: 2, Username: , Phone: 
ID: 3, Username: , Phone: 
ID: 4, Username: , Phone: ";
}

function doSomethingThreeSeconds(){
        sleep(3);
}
$asyncDb = new AsyncDatabase();
var_dump($asyncDb);

$query_key = $asyncDb->initialize("q1");
$query_key2 = $asyncDb->initialize("q2");
while(true){
        $start_time = microtime(true);
        $query = $_POST['query'];
        
        $asyncDb2 = $asyncDb;
        $asyncDb->async_db_wrapper($query_key, $query);
        $query2 = $query;
        $asyncDb2->async_db_wrapper($query_key2, $query2);
        doSomethingThreeSeconds(); //this does not need query result
        while (strlen($asyncDb->get_query_result($query_key))== 0 || $asyncDb->get_query_result($query_key)[strlen($asyncDb->get_query_result($query_key))-1] != "\0") {}
        while (strlen($asyncDb2->get_query_result($query_key2)) == 0 || $asyncDb2->get_query_result($query_key2)[strlen($asyncDb2->get_query_result($query_key2))-1] != "\0") {}
        
        $result = $asyncDb->get_query_result($query_key);
        $result2 = $asyncDb2->get_query_result($query_key2);

        /*$result = syncQueryAccess($query);
        $result2 = "";*/
        
        $end_time = microtime(true);
        $elapsed_time = $end_time - $start_time;


        $full_result = $result . "\n" . $result2;


        header('Location: index.php?result=' . urlencode($full_result) . '&time=' . urlencode($elapsed_time));
        exit();
    
}
?>
