<?php

namespace Model;

require_once $_SERVER['DOCUMENT_ROOT'] . '/code/control/DataBaseConnection.php';

use const Control\DataBaseConnection;
use \Exception;

class File
{
    protected int $id;

    protected string $name;

    protected ?string $source;

    public function __construct(int $id)
    {
        $this->id = $id;
        $this->load();
    }

    private function load(): void
    {
        $statement = DataBaseConnection->prepare('SELECT Source, Name 
        FROM Files WHERE Id = :id');
        $statement->bindValue(':id', $this->id);
        $statement->execute();
        if ($row = $statement->fetch()) {
            $this->source = $row['Source'];
            $this->name = $row['Name'];
        } else {
            throw new Exception('Fail to load file ' . $this->id);
        }
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getSource(): ?string
    {
        return $this->source;
    }

    public function getName(): string
    {
        return $this->name;
    }
}
