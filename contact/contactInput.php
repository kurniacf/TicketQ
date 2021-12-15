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

    $query = "SELECT * FROM Contact WHERE (email_contact = '$email_contact' OR telp_contact = '$telp_contact') AND id_country = '$id_country'";
    $get = pg_query($connect, $query);

    if (pg_num_rows($get)) {
        $query = "UPDATE Contact set email_contact = '$email_contact', telp_contact = '$telp_contact' 
            WHERE id_country = '$id_country' AND (email_contact = '$email_contact' OR telp_contact = '$telp_contact')";
        $insert = pg_query($connect, $query);

        if ($insert) {
            set_response(true, "Update Contact Success");
        } else {
            http_response_code(401);
            set_response(false, "Update Contact Failed");
        }
    } else {
        $query = "SELECT * FROM Contact WHERE email_contact = '$email_contact'";
        $get = pg_query($connect, $query);

        if (pg_num_rows($get)) {
            $query = "UPDATE Contact set email_contact = '$email_contact', telp_contact = '$telp_contact', id_country = '$id_country' 
            WHERE email_contact = '$email_contact'";
            $insert = pg_query($connect, $query);

            if ($insert) {
                set_response(true, "Update Contact Success");
            } else {
                http_response_code(401);
                set_response(false, "Update Contact Failed");
            }
        } else {
            $query = "INSERT INTO Contact(id_country, email_contact, telp_contact) 
            VALUES ('$id_country', '$email_contact','$telp_contact')";
            $insert = pg_query($connect, $query);

            if ($insert) {
                set_response(true, "Input Contact Success");
            } else {
                http_response_code(401);
                set_response(false, "Input Contact Failed");
            }
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
