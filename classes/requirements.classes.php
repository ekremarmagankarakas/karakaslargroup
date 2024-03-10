<?php

class Requirement extends Dbh {

    public function submitRequirement($userId, $itemName, $price, $explanation) {
        $stmt = $this->connect()->prepare('INSERT INTO requirements (user_id, item_name, price, explanation) VALUES (?, ?, ?, ?);');

        if (!$stmt->execute([$userId, $itemName, $price, $explanation])) {
            // Handle error appropriately
            header("location: ../dashboard.php?error=stmtfailed");
            exit();
        }
    }

    public function getUserRequirements($userId) {
        try {
            $stmt = $this->connect()->prepare('SELECT * FROM requirements WHERE user_id = ? ORDER BY creation_date DESC');
            $stmt->execute([$userId]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error fetching user requirements: " . $e->getMessage());
            return [];
        }
    }

    public function getAllRequirements() {
        try {
            $stmt = $this->connect()->prepare('SELECT requirements.*, users_uid FROM requirements JOIN users ON requirements.user_id = users_id ORDER BY creation_date DESC');
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error fetching all requirements: " . $e->getMessage());
            return [];
        }
    }

    public function updateRequirementStatus($id, $status) {
        try {
            $stmt = $this->connect()->prepare('UPDATE requirements SET status = ? WHERE requirement_id = ?');
            $stmt->execute([$status, $id]);
        } catch (PDOException $e) {
            error_log("Error updating requirement status: " . $e->getMessage());
            // Handle error appropriately
        }
    }

    public function getRequirementsPaginated($userId, $page, $limit = 5, $filters = []) {
        $offset = ($page - 1) * $limit;
        $sql = ($_SESSION["usertype"] == "manager" OR $_SESSION["usertype"] == "accountant") ? 
                    'SELECT requirements.*, users_uid FROM requirements JOIN users ON requirements.user_id = users_id' :
                    'SELECT * FROM requirements WHERE user_id = :userId';
        $whereConditions = [];
        $sqlParams = [':limit' => $limit, ':offset' => $offset];
        if (!empty($filters['status'])) {
            $whereConditions[] = 'requirements.status = :status';
            $sqlParams[':status'] = $filters['status'];
        }
        if (($_SESSION["usertype"] == "manager" OR $_SESSION["usertype"] == "accountant") && !empty($filters['username'])) {
            $whereConditions[] = 'users_id = :username';
            $sqlParams[':username'] = $filters['username'];
        }
        if (!empty($filters['search'])) {
            $searchTerm = "%" . $filters['search'] . "%";
            $whereConditions[] = "(item_name LIKE :search OR explanation LIKE :search)";
            $sqlParams[':search'] = $searchTerm;
        }
        if (!empty($filters['dateRange'])) {
            $dateCondition = $this->getDateRangeCondition($filters['dateRange']);
            if ($dateCondition) {
                $whereConditions[] = $dateCondition;
            }
        }
        if (!empty($whereConditions)) {
            $sql .= ' WHERE ' . implode(' AND ', $whereConditions);
        }
        $sql .= " ORDER BY requirements.creation_date DESC LIMIT :limit OFFSET :offset";
        try {
            $stmt = $this->connect()->prepare($sql);
            foreach ($sqlParams as $param => &$value) {
                if ($param == ':limit' || $param == ':offset') {
                    $stmt->bindParam($param, $value, PDO::PARAM_INT);
                } else {
                    $stmt->bindParam($param, $value, PDO::PARAM_STR);
                }
            }
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error fetching paginated and filtered requirements: " . $e->getMessage());
            return [];
        }
    }

    public function getEmployeeRequirementsPaginated($userId, $page, $limit = 5, $filters = []) {
        $offset = ($page - 1) * $limit;
        $sql = 'SELECT * FROM requirements WHERE user_id = :userId';
        $whereConditions = [];
        $sqlParams = [':userId' => $userId, ':limit' => $limit, ':offset' => $offset];
        if (!empty($filters['status'])) {
            $whereConditions[] = 'status = :status';
            $sqlParams[':status'] = $filters['status'];
        }
        if (!empty($filters['search'])) {
            $searchTerm = "%" . $filters['search'] . "%";
            $whereConditions[] = "(item_name LIKE :search OR explanation LIKE :search)";
            $sqlParams[':search'] = $searchTerm;
        }
        if (!empty($filters['dateRange'])) {
            $dateCondition = $this->getDateRangeCondition($filters['dateRange']);
            if ($dateCondition) {
                $whereConditions[] = $dateCondition;
            }
        }
        if (!empty($whereConditions)) {
            $sql .= ' AND ' . implode(' AND ', $whereConditions);
        }
        $sql .= " ORDER BY creation_date DESC LIMIT :limit OFFSET :offset";
        try {
            $stmt = $this->connect()->prepare($sql);
            foreach ($sqlParams as $param => &$value) {
                if ($param == ':limit' || $param == ':offset') {
                    $stmt->bindParam($param, $value, PDO::PARAM_INT);
                } else {
                    $stmt->bindParam($param, $value, PDO::PARAM_STR);
                }
            }
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error fetching paginated requirements for employee: " . $e->getMessage());
            return [];
        }
    }


        public function getTotalEntries($userId = null, $filters = []) {
        $sql = "SELECT COUNT(*) AS total FROM requirements";
        $params = [];
        $conditions = [];
        if (!empty($filters['status'])) {
            $conditions[] = "status = ?";
            $params[] = $filters['status'];
        }
        if ($userId !== null) {
            $conditions[] = "user_id = ?";
            $params[] = $userId;
        }
        if (!empty($filters['search'])) {
            $search = "%" . $filters['search'] . "%";
            $conditions[] = "(item_name LIKE ? OR explanation LIKE ?)";
            array_push($params, $search, $search);
        }
        if (!empty($filters['username'])) {
            $sql = "SELECT COUNT(requirements.status) AS total FROM requirements JOIN users ON requirements.user_id = users_id";
            $conditions[] = "users_id = ?";
            $params[] = $filters['username'];
        }
        if (!empty($filters['dateRange'])) {
            $dateCondition = $this->getDateRangeCondition($filters['dateRange']);
            if ($dateCondition) {
                $conditions[] = $dateCondition;
            }
        }
        if (!empty($conditions)) {
            $sql .= " WHERE " . implode(' AND ', $conditions);
        }
        try {
            $stmt = $this->connect()->prepare($sql);
            $stmt->execute($params);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result['total'] ?? 0;
        } catch (PDOException $e) {
            error_log("Error fetching total entries with filters: " . $e->getMessage());
            return 0;
        }
    }

    public function getTotalEntriesByStatus($status = null, $userId = null, $filters = []) {
        $sql = "SELECT COUNT(*) AS total FROM requirements";
        $params = [];
        $conditions = [];
        if ($status !== null) {
            $conditions[] = "status = ?";
            $params[] = $status;
        }
        if ($userId !== null) {
            $conditions[] = "user_id = ?";
            $params[] = $userId;
        }
        if (!empty($filters['search'])) {
            $search = "%" . $filters['search'] . "%";
            $conditions[] = "(item_name LIKE ? OR explanation LIKE ?)";
            array_push($params, $search, $search);
        }
        if (!empty($filters['username'])) {
            $sql = "SELECT COUNT(requirements.status) AS total FROM requirements JOIN users ON requirements.user_id = users_id";
            $conditions[] = "users_id = ?";
            $params[] = $filters['username'];
        }
        if (!empty($filters['dateRange'])) {
            $dateCondition = $this->getDateRangeCondition($filters['dateRange']);
            if ($dateCondition) {
                $conditions[] = $dateCondition;
            }
        }
        if (!empty($conditions)) {
            $sql .= " WHERE " . implode(' AND ', $conditions);
        }
        try {
            $stmt = $this->connect()->prepare($sql);
            $stmt->execute($params);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result['total'] ?? 0;
        } catch (PDOException $e) {
            error_log("Error fetching total entries with filters: " . $e->getMessage());
            return 0;
        }
    }

    public function getTotalPrice($userId = null, $filters = []) {
        $sql = "SELECT SUM(price) AS totalPrice FROM requirements";
        $params = [];
        $conditions = [];

        if ($userId !== null) {
            $conditions[] = "user_id = ?";
            $params[] = $userId;
        }
        if (!empty($filters['search'])) {
            $search = "%" . $filters['search'] . "%";
            $conditions[] = "(item_name LIKE ? OR explanation LIKE ?)";
            array_push($params, $search, $search);
        }
        if (!empty($filters['status'])) {
            $conditions[] = "status = ?";
            $params[] = $filters['status'];
        }
        if (!empty($filters['username'])) {
            $sql = "SELECT SUM(requirements.price) AS totalPrice FROM requirements JOIN users ON requirements.user_id = users_id";
            $conditions[] = "users_id = ?";
            $params[] = $filters['username'];
        }
        if (!empty($filters['dateRange'])) {
            $dateCondition = $this->getDateRangeCondition($filters['dateRange']);
            if ($dateCondition) {
                $conditions[] = $dateCondition;
            }
        }
        if (!empty($conditions)) {
            $sql .= " WHERE " . implode(' AND ', $conditions);
        }
        try {
            $stmt = $this->connect()->prepare($sql);
            $stmt->execute($params);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result['totalPrice'] ?? 0;
        } catch (PDOException $e) {
            error_log("Error fetching total price with filters: " . $e->getMessage());
            return 0;
        }
    }


    private function getDateRangeCondition($dateRange) {
        switch ($dateRange) {
            case 'week':
                return "requirements.creation_date >= DATE_SUB(CURDATE(), INTERVAL 1 WEEK)";
            case 'month':
                return "requirements.creation_date >= DATE_SUB(CURDATE(), INTERVAL 1 MONTH)";
            case 'year':
                return "requirements.creation_date >= DATE_SUB(CURDATE(), INTERVAL 1 YEAR)";
            default:
                return null;
        }
    }

    public function getTotalPriceByStatus($status, $userId = null, $filters = []) {
        $sql = "SELECT SUM(price) AS totalPrice FROM requirements";
        $params = [];
        $conditions = ["status = ?"];
        $params[] = $status;

        if ($userId !== null) {
            $conditions[] = "user_id = ?";
            $params[] = $userId;
        }

        if (!empty($filters['search'])) {
            $search = "%" . $filters['search'] . "%";
            $conditions[] = "(item_name LIKE ? OR explanation LIKE ?)";
            array_push($params, $search, $search);
        }

        if (!empty($filters['username'])) {
            $sql = "SELECT SUM(requirements.price) AS totalPrice FROM requirements JOIN users ON requirements.user_id = users_id";
            $conditions[] = "users_id = ?";
            $params[] = $filters['username'];
        }

        if (!empty($filters['dateRange'])) {
            $dateCondition = $this->getDateRangeCondition($filters['dateRange']);
            if ($dateCondition) {
                $conditions[] = $dateCondition;
            }
        }

        if (!empty($conditions)) {
            $sql .= " WHERE " . implode(' AND ', $conditions);
        }

        try {
            $stmt = $this->connect()->prepare($sql);
            $stmt->execute($params);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result['totalPrice'] ?? 0;
        } catch (PDOException $e) {
            error_log("Error fetching total price by status with filters: " . $e->getMessage());
            return 0;
        }
    }



}
