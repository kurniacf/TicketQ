<?php
include_once('../routes/connect.php');
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Credentials: true");
header('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');
header('Access-Control-Max-Age: 1000');
header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token , Authorization, X-Requested-With');
$rest_json = file_get_contents("php://input");
$_POST = json_decode($rest_json, true);


if (!empty($_POST['id_customer'])) {
    $id_customer = $_POST['id_customer'];

    $query = "SELECT id_contact FROM Customer WHERE id_customer = '$id_customer'";
    $get = pg_query($connect, $query);
    $data = pg_fetch_row($get);
    $id_contact = intval(array_pop($data));

    $query = "SELECT id_account FROM Customer WHERE id_customer = '$id_customer'";
    $get = pg_query($connect, $query);
    $data = pg_fetch_row($get);
    $id_account = intval(array_pop($data));

    $query1 = "SELECT id_country FROM contact WHERE id_contact = '$id_contact'";
    $get1 = pg_query($connect, $query1);
    $data1 = pg_fetch_row($get1);
    $id_country = intval(array_pop($data1));

    $query = "SELECT Customer.id_customer, Customer.firstname_customer, Customer.lastname_customer, Customer.address_customer, Customer.type_age_customer, Customer.gender_customer, Customer.nationality_customer,
                    Account.id_account, Account.id_session_account, Account.name_account, Account.username_account, Account.email_account,
                    Contact.id_contact, Contact.email_contact, Contact.telp_contact,
                    Country.id_country, Country.name_country, Country.iso3_country, Country.phonecode_country
                    FROM Account, Contact
                    JOIN Customer ON Customer.id_customer = '$id_customer'
                    JOIN Country ON Country.id_country = '$id_country'
                    WHERE Contact.id_contact = '$id_contact' AND  Contact.id_country = '$id_country' AND Account.id_account = '$id_account'";
} else if (!empty($_POST['id_account'])) {
    $id_account = $_POST['id_account'];

    $query = "SELECT DISTINCT Customer.id_customer, Customer.firstname_customer, Customer.lastname_customer, Customer.address_customer, Customer.type_age_customer, Customer.gender_customer, Customer.nationality_customer,
                    Account.id_account, Account.id_session_account, Account.name_account, Account.username_account, Account.email_account,
                    Contact.id_contact, Contact.email_contact, Contact.telp_contact,
                    Country.id_country, Country.name_country, Country.iso3_country, Country.phonecode_country
                    FROM Customer
					JOIN Contact ON Contact.id_contact = Customer.id_contact
                    JOIN Country ON Country.id_country = Contact.id_country
					JOIN Account ON Account.id_account = '$id_account'
                    WHERE Customer.id_account = '$id_account'";
} else {
    $query =  "SELECT DISTINCT Customer.id_customer, Customer.firstname_customer, Customer.lastname_customer, Customer.address_customer, Customer.type_age_customer, Customer.gender_customer, Customer.nationality_customer,
                    Account.id_account, Account.id_session_account, Account.name_account, Account.username_account, Account.email_account,
                    Contact.id_contact, Contact.email_contact, Contact.telp_contact,
                    Country.id_country, Country.name_country, Country.iso3_country, Country.phonecode_country
                    FROM Customer
					JOIN Contact ON Contact.id_contact = Customer.id_contact
                    JOIN Country ON Country.id_country = Contact.id_country
					JOIN Account ON Account.id_account = Customer.id_account";
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
