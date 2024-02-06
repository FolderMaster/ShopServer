<?php

namespace Model\Items\Characteristics;

require_once $_SERVER['DOCUMENT_ROOT'] . '/code/control/DataBaseConnection.php';

use const Control\DataBaseConnection;
use \Exception;

class Characteristic
{
    protected int $id;

    protected string $name;

    protected ?string $description;

    protected int $propertyId;

    public function __construct(int $id)
    {
        $this->id = $id;
        $this->load();
    }

    private function load(): void
    {
        $statement = DataBaseConnection->prepare('SELECT * FROM Characteristics 
        WHERE Id = :id');
        $statement->bindValue(':id', $this->id);
        $statement->execute();
        if ($row = $statement->fetch()) {
            $this->name = $row['Name'];
            $this->description = $row['Description'];
            $this->propertyId = $row['PropertyId'];
        } else {
            throw new Exception('Fail to load characteristic ' . $this->id);
        }
    }

    public static function getCharacteristicsBySectionId(int $sectionId): array
    {
        $statement = DataBaseConnection->prepare('SELECT CharacteristicId 
        FROM SectionCharacteristics WHERE SectionId = :sectionId');
        $statement->bindValue(':sectionId', $sectionId);
        $statement->execute();
        $result = [];
        while ($row = $statement->fetch()) {
            $result[] = new Characteristic($row['CharacteristicId']);
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function getPropertyId(): int
    {
        return $this->propertyId;
    }
}
