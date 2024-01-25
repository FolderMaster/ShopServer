<?php

namespace Model\Items\Characteristics;

require_once 'code/model/items/characteristics/Unit.php';
require_once 'code/model/items/characteristics/Characteristic.php';
require_once 'code/control/DataBaseConnection.php';

use const Control\DataBaseConnection;
use \Exception;

class ItemCharacteristic extends Characteristic
{
    protected int $itemId;

    protected Unit $unit;

    protected string $value;

    public function __construct(int $characteristicId, int $itemId)
    {
        parent::__construct($characteristicId);
        $this->itemId = $itemId;
        $this->load();
    }

    private function load(): void
    {
        $statement = DataBaseConnection->prepare('SELECT * 
        FROM ItemCharacteristics 
        WHERE CharacteristicId = :id AND ItemId = :itemId');
        $statement->bindValue(':id', $this->id);
        $statement->bindValue(':itemId', $this->itemId);
        $statement->execute();
        if ($row = $statement->fetch()) {
            $this->value = $row['Value'];
            $this->unit = new Unit($row['UnitId']);
        } else {
            throw new Exception('Fail to load item characteristic ' . $this->id);
        }
    }

    public function getItemId(): int
    {
        return $this->itemId;
    }

    public function getUnit(): Unit
    {
        return $this->unit;
    }

    public function getValue(): string
    {
        return $this->value;
    }
}
