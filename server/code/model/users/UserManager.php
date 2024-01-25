<?php

namespace Model\Users;

require_once 'code/control/DataBaseConnection.php';

use const Control\DataBaseConnection;
use \DateTime;

class UserManager
{
    public static function checkUser(string $email): bool
    {
        $statement = DataBaseConnection->prepare('SELECT 1 FROM Users 
        WHERE EmailAddress = :email');
        $statement->bindValue(':email', $email);
        $statement->execute();
        return $statement->rowCount() > 0;
    }

    public static function getUserId(string $email, string $password): ?int
    {
        $statement = DataBaseConnection->prepare('SELECT Id, Password FROM Users 
        WHERE EmailAddress = :email');
        $statement->bindValue(':email', $email);
        $statement->execute();
        if ($row = $statement->fetch()) {
            if (!password_verify($password, $row['Password'])) {
                return null;
            }
            return $row['Id'];
        } else {
            return null;
        }
    }

    public static function createUser(
        string $fullName,
        string $email,
        string $password,
        string $phoneNumber,
        DateTime $birthDate,
        string $avatarPath,
        string $avatarFullName,
        string $avatarContent
    ): bool {
        DataBaseConnection->beginTransaction();
        $statement = DataBaseConnection->prepare('INSERT INTO Files 
        (Source, FullName, Data) VALUES (:source, :fullName, :data)');
        $statement->bindValue(':source', $avatarPath);
        $statement->bindValue(':fullName', $avatarFullName);
        $statement->bindValue(':data', $avatarContent);
        $statement->execute();
        if ($statement->rowCount() == 0) {
            $statement->closeCursor();
            DataBaseConnection->rollBack();
            return false;
        }

        DataBaseConnection->exec('SET @id = LAST_INSERT_ID()');

        $statement = DataBaseConnection->prepare('INSERT INTO Users (FullName, 
        Password, Avatar, BirthDate, EmailAddress, PhoneNumber, Role) VALUES 
        (:fullName, :password, @id, :birthDate, :email, :phoneNumber, :role)');
        $statement->bindValue(':fullName', $fullName);
        $statement->bindValue(':password', $password);
        $statement->bindValue(':birthDate', $birthDate->format('Y-m-d'));
        $statement->bindValue(':email', $email);
        $statement->bindValue(':phoneNumber', $phoneNumber);
        $statement->bindValue(':role', 'Пользователь');
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
