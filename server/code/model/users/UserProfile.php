<?php

namespace Model\Users;

require_once $_SERVER['DOCUMENT_ROOT'] . '/code/model/File.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/code/control/DataBaseConnection.php';

use const Control\DataBaseConnection;
use Model\File;
use \Exception;

class UserProfile
{
    protected int $userId;

    protected string $fullName;

    protected File $avatar;

    public function __construct(int $userId)
    {
        $this->userId = $userId;
        $this->load();
    }

    private function load(): void
    {
        $statement = DataBaseConnection->prepare('SELECT FullName, Avatar 
        FROM Users WHERE Id = :id');
        $statement->bindValue(':id', $this->userId);
        $statement->execute();
        if ($row = $statement->fetch()) {
            $this->fullName = $row['FullName'];
            $this->avatar = new File($row['Avatar']);
        } else {
            throw new Exception('Fail to load user profile ' . $this->userId);
        }
    }

    public function getUserId(): int
    {
        return $this->userId;
    }

    public function getFullName(): string
    {
        return $this->fullName;
    }

    public function getAvatar(): File
    {
        return $this->avatar;
    }
}
