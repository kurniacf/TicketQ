<?php
include_once('../routes/connect.php');
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Credentials: true");
header('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');
header('Access-Control-Max-Age: 1000');
header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token , Authorization, X-Requested-With');
$rest_json = file_get_contents("php://input");
$_POST = json_decode($rest_json, true);


if (!empty($_POST['id_account']) && !empty($_POST['firstname_customer']) && !empty($_POST['type_age_customer']) && !empty($_POST['gender_customer']) && !empty($_POST['nationality_customer']) && !empty($_POST['id_country']) && !empty($_POST['email_contact']) && !empty($_POST['telp_contact'])) {
    $firstname_customer = $_POST['firstname_customer'];
    $lastname_customer = $_POST['lastname_customer'];
    $address_customer = $_POST['address_customer'];
    $type_age_customer = $_POST['type_age_customer'];
    $gender_customer = $_POST['gender_customer'];
    $nationality_customer = $_POST['nationality_customer'];
    $id_account = $_POST['id_account'];
    $id_country = $_POST['id_country'];
    $email_contact = $_POST['email_contact'];
    $telp_contact = $_POST['telp_contact'];

    $query = "SELECT * FROM Customer WHERE id_account = '$id_account' AND firstname_customer = '$firstname_customer' AND lastname_customer = '$lastname_customer' AND type_age_customer = '$type_age_customer' AND gender_customer = '$gender_customer' AND nationality_customer = '$nationality_customer'";
    $get = pg_query($connect, $query);

    if (pg_num_rows($get)) {
        $query = "SELECT * FROM Account WHERE Account.email_account = '$email_contact'";
        $get = pg_query($connect, $query);

        if (pg_num_rows($get)) {
            $query = "SELECT id_contact FROM Contact WHERE email_contact = '$email_contact' AND id_country = '$id_country'";
            $get = pg_query($connect, $query);
            $data = pg_fetch_row($get);
            $id_contact = intval(array_pop($data));

            $query3 = "UPDATE Customer set id_account = '$id_account', id_contact = '$id_contact', address_customer = '$address_customer' WHERE firstname_customer = '$firstname_customer' AND lastname_customer = '$lastname_customer' AND type_age_customer = '$type_age_customer' AND gender_customer = '$gender_customer' AND nationality_customer = '$nationality_customer'";
            $update = pg_query($connect, $query3);

            $query = "SELECT id_customer FROM Customer WHERE firstname_customer = '$firstname_customer' AND lastname_customer = '$lastname_customer' AND type_age_customer = '$type_age_customer' AND gender_customer = '$gender_customer' AND nationality_customer = '$nationality_customer'";
            $get = pg_query($connect, $query);
            $data = pg_fetch_row($get);
            $id_customer = intval(array_pop($data));

            $queryFinal = "SELECT Customer.id_customer, Customer.firstname_customer, Customer.lastname_customer, Customer.address_customer, Customer.type_age_customer, Customer.gender_customer, Customer.nationality_customer,
                    Account.id_account, Account.id_session_account, Account.name_account, Account.username_account, Account.email_account,
                    Contact.id_contact, Contact.email_contact, Contact.telp_contact,
                    Country.id_country, Country.name_country, Country.iso3_country, Country.phonecode_country
                    FROM Account, Contact
                    JOIN Customer ON Customer.id_customer = '$id_customer'
                    JOIN Country ON Country.id_country = '$id_country'
                    WHERE Contact.email_contact = '$email_contact' AND  Contact.id_country = '$id_country' AND Account.id_account = '$id_account'";

            $getFinal = pg_query($connect, $queryFinal);
            $data = array();

            if (pg_num_rows($getFinal)) {
                while ($row = pg_fetch_assoc($getFinal)) {
                    $data[] = $row;
                }
                set_response(true, "Update Data Customer Success", $data);
            } else {
                http_response_code(400);
                set_response(false, "Update Data Customer Failed", "Input Data is Wrong!");
            }
        } else {
            $query = "SELECT * FROM Contact WHERE email_contact = '$email_contact' AND id_country = '$id_country'";
            $get = pg_query($connect, $query);

            if (pg_num_rows($get)) {
                $query = "SELECT id_contact FROM Contact WHERE email_contact = '$email_contact' AND id_country = '$id_country'";
                $get = pg_query($connect, $query);
                $data = pg_fetch_row($get);
                $id_contact = intval(array_pop($data));

                $query3 = "UPDATE Contact set email_contact = '$email_contact', telp_contact = '$telp_contact' WHERE id_contact = '$id_contact'";
                $update = pg_query($connect, $query3);

                $query3 = "UPDATE Customer set id_account = '$id_account', id_contact = '$id_contact', address_customer = '$address_customer' WHERE firstname_customer = '$firstname_customer' AND lastname_customer = '$lastname_customer' AND type_age_customer = '$type_age_customer' AND gender_customer = '$gender_customer' AND nationality_customer = '$nationality_customer'";
                $update = pg_query($connect, $query3);

                $query = "SELECT id_customer FROM Customer WHERE firstname_customer = '$firstname_customer' AND lastname_customer = '$lastname_customer' AND type_age_customer = '$type_age_customer' AND gender_customer = '$gender_customer' AND nationality_customer = '$nationality_customer'";
                $get = pg_query($connect, $query);
                $data = pg_fetch_row($get);
                $id_customer = intval(array_pop($data));

                $queryFinal = "SELECT Customer.id_customer, Customer.firstname_customer, Customer.lastname_customer, Customer.address_customer, Customer.type_age_customer, Customer.gender_customer, Customer.nationality_customer,
                    Account.id_account, Account.id_session_account, Account.name_account, Account.username_account, Account.email_account,
                    Contact.id_contact, Contact.email_contact, Contact.telp_contact,
                    Country.id_country, Country.name_country, Country.iso3_country, Country.phonecode_country
                    FROM Account, Contact
                    JOIN Customer ON Customer.id_customer = '$id_customer'
                    JOIN Country ON Country.id_country = '$id_country'
                    WHERE Contact.email_contact = '$email_contact' AND  Contact.id_country = '$id_country' AND Account.id_account = '$id_account'";

                $getFinal = pg_query($connect, $queryFinal);
                $data = array();

                if (pg_num_rows($getFinal)) {
                    while ($row = pg_fetch_assoc($getFinal)) {
                        $data[] = $row;
                    }
                    set_response(true, "Update Data Customer Success", $data);
                } else {
                    http_response_code(400);
                    set_response(false, "Update Data Customer Failed", "Input Data is Wrong!");
                }
            } else {
                $query = "SELECT id_contact FROM Customer WHERE id_account = '$id_account' AND firstname_customer = '$firstname_customer' AND lastname_customer = '$lastname_customer' AND type_age_customer = '$type_age_customer' AND gender_customer = '$gender_customer' AND nationality_customer = '$nationality_customer'";
                $get = pg_query($connect, $query);
                $data = pg_fetch_row($get);
                $id_contact = intval(array_pop($data));

                $query3 = "UPDATE Contact set email_contact = '$email_contact', telp_contact = '$telp_contact' WHERE id_contact = '$id_contact'";
                $update = pg_query($connect, $query3);

                $query3 = "UPDATE Customer set id_account = '$id_account', id_contact = '$id_contact', address_customer = '$address_customer' WHERE firstname_customer = '$firstname_customer' AND lastname_customer = '$lastname_customer' AND type_age_customer = '$type_age_customer' AND gender_customer = '$gender_customer' AND nationality_customer = '$nationality_customer'";
                $update = pg_query($connect, $query3);

                $query = "SELECT id_customer FROM Customer WHERE firstname_customer = '$firstname_customer' AND lastname_customer = '$lastname_customer' AND type_age_customer = '$type_age_customer' AND gender_customer = '$gender_customer' AND nationality_customer = '$nationality_customer'";
                $get = pg_query($connect, $query);
                $data = pg_fetch_row($get);
                $id_customer = intval(array_pop($data));

                $queryFinal = "SELECT Customer.id_customer, Customer.firstname_customer, Customer.lastname_customer, Customer.address_customer, Customer.type_age_customer, Customer.gender_customer, Customer.nationality_customer,
                    Account.id_account, Account.id_session_account, Account.name_account, Account.username_account, Account.email_account,
                    Contact.id_contact, Contact.email_contact, Contact.telp_contact,
                    Country.id_country, Country.name_country, Country.iso3_country, Country.phonecode_country
                    FROM Account, Contact
                    JOIN Customer ON Customer.id_customer = '$id_customer'
                    JOIN Country ON Country.id_country = '$id_country'
                    WHERE Contact.email_contact = '$email_contact' AND  Contact.id_country = '$id_country' AND Account.id_account = '$id_account'";

                $getFinal = pg_query($connect, $queryFinal);
                $data = array();

                if (pg_num_rows($getFinal)) {
                    while ($row = pg_fetch_assoc($getFinal)) {
                        $data[] = $row;
                    }
                    set_response(true, "Update Data Customer Success", $data);
                } else {
                    http_response_code(400);
                    set_response(false, "Update Data Customer Failed", "Input Data is Wrong!");
                }
            }
        }
    } else {
        $query = "SELECT * FROM Account WHERE Account.email_account = '$email_contact'";
        $get = pg_query($connect, $query);

        if (pg_num_rows($get)) {
            $query = "SELECT id_contact FROM Contact WHERE email_contact = '$email_contact' AND id_country = '$id_country'";
            $get = pg_query($connect, $query);
            $data = pg_fetch_row($get);
            $id_contact = intval(array_pop($data));

            $query = "INSERT INTO Customer(id_account, id_contact, firstname_customer, lastname_customer, address_customer, type_age_customer, gender_customer, nationality_customer) 
                            VALUES ('$id_account', '$id_contact','$firstname_customer', '$lastname_customer', '$address_customer', '$type_age_customer', '$gender_customer', '$nationality_customer')";
            $insert = pg_query($connect, $query);

            $query = "SELECT id_customer FROM Customer WHERE firstname_customer = '$firstname_customer' AND lastname_customer = '$lastname_customer' AND type_age_customer = '$type_age_customer' AND gender_customer = '$gender_customer' AND nationality_customer = '$nationality_customer'";
            $get = pg_query($connect, $query);
            $data = pg_fetch_row($get);
            $id_customer = intval(array_pop($data));

            $queryFinal = "SELECT Customer.id_customer, Customer.firstname_customer, Customer.lastname_customer, Customer.address_customer, Customer.type_age_customer, Customer.gender_customer, Customer.nationality_customer,
                    Account.id_account, Account.id_session_account, Account.name_account, Account.username_account, Account.email_account,
                    Contact.id_contact, Contact.email_contact, Contact.telp_contact,
                    Country.id_country, Country.name_country, Country.iso3_country, Country.phonecode_country
                    FROM Account, Contact
                    JOIN Customer ON Customer.id_customer = '$id_customer'
                    JOIN Country ON Country.id_country = '$id_country'
                    WHERE Contact.email_contact = '$email_contact' AND  Contact.id_country = '$id_country' AND Account.id_account = '$id_account'";

            $getFinal = pg_query($connect, $queryFinal);
            $data = array();

            if (pg_num_rows($getFinal)) {
                while ($row = pg_fetch_assoc($getFinal)) {
                    $data[] = $row;
                }
                set_response(true, "Create Customer Success", $data);
            } else {
                http_response_code(400);
                set_response(false, "Create Customer Failed", "Input Data is Wrong!");
            }
        } else {
            $query = "SELECT * FROM Contact WHERE email_contact = '$email_contact' AND telp_contact = '$telp_contact' AND id_country = '$id_country'";
            $get = pg_query($connect, $query);

            if (pg_num_rows($get)) {
                $query = "SELECT id_contact FROM Contact WHERE email_contact = '$email_contact' AND telp_contact = '$telp_contact' AND id_country = '$id_country'";
                $get = pg_query($connect, $query);
                $data = pg_fetch_row($get);
                $id_contact = intval(array_pop($data));

                $query = "INSERT INTO Customer(id_account, id_contact, firstname_customer, lastname_customer, address_customer, type_age_customer, gender_customer, nationality_customer) 
                            VALUES ('$id_account', '$id_contact','$firstname_customer', '$lastname_customer', '$address_customer', '$type_age_customer', '$gender_customer', '$nationality_customer')";
                $insert = pg_query($connect, $query);

                $query = "SELECT id_customer FROM Customer WHERE firstname_customer = '$firstname_customer' AND lastname_customer = '$lastname_customer' AND type_age_customer = '$type_age_customer' AND gender_customer = '$gender_customer' AND nationality_customer = '$nationality_customer'";
                $get = pg_query($connect, $query);
                $data = pg_fetch_row($get);
                $id_customer = intval(array_pop($data));

                $queryFinal = "SELECT Customer.id_customer, Customer.firstname_customer, Customer.lastname_customer, Customer.address_customer, Customer.type_age_customer, Customer.gender_customer, Customer.nationality_customer,
                    Account.id_account, Account.id_session_account, Account.name_account, Account.username_account, Account.email_account,
                    Contact.id_contact, Contact.email_contact, Contact.telp_contact,
                    Country.id_country, Country.name_country, Country.iso3_country, Country.phonecode_country
                    FROM Account, Contact
                    JOIN Customer ON Customer.id_customer = '$id_customer'
                    JOIN Country ON Country.id_country = '$id_country'
                    WHERE Contact.email_contact = '$email_contact' AND  Contact.id_country = '$id_country' AND Account.id_account = '$id_account'";

                $getFinal = pg_query($connect, $queryFinal);
                $data = array();

                if (pg_num_rows($getFinal)) {
                    while ($row = pg_fetch_assoc($getFinal)) {
                        $data[] = $row;
                    }
                    set_response(true, "Create Customer Success", $data);
                } else {
                    http_response_code(400);
                    set_response(false, "Create Customer Failed", "Input Data is Wrong!");
                }
            } else {
                $query = "SELECT * FROM Contact WHERE email_contact = '$email_contact' AND id_country = '$id_country'";
                $get = pg_query($connect, $query);

                if (pg_num_rows($get)) {
                    $query = "SELECT id_contact FROM Contact WHERE email_contact = '$email_contact' AND id_country = '$id_country'";
                    $get = pg_query($connect, $query);
                    $data = pg_fetch_row($get);
                    $id_contact = intval(array_pop($data));

                    $query3 = "UPDATE Contact set email_contact = '$email_contact', telp_contact = '$telp_contact' WHERE id_contact = '$id_contact'";
                    $update = pg_query($connect, $query3);

                    $query = "INSERT INTO Customer(id_account, id_contact, firstname_customer, lastname_customer, address_customer, type_age_customer, gender_customer, nationality_customer) 
                            VALUES ('$id_account', '$id_contact','$firstname_customer', '$lastname_customer', '$address_customer', '$type_age_customer', '$gender_customer', '$nationality_customer')";
                    $insert = pg_query($connect, $query);

                    $query = "SELECT id_customer FROM Customer WHERE firstname_customer = '$firstname_customer' AND lastname_customer = '$lastname_customer' AND type_age_customer = '$type_age_customer' AND gender_customer = '$gender_customer' AND nationality_customer = '$nationality_customer'";
                    $get = pg_query($connect, $query);
                    $data = pg_fetch_row($get);
                    $id_customer = intval(array_pop($data));

                    $queryFinal = "SELECT Customer.id_customer, Customer.firstname_customer, Customer.lastname_customer, Customer.address_customer, Customer.type_age_customer, Customer.gender_customer, Customer.nationality_customer,
                    Account.id_account, Account.id_session_account, Account.name_account, Account.username_account, Account.email_account,
                    Contact.id_contact, Contact.email_contact, Contact.telp_contact,
                    Country.id_country, Country.name_country, Country.iso3_country, Country.phonecode_country
                    FROM Account, Contact
                    JOIN Customer ON Customer.id_customer = '$id_customer'
                    JOIN Country ON Country.id_country = '$id_country'
                    WHERE Contact.email_contact = '$email_contact' AND  Contact.id_country = '$id_country' AND Account.id_account = '$id_account'";

                    $getFinal = pg_query($connect, $queryFinal);
                    $data = array();

                    if (pg_num_rows($getFinal)) {
                        while ($row = pg_fetch_assoc($getFinal)) {
                            $data[] = $row;
                        }
                        set_response(true, "Create Customer Success", $data);
                    } else {
                        http_response_code(400);
                        set_response(false, "Create Customer Failed", "Input Data is Wrong!");
                    }
                } else {
                    $query = "INSERT INTO Contact(email_contact, telp_contact, id_country) 
                            VALUES ('$email_contact', '$telp_contact','$id_country')";
                    $insert = pg_query($connect, $query);

                    $query = "SELECT id_contact FROM Contact WHERE email_contact = '$email_contact' AND telp_contact = '$telp_contact' AND id_country = '$id_country'";
                    $get = pg_query($connect, $query);
                    $data = pg_fetch_row($get);
                    $id_contact = intval(array_pop($data));

                    $query = "INSERT INTO Customer(id_account, id_contact, firstname_customer, lastname_customer, address_customer, type_age_customer, gender_customer, nationality_customer) 
                            VALUES ('$id_account', '$id_contact','$firstname_customer', '$lastname_customer', '$address_customer', '$type_age_customer', '$gender_customer', '$nationality_customer')";
                    $insert = pg_query($connect, $query);

                    $query = "SELECT id_customer FROM Customer WHERE firstname_customer = '$firstname_customer' AND lastname_customer = '$lastname_customer' AND type_age_customer = '$type_age_customer' AND gender_customer = '$gender_customer' AND nationality_customer = '$nationality_customer'";
                    $get = pg_query($connect, $query);
                    $data = pg_fetch_row($get);
                    $id_customer = intval(array_pop($data));

                    $queryFinal = "SELECT Customer.id_customer, Customer.firstname_customer, Customer.lastname_customer, Customer.address_customer, Customer.type_age_customer, Customer.gender_customer, Customer.nationality_customer,
                    Account.id_account, Account.id_session_account, Account.name_account, Account.username_account, Account.email_account,
                    Contact.id_contact, Contact.email_contact, Contact.telp_contact,
                    Country.id_country, Country.name_country, Country.iso3_country, Country.phonecode_country
                    FROM Account, Contact
                    JOIN Customer ON Customer.id_customer = '$id_customer'
                    JOIN Country ON Country.id_country = '$id_country'
                    WHERE Contact.email_contact = '$email_contact' AND  Contact.id_country = '$id_country' AND Account.id_account = '$id_account'";

                    $getFinal = pg_query($connect, $queryFinal);
                    $data = array();

                    if (pg_num_rows($getFinal)) {
                        while ($row = pg_fetch_assoc($getFinal)) {
                            $data[] = $row;
                        }
                        set_response(true, "Create Customer Success", $data);
                    } else {
                        http_response_code(400);
                        set_response(false, "Create Customer Failed", "Input Data is Wrong!");
                    }
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
