<?php
include_once('../routes/connect.php');
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Credentials: true");
header('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');
header('Access-Control-Max-Age: 1000');
header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token , Authorization, X-Requested-With');
$rest_json = file_get_contents("php://input");
$_POST = json_decode($rest_json, true);

if (!empty($_POST['company_type_plane'])) {
    $company_type_plane = $_POST['company_type_plane'];
    $query = "SELECT *
        FROM Plane WHERE company_plane = '$company_type_plane' OR type_plane = '$company_type_plane'";
} else if (!empty($_POST['id_plane'])) {
    $id_plane = $_POST['id_plane'];
    $query = "SELECT *
        FROM Plane WHERE id_plane = '$id_plane'";
} else {
    $query = "SELECT * FROM Plane";
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
