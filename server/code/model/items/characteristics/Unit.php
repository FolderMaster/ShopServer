<?php

namespace Model\Items\Characteristics;

require_once $_SERVER['DOCUMENT_ROOT'] . '/code/control/DataBaseConnection.php';

use const Control\DataBaseConnection;
use \Exception;

class Unit
{
    protected int $id;

    protected string $name;

    protected string $symbol;

    protected float $coefficient;

    public function __construct(int $id)
    {
        $this->id = $id;
        $this->load();
    }

    private function load(): void
    {
        $statement = DataBaseConnection->prepare('SELECT * FROM Units 
        WHERE Id = :id');
        $statement->bindValue(':id', $this->id);
        $statement->execute();
        if ($row = $statement->fetch()) {
            $this->name = $row['Name'];
            $this->symbol = $row['Symbol'];
            $this->coefficient = $row['Coefficient'];
        } else {
            throw new Exception('Fail to load unit ' . $this->id);
        }
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

    public function getCoefficient(): float
    {
        return $this->coefficient;
    }
}
