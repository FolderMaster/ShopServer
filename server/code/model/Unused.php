<?php

function ProcessOrderDelivery(int $userId, array $itemData): bool
{
    $connection = ConnectToDataBase();

    $result = $connection->prepare('DELETE FROM Carts WHERE UserId = :userId');
    $result->bindValue(':userId', $userId);

    $result = $connection->prepare('INSERT INTO Orders (UserId) VALUES (:userId)');
    $result->bindValue(':userId', $userId);

    $connection->exec('SET @id = LAST_INSERT_ID()');

    $result = $connection->prepare('INSERT INTO
    OrderHistories (OrderId, OrderStatusName) VALUES
    (@id, :orderStatus)');
    $result->bindValue(':orderStatus', 'Оформление');

    $result = $connection->prepare('INSERT INTO StoredOrderItemSets
    (StorageId, ItemId, OrderId, Count) VALUES
    (:storageId, :itemId, @id, :count)');
    //$result->bindValue(':storageId', $storageId);
    //$result->bindValue(':itemId', $itemId);
    //$result->bindValue(':count', $count);

    return true;
}

function ProcessOrderPayment(int $userId, int $orderId): bool
{
    $connection = ConnectToDataBase();

    $result = $connection->prepare('INSERT INTO OrderItemSets
    (OrderId, ItemId, CurrencyUnit, Count) VALUES
    (:orderId, :itemId, :currencyUnit, :count)');
    //$result->bindValue(':orderId', $orderId);
    //$result->bindValue(':itemId', $itemId);
    //$result->bindValue(':currencyUnit', $currencyUnit);
    //$result->bindValue(':count', $count);

    return true;
}
