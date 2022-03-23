<?php
/*
include_once('../config/Database.php');
include_once('../config/Template.php');
include_once('../models/Postit.php');
*/
class PostitManager implements Template
{
    private $conn;
    private $table = 'postit';
    private $query;

    public function __construct()
    {
        $this->conn = new Database();
        $this->conn = $this->conn->connect();
    }

    public function create($postit)
    {
        $this->query = 'INSERT INTO ' . $this->table . '(`title`, `content`, `date`, `user_id`) VALUES (?,?,?,?);';
        $statement = $this->conn->prepare($this->query);
        if ($statement->bind_param('sssi', ...[$postit->getTitle(), $postit->getContent(), $postit->getDate()->format('Y-m-d H:i:s'), $postit->getUser_id()])) {
            if ($statement->execute()) {
                $postit->setId($statement->insert_id);
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
                $postit_array = array();
                while ($row = $result->fetch_assoc()) {
                    extract($row);
                    $postit_item = new Postit($title, $content, $date, $user_id, $id);
                    array_push($postit_array, $postit_item);
                }
                return $postit_array;
            }
            return 'There is nothing in the table !!';
        }
    }

    public function read_one($postitId)
    {
        $this->query = 'SELECT id, title, content, date, user_id FROM ' . $this->table . ' WHERE id = ?';
        $statement = $this->conn->prepare($this->query);
        if ($statement->bind_param('s', $postitId)) {
            if ($statement->execute()) {
                $result = $statement->get_result();
                $num = $result ? $result->num_rows : 0;
                if ($num > 0) {
                    extract($result->fetch_assoc());
                    return new Postit($title, $content, $date, $user_id, $id);
                }
                return "There is no result :(";
            }
            return "Can't bind params (" . $statement->errno . ") " . $statement->error;
        }
        return "Can't prepare the query (" . $statement->errno . ") " . $statement->error;
    }

    public function read_all_for_one_user($userId)
    {
        $this->query = 'SELECT id, title, content, date, user_id FROM ' . $this->table . ' WHERE user_id = ?';
        $statement = $this->conn->prepare($this->query);
        if ($statement->bind_param('s', $userId)) {
            if ($statement->execute()) {
                $result = $statement->get_result();
                $num = $result ? $result->num_rows : 0;
                if ($num > 0) {
                    $postit_array = array();
                    while ($row = $result->fetch_assoc()) {
                        extract($row);
                        $postit_item = new Postit($title, $content, $date, $user_id, $id);
                        array_push($postit_array, $postit_item);
                    }
                    return $postit_array;
                }
            }
            return "Can't bind params (" . $statement->errno . ") " . $statement->error;
        }
        return "Can't prepare the query (" . $statement->errno . ") " . $statement->error;
    }

    public function update($postit)
    {
        $this->query = 'UPDATE ' . $this->table . ' SET `title`=?,`content`=?,`user_id`=? WHERE id = ?;';
        $statement = $this->conn->prepare($this->query);
        if ($statement->bind_param('ssii', ...[$postit->geTitle(), $postit->getContent(), $postit->getUser_id(), $postit->getId()])) {
            if ($statement->execute()) {
                $postit->setId($statement->insert_id);
                return true;
            }
            return "Something went wrong ! (" . $statement->errno . ") " . $statement->error;
        }
        return "Can't bind params (" . $statement->errno . ") " . $statement->error;
    }

    public function delete($postit_id)
    {
        $this->query = 'DELETE FROM ' . $this->table . ' WHERE id = ?;';
        $statement = $this->conn->prepare($this->query);
        if ($statement->bind_param('i', $postit_id)) {
            if ($statement->execute()) {
                return true;
            }
            return "Something went wrong ! (" . $statement->errno . ") " . $statement->error;
        }
        return "Can't bind params (" . $statement->errno . ") " . $statement->error;
    }
}
