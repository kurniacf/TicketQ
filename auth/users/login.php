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

if (!empty($_POST['username_email_account']) && !empty($_POST['password_account'])) {
    $username_email_account = $_POST['username_email_account'];
    $password_account = $_POST['password_account'];

    $IdCheck = "SELECT id_account FROM Account WHERE email_account = '$username_email_account' OR username_account = '$username_email_account'";
    $getId = pg_query($connect, $IdCheck);
    $rowId = pg_fetch_row($getId);

    if ($rowId) {
        $passwordDB = "SELECT password_account FROM Account WHERE email_account = '$username_email_account' OR username_account = '$username_email_account'";
        $get = pg_query($connect, $passwordDB);
        $row = pg_fetch_row($get);
        $passwordORI = array_pop($row);
        $passwordCheck = password_verify($password_account, $passwordORI);

        if ($passwordCheck) {
            $get1 = pg_query($connect, $IdCheck);
            $data1 = pg_fetch_row($get1);
            $id_account = intval(array_pop($data1));

            $query2 = "SELECT email_account FROM Account WHERE email_account = '$username_email_account' OR username_account = '$username_email_account'";
            $get2 = pg_query($connect, $query2);
            $data2 = pg_fetch_row($get2);
            $email_account = array_pop($data2);

            $query2 = "SELECT id_contact FROM Contact WHERE email_contact = '$email_account'";
            $get2 = pg_query($connect, $query2);
            $data2 = pg_fetch_row($get2);
            $id_contact = intval(array_pop($data2));

            $query2 = "SELECT id_country FROM Contact WHERE email_contact = '$email_account'";
            $get2 = pg_query($connect, $query2);
            $data2 = pg_fetch_row($get2);
            $id_country = intval(array_pop($data2));

            $query3 = "UPDATE Account set id_session_account = '$id_session_account' WHERE id_account = '$id_account'";
            $update = pg_query($connect, $query3);

            if ($update) {
                $_SESSION = array(
                    "id_session_account" => $id_session_account,
                    "id_account" => $id_account,
                    "id_contact" => $id_contact,
                    "id_country" => $id_country,
                );
                set_response(true, "Login Account success", $_SESSION);
            } else {
                http_response_code(401);
                set_response(false, "Login Account failed", null);
            }
        } else {
            http_response_code(400);
            set_response(false, "Password False", "Please Check Your Password");
        }
    } else {
        http_response_code(400);
        set_response(false, "Email or Username False", "Please Check Your Email or Username");
    }
} else {
    http_response_code(400);
    set_response(false, "Dont Empty", "Fill All Data");
}

function set_response($isSuccess, $message, $data)
{
    $resul = array(
        'isSuccess' => $isSuccess,
        'message' => $message,
        'data' => $data
    );

    echo json_encode($resul);
}
