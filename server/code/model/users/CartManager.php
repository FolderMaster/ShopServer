<?php

namespace Model\Users;

require_once 'code/model/items/ItemSet.php';
require_once 'code/control/DataBaseConnection.php';

use Model\Items\ItemSet;
use const Control\DataBaseConnection;

class CartManager
{
    public static function getItems(int $userId): array
    {
        $statement = DataBaseConnection->prepare('SELECT ItemId, Count 
        FROM Carts WHERE UserId = :userId');
        $statement->bindValue(':userId', $userId);
        $statement->execute();
        $result = [];
        while ($row = $statement->fetch()) {
            $result[] = new ItemSet($row['ItemId'], $row['Count']);
        }
        return $result;
    }

    public static function getItemCount(int $userId, int $itemId): int
    {
        $statement = DataBaseConnection->prepare('SELECT Count FROM Carts 
        WHERE UserId = :userId AND ItemId = :itemId');
        $statement->bindValue(':userId', $userId);
        $statement->bindValue(':itemId', $itemId);
        $statement->execute();
        if ($row = $statement->fetch()) {
            return $row['Count'];
        } else {
            return 0;
        }
    }

    public static function checkItemSet(int $userId, int $itemId): bool
    {
        $statement = DataBaseConnection->prepare('SELECT 1 FROM Carts
        WHERE ItemId = :itemId AND UserId = :userId');
        $statement->bindValue(':itemId', $itemId);
        $statement->bindValue(':userId', $userId);
        $statement->execute();
        return $statement->rowCount() > 0;
    }

    public static function addItemSet(
        int $userId,
        int $itemId,
        int $count
    ): bool {
        $statement = DataBaseConnection->prepare('INSERT INTO Carts 
        (ItemId, UserId, Count) VALUES (:itemId, :userId, :count)');
        $statement->bindValue(':itemId', $itemId);
        $statement->bindValue(':userId', $userId);
        $statement->bindValue(':count', $count);
        $statement->execute();
        return $statement->rowCount() > 0;
    }

    public static function changeItemSet(
        int $userId,
        int $itemId,
        int $count
    ): bool {
        $statement = DataBaseConnection->prepare('UPDATE Carts 
        SET Count = :count WHERE ItemId = :itemId AND UserId = :userId');
        $statement->bindValue(':itemId', $itemId);
        $statement->bindValue(':userId', $userId);
        $statement->bindValue(':count', $count);
        $statement->execute();
        return $statement->rowCount() > 0;
    }

    public static function removeItemSet(int $userId, int $itemId): bool
    {
        $statement = DataBaseConnection->prepare('DELETE FROM Carts 
        WHERE ItemId = :itemId AND UserId = :userId');
        $statement->bindValue(':itemId', $itemId);
        $statement->bindValue(':userId', $userId);
        $statement->execute();
        return $statement->rowCount() > 0;
    }
}
