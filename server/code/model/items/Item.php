<?php

namespace Model\Items;

require_once 'code/control/DataBaseConnection.php';
require_once 'code/model/File.php';
require_once 'code/model/items/characteristics/ItemCharacteristic.php';

use Model\Items\Characteristics\ItemCharacteristic;
use Model\File;
use const Control\DataBaseConnection;
use \Exception;

class Item
{
    protected int $id;

    protected string $name;

    protected array $characteristics;

    protected array $images;

    protected string $description;

    protected int $sectionId;

    public function __construct(int $id)
    {
        $this->id = $id;
        $this->load();
    }

    private function load(): void
    {
        $statement = DataBaseConnection->prepare('SELECT * FROM Items 
        WHERE Id = :id');
        $statement->bindValue(':id', $this->id);
        $statement->execute();
        if ($row = $statement->fetch()) {
            $this->name = $row['Name'];
            $this->description = $row['Description'];
            $this->sectionId = $row['SectionId'];

            $statement = DataBaseConnection->prepare('SELECT FileId 
            FROM ItemFiles WHERE ItemId = :id');
            $statement->bindValue(':id', $this->id);
            $statement->execute();
            $this->images = [];
            while ($row = $statement->fetch()) {
                $this->images[] = new File($row['FileId']);
            }

            $statement = DataBaseConnection->prepare('SELECT CharacteristicId 
            FROM ItemCharacteristics WHERE ItemId = :id');
            $statement->bindValue(':id', $this->id);
            $statement->execute();
            $this->characteristics = [];
            while ($row = $statement->fetch()) {
                $this->characteristics[] = new ItemCharacteristic(
                    $row['CharacteristicId'],
                    $this->id
                );
            }
        } else {
            throw new Exception('Fail to load item ' . $this->id);
        }
    }

    public static function getItemsBySectionId(int $sectionId): array
    {
        $result = DataBaseConnection->prepare('SELECT Id FROM Items 
        WHERE SectionId = :sectionId');
        $result->bindValue(':sectionId', $sectionId);
        $result->execute();
        $items = [];
        while ($row = $result->fetch()) {
            $items[] = new Item($row['Id']);
        }
        return $items;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getCharacteristics(): array
    {
        return $this->characteristics;
    }

    public function getImages(): array
    {
        return $this->images;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $value): void
    {
        $this->description = $value;
    }

    public function getSectionId(): int
    {
        return $this->sectionId;
    }

    public function getUrl(): string
    {
        return '/shop/item.php?' . $this->id;
    }
}
