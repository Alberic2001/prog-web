<?php
/*
include_once('../config/Database.php');
include_once('../config/Template.php');
include_once('../models/User.php');
*/
class UserManager
{
    private $conn;
    private $table = 'user';
    private $query;
    public function __construct()
    {
        $this->conn = new Database();
        $this->conn = $this->conn->connect();
    }

    public function sign_up($user)
    {
        $this->query = 'INSERT INTO ' . $this->table . '(`email`, `password`, `surname`, `lastname`, `birth_date`) VALUES (?,?,?,?,?);';
        $statement = $this->conn->prepare($this->query);
        $user->setBirth_date($user->getBirth_date());
        $hashed_password = password_hash($user->getPassword(), PASSWORD_BCRYPT);
        if ($statement->bind_param('sssss', $user->getEmail(), $hashed_password, $user->getSurname(), $user->getLastname(), $user->getBirth_date())) {
            if ($statement->execute()) {
                $user->setId($statement->insert_id);
                return true;
            }
            return "Something went wrong ! (" . $statement->errno . ") " . $statement->error;
        }
        return "Can't bind params (" . $statement->errno . ") " . $statement->error;
    }

    public function sign_in($email, $input_password)
    {

        $this->query = 'SELECT * FROM ' . $this->table . ' WHERE email = ?';
        $statement = $this->conn->prepare($this->query);
        $message = null;
        if ($statement->bind_param('s', trim($email))) {
            if ($statement->execute()) {
                $result = $statement->get_result();
                $num = $result ? $result->num_rows : 0;
                if ($num > 0) {
                    extract($result->fetch_assoc());
                    $log = password_verify($input_password, $password) ? 'Successfully connected' : 'Passwword incorrect';
                    if (!$log) {
                        return;
                    }
                    return new User($email, $password, $surname, $lastname, $birth_date, $id);
                }
                return "There is no result :(";
            }
            return "Can't bind params (" . $statement->errno . ") " . $statement->error;
        }
        return "Can't prepare the query (" . $statement->errno . ") " . $statement->error;
    }
}
