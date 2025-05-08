<?php
require_once BASE_PATH . '/models/Client.php';
require_once BASE_PATH . '/controllers/PhotoController.php';
require_once BASE_PATH . '/config/Database.php';

class ClientController
{

    // Fetch all clients with their photos
    public static function getAllClients()
    {
        $pdo = Database::connect();
        $stmt = $pdo->query("SELECT * FROM Clients");
        $clients = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return array_map(function ($client) {
            $photo = PhotoController::getPhoto('Client', $client['Client_ID']);
            return new Client(
                $client['Client_ID'],
                $client['First_Name'],
                $client['Last_Name'],
                $client['Email'],
                $client['Phone'],
                $client['Login_ID'],
                $client['Password'],
                $client['Address'],
                $photo
            );
        }, $clients);
    }

    // Fetch a single client by ID with their photo
    public static function getClientById($id)
    {
        $pdo = Database::connect();
        $stmt = $pdo->prepare("SELECT * FROM Clients WHERE Client_ID = :id");
        $stmt->execute(['id' => $id]);
        $client = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$client) return null;

        // Fetch client photo using PhotoController
        $photo = PhotoController::getPhoto('Client', $client['Client_ID']);

        return new Client(
            $client['Client_ID'],
            $client['First_Name'],
            $client['Last_Name'],
            $client['Email'],
            $client['Phone'],
            $client['Login_ID'],
            $client['Password'],
            $client['Address'],
            $photo
        );
    }

    // Add a new client
    public static function addClient($firstName, $lastName, $email, $phone, $loginId, $password, $addressJson)
    {
        $pdo = Database::connect();
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $pdo->prepare("
            INSERT INTO Clients (First_Name, Last_Name, Email, Phone, Login_ID, Password, Address) 
            VALUES (:firstName, :lastName, :email, :phone, :loginId, :password, :address)
        ");
        return $stmt->execute([
            'firstName' => $firstName,
            'lastName' => $lastName,
            'email' => $email,
            'phone' => $phone,
            'loginId' => $loginId,
            'password' => $hashedPassword,
            'address' => $addressJson
        ]);
    }

    // Update client details
    public static function updateClient($id, $firstName, $lastName, $email, $phone, $loginId, $addressJson)
    {
        $pdo = Database::connect();
        $stmt = $pdo->prepare("
            UPDATE Clients SET First_Name = :firstName, Last_Name = :lastName, Email = :email, 
            Phone = :phone, Login_ID = :loginId, Address = :address WHERE Client_ID = :id
        ");
        return $stmt->execute([
            'id' => $id,
            'firstName' => $firstName,
            'lastName' => $lastName,
            'email' => $email,
            'phone' => $phone,
            'loginId' => $loginId,
            'address' => $addressJson
        ]);
    }

    // Update client password
    public static function updateClientPassword($id, $password)
    {
        $hashedNewPassword = password_hash($password, PASSWORD_BCRYPT);

        $pdo = Database::connect();
        $stmt = $pdo->prepare("
            UPDATE Clients SET Password = :password WHERE Client_ID = :id
        ");
        return $stmt->execute([
            'id' => $id,
            'password' => $hashedNewPassword
        ]);
    }

    // Delete a client
    public static function deleteClient($id)
    {
        $pdo = Database::connect();
        $stmt = $pdo->prepare("DELETE FROM Clients WHERE Client_ID = :id");
        return $stmt->execute(['id' => $id]);
    }

    // Upload or update client profile photo
    public static function updateClientPhoto($clientId, $photoName, $photoAlt)
    {
        return PhotoController::updatePhoto('Client', $clientId, $photoName, $photoAlt);
    }

    // Authenticate client (Login)
    public static function authenticateClient($loginId, $password)
    {
        $pdo = Database::connect();
        $stmt = $pdo->prepare("SELECT * FROM Clients WHERE Login_ID = :loginId");
        $stmt->execute(['loginId' => $loginId]);
        $client = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($client && password_verify($password, $client['Password'])) {
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }
            session_regenerate_id(true); // ✅ Generate a fresh session ID after login
            // ✅ Store necessary data in session
            $_SESSION['user_id'] = $client['Client_ID'];
            $_SESSION['logged_in'] = true; // Track login state

            // Fetch client photo
            $photo = PhotoController::getPhoto('Client', $client['Client_ID']);
            return new Client(
                $client['Client_ID'],
                $client['First_Name'],
                $client['Last_Name'],
                $client['Email'],
                $client['Phone'],
                $client['Login_ID'],
                $client['Password'],
                $client['Address'],
                $photo
            );
        }
        return null;
    }
}

