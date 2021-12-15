<?php
include_once('../routes/connect.php');
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Credentials: true");
header('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');
header('Access-Control-Max-Age: 1000');
header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token , Authorization, X-Requested-With');
$rest_json = file_get_contents("php://input");
$_POST = json_decode($rest_json, true);


if (!empty($_POST['id_account']) && !empty($_POST['id_contact']) && !empty($_POST['firstname_customer']) && !empty($_POST['type_age_customer']) && !empty($_POST['gender_customer']) && !empty($_POST['nationality_customer'])) {
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
        $query = "UPDATE Customer set firstname_customer = '$firstname_customer', lastname_customer = '$lastname_customer',  address_customer = '$address_customer',  type_age_customer = '$type_age_customer',  gender_customer = '$gender_customer', nationality_customer = '$nationality_customer'
                WHERE id_account = '$id_account' AND id_contact = '$id_contact'";
        $insert = pg_query($connect, $query);

        if ($insert) {
            set_response(true, "Update Customer Success");
        } else {
            http_response_code(401);
            set_response(false, "Update Customer Failed");
        }
    } else {
        $query = "INSERT INTO Customer(id_account, id_contact, firstname_customer, lastname_customer, address_customer, type_age_customer, gender_customer, nationality_customer) 
            VALUES ('$id_account', '$id_contact','$firstname_customer', '$lastname_customer', '$address_customer', '$type_age_customer', '$gender_customer', '$nationality_customer',)";
        $insert = pg_query($connect, $query);

        if ($insert) {
            set_response(true, "Input Customer Success");
        } else {
            http_response_code(401);
            set_response(false, "Input Customer Failed");
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
