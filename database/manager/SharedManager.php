<?php
// include_once('../config/Database.php');
// include_once('../models/Shared.php');
// include_once('../config/Template.php');

class SharedManager implements Template
{
    private $conn;
    private $table = 'shared';
    private $query;

    public function __construct()
    {
        $this->conn = new Database();
        $this->conn = $this->conn->connect();
    }

    public function create($shared)
    {
        $this->query = 'INSERT INTO ' . $this->table . '(`user_id`, `postit_id`) VALUES (?,?);';
        $statement = $this->conn->prepare($this->query);
        if ($statement->bind_param('ii', $shared->getUser_id(), $shared->getPostit_id())) {
            if ($statement->execute()) {
                $shared->setId($statement->insert_id);
                return true;
            }
            return "Something went wrong ! (" . $statement->errno . ") " . $statement->error;
        }
        return "Can't bind params (" . $statement->errno . ") " . $statement->error;
    }

    public function read_all()
    {
        $this->query = 'SELECT * FROM ' . $this->table . ';';
        $statement = $this->conn->prepare($this->query);
        if ($statement->execute()) {
            $result = $statement->get_result();
            $num = $result ? $result->num_rows : 0;
            if ($num > 0) {
                $shared_array = array();
                while ($row = $result->fetch_assoc()) {
                    extract($row);

                    $shared_item = new Shared($postit_id, $user_id, $id);
                    array_push($shared_array, $shared_item);
                }
                return $shared_array;
            }
            return 'There is nothing in the table !!';
        }
    }

    public function read_one($shared_id)
    {
        $this->query = 'SELECT id, user_id, postit_id FROM ' . $this->table . ' WHERE id = ?';
        $statement = $this->conn->prepare($this->query);

        if ($statement->bind_param('i', $shared_id)) {
            if ($statement->execute()) {
                $result = $statement->get_result();
                $num = $result ? $result->num_rows : 0;
                if ($num > 0) {
                    extract($result->fetch_assoc());
                    return new Shared($postit_id, $user_id, $id);
                }
                return "There is no result :(";
            }
            return "Can't bind params (" . $statement->errno . ") " . $statement->error;
        }
        return "Can't prepare the query (" . $statement->errno . ") " . $statement->error;
    }



    public function read_all_for_one_user($userId)
    {
        $this->query = 'SELECT id, user_id, postit_id FROM ' . $this->table . ' WHERE user_id = ?';
        $statement = $this->conn->prepare($this->query);

        if ($statement->bind_param('s', $userId)) {
            if ($statement->execute()) {
                $result = $statement->get_result();
                $num = $result ? $result->num_rows : 0;
                if ($num > 0) {
                    $shared_item = array();
                    while ($row = $result->fetch_assoc()) {
                        extract($row);
                        $shared_item = new Shared($postit_id, $user_id, $id);
                        array_push($postit_array, $shared_item);
                    }
                    return $shared_item;
                }
            }
            return "Can't bind params (" . $statement->errno . ") " . $statement->error;
        }
        return "Can't prepare the query (" . $statement->errno . ") " . $statement->error;
    }



    public function update($shared){}

    public function delete($id)
    {
        $this->query = 'DELETE FROM ' . $this->table . ' WHERE id = ?;';
        $statement = $this->conn->prepare($this->query);
        if ($statement->bind_param('i', $id)) {
            if ($statement->execute()) {
                return true;
            }
            return "Something went wrong ! (" . $statement->errno . ") " . $statement->error;
        }
        return "Can't bind params (" . $statement->errno . ") " . $statement->error;
    }



}
