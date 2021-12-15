<?php
include_once('../routes/connect.php');
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Credentials: true");
header('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');
header('Access-Control-Max-Age: 1000');
header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token , Authorization, X-Requested-With');
$rest_json = file_get_contents("php://input");
$_POST = json_decode($rest_json, true);


if (!empty($_POST['id_account']) && !empty($_POST['id_contact'])) {
    $id_account = $_POST['id_account'];
    $id_contact = $_POST['id_contact'];
    $firstname_customer = $_POST['firstname_customer'];
    $lastname_customer = $_POST['lastname_customer'];
    $address_customer = $_POST['address_customer'];
    $type_age_customer = $_POST['type_age_customer'];
    $gender_customer = $_POST['gender_customer'];
    $nationality_customer = $_POST['nationality_customer'];

    $query = "SELECT * FROM Customer WHERE id_account = '$id_account' AND id_contact = '$id_contact'";
    $get = pg_query($connect, $query);

    if (pg_num_rows($get)) {
        $query1 = "SELECT id_contact FROM contact WHERE (email_contact = '$email_contact' OR telp_contact = '$telp_contact') AND id_country = '$id_country'";
        $get1 = pg_query($connect, $query1);
        $data1 = pg_fetch_row($get1);
        $id_contact = intval(array_pop($data1));

        $query = "SELECT * FROM Contact WHERE id_contact = '$id_contact'";
        $get = pg_query($connect, $query);

        if (pg_num_rows($get)) {
            $query = "UPDATE Contact set email_contact = '$email_contact', telp_contact = '$telp_contact'
                WHERE id_country = '$id_country' AND (email_contact = '$email_contact' OR telp_contact = '$telp_contact')";
            $insert = pg_query($connect, $query);
        } else {
            $query = "INSERT INTO Contact(id_country, email_contact, telp_contact) 
                VALUES ('$id_country', '$email_contact','$telp_contact')";
            $insert = pg_query($connect, $query);
        }

        if ($insert) {
            set_response(true, "Update Contact Success");
        } else {
            http_response_code(401);
            set_response(false, "Update Contact Failed");
        }
    } else {
        $query = "INSERT INTO Customer(id_account, id_contact, firstname_customer, lastname_customer, address_customer, type_age_customer, gender_customer, nationality_customer) 
            VALUES ('$id_country', '$email_contact','$telp_contact')";
        $insert = pg_query($connect, $query);

        if ($insert) {
            set_response(true, "Input Contact Success");
        } else {
            http_response_code(401);
            set_response(false, "Input Contact Failed");
        }
    }
} else {
    http_response_code(400);
    set_response(false, "Dont Empty!!");
}

function set_response($isSuccess, $message)
{
    $result = array(
        'isSuccess' => $isSuccess,
        'message' => $message
    );
    echo json_encode($result);
}
