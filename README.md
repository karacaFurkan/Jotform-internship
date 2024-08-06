These are the projects I developed during my internship at Jotform.

StressTester: 
This folder contains useful tools to test your website by getting GET requests by specified amounts. Create a localhost for it to work. Then go to this localhost using a web browser and start the test. 
Using this code on websites that do not belong to you may be considered illegal.
Ensuring legal use is the user's responsibility.
In this version, you have to specify the amount of time. Due to FPM rules, the code won't stop until the localhost is stopped.

StressTester-openswoole-version: 
This folder also contains tools similar to StressTester. But, this version uses the OpenSwoole library to overcome FPM behavior. Also, in this version, 
sending POST requests is possible. For POST requests, you need to specify the name of the field you want to post in attack_process.php.
In your machine, go to the folder containing these files, type php main.php to the terminal, and click index.html to reach the website tool. 
In this version stop button also works instead of specifying the time amount thanks to the OpenSwoole library.
Using this code on websites that do not belong to you may be considered illegal.
Ensuring legal use is the user's responsibility.

//Both of the tools above use the cURL library to send multiple parallel requests. Running these codes for too long may cause memory leaks and unexpected behaviors due to high memory allocation cURL uses. 

Async-Database:
This folder includes a prebuilt Rust extension and demo files. You can directly use the extension after specifying the fields in the lib.rs which must be the same as the columns in your database. This extension sends async queries to your database and returns immediately without waiting for the query result. Do not forget to build again if you are planning to change the fields.
