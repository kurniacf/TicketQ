<?php
include_once('../../routes/connect.php');
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Credentials: true");
header('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');
header('Access-Control-Max-Age: 1000');
header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token , Authorization, X-Requested-With');
$rest_json = file_get_contents("php://input");
$_POST = json_decode($rest_json, true);

if (!empty($_POST['id_session_account']) && !empty($_POST['id_country'])) {
    $id_session_account = $_POST['id_session_account'];
    $id_country = $_POST['id_country'];

    $query = "SELECT * FROM Account WHERE id_session_account = '$id_session_account'";
    $get = pg_query($connect, $query);

    if (pg_num_rows($get)) {
        $query1 = "SELECT email_account FROM Account WHERE id_session_account = '$id_session_account'";
        $get1 = pg_query($connect, $query1);
        $data1 = pg_fetch_row($get1);
        $email_account = array_pop($data1);

        $query = "SELECT Account.id_account, Account.id_session_account, Account.name_account, Account.username_account, Account.email_account,
                    Contact.id_contact, Contact.telp_contact,
                    Country.id_country, Country.name_country, Country.iso3_country, Country.phonecode_country
                    FROM Account, Contact
                    JOIN Country ON Country.id_country = '$id_country'
                    WHERE Contact.email_contact = '$email_account' AND  Contact.id_country = '$id_country' AND Account.id_session_account = '$id_session_account'";
    } else {
        http_response_code(401);
        set_response(false, "Data is Not Found", $data);
    }
}

$get = pg_query($connect, $query);
$data = array();

if (pg_num_rows($get) > 0) {
    while ($row = pg_fetch_assoc($get)) {
        $data[] = $row;
    }
    set_response(true, "Data is Found", $data);
} else {
    http_response_code(400);
    set_response(false, "Data is Not Found", "Session is Wrong!");
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
