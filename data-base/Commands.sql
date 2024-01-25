USE ShopDb;

UPDATE Files
SET Data = ''
WHERE Id IN (SELECT Avatar FROM Users WHERE UserId = 1);
UPDATE Users
SET FullName = '', BirthDate = ''
WHERE UserId = 1;
UPDATE Users
SET EmailAddress = ''
WHERE UserId = 1;
UPDATE Users
SET PhoneNumber = ''
WHERE UserId = 1;
UPDATE Users
SET Password = ''
WHERE UserId = 1;

DELETE FROM Carts
WHERE ItemId = 1 AND UserId = 1;
UPDATE Carts
SET Count = 1
WHERE ItemId = 1 AND UserId = 1;
INSERT INTO Carts
(ItemId, UserId, Count) VALUES
(1, 1, 1);

DELETE FROM Favorites
WHERE ItemId = 1 AND UserId = 1;
INSERT INTO Favorites
(ItemId, UserId) VALUES
(1, 1);

START TRANSACTION;
INSERT INTO Orders (UserId) VALUES
(1);
SET @id = (SELECTLAST_INSERT_ID());
INSERT INTO PickupPointOrders (Id, PickupPointId) VALUES
(@id, 1);
INSERT INTO OrderItemSets (OrderId, ItemId, CurrencyUnit, Count) VALUES
(@id, 1, 'Рубль', 1), (@id, 2, 'Рубль', 2), (@id, 3, 'Рубль', 1);
INSERT INTO OrderHistories (OrderId, OrderStatusName) VALUES
(@id, 'Оформление');
SELECT @id;
COMMIT;

START TRANSACTION;
INSERT INTO Files
(Source, FullName, Data) VALUES
('/resources/images/headphones.jpg', 'headphones.jpg', 'headphones.jpg');
SET @id = LAST_INSERT_ID();
INSERT INTO Users
(FullName, Password, Avatar, BirthDate, Email, PhoneNumber, Address, Role, Priority) VALUES
('Пчелинцев Андрей Александрович', '1056', @id, '2001-10-11', 'andreypchelintsev02@mail.ru',
'79008783468', 'Россия, город Томск', 'Админ', 1);
COMMIT;

SELECT ItemId FROM Favorites WHERE UserId IN(SELECT Id FROM Users WHERE Email = '' AND Password = '');
SELECT ItemId, Count FROM Carts WHERE UserId IN(SELECT Id FROM Users WHERE Email = '' AND Password = '');

SELECT SUM(Count) AS Count FROM StoredItemSets WHERE ItemId = 2;
SELECT StorageId FROM StoredItemSets WHERE ItemId = 2;
SELECT Price FROM ItemPriceHistories WHERE ItemId = 3 AND CurrencyUnitId = 1 AND StartDateTime <= '2024-01-17 15:50:14' ORDER BY StartDateTime DESC LIMIT 1;

WITH RECURSIVE SectionsByParentSectionId (Id,  ParentSectionId) AS (
SELECT Id, ParentSectionId FROM Sections WHERE ParentSectionId <=> NULL
UNION
SELECT s.Id, s.ParentSectionId FROM Sections s
INNER JOIN SectionsByParentSection sbps ON s.ParentSectionId = sbps.Id
)
SELECT * FROM SectionsByParentSectionId;

WITH RECURSIVE SectionsByChildSectionId (Id, ParentSectionId) AS (
    SELECT Id, ParentSectionId
    FROM Sections
    WHERE Id = 2
    UNION
    SELECT s.Id, s.ParentSectionId
    FROM Sections s
    INNER JOIN SectionsByChildSectionId sbcs ON s.Id = sbcs.ParentSectionId
)
SELECT * FROM SectionsByChildSectionId;

SELECT * FROM OrderHistories WHERE OrderId = 1 ORDER BY StartDateTime ASC;

SELECT CharacteristicId FROM SectionCharacteristics WHERE SectionId = 2;
SELECT * FROM Items WHERE Name LIKE '%' AND Id IN (SELECT ItemId FROM ItemCharacteristics, Units u WHERE u.Id = UnitId AND CAST(Value AS REAL) * Coefficient BETWEEN 1 AND 1000);

SELECT * FROM PickupPointOrders WHERE Id IN(SELECT DISTINCT StorageId FROM StoredItemSets WHERE ItemId = 1);
SELECT * FROM Orders WHERE Id IN(SELECT DISTINCT OrderId FROM OrderHistories WHERE StartDateTime BETWEEN '2023-10-06' AND '2024-12-30');
SELECT * FROM Items WHERE Id NOT IN (SELECT DISTINCT ItemId FROM StoredItemSets);
SELECT * FROM Items WHERE Id IN (SELECT DISTINCT ItemId FROM StoredItemSets WHERE StorageId = 1) AND SectionId = 4;
SELECT * FROM Items WHERE Id IN (SELECT ItemId FROM ItemCharacteristics, Units WHERE CharacteristicId = 1 AND Units.Id = UnitId AND CAST(Value AS REAL) * Coefficient BETWEEN 1 AND 700);

SELECT * FROM Items WHERE Id IN (SELECT ItemId FROM ItemCharacteristics WHERE CharacteristicId = 1 AND CAST('2024-10-06' AS DATE) < NOW());