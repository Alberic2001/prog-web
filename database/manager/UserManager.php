<?php
require_once('../config/Database.php');
require_once('../models/helper.php');
define('TABLE', 'user');

function email_exists($conn, $user)
{
    $query = 'SELECT * FROM ' . TABLE . ' where email=?';
    $statement = $conn->prepare($query);
    if ($statement->bind_param('s', $user['email'])) {
        $statement->execute();
        $log = $statement->get_result()->num_rows > 0;
        return $log;
    }
}

function sign_up($user)
{
    $conn = connect();
    if (!email_exists($conn, $user)) {
        $query = 'INSERT INTO ' . TABLE . '(`email`, `password`, `surname`, `lastname`, `birth_date`) VALUES (?,?,?,?,?);';
        $statement = $conn->prepare($query);
        // $user->setBirth_date($user->getBirth_date());
        $hashed_password = password_hash($user['password'], PASSWORD_BCRYPT);
        if ($statement->bind_param('sssss', $user['email'], $hashed_password, $user['surname'], $user['lastname'], $user['birth_date'])) {
            if ($statement->execute()) {
                $user['id'] = $statement->insert_id;
                mysqli_close($conn);
                return true;
            }
            return "Something went wrong ! (" . $statement->errno . ") " . $statement->error;
        }
        return "Can't bind params (" . $statement->errno . ") " . $statement->error;
    }
    return 'Email already exists';

}

function sign_in($email, $input_password)
{
    $conn = connect();
    $query = 'SELECT * FROM ' . TABLE . ' WHERE email = ?';
    $statement = $conn->prepare($query);
    if ($statement->bind_param('s', trim($email))) {
        if ($statement->execute()) {
            $result = $statement->get_result();
            $num = $result ? $result->num_rows : 0;
            if ($num > 0) {
                extract($result->fetch_assoc());
                $log = password_verify($input_password, $password) ? 'Successfully connected' : '';
                if (!$log) {
                    return;
                }
                mysqli_close($conn);
                return user_builder($email, $password, $surname, $lastname, $birth_date, $id);
            }
            return "There is no result :(";
        }
        return "Can't bind params (" . $statement->errno . ") " . $statement->error;
    }
    return "Can't prepare the query (" . $statement->errno . ") " . $statement->error;
}
