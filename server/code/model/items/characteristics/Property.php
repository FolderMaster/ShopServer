<?php

namespace Model\Items\Characteristics;

require_once 'code/control/DataBaseConnection.php';
require_once 'code/model/items/characteristics/Unit.php';

use const Control\DataBaseConnection;
use \Exception;

class Property
{
    protected int $id;

    protected string $type;

    protected string $name;

    protected array $units;

    public function __construct(int $id)
    {
        $this->id = $id;
        $this->load();
    }

    private function load(): void
    {
        $statement = DataBaseConnection->prepare('SELECT * FROM Properties 
        WHERE Id = :id');
        $statement->bindValue(':id', $this->id);
        $statement->execute();
        if ($row = $statement->fetch()) {
            $this->name = $row['Name'];
            $this->type = $row['Type'];
        } else {
            throw new Exception('Fail to load property ' . $this->id);
        }
        $statement = DataBaseConnection->prepare('SELECT * FROM PropertyUnits 
        WHERE PropertyId = :id');
        $statement->bindValue(':id', $this->id);
        $statement->execute();
        $this->units = [];
        while ($row = $statement->fetch()) {
            $this->units[] = new Unit($row['UnitId']);
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

    public function getType(): string
    {
        return $this->type;
    }

    public function getUnits(): array
    {
        return $this->units;
    }
}
