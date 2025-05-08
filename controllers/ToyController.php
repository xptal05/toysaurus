<?php
require_once BASE_PATH . '/models/Toy.php';
require_once BASE_PATH . '/controllers/ToyMetaController.php';
require_once BASE_PATH . '/config/Database.php';

class ToyController {

    // Fetch all toys
    public static function getAllToys() {
        $pdo = Database::connect();
        $stmt = $pdo->query("SELECT * FROM Toys");
        $toys = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return array_map(function($toy) {
            return new Toy(
                $toy['Toy_ID'],
                $toy['Name'],
                $toy['T_Points'],
                $toy['Price_New'],
                $toy['Type'],
                $toy['Status'],
                $toy['State'],
                $toy['Date_Created'],
                $toy['History'],
                $toy['Categories']
            );
        }, $toys);
    }

        // Fetch meta attributes for a toy
        public static function getMetaByToyId($toyId) {
            return ToyMetaController::getMetaByToyId($toyId); // Ensure this function exists
        }

    // Fetch a single toy by ID (with meta attributes)
    public static function getToyById($id) {
        $pdo = Database::connect();
        $stmt = $pdo->prepare("SELECT * FROM Toys WHERE Toy_ID = :id");
        $stmt->execute(['id' => $id]);
        $toy = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$toy) return null;

        return new Toy(
            $toy['Toy_ID'],
            $toy['Name'],
            $toy['T_Points'],
            $toy['Price_New'],
            $toy['Type'],
            $toy['Status'],
            $toy['State'],
            $toy['Date_Created'],
            $toy['History'],
            $toy['Categories']
        );
    }

    public static function getToyByName($name) {
        $pdo = Database::connect();
        $stmt = $pdo->prepare("SELECT * FROM Toys WHERE Name = :name");
        $stmt->execute(['name' => $name]);
        $toy = $stmt->fetch(PDO::FETCH_ASSOC);
    
        if (!$toy) return null;
    
        return new Toy(
            $toy['Toy_ID'],
            $toy['Name'],
            $toy['T_Points'],
            $toy['Price_New'],
            $toy['Type'],
            $toy['Status'],
            $toy['State'],
            $toy['Date_Created'],
            $toy['History'],
            $toy['Categories']
        );
    }
    
    // Add a new toy
    public static function addToy($name, $tPoints, $priceNew, $type, $status, $state, $metaData = []) {
        $pdo = Database::connect();
        
        $history = json_encode([["date" => date("Y-m-d H:i:s"), "status" => $status]]);

        $stmt = $pdo->prepare("
            INSERT INTO Toys (Name, T_Points, Price_New, Type, Status, State, Date_Created, History) 
            VALUES (:name, :tPoints, :priceNew, :type, :status, :state, NOW(), :history)
        ");
        $success = $stmt->execute([
            'name' => $name, 
            'tPoints' => $tPoints, 
            'priceNew' => $priceNew, 
            'type' => $type, 
            'status' => $status, 
            'state' => $state, 
            'history' => $history
        ]);

        if ($success) {
            $toyId = $pdo->lastInsertId();

            // Insert meta attributes if provided
            foreach ($metaData as $metaKey => $metaValue) {
                ToyMetaController::addMeta($toyId, $metaKey, $metaValue);
            }
            return $toyId;
        }
        return false;
    }

    // Update toy details
    public static function updateToy($id, $name, $tPoints, $priceNew, $type, $status, $state) {
        $pdo = Database::connect();

        $stmt = $pdo->prepare("
            UPDATE Toys SET Name = :name, T_Points = :tPoints, Price_New = :priceNew, 
            Type = :type, Status = :status, State = :state, Date_Created = NOW()
            WHERE Toy_ID = :id
        ");
        return $stmt->execute([
            'id' => $id,
            'name' => $name, 
            'tPoints' => $tPoints, 
            'priceNew' => $priceNew, 
            'type' => $type, 
            'status' => $status, 
            'state' => $state
        ]);
    }

    // Update toy status (with history tracking)
    public static function updateToyStatus($id, $status) {
        $pdo = Database::connect();

        // Fetch current toy history
        $stmt = $pdo->prepare("SELECT History FROM Toys WHERE Toy_ID = :id");
        $stmt->execute(['id' => $id]);
        $toy = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$toy) return false;

        $history = json_decode($toy['History'], true);
        $history[] = ["date" => date("Y-m-d H:i:s"), "status" => $status];

        $stmt = $pdo->prepare("
            UPDATE Toys SET Status = :status, History = :history WHERE Toy_ID = :id
        ");
        return $stmt->execute([
            'id' => $id, 
            'status' => $status, 
            'history' => json_encode($history)
        ]);
    }

    // Delete a toy (also deletes meta attributes)
    public static function deleteToy($id) {
        $pdo = Database::connect();
        
        // Delete associated meta attributes
        //ToyMetaController::deleteMetaByToyId($id);

        $stmt = $pdo->prepare("DELETE FROM Toys WHERE Toy_ID = :id");
        return $stmt->execute(['id' => $id]);
    }
}
?>
