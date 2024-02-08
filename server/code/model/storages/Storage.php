<?php

namespace Model\Storages;

require_once $_SERVER['DOCUMENT_ROOT'] . '/code/control/DataBaseConnection.php';

use const Control\DataBaseConnection;
use \Exception;

class Storage
{
    protected int $id;

    protected string $name;

    protected ?string $description;

    protected string $address;

    public function __construct(int $id)
    {
        $this->id = $id;
        $this->load();
    }

    private function load(): void
    {
        $statement = DataBaseConnection->prepare('SELECT * FROM Storages 
        WHERE Id = :id');
        $statement->bindValue(':id', $this->id);
        $statement->execute();
        if ($row = $statement->fetch()) {
            $this->name = $row['Name'];
            $this->description = $row['Description'];
            $this->address = $row['Address'];
        } else {
            throw new Exception("Fail to storage $this->id");
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function getAddress(): string
    {
        return $this->address;
    }
}
