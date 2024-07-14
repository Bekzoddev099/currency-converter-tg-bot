<?php

declare(strict_types=1);

require 'currency.php';

class SaveUsersData 
{
    private PDO $pdo;
    private Currency $bot;

    public function __construct() 
    {
        $host = 'localhost';
        $dbname = 'currency_converter';
        $username = 'beko';
        $password = '9999';

        $this->bot = new Currency();

        try {
            $this->pdo = new PDO(
                "mysql:host={$host};dbname={$dbname}", 
                $username, 
                $password
            );
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("Database connection failed: " . $e->getMessage());
        }
    }

    public function saveuser (int $chat_id, string $callback_data): void
    {
        $query = "INSERT INTO usersave (chat_id, callback_data, data_time) VALUES (:chat_id, :callback_data, :data_time)";
        $now = date('Y-m-d H:i:s');

        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':chat_id', $chat_id);
        $stmt->bindParam(':callback_data', $callback_data);
        $stmt->bindParam(':data_time', $now);
        $stmt->execute();
    }

    public function getuser (float $amount, int $chat_id): string
    {
        $query = "SELECT callback_data FROM usersave WHERE chat_id = :chat_id ORDER BY data_time DESC LIMIT 1";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam('chat_id', $chat_id, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$result) {
            return 'Error: No conversion data found.';
        }

        $stateName = $result['callback_data'];
        $toConverter = explode("2", $stateName);

        $response = $this->bot->getAmount($toConverter[1], $amount);

        return sprintf("Converted amount: %.2f", $response);
    }

    public function allusersinfo(int $chat_id, string $convertion_type, float $user_amount): void
    {
        $query = "INSERT INTO userinfo (chat_id, convertion_type, user_amount, data_time) VALUES (:chat_id, :convertion_type, :user_amount, :data_time)";
        $now = date('Y-m-d H:i:s');

        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':chat_id', $chat_id);
        $stmt->bindParam(':convertion_type', $convertion_type);
        $stmt->bindParam(':user_amount', $user_amount);
        $stmt->bindParam(':data_time', $now);
        $stmt->execute();
    }

    public function sendConvertionType(int $chat_id): ?string
    {
        $query = "SELECT callback_data FROM usersave WHERE chat_id = :chat_id ORDER BY data_time DESC LIMIT 1";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':chat_id', $chat_id, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return $result['callback_data'] ?? null;
    }

    public function sendAllUsersInfo(): array
    {
        $query = "SELECT * FROM userinfo";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute();
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $results;
    }
}