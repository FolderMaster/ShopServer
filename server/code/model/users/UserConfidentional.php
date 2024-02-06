<?php

namespace Model\Users;

require_once $_SERVER['DOCUMENT_ROOT'] . '/code/control/DataBaseConnection.php';

use const Control\DataBaseConnection;
use \Exception;
use \DateTime;

class UserConfidential
{
    protected $userId;

    protected DateTime $birthDate;

    protected string $emailAddress;

    protected string $phoneNumber;

    protected ?string $address;

    protected string $role;

    protected int $priority;

    public function __construct(int $userId)
    {
        $this->userId = $userId;
        $this->load();
    }

    public function load(): void
    {
        $statement = DataBaseConnection->prepare('SELECT BirthDate, EmailAddress, 
        PhoneNumber, Address, Role, Priority FROM Users WHERE Id = :id');
        $statement->bindValue(':id', $this->userId);
        $statement->execute();
        if ($row = $statement->fetch()) {
            $this->birthDate = new DateTime($row['BirthDate']);
            $this->emailAddress = $row['EmailAddress'];
            $this->phoneNumber = $row['PhoneNumber'];
            $this->address = $row['Address'];
            $this->address = $row['Role'];
            $this->address = $row['Priority'];
        } else {
            throw new Exception('Fail to load user confidential ' . $this->userId);
        }
    }

    public function getUserId(): int
    {
        return $this->userId;
    }

    public function getBirthDate(): DateTime
    {
        return $this->birthDate;
    }

    public function getEmailAddress(): string
    {
        return $this->emailAddress;
    }

    public function getPhoneNumber(): string
    {
        return $this->phoneNumber;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function getRole(): string
    {
        return $this->role;
    }

    public function getPriority(): int
    {
        return $this->priority;
    }
}
