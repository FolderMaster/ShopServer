<?php

namespace Model\Items;

require_once 'code/control/DataBaseConnection.php';

use const Control\DataBaseConnection;
use \DateTime;

class ItemPriceManager
{
    public static function getItemPrice(
        int $itemId,
        int $currencyUnitId,
        DateTime $dateTime = new DateTime()
    ): float | null {
        $statement = DataBaseConnection->prepare('SELECT Price 
        FROM ItemPriceHistories  WHERE ItemId = :itemId AND 
        CurrencyUnitId = :currencyUnitId AND  StartDateTime <= :dateTime 
        ORDER BY StartDateTime DESC LIMIT 1');
        $statement->bindValue(':itemId', $itemId);
        $statement->bindValue(':currencyUnitId', $currencyUnitId);
        $statement->bindValue(':dateTime', $dateTime->format('Y-m-d H:i:s'));
        $statement->execute();
        if ($row = $statement->fetch()) {
            return $row['Price'];
        } else {
            return null;
        }
    }
}
