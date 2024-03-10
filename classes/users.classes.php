<?php
require_once 'dbh.classes.php';

class User extends Dbh {

    public function getAllUsers() {
        $sql = "SELECT users_id FROM users ORDER BY users_id ASC";
        $stmt = $this->connect()->prepare($sql);
        $stmt->execute();
        $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $users;
    }

}
