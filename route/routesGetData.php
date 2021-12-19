<?php
include_once('../routes/connect.php');
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Credentials: true");
header('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');
header('Access-Control-Max-Age: 1000');
header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token , Authorization, X-Requested-With');
$rest_json = file_get_contents("php://input");
$_POST = json_decode($rest_json, true);

if (!empty($_POST['start_city_route'])) {
    $start_city_route = $_POST['start_city_route'];
    $query = "SELECT *
        FROM Routes WHERE start_city_route = '$start_city_route'";
} else if (!empty($_POST['destination_city_route'])) {
    $destination_city_route = $_POST['destination_city_route'];
    $query = "SELECT *
        FROM Routes WHERE destination_city_route = '$destination_city_route'";
} else if (!empty($_POST['start_city_route']) && !empty($_POST['destination_city_route'])) {
    $start_city_route = $_POST['start_city_route'];
    $destination_city_route = $_POST['destination_city_route'];
    $query = "SELECT *
        FROM Routes WHERE start_city_route = '$start_city_route' AND destination_city_route = '$destination_city_route'";
} else if (!empty($_POST['id_route'])) {
    $id_route = $_POST['id_route'];
    $query = "SELECT *
        FROM Routes WHERE id_route = '$id_route'";
} else {
    $query = "SELECT * FROM Routes";
}

$get = pg_query($connect, $query);

$data = array();

if (pg_num_rows($get) > 0) {
    while ($row = pg_fetch_assoc($get)) {
        $data[] = $row;
    }
    set_response(true, "Data Country is Found", $data);
} else {
    http_response_code(401);
    set_response(false, "Data Country is Not Found", $data);
}

function set_response($isSuccess, $message, $data)
{
    $result = array(
        'isSuccess' => $isSuccess,
        'message' => $message,
        'data' => $data
    );
    echo json_encode($result);
}
