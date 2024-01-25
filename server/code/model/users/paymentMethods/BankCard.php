<?php

namespace Model\Users\PaymentMethods;

require_once 'code/control/DataBaseConnection.php';

use const Control\DataBaseConnection;
use \Exception;

class BankCard
{
    protected int $id;

    protected string $number;

    protected int $expiryMonth;

    protected int $expiryYear;

    protected string $cardholderName;

    protected string $cvc;

    public function __construct(int $id)
    {
        $this->id = $id;
        $this->load();
    }

    private function load(): void
    {
        $statement = DataBaseConnection->prepare('SELECT * FROM BankCards 
        WHERE Id = :id');
        $statement->bindValue(':id', $this->id);
        $statement->execute();
        if ($row = $statement->fetch()) {
            $this->number = $row['Number'];
            $this->expiryMonth = $row['ExpiryMonth'];
            $this->expiryYear = $row['ExpiryYear'];
            $this->cardholderName = $row['CardholderName'];
            $this->cvc = $row['Cvc'];
        } else {
            throw new Exception('Fail to load bank card ' . $this->id);
        }
    }

    public static function getBankCards(int $userId): array
    {
        $statement = DataBaseConnection->prepare('SELECT Id FROM BankCards 
        WHERE Id IN (SELECT PaymentMethodId FROM UserPaymentMethods 
        WHERE UserId = :userId)');
        $statement->bindValue(':userId', $userId);
        $statement->execute();
        $result = [];
        while ($row = $statement->fetch()) {
            $result[] = new BankCard($row['Id']);
        }
        return $result;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getNumber(): string
    {
        return $this->number;
    }

    public function getExpiryMonth(): int
    {
        return $this->expiryMonth;
    }

    public function getExpiryYear(): int
    {
        return $this->expiryYear;
    }

    public function getCardholderName(): string
    {
        return $this->cardholderName;
    }

    public function getCvc(): string
    {
        return $this->cvc;
    }
}
