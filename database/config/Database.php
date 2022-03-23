<?php

class Database {
    
    private $url='localhost';
    private $username='root';
    private $password='';
    private $db_name = 'projet_ter';
    private $conn;
    
    public function connect(){
        $this->conn = mysqli_connect($this->url,$this->username,$this->password,$this->db_name);
        if(!$this->conn){
            die('Could not Connect My Sql:' .mysql_error());
            return;
        }
        echo 'Connection established :)';
        return $this->conn;
    }
}