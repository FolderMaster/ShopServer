<?php

namespace Model\Users;

require_once 'code/control/DataBaseConnection.php';

use const Control\DataBaseConnection;

class FavoritesManager
{
    public static function getItems(int $userId): array
    {
        $statement = DataBaseConnection->prepare('SELECT ItemId FROM Favorites 
        WHERE UserId = :id');
        $statement->bindValue(':id', $userId);
        $statement->execute();
        $result = [];
        while ($row = $statement->fetch()) {
            $result[] = $row['ItemId'];
        }
        return $result;
    }

    public static function checkItem(int $userId, int $itemId): bool
    {
        $statement = DataBaseConnection->prepare('SELECT 1 FROM Favorites 
        WHERE ItemId = :itemId AND UserId = :userId');
        $statement->bindValue(':itemId', $itemId);
        $statement->bindValue(':userId', $userId);
        $statement->execute();
        return $statement->rowCount() > 0;
    }

    public static function addItem(int $userId, int $itemId): bool
    {
        $statement = DataBaseConnection->prepare('INSERT INTO Favorites 
        (ItemId, UserId) VALUES (:itemId, :userId)');
        $statement->bindValue(':itemId', $itemId);
        $statement->bindValue(':userId', $userId);
        $statement->execute();
        return $statement->rowCount() > 0;
    }

    public static function removeItem(int $userId, int $itemId): bool
    {
        $statement = DataBaseConnection->prepare('DELETE FROM Favorites 
        WHERE ItemId = :itemId AND UserId = :userId');
        $statement->bindValue(':itemId', $itemId);
        $statement->bindValue(':userId', $userId);
        $statement->execute();
        return $statement->rowCount() > 0;
    }
}
