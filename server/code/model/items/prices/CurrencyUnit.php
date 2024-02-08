<?php

namespace Model\Items\Prices;

require_once $_SERVER['DOCUMENT_ROOT'] . '/code/control/DataBaseConnection.php';

use const Control\DataBaseConnection;
use \Exception;

class CurrencyUnit
{
    protected int $id;

    protected string $name;

    protected string $symbol;

    protected float $rate;

    public function __construct(int $id)
    {
        $this->id = $id;
        $this->load();
    }

    private function load(): void
    {
        $statement = DataBaseConnection->prepare('SELECT * FROM CurrencyUnits 
        WHERE Id = :id');
        $statement->bindValue(':id', $this->id);
        $statement->execute();
        if ($row = $statement->fetch()) {
            $this->name = $row['Name'];
            $this->symbol = $row['Symbol'];
            $this->rate = $row['Rate'];
        } else {
            throw new Exception("Fail to load currency unit $this->id");
        }
    }

    public static function getCurrencyUnits(): array
    {
        $statement = DataBaseConnection->prepare('SELECT * FROM CurrencyUnits');
        $statement->execute();
        $result = [];
        while ($row = $statement->fetch()) {
            $result[] = new CurrencyUnit($row['Id']);
        }
        return $result;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getSymbol(): string
    {
        return $this->symbol;
    }

    public function getRate(): float
    {
        return $this->rate;
    }
}
