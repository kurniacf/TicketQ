<?php
include_once('../routes/connect.php');
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Credentials: true");
header('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');
header('Access-Control-Max-Age: 1000');
header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token , Authorization, X-Requested-With');
$rest_json = file_get_contents("php://input");
$_POST = json_decode($rest_json, true);

if (!empty($_POST['id_schedule'])) {
    $id_schedule = $_POST['id_schedule'];

    $query = "SELECT id_route FROM Schedule WHERE id_schedule = '$id_schedule'";
    $get = pg_query($connect, $query);
    $data = pg_fetch_row($get);
    $id_route = array_pop($data);

    $query = "SELECT id_plane FROM Schedule WHERE id_schedule = '$id_schedule'";
    $get = pg_query($connect, $query);
    $data = pg_fetch_row($get);
    $id_plane = array_pop($data);

    $query = "SELECT Schedule.id_schedule, Schedule.id_plane, Schedule.id_route, Schedule.departure_schedule, Schedule.arrival_schedule, Schedule.available_seat_schedule, Schedule.normal_price_schedule,
                Routes.start_city_route, Routes.destination_city_route, Routes.start_airport_route, Routes.destination_airport_route, 
                Plane.capacity_plane, Plane.company_plane, Plane.type_plane
                FROM Schedule
                JOIN Routes ON Routes.id_route = '$id_route'
                JOIN Plane ON Plane.id_plane = '$id_plane'
                WHERE Schedule.id_plane = '$id_plane' AND Schedule.id_route = '$id_route'";
} else if (!empty($_POST['id_route'])) {
    $id_route = $_POST['id_route'];

    $query = "SELECT Schedule.id_schedule, Schedule.id_plane, Schedule.id_route, Schedule.departure_schedule, Schedule.arrival_schedule, Schedule.available_seat_schedule, Schedule.normal_price_schedule,
                Routes.start_city_route, Routes.destination_city_route, Routes.start_airport_route, Routes.destination_airport_route, 
                Plane.capacity_plane, Plane.company_plane, Plane.type_plane
                FROM Schedule
                JOIN Routes ON Routes.id_route = '$id_route'
                JOIN Plane ON Plane.id_plane = Schedule.id_plane
                WHERE Schedule.id_plane = Plane.id_plane AND Schedule.id_route = '$id_route'";
} else if (!empty($_POST['id_plane'])) {
    $id_plane = $_POST['id_plane'];
    $query = "SELECT Schedule.id_schedule, Schedule.id_plane, Schedule.id_route, Schedule.departure_schedule, Schedule.arrival_schedule, Schedule.available_seat_schedule, Schedule.normal_price_schedule,
                Routes.start_city_route, Routes.destination_city_route, Routes.start_airport_route, Routes.destination_airport_route, 
                Plane.capacity_plane, Plane.company_plane, Plane.type_plane
                FROM Schedule
                JOIN Routes ON Routes.id_route = Schedule.id_route
                JOIN Plane ON Plane.id_plane = '$id_plane'
                WHERE Schedule.id_plane = '$id_plane' AND Schedule.id_route = Routes.id_route";
} else {
    $query = "SELECT Schedule.id_schedule, Schedule.id_plane, Schedule.id_route, Schedule.departure_schedule, Schedule.arrival_schedule, Schedule.available_seat_schedule, Schedule.normal_price_schedule,
                Routes.start_city_route, Routes.destination_city_route, Routes.start_airport_route, Routes.destination_airport_route, 
                Plane.capacity_plane, Plane.company_plane, Plane.type_plane
                FROM Schedule
                JOIN Routes ON Routes.id_route = Schedule.id_route
                JOIN Plane ON Plane.id_plane = Schedule.id_plane
                ";
}

$get = pg_query($connect, $query);
$data = array();

if (pg_num_rows($get) > 0) {
    while ($row = pg_fetch_assoc($get)) {
        $data[] = $row;
    }
    set_response(true, "Data Schedule is Found", $data);
} else {
    http_response_code(401);
    set_response(false, "Data Schedule is Not Found", $data);
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
