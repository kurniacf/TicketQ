<?php
include_once('../routes/connect.php');
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Credentials: true");
header('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');
header('Access-Control-Max-Age: 1000');
header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token , Authorization, X-Requested-With');
$rest_json = file_get_contents("php://input");
$_POST = json_decode($rest_json, true);

if (!empty($_POST['id_schedule']) && !empty($_POST['id_account'])) {
    $id_schedule = $_POST['id_schedule'];
    $id_account = $_POST['id_account'];
    $query =  "SELECT DISTINCT
                    Transactions.id_transactions, Transactions.booking_date, Transactions.price_transactions, Transactions.seat_number, Transactions.customer_adult, Transactions.customer_child,
                    Customer.id_customer, Customer.firstname_customer, Customer.lastname_customer, Customer.address_customer, Customer.type_age_customer, Customer.gender_customer, Customer.nationality_customer,
                    Account.id_account, Account.id_session_account, Account.name_account, Account.username_account, Account.email_account,
                    Contact.id_contact, Contact.email_contact, Contact.telp_contact,
                    Country.id_country, Country.name_country, Country.iso3_country, Country.phonecode_country,
                    Schedule.id_schedule, Schedule.id_plane, Schedule.id_route, Schedule.departure_schedule, Schedule.arrival_schedule, Schedule.available_seat_schedule, Schedule.normal_price_schedule,
                    Routes.start_city_route, Routes.destination_city_route, Routes.start_airport_route, Routes.destination_airport_route, 
                    Plane.capacity_plane, Plane.company_plane, Plane.type_plane
                    FROM Transactions 
                    JOIN Customer ON Customer.id_customer = Transactions.id_customer
                    JOIN Account ON Account.id_account = '$id_account'
                    JOIN Contact ON Contact.id_contact = Customer.id_contact
                    JOIN Country ON Country.id_country = Contact.id_country
                    JOIN Schedule ON Schedule.id_schedule = '$id_schedule'
                    JOIN Plane ON Plane.id_plane = Schedule.id_plane
                    JOIN Routes ON Routes.id_route = Schedule.id_route
                    WHERE Transactions.id_schedule = '$id_schedule' AND Customer.id_account = '$id_account''
                    ";
} else if (!empty($_POST['id_schedule']) && !empty($_POST['id_customer'])) {
    $id_schedule = $_POST['id_schedule'];
    $id_customer = $_POST['id_customer'];
    $query =  "SELECT DISTINCT
                    Transactions.id_transactions, Transactions.booking_date, Transactions.price_transactions, Transactions.seat_number, Transactions.customer_adult, Transactions.customer_child,
                    Customer.id_customer, Customer.firstname_customer, Customer.lastname_customer, Customer.address_customer, Customer.type_age_customer, Customer.gender_customer, Customer.nationality_customer,
                    Account.id_account, Account.id_session_account, Account.name_account, Account.username_account, Account.email_account,
                    Contact.id_contact, Contact.email_contact, Contact.telp_contact,
                    Country.id_country, Country.name_country, Country.iso3_country, Country.phonecode_country,
                    Schedule.id_schedule, Schedule.id_plane, Schedule.id_route, Schedule.departure_schedule, Schedule.arrival_schedule, Schedule.available_seat_schedule, Schedule.normal_price_schedule,
                    Routes.start_city_route, Routes.destination_city_route, Routes.start_airport_route, Routes.destination_airport_route, 
                    Plane.capacity_plane, Plane.company_plane, Plane.type_plane
                    FROM Transactions 
                    JOIN Customer ON Customer.id_customer = '$id_customer'
                    JOIN Account ON Account.id_account = Customer.id_account
                    JOIN Contact ON Contact.id_contact = Customer.id_contact
                    JOIN Country ON Country.id_country = Contact.id_country
                    JOIN Schedule ON Schedule.id_schedule = '$id_schedule'
                    JOIN Plane ON Plane.id_plane = Schedule.id_plane
                    JOIN Routes ON Routes.id_route = Schedule.id_route
                    WHERE Transactions.id_schedule = '$id_schedule' AND Transactions.id_customer = '$id_customer'
                    ";
} else if (!empty($_POST['id_schedule'])) {
    $id_schedule = $_POST['id_schedule'];
    $query =  "SELECT DISTINCT
                    Transactions.id_transactions, Transactions.booking_date, Transactions.price_transactions, Transactions.seat_number, Transactions.customer_adult, Transactions.customer_child,
                    Customer.id_customer, Customer.firstname_customer, Customer.lastname_customer, Customer.address_customer, Customer.type_age_customer, Customer.gender_customer, Customer.nationality_customer,
                    Account.id_account, Account.id_session_account, Account.name_account, Account.username_account, Account.email_account,
                    Contact.id_contact, Contact.email_contact, Contact.telp_contact,
                    Country.id_country, Country.name_country, Country.iso3_country, Country.phonecode_country,
                    Schedule.id_schedule, Schedule.id_plane, Schedule.id_route, Schedule.departure_schedule, Schedule.arrival_schedule, Schedule.available_seat_schedule, Schedule.normal_price_schedule,
                    Routes.start_city_route, Routes.destination_city_route, Routes.start_airport_route, Routes.destination_airport_route, 
                    Plane.capacity_plane, Plane.company_plane, Plane.type_plane
                    FROM Transactions 
                    JOIN Customer ON Customer.id_customer = Transactions.id_customer
                    JOIN Account ON Account.id_account = Customer.id_account
                    JOIN Contact ON Contact.id_contact = Customer.id_contact
                    JOIN Country ON Country.id_country = Contact.id_country
                    JOIN Schedule ON Schedule.id_schedule = '$id_schedule'
                    JOIN Plane ON Plane.id_plane = Schedule.id_plane
                    JOIN Routes ON Routes.id_route = Schedule.id_route
                    WHERE Transactions.id_schedule = '$id_schedule'
                    ";
} else if (!empty($_POST['id_customer'])) {
    $id_customer = $_POST['id_customer'];
    $query =  "SELECT DISTINCT
                    Transactions.id_transactions, Transactions.booking_date, Transactions.price_transactions, Transactions.seat_number, Transactions.customer_adult, Transactions.customer_child,
                    Customer.id_customer, Customer.firstname_customer, Customer.lastname_customer, Customer.address_customer, Customer.type_age_customer, Customer.gender_customer, Customer.nationality_customer,
                    Account.id_account, Account.id_session_account, Account.name_account, Account.username_account, Account.email_account,
                    Contact.id_contact, Contact.email_contact, Contact.telp_contact,
                    Country.id_country, Country.name_country, Country.iso3_country, Country.phonecode_country,
                    Schedule.id_schedule, Schedule.id_plane, Schedule.id_route, Schedule.departure_schedule, Schedule.arrival_schedule, Schedule.available_seat_schedule, Schedule.normal_price_schedule,
                    Routes.start_city_route, Routes.destination_city_route, Routes.start_airport_route, Routes.destination_airport_route, 
                    Plane.capacity_plane, Plane.company_plane, Plane.type_plane
                    FROM Transactions 
                    JOIN Customer ON Customer.id_customer = '$id_customer'
                    JOIN Account ON Account.id_account = Customer.id_account
                    JOIN Contact ON Contact.id_contact = Customer.id_contact
                    JOIN Country ON Country.id_country = Contact.id_country
                    JOIN Schedule ON Schedule.id_schedule = Transactions.id_schedule
                    JOIN Plane ON Plane.id_plane = Schedule.id_plane
                    JOIN Routes ON Routes.id_route = Schedule.id_route
                    WHERE Transactions.id_customer = '$id_customer'
                    ";
} else if (!empty($_POST['id_account'])) {
    $id_account = $_POST['id_account'];

    $query =  "SELECT DISTINCT
                    Transactions.id_transactions, Transactions.booking_date, Transactions.price_transactions, Transactions.seat_number, Transactions.customer_adult, Transactions.customer_child,
                    Customer.id_customer, Customer.firstname_customer, Customer.lastname_customer, Customer.address_customer, Customer.type_age_customer, Customer.gender_customer, Customer.nationality_customer,
                    Account.id_account, Account.id_session_account, Account.name_account, Account.username_account, Account.email_account,
                    Contact.id_contact, Contact.email_contact, Contact.telp_contact,
                    Country.id_country, Country.name_country, Country.iso3_country, Country.phonecode_country,
                    Schedule.id_schedule, Schedule.id_plane, Schedule.id_route, Schedule.departure_schedule, Schedule.arrival_schedule, Schedule.available_seat_schedule, Schedule.normal_price_schedule,
                    Routes.start_city_route, Routes.destination_city_route, Routes.start_airport_route, Routes.destination_airport_route, 
                    Plane.capacity_plane, Plane.company_plane, Plane.type_plane
                    FROM Transactions 
                    JOIN Customer ON Customer.id_customer = Transactions.id_customer
                    JOIN Account ON Account.id_account = '$id_account'
                    JOIN Contact ON Contact.id_contact = Customer.id_contact
                    JOIN Country ON Country.id_country = Contact.id_country
                    JOIN Schedule ON Schedule.id_schedule = Transactions.id_schedule
                    JOIN Plane ON Plane.id_plane = Schedule.id_plane
                    JOIN Routes ON Routes.id_route = Schedule.id_route
                    WHERE Customer.id_account = '$id_account'
                    ";
} else {
    $query =  "SELECT 
                    Transactions.id_transactions, Transactions.booking_date, Transactions.price_transactions, Transactions.seat_number, Transactions.customer_adult, Transactions.customer_child,
                    Customer.id_customer, Customer.firstname_customer, Customer.lastname_customer, Customer.address_customer, Customer.type_age_customer, Customer.gender_customer, Customer.nationality_customer,
                    Account.id_account, Account.id_session_account, Account.name_account, Account.username_account, Account.email_account,
                    Contact.id_contact, Contact.email_contact, Contact.telp_contact,
                    Country.id_country, Country.name_country, Country.iso3_country, Country.phonecode_country,
                    Schedule.id_schedule, Schedule.id_plane, Schedule.id_route, Schedule.departure_schedule, Schedule.arrival_schedule, Schedule.available_seat_schedule, Schedule.normal_price_schedule,
                    Routes.start_city_route, Routes.destination_city_route, Routes.start_airport_route, Routes.destination_airport_route, 
                    Plane.capacity_plane, Plane.company_plane, Plane.type_plane
                    FROM Transactions 
                    JOIN Customer ON Customer.id_customer = Transactions.id_customer
                    JOIN Account ON Account.id_account = Customer.id_account
                    JOIN Contact ON Contact.id_contact = Customer.id_contact
                    JOIN Country ON Country.id_country = Contact.id_country
                    JOIN Schedule ON Schedule.id_schedule = Transactions.id_schedule
                    JOIN Plane ON Plane.id_plane = Schedule.id_plane
                    JOIN Routes ON Routes.id_route = Schedule.id_route
                    ";
}

$get = pg_query($connect, $query);
$data = array();

if (pg_num_rows($get) > 0) {
    while ($row = pg_fetch_assoc($get)) {
        $data[] = $row;
    }
    set_response(true, "Data is Found", $data);
} else {
    http_response_code(401);
    set_response(false, "Data is Not Found", $data);
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
