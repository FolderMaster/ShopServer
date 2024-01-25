USE ShopDb;

DELETE FROM Files;
DELETE FROM Sections;
DELETE FROM UserRoles;
DELETE FROM Users;
DELETE FROM PaymentMethods;
DELETE FROM BankCards;
DELETE FROM UserPaymentMethods;
DELETE FROM Items;
DELETE FROM CurrencyUnits;
DELETE FROM ItemFiles;
DELETE FROM ItemPriceHistories;
DELETE FROM Types;
DELETE FROM Properties;
DELETE FROM Units;
DELETE FROM PropertyUnits;
DELETE FROM Characteristics;
DELETE FROM ItemCharacteristics;
DELETE FROM SectionCharacteristics;
DELETE FROM Favorites;
DELETE FROM Carts;
DELETE FROM Storages;
DELETE FROM PickupPoints;
DELETE FROM PickupPointFiles;
DELETE FROM WorkingTimes;
DELETE FROM Orders;
DELETE FROM PickupPointOrders;
DELETE FROM IssueOrders;
DELETE FROM StoredItemSets;
DELETE FROM StoredOrderItemSets;
DELETE FROM OrderItemSets;
DELETE FROM ItemPriceHistories;
DELETE FROM OrderStatuses;
DELETE FROM OrderHistories;

INSERT INTO Files
(Source, FullName, Data) VALUES
('/resources/images/headphones.jpg', 'headphones.jpg', 'headphones.jpg'),
('/resources/images/keyboard.jpg', 'keyboard.jpg',  'keyboard.jpg'),
('/resources/images/mouse.jpg', 'mouse.jpg',  'mouse.jpg'),
('/resources/images/user.png', 'user.png',  'user.png'),
('/resources/images/manezh.png', 'manezh.png', 'manezh.png');

INSERT INTO Sections
(Name, Image, Description, ParentSectionId) VALUES
('Электроника', 1, 'Обычная электроника', NULL), 
('Периферия', 1, 'Обычная периферия', 1),
('Наушники', 1, 'Обычная наушники', 2),
('Клавиатуры', 1, 'Обычная клавиатуры', 2),
('Компьтерные мыши', 1, 'Обычная компьтерные мыши', 2);

INSERT INTO UserRoles
(Name) VALUES ('Пользователь'), ('Админ');

INSERT INTO Users
(FullName, Password, Avatar, BirthDate, EmailAddress, PhoneNumber, Address, Role, Priority) VALUES
('Пчелинцев Андрей Александрович', '1056', 4, '2001-10-11', 'andreypchelintsev02@mail.ru',
'79008783468', 'Россия, город Томск', 'Админ', 1);

INSERT INTO PaymentMethods () VALUES ();

INSERT INTO BankCards (Id, Number, ExpiryMonth, ExpiryYear, CardholderName, Cvc) VALUES
(1, '2002220200202022', 3, 24, 'PCHELINTSEV ANDREW', '404');

INSERT INTO UserPaymentMethods (PaymentMethodId, UserId) VALUES (1, 1);

INSERT INTO Items (Name, Description, SectionId) VALUES
('Клавиатура', 'Обычная клавиатура.',  4),
('Мышь', 'Обычная мышь.',  5),
('Наушники', 'Обычные наушники.',  3);

INSERT INTO CurrencyUnits (Name, Symbol, Rate) VALUES
('Рубль', '₽',  1),
('Доллар', '$',  100);

INSERT INTO ItemFiles (ItemId, FileId) VALUES
(1, 2), (2, 3), (3, 1);

INSERT INTO ItemPriceHistories (ItemId, Price, CurrencyUnitId) VALUES
(1, 1000, 1), (2, 500, 1), (3, 2000, 1);

INSERT INTO Types (Name) VALUES
('String'), ('Interger'), ('Date'), ('Real');

INSERT INTO Properties (Name, Type) VALUES
('Название', 'String'), ('Количество', 'Interger'), ('Масса', 'Real'), ('Дата', 'Date');

INSERT INTO Units (Name, Symbol, Coefficient) VALUES
('Штук', 'шт', 1), ('Грам', 'гр', 1), ('Килограм', 'кг', 1000);

INSERT INTO PropertyUnits (PropertyId, UnitId) VALUES
(2, 1), (3, 2), (3, 3);

INSERT INTO Characteristics (Name, PropertyId, Description) VALUES
('Масса устройства', 2, 'Масса самого устройства.');

INSERT INTO ItemCharacteristics (ItemId, CharacteristicId, UnitId, Value) VALUES
(1, 1, 3, '0.7'),  (2, 1, 2, '300'), (3, 1, 3, '1');

INSERT INTO SectionCharacteristics (SectionId, CharacteristicId) VALUES
(2, 1);

INSERT INTO Favorites (UserId, ItemId) VALUES
(1, 2);

INSERT INTO Carts (UserId, ItemId, Count) VALUES
(1, 1, 3), (1, 2, 4);

INSERT INTO Storages (Name, Description, Address) VALUES
('Манеж', 'Торговый центр', 'Россия, город Томск, улица Беринга, дом 10');

INSERT INTO StoredItemSets (StorageId, ItemId, Count) VALUES
(1, 1, 6), (1, 2, 8);

INSERT INTO PickupPoints (Id) VALUES
(1);

INSERT INTO PickupPointFiles (PickupPointId, FileId) VALUES
(1, 5);

INSERT INTO WorkingTimes (PickupPointId, StartTime, EndTime) VALUES
(1, '08:00:00', '14:00:00'), (1, '15:00:00', '21:00:00');

INSERT INTO OrderStatuses (Name) VALUES
('Оформление'), ('Обработка'), ('Оплата'), ('Отмена'), ('Отправка'), ('Ожидание'), ('Доставка'), ('Возврат'), ('Отказ'), ('Завершение');

INSERT INTO Orders (UserId) VALUES
(1);

INSERT INTO OrderItemSets (OrderId, ItemId, Count) VALUES
(1, 1, 1), (1, 2, 2), (1, 3, 1);

INSERT INTO StoredOrderItemSets (StorageId, ItemId, OrderId, Count) VALUES
(1, 1, 1, 1), (1, 2, 1, 2), (1, 3, 1, 1);

INSERT INTO OrderHistories (OrderId, StartDateTime, OrderStatus) VALUES
(1, NOW(), 'Оформление');

INSERT INTO PickupPointOrders (Id, PickupPointId) VALUES
(1, 1);

INSERT INTO OrderHistories (OrderId, StartDateTime, OrderStatus) VALUES
(1, (NOW() + 1), 'Обработка');

INSERT INTO CheckSets (CurrencyUnitId, PaymentMethodId) VALUES (1, 1), (1, 1), (1, 1);

INSERT INTO CheckItemSets (Id, ItemId, Count) VALUES
(1, 1, 1), (2, 2, 2), (3, 3, 1);

INSERT INTO Checks (OrderId, CheckSetId) VALUES
(1, 1), (1, 2), (1, 3);

INSERT INTO OrderHistories (OrderId, StartDateTime, OrderStatus) VALUES
(1, (NOW() + 2), 'Оплата');

INSERT INTO ItemPriceHistories (ItemId, Price, CurrencyUnitId, StartDateTime) VALUES
(3, 2500, 1, (NOW() + 3));

INSERT INTO OrderHistories (OrderId, StartDateTime, OrderStatus) VALUES
(1, (NOW() + 3), 'Отправка'), (1, (NOW() + 4), 'Ожидание'), (1, (NOW() + 5), 'Завершение');