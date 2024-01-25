<?php

namespace Model\Items;

require_once 'code/control/DataBaseConnection.php';

use const Control\DataBaseConnection;

class StoredItemManager
{
    public static function getCountOfItem(int $itemId): int
    {
        $statement = DataBaseConnection->prepare('SELECT SUM(Count) AS Count 
        FROM StoredItemSets WHERE ItemId = :itemId');
        $statement->bindValue(':itemId', $itemId);
        $statement->execute();
        return $statement->fetch()['Count'] ?? 0;
    }

    public static function getStoragesKeepItem(int $itemId): array
    {
        $statement = DataBaseConnection->prepare('SELECT StorageId 
        FROM StoredItemSets  WHERE ItemId = :itemId');
        $statement->bindValue(':itemId', $itemId);
        $statement->execute();
        $result = [];
        while ($row = $statement->fetch()) {
            $result[] = $row['StorageId'];
        }
        return $result;
    }
}
