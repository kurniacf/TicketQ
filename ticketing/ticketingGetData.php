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
    $id_discount = $_POST['id_discount'];
    $id_fee = $_POST['id_fee'];
    $id_schedule = $_POST['id_schedule'];
    $id_account = $_POST['id_account'];

    $query = "SELECT SUM(price_transactions) FROM Transactions 
                JOIN Customer ON Customer.id_account = '$id_account'
                WHERE id_schedule = '$id_schedule' AND Transactions.id_customer = Customer.id_customer";
    $get = pg_query($connect, $query);
    $data = pg_fetch_row($get);
    $total_price_transactions = intval(array_pop($data));

    $query = "SELECT SUM(customer_adult) FROM Transactions 
                JOIN Customer ON Customer.id_account = '$id_account'
                WHERE id_schedule = '$id_schedule' AND Transactions.id_customer = Customer.id_customer";
    $get = pg_query($connect, $query);
    $data = pg_fetch_row($get);
    $total_customer_adult = intval(array_pop($data));

    $query = "SELECT SUM(customer_child) FROM Transactions 
                JOIN Customer ON Customer.id_account = '$id_account'
                WHERE id_schedule = '$id_schedule' AND Transactions.id_customer = Customer.id_customer";
    $get = pg_query($connect, $query);
    $data = pg_fetch_row($get);
    $total_customer_child = intval(array_pop($data));

    $query = "SELECT id_transactions FROM Transactions 
                JOIN Customer ON Customer.id_account = '$id_account'
                WHERE Transactions.id_schedule = '$id_schedule' AND Transactions.id_customer = Customer.id_customer";
    $get = pg_query($connect, $query);
    $data = pg_fetch_row($get);
    $id_transactions = intval(array_pop($data));

    $query = "SELECT * FROM Ticketing 
                WHERE id_schedule = '$id_schedule' AND id_account = '$id_account'";
    $get = pg_query($connect, $query);

    if (pg_num_rows($get)) {
        if ($id_discount && $id_fee) {
            $query = "SELECT number_discount FROM Discount 
                WHERE id_discount = '$id_discount'";
            $get = pg_query($connect, $query);
            $data = pg_fetch_row($get);
            $number_discount = intval(array_pop($data));

            $query = "SELECT number_fee FROM Additional_Fee 
                WHERE id_fee = '$id_fee'";
            $get = pg_query($connect, $query);
            $data = pg_fetch_row($get);
            $number_fee = intval(array_pop($data));

            $total_price_transactions = $total_price_transactions - ($total_price_transactions * $number_discount / 100);
            $total_price_transactions = (int)($total_price_transactions);
            $total_price_transactions = $total_price_transactions + ($total_price_transactions * $number_fee / 100);
            $total_price_transactions = (int)($total_price_transactions);

            $query = "UPDATE Ticketing set 
                    id_discount = '$id_discount', id_fee = '$id_fee', total_price_transactions = '$total_price_transactions', total_customer_adult = '$total_customer_adult', total_customer_child = '$total_customer_child' 
                    WHERE id_schedule = '$id_schedule' AND id_account = '$id_account'";
            $update = pg_query($connect, $query);
        } else if ($id_discount) {
            $query = "SELECT number_discount FROM Discount 
                WHERE id_discount = '$id_discount'";
            $get = pg_query($connect, $query);
            $data = pg_fetch_row($get);
            $number_discount = intval(array_pop($data));
            $total_price_transactions = $total_price_transactions - ($total_price_transactions * $number_discount / 100);
            $total_price_transactions = (int)($total_price_transactions);

            $query = "UPDATE Ticketing set 
                    id_discount = '$id_discount', id_fee = null, total_price_transactions = '$total_price_transactions', total_customer_adult = '$total_customer_adult', total_customer_child = '$total_customer_child' 
                    WHERE id_schedule = '$id_schedule' AND id_account = '$id_account'";
            $update = pg_query($connect, $query);
        } else if ($id_fee) {
            $query = "SELECT number_fee FROM Additional_Fee 
                WHERE id_fee = '$id_fee'";
            $get = pg_query($connect, $query);
            $data = pg_fetch_row($get);
            $number_fee = intval(array_pop($data));
            $total_price_transactions = $total_price_transactions + ($total_price_transactions * $number_fee / 100);
            $total_price_transactions = (int)($total_price_transactions);

            $query = "UPDATE Ticketing set 
                    id_discount = null, id_fee = '$id_fee', total_price_transactions = '$total_price_transactions', total_customer_adult = '$total_customer_adult', total_customer_child = '$total_customer_child' 
                    WHERE id_schedule = '$id_schedule' AND id_account = '$id_account'";
            $update = pg_query($connect, $query);
        }

        $query =  "SELECT DISTINCT
                    Ticketing.id_ticketing, Ticketing.id_discount, Ticketing.id_fee, Ticketing.total_price_transactions, Ticketing.total_customer_adult, Ticketing.total_customer_child,
                    Transactions.id_transactions, Transactions.booking_date, Transactions.price_transactions, Transactions.customer_adult, Transactions.customer_child,
                    Customer.id_customer, Customer.firstname_customer, Customer.lastname_customer, Customer.address_customer, Customer.type_age_customer, Customer.gender_customer, Customer.nationality_customer,
                    Account.id_account, Account.id_session_account, Account.name_account, Account.username_account, Account.email_account,
                    Contact.id_contact, Contact.email_contact, Contact.telp_contact,
                    Country.id_country, Country.name_country, Country.iso3_country, Country.phonecode_country,
                    Schedule.id_schedule, Schedule.id_plane, Schedule.id_route, Schedule.departure_schedule, Schedule.arrival_schedule, Schedule.available_seat_schedule, Schedule.normal_price_schedule,
                    Routes.start_city_route, Routes.destination_city_route, Routes.start_airport_route, Routes.destination_airport_route, 
                    Plane.capacity_plane, Plane.company_plane, Plane.type_plane
                    FROM Ticketing
                    JOIN Transactions ON Transactions.id_schedule = '$id_schedule'
                    JOIN Customer ON Customer.id_customer = Transactions.id_customer
                    JOIN Account ON Account.id_account = '$id_account'
                    JOIN Contact ON Contact.id_contact = Customer.id_contact
                    JOIN Country ON Country.id_country = Contact.id_country
                    JOIN Schedule ON Schedule.id_schedule = '$id_schedule'
                    JOIN Plane ON Plane.id_plane = Schedule.id_plane
                    JOIN Routes ON Routes.id_route = Schedule.id_route
                    WHERE Customer.id_account = '$id_account' AND Ticketing.id_account = '$id_account' AND Ticketing.id_schedule = '$id_schedule' AND Transactions.id_transactions = '$id_transactions'
                    ";
        $get = pg_query($connect, $query);
        $data = array();
    } else {
        if ($id_discount && $id_fee) {
            $query = "SELECT number_discount FROM Discount 
                WHERE id_discount = '$id_discount'";
            $get = pg_query($connect, $query);
            $data = pg_fetch_row($get);
            $number_discount = intval(array_pop($data));

            $query = "SELECT number_fee FROM Additional_Fee 
                WHERE id_fee = '$id_fee'";
            $get = pg_query($connect, $query);
            $data = pg_fetch_row($get);
            $number_fee = intval(array_pop($data));

            $total_price_transactions = $total_price_transactions - ($total_price_transactions * $number_discount / 100);
            $total_price_transactions = (int)($total_price_transactions);
            $total_price_transactions = $total_price_transactions + ($total_price_transactions * $number_fee / 100);
            $total_price_transactions = (int)($total_price_transactions);

            $query = "INSERT INTO Ticketing(id_schedule, id_account, id_discount, id_fee, total_price_transactions, total_customer_adult, total_customer_child) 
                VALUES ('$id_schedule', '$id_account', '$id_discount', '$id_fee', '$total_price_transactions', '$total_customer_adult', '$total_customer_child')";
            $insert = pg_query($connect, $query);
        } else if ($id_discount) {
            $query = "SELECT number_discount FROM Discount 
                WHERE id_discount = '$id_discount'";
            $get = pg_query($connect, $query);
            $data = pg_fetch_row($get);
            $number_discount = intval(array_pop($data));
            $total_price_transactions = $total_price_transactions - ($total_price_transactions * $number_discount / 100);
            $total_price_transactions = (int)($total_price_transactions);

            $query = "INSERT INTO Ticketing(id_schedule, id_account, id_discount, id_fee, total_price_transactions, total_customer_adult, total_customer_child) 
                VALUES ('$id_schedule', '$id_account', '$id_discount', null, '$total_price_transactions', '$total_customer_adult', '$total_customer_child')";
            $insert = pg_query($connect, $query);
        } else if ($id_fee) {
            $query = "SELECT number_fee FROM Additional_Fee 
                WHERE id_fee = '$id_fee'";
            $get = pg_query($connect, $query);
            $data = pg_fetch_row($get);
            $number_fee = intval(array_pop($data));
            $total_price_transactions = $total_price_transactions + ($total_price_transactions * $number_fee / 100);
            $total_price_transactions = (int)($total_price_transactions);

            $query = "INSERT INTO Ticketing(id_schedule, id_account, id_discount, id_fee, total_price_transactions, total_customer_adult, total_customer_child) 
                VALUES ('$id_schedule', '$id_account', null, '$id_fee', '$total_price_transactions', '$total_customer_adult', '$total_customer_child')";
            $insert = pg_query($connect, $query);
        }

        $query =  "SELECT DISTINCT
                    Ticketing.id_ticketing, Ticketing.id_discount, Ticketing.id_fee, Ticketing.total_price_transactions, Ticketing.total_customer_adult, Ticketing.total_customer_child,
                    Transactions.id_transactions, Transactions.booking_date, Transactions.price_transactions, Transactions.customer_adult, Transactions.customer_child,
                    Customer.id_customer, Customer.firstname_customer, Customer.lastname_customer, Customer.address_customer, Customer.type_age_customer, Customer.gender_customer, Customer.nationality_customer,
                    Account.id_account, Account.id_session_account, Account.name_account, Account.username_account, Account.email_account,
                    Contact.id_contact, Contact.email_contact, Contact.telp_contact,
                    Country.id_country, Country.name_country, Country.iso3_country, Country.phonecode_country,
                    Schedule.id_schedule, Schedule.id_plane, Schedule.id_route, Schedule.departure_schedule, Schedule.arrival_schedule, Schedule.available_seat_schedule, Schedule.normal_price_schedule,
                    Routes.start_city_route, Routes.destination_city_route, Routes.start_airport_route, Routes.destination_airport_route, 
                    Plane.capacity_plane, Plane.company_plane, Plane.type_plane
                    FROM Ticketing
                    JOIN Transactions ON Transactions.id_schedule = '$id_schedule'
                    JOIN Customer ON Customer.id_customer = Transactions.id_customer
                    JOIN Account ON Account.id_account = '$id_account'
                    JOIN Contact ON Contact.id_contact = Customer.id_contact
                    JOIN Country ON Country.id_country = Contact.id_country
                    JOIN Schedule ON Schedule.id_schedule = '$id_schedule'
                    JOIN Plane ON Plane.id_plane = Schedule.id_plane
                    JOIN Routes ON Routes.id_route = Schedule.id_route
                    WHERE Customer.id_account = '$id_account' AND Ticketing.id_account = '$id_account' AND Ticketing.id_schedule = '$id_schedule' AND Transactions.id_transactions = '$id_transactions'
                    ";
        $get = pg_query($connect, $query);
        $data = array();
    }
}

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
