<?php
$host = "ec2-52-54-38-229.compute-1.amazonaws.com";
$port = "5432";
$user = "wmfhtezfzivfwq";
$password = "5d776626f9d867f4233f3263fca42c3e79c5271954e89a5f212c8ca0bf421fec";
$dbname = "dckkbpp3p1gt22";

$connection_string = "host={$host} port={$port} dbname={$dbname} user={$user} password={$password}";
$connect = pg_connect($connection_string);

// if (!$connect) {
//     echo "Database connection failed.";
// } else {
//     echo "Database connection success.";
// }
