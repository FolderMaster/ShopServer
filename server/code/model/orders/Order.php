<?php

namespace Model\Orders;

require_once 'code/model/items/ItemSet.php';
require_once 'code/control/DataBaseConnection.php';

use Model\Items\ItemSet;
use const Control\DataBaseConnection;
use \Exception;
use \DateTime;

class Order
{
    protected int $id;

    protected int $userId;

    protected array $itemSets;

    protected array $history;

    public function __construct(int $id)
    {
        $this->id = $id;
        $this->load();
    }

    private function load(): void
    {
        $statement = DataBaseConnection->prepare('SELECT * FROM Orders 
        WHERE Id = :id');
        $statement->bindValue(':id', $this->id);
        $statement->execute();
        if ($row = $statement->fetch()) {
            $this->userId = $row['UserId'];
        } else {
            throw new Exception('Fail to load order ' . $this->id);
        }

        $statement = DataBaseConnection->prepare('SELECT * FROM OrderItemSets 
        WHERE OrderId = :orderId');
        $statement->bindValue(':orderId', $this->id);
        $statement->execute();
        $this->itemSets = [];
        while ($row = $statement->fetch()) {
            $this->itemSets[] = new ItemSet($row['ItemId'], $row['Count']);
        }

        $statement = DataBaseConnection->prepare('SELECT * FROM OrderHistories 
        WHERE OrderId = :orderId ORDER BY StartDateTime ASC');
        $statement->bindValue(':orderId', $this->id);
        $statement->execute();
        $this->history = [];
        while ($row = $statement->fetch()) {
            $this->history[$row['StartDateTime']] = $row['OrderStatus'];
        }
    }

    public static function getOrdersByUserId(int $userId): array
    {
        $statement = DataBaseConnection->prepare('SELECT Id FROM Orders 
        WHERE UserId = :userId');
        $statement->bindValue(':userId', $userId);
        $statement->execute();
        $result = [];
        while ($row = $statement->fetch()) {
            $result[] = new Order($row['Id']);
        }
        return $result;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getHistory(): array
    {
        return $this->history;
    }

    public function getItemSets(): array
    {
        return $this->itemSets;
    }

    public function getPaymentDateTime(): DateTime | null
    {
        foreach ($this->history as $key => $value) {
            if ($value == 'Оплата') {
                return new DateTime($key);
            }
        }
        return null;
    }

    public function getLastStatus(): string
    {
        return $this->history[array_key_last($this->history)];
    }

    public function getLastDate(): DateTime | null
    {
        $key = array_key_last($this->history);
        return $key ? new DateTime($key) : null;
    }

    public static function createOrder(int $userId): bool
    {
        DataBaseConnection->beginTransaction();

        $statement = DataBaseConnection->prepare('SELECT * FROM Carts 
        WHERE UserId = :userId');
        $statement->bindValue(':userId', $userId);
        $statement->execute();
        $itemSets = [];
        while ($row = $statement->fetch()) {
            $itemSets[] = new ItemSet($row['ItemId'], $row['Count']);
        }

        foreach ($itemSets as $itemSet) {
            $statement = DataBaseConnection->prepare('DELETE FROM Carts 
            WHERE UserId = :userId AND ItemId = :itemId AND Count = :count');
            $statement->bindValue(':userId', $userId);
            $statement->bindValue(':itemId', $itemSet->getItemId());
            $statement->bindValue(':count', $itemSet->getCount());
            $statement->execute();
            if ($statement->rowCount() == 0) {
                $statement->closeCursor();
                DataBaseConnection->rollBack();
                return false;
            }
        }

        $statement = DataBaseConnection->prepare('INSERT INTO Orders (UserId) 
        VALUES (:userId)');
        $statement->bindValue(':userId', $userId);
        $statement->execute();
        if ($statement->rowCount() == 0) {
            $statement->closeCursor();
            DataBaseConnection->rollBack();
            return false;
        }

        DataBaseConnection->exec('SET @id = LAST_INSERT_ID()');

        foreach ($itemSets as $itemSet) {
            $statement = DataBaseConnection->prepare('INSERT INTO OrderItemSets 
            (OrderId, ItemId, Count) VALUES (@id, :itemId, :count)');
            $statement->bindValue(':itemId', $itemSet->getItemId());
            $statement->bindValue(':count', $itemSet->getCount());
            $statement->execute();
            if ($statement->rowCount() == 0) {
                $statement->closeCursor();
                DataBaseConnection->rollBack();
                return false;
            }
        }

        $statement = DataBaseConnection->prepare('INSERT INTO OrderHistories 
        (OrderId, OrderStatus) VALUES (@id, :orderStatus)');
        $statement->bindValue(':orderStatus', 'Оформление');
        $statement->execute();
        if ($statement->rowCount() == 0) {
            $statement->closeCursor();
            DataBaseConnection->rollBack();
            return false;
        }
        $statement->closeCursor();
        DataBaseConnection->commit();
        return true;
    }
}
