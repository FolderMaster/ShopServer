<?php

namespace Model\Orders;

require_once $_SERVER['DOCUMENT_ROOT'] . '/code/control/DataBaseConnection.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/code/model/storages/Storage.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/code/model/File.php';

use Model\Storages\Storage;
use Model\File;
use const Control\DataBaseConnection;

class PickupPoint extends Storage
{
    protected array $files;

    protected array $workingTimes;

    public function __construct(int $id)
    {
        parent::__construct($id);
        $this->load();
    }

    private function load(): void
    {
        $statement = DataBaseConnection->prepare('SELECT * FROM PickupPointFiles 
        WHERE PickupPointId = :id');
        $statement->bindValue(':id', $this->id);
        $statement->execute();
        $this->files = [];
        while ($row = $statement->fetch()) {
            $this->files[] = new File($row['FileId']);
        }

        $statement = DataBaseConnection->prepare('SELECT * FROM WorkingTimes 
        WHERE PickupPointId = :id');
        $statement->bindValue(':id', $this->id);
        $statement->execute();
        $this->workingTimes = [];
        while ($row = $statement->fetch()) {
            $this->workingTimes[$row['StartTime']] = $row['EndTime'];
        }
    }

    public static function getPickupPoints(): array
    {
        $statement = DataBaseConnection->prepare('SELECT * FROM PickupPoints');
        $statement->execute();
        $result = [];
        while ($row = $statement->fetch()) {
            $result[] = new PickupPoint($row['Id']);
        }
        return $result;
    }

    public function getFiles(): array
    {
        return $this->files;
    }

    public function getWorkingTimes(): array
    {
        return $this->workingTimes;
    }
}
