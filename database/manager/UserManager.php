<?php
$path = realpath(dirname(__DIR__) . '/.');
require_once($path.'/config/Database.php');
require_once($path.'/models/helper.php');
define('USER', 'user');

function email_exists($conn, $user)
{
    $query = 'SELECT * FROM ' . USER . ' where email=?';
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
        $query = 'INSERT INTO ' . USER . '(`email`, `password`, `surname`, `lastname`, `birth_date`) VALUES (?,?,?,?,?);';
        $statement = $conn->prepare($query);
        // $user->setBirth_date($user->getBirth_date());
        $hashed_password = password_hash($user['password'], PASSWORD_BCRYPT);
        if ($statement->bind_param('sssss', $user['email'], $hashed_password, $user['surname'], $user['lastname'], $user['birth_date'])) {
            if ($statement->execute()) {
                $user['id'] = $statement->insert_id;
                return array('success' => true, 'id'=>$user['id'], 'surname'=>$user['surname'], 'lastname'=>$user['lastname']);
            }
            message_builder(false, 'database' ,'database error');
        }
        message_builder(false, 'database', 'database error');
    }
    message_builder(false, 'email' , 'Email already exists');
}

function sign_in($email, $input_password)
{
    $conn = connect();
    $query = 'SELECT * FROM ' . USER . ' WHERE email = ?';
    $statement = $conn->prepare($query);
    if ($statement->bind_param('s', $email)) {
        if ($statement->execute()) {
            $result = $statement->get_result();
            $num = $result ? $result->num_rows : 0;
            if ($num > 0) {
                extract($result->fetch_assoc());
                if (password_verify($input_password, $password)) {
                    return array('success' => true, 'id'=>$id,'email' => $email ,'surname' => $surname, 'lastname' => $lastname);
                }
                mysqli_close($conn);
                message_builder(false,  'password' ,'Password incorrect :(');
            }
            message_builder(false, 'email', 'Email does not exist :(');
        }
        message_builder(false, 'database' , 'database error');
    }
    message_builder(false, 'database' , 'database error');
}

function read_all(){
    $conn = connect();
    $query = 'SELECT * FROM ' . USER . ';';
    $statement = $conn->prepare($query);
    if ($statement->execute()) {
        $result = $statement->get_result();
        $num = $result ? $result->num_rows : 0;
        if ($num > 0) {
            $user_array = array();
            while ($row = $result->fetch_assoc()) {
                extract($row);
                $user = user_builder($email, '', $surname, $lastname, $birth_date, $id);
                array_push($user_array, $user);
            }
            mysqli_close($conn);
            return $user_array;
        }
        return 'There is nothing in the table !!';
    }
}

function get_email_for_userId($userId){
    $conn = connect();
    $query = 'SELECT email FROM user WHERE id = ?';
    $statement = $conn->prepare($query);
    if ($statement->bind_param('i', $userId)) {
        if ($statement->execute()) {
            $result = $statement->get_result();
            $num = $result ? $result->num_rows : 0;
            if ($num > 0) {
                extract($result->fetch_assoc());
                mysqli_close($conn);
                return $email;
            }
            return "There is no result :(";
        }
        return "Can't bind params (" . $statement->errno . ") " . $statement->error;
    }
    return "Can't prepare the query (" . $statement->errno . ") " . $statement->error;
}