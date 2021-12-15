<?php
include_once('../routes/connect.php');
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Credentials: true");
header('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');
header('Access-Control-Max-Age: 1000');
header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token , Authorization, X-Requested-With');
$rest_json = file_get_contents("php://input");
$_POST = json_decode($rest_json, true);


if (!empty($_POST['id_country'])) {
    $id_country = $_POST['id_country'];
    $email_contact = $_POST['email_contact'];
    $telp_contact = $_POST['telp_contact'];

    if (!(isset($email_contact))) {
        $email_contact = null;
    }
    if (!(isset($telp_contact))) {
        $telp_contact = null;
    }

    $query1 = "SELECT id_contact FROM contact WHERE email_contact = '$email_contact' OR telp_contact = '$telp_contact' AND id_country = '$id_country'";
    $get1 = pg_query($connect, $query1);
    $data1 = pg_fetch_row($get1);
    $id_contact = intval(array_pop($data1));

    if ($id_contact) {
        $query = "SELECT Contact.id_contact, Contact.id_country, Contact.email_contact, Contact.telp_contact, Country.name_country, Country.iso3_country, Country.phonecode_country 
        FROM Contact 
        JOIN Country ON Country.id_country = '$id_country'
        WHERE id_contact = $id_contact AND (email_contact = '$email_contact' OR telp_contact = '$telp_contact')";
    } else {
        http_response_code(401);
        set_response(false, "Data is Not Found", $data);
    }
} else if (!empty($_POST['id_contact'])) {
    $id_contact = $_POST['id_contact'];

    $query1 = "SELECT id_country FROM contact WHERE id_contact = '$id_contact'";
    $get1 = pg_query($connect, $query1);
    $data1 = pg_fetch_row($get1);
    $id_country = intval(array_pop($data1));

    $query = "SELECT DISTINCT Contact.id_contact, Contact.id_country, Contact.email_contact, Contact.telp_contact, Country.name_country, Country.iso3_country, Country.phonecode_country 
        FROM Contact, Country 
        WHERE Contact.id_contact = '$id_contact' AND Country.id_country = '$id_country'";
} else {
    $query = "SELECT Contact.id_contact, Contact.id_country, Contact.email_contact, Contact.telp_contact, Country.name_country, Country.iso3_country, Country.phonecode_country 
        FROM Contact
        INNER JOIN Country ON  Country.id_country = Contact.id_country";
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
