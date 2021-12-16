<?php
include_once('../../routes/connect.php');
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Credentials: true");
header('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');
header('Access-Control-Max-Age: 1000');
header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token , Authorization, X-Requested-With');
$rest_json = file_get_contents("php://input");
$_POST = json_decode($rest_json, true);

session_start();
$id_session_account = session_id();
session_regenerate_id();
$id_session_account = session_id();

if (!empty($_POST['name_account']) && !empty($_POST['username_account']) && !empty($_POST['email_account']) && !empty($_POST['password_account']) && !empty($_POST['telp_account'])) {
    $name_account = $_POST['name_account'];
    $username_account = $_POST['username_account'];
    $email_account = $_POST['email_account'];
    $password_account = password_hash($_POST['password_account'], PASSWORD_DEFAULT);
    $telp_account = $_POST['telp_account'];
    $id_country = $_POST['id_country'];

    $query = "SELECT * FROM Account WHERE email_account = '$email_account'";
    $get = pg_query($connect, $query);

    if (pg_num_rows($get)) {
        http_response_code(400);
        set_response(false, "Email has Already Account!", "Check Data Again");
    } else {
        $query = "SELECT * FROM Contact WHERE email_contact = '$email_account' OR telp_contact = '$telp_account' AND id_country = '$id_country'";
        $get = pg_query($connect, $query);

        if (pg_num_rows($get)) {
            http_response_code(400);
            set_response(false, "Email & Telp has Already Used!", "Please Login or Check Data!");
        } else {
            $query = "SELECT * FROM Account WHERE username_account = '$username_account'";
            $get = pg_query($connect, $query);

            if (pg_num_rows($get)) {
                http_response_code(400);
                set_response(false, "Username has been Used!", "Please Change Username");
            } else {
                $query2 = "INSERT INTO Account(name_account, username_account, email_account, password_account, id_session_account) 
                                VALUES ('$name_account', '$username_account', '$email_account','$password_account', '$id_session_account')";
                $insert2 = pg_query($connect, $query2);
                $query3 = "INSERT INTO Contact(email_contact, telp_contact, id_country) 
                                VALUES ('$email_account', '$telp_account', '$id_country')";
                $insert3 = pg_query($connect, $query3);

                if ($insert3) {
                    $query = "SELECT id_account FROM Account WHERE email_account = '$email_account'";
                    $get = pg_query($connect, $query);
                    $data = pg_fetch_row($get);
                    $id_account = intval(array_pop($data));

                    $query1 = "SELECT id_contact FROM Contact WHERE email_contact = '$email_account' AND telp_contact = '$telp_account'";
                    $get1 = pg_query($connect, $query1);
                    $data1 = pg_fetch_row($get1);
                    $id_contact = intval(array_pop($data1));

                    $_SESSION = array(
                        "id_session_account" => $id_session_account,
                        "id_account" => $id_account,
                        "id_contact" => $id_contact,
                    );
                    set_response(true, "Register Account Success", $_SESSION);
                } else {
                    http_response_code(401);
                    set_response(false, "Register Account Failed", null);
                }
            }
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
