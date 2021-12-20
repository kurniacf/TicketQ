<?php
include_once('../routes/connect.php');
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Credentials: true");
header('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');
header('Access-Control-Max-Age: 1000');
header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token , Authorization, X-Requested-With');
$rest_json = file_get_contents("php://input");
$_POST = json_decode($rest_json, true);


if (!empty($_POST['id_schedule']) && !empty($_POST['id_customer'])) {
    $id_schedule = $_POST['id_schedule'];
    $id_customer = $_POST['id_customer'];

    $query = "SELECT id_account FROM Customer WHERE id_customer = '$id_customer'";
    $get = pg_query($connect, $query);
    $data = pg_fetch_row($get);
    $id_account = intval(array_pop($data));

    $query = "SELECT available_seat_schedule FROM Schedule WHERE id_schedule = '$id_schedule'";
    $get = pg_query($connect, $query);
    $data = pg_fetch_row($get);
    $available_seat_schedule = intval(array_pop($data));

    $query = "SELECT MAX(seat_number) FROM Transactions WHERE id_schedule = '$id_schedule'";
    $get = pg_query($connect, $query);
    $data = pg_fetch_row($get);
    $seat_number = intval(array_pop($data));

    if (!(isset($seat_number))) {
        $seat_number = 0;
    }

    if ($available_seat_schedule < $seat_number) {
        http_response_code(400);
        set_response(false, "Seat is Fully", "Sorry, Please Find Schedule!!");
    } else {
        $seat_number += 1;
        $query = "SELECT normal_price_schedule FROM Schedule WHERE id_schedule = '$id_schedule'";
        $get = pg_query($connect, $query);
        $data = pg_fetch_row($get);
        $normal_price_schedule = intval(array_pop($data));

        $query = "SELECT type_age_customer FROM Customer WHERE id_customer = '$id_customer'";
        $get = pg_query($connect, $query);
        $data = pg_fetch_row($get);
        $type_age_customer = array_pop($data);

        if ($type_age_customer == 'f') {
            $price_transactions = $normal_price_schedule - ($normal_price_schedule * 20 / 100);
            $price_transactions = (int)($price_transactions);
            $customer_child = 1;
            $customer_adult = 0;
        } else {
            $price_transactions = $normal_price_schedule;
            $price_transactions = (int)($price_transactions);
            $customer_adult = 1;
            $customer_child = 0;
        }

        $query = "INSERT INTO Transactions(id_schedule, id_customer, price_transactions, seat_number, customer_adult, customer_child) 
                VALUES ('$id_schedule', '$id_customer', '$price_transactions', '$seat_number', '$customer_adult', '$customer_child')";
        $insert = pg_query($connect, $query);

        $query = "SELECT id_transactions FROM Transactions WHERE id_schedule = '$id_schedule' AND id_customer = '$id_customer'";
        $get = pg_query($connect, $query);
        $data = pg_fetch_row($get);
        $id_transactions = intval(array_pop($data));

        $query = "SELECT id_contact FROM Customer WHERE id_customer = '$id_customer'";
        $get = pg_query($connect, $query);
        $data = pg_fetch_row($get);
        $id_contact = intval(array_pop($data));

        $query = "SELECT id_country FROM Contact WHERE id_contact = '$id_contact'";
        $get = pg_query($connect, $query);
        $data = pg_fetch_row($get);
        $id_country = intval(array_pop($data));

        $query = "SELECT id_route FROM Schedule WHERE id_schedule = '$id_schedule'";
        $get = pg_query($connect, $query);
        $data = pg_fetch_row($get);
        $id_route = array_pop($data);

        $query = "SELECT id_plane FROM Schedule WHERE id_schedule = '$id_schedule'";
        $get = pg_query($connect, $query);
        $data = pg_fetch_row($get);
        $id_plane = array_pop($data);

        $queryFinal = "SELECT 
                    Transactions.id_transactions, Transactions.booking_date, Transactions.price_transactions, Transactions.seat_number, Transactions.customer_adult, Transactions.customer_child,
                    Customer.id_customer, Customer.firstname_customer, Customer.lastname_customer, Customer.address_customer, Customer.type_age_customer, Customer.gender_customer, Customer.nationality_customer,
                    Account.id_account, Account.id_session_account, Account.name_account, Account.username_account, Account.email_account,
                    Contact.id_contact, Contact.email_contact, Contact.telp_contact,
                    Country.id_country, Country.name_country, Country.iso3_country, Country.phonecode_country,
                    Schedule.id_schedule, Schedule.id_plane, Schedule.id_route, Schedule.departure_schedule, Schedule.arrival_schedule, Schedule.available_seat_schedule, Schedule.normal_price_schedule,
                    Routes.start_city_route, Routes.destination_city_route, Routes.start_airport_route, Routes.destination_airport_route, 
                    Plane.capacity_plane, Plane.company_plane, Plane.type_plane
                    FROM Transactions, Account
                    JOIN Customer ON Customer.id_customer = '$id_customer'
                    JOIN Country ON Country.id_country = '$id_country'
                    JOIN Routes ON Routes.id_route = '$id_route'
                    JOIN Plane ON Plane.id_plane = '$id_plane'
                    JOIN Schedule ON Schedule.id_schedule = '$id_schedule'
        			JOIN Contact ON Contact.id_contact = '$id_contact'
                    WHERE Contact.id_country = '$id_country' AND Account.id_account = '$id_account' AND Transactions.id_transactions = '$id_transactions' AND Schedule.id_route = '$id_route' AND Schedule.id_plane = '$id_plane'";

        $getFinal = pg_query($connect, $queryFinal);
        $data = array();

        if (pg_num_rows($getFinal)) {
            while ($row = pg_fetch_assoc($getFinal)) {
                $data[] = $row;
            }
            set_response(true, "Transactions Success", $data);
        } else {
            http_response_code(400);
            set_response(false, "Transactions Failed", "Input Data is Wrong!");
        }
    }
} else {
    http_response_code(400);
    set_response(false, "Dont Empty!!", "Fill All Data");
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
