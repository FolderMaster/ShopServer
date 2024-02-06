<?php

namespace Model\Items;

require_once $_SERVER['DOCUMENT_ROOT'] . '/code/model/File.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/code/control/DataBaseConnection.php';

use const Control\DataBaseConnection;
use Model\File;
use \Exception;

class Section
{
    protected int $id;

    protected string $name;

    protected File $image;

    protected string $description;

    protected ?int $parentSectionId;

    public function __construct(int $id)
    {
        $this->id = $id;
        $this->load();
    }

    private function load(): void
    {
        $statement = DataBaseConnection->prepare('SELECT * FROM Sections 
        WHERE Id = :id');
        $statement->bindValue(':id', $this->id);
        $statement->execute();
        if ($row = $statement->fetch()) {
            $this->name = $row['Name'];
            $this->image = new File($row['Image']);
            $this->description = $row['Description'];
            $this->parentSectionId = $row['ParentSectionId'];
        } else {
            throw new Exception('Fail to load section ' . $this->id);
        }
    }

    public static function checkId(int $id): bool
    {
        $statement = DataBaseConnection->prepare('SELECT 1 FROM Sections 
        WHERE Id = :id');
        $statement->bindValue(':id', $id);
        $statement->execute();
        return $statement->rowCount() > 0;
    }

    public static function getSectionsByParentSectionId(
        ?int $parentSectionId
    ): array {
        $statement = DataBaseConnection->prepare('SELECT Id FROM Sections 
        WHERE ParentSectionId <=> :parentSectionId');
        $statement->bindValue(':parentSectionId', $parentSectionId);
        $statement->execute();
        $result = [];
        while ($row = $statement->fetch()) {
            $result[] = new Section($row['Id']);
        }
        return $result;
    }

    public static function getSectionsBranchByParentSectionId(
        ?int $parentSectionId
    ): array {
        $statement = DataBaseConnection->prepare('WITH RECURSIVE 
            SectionsByParentSectionId (Id,  ParentSectionId) AS ( 
            SELECT Id, ParentSectionId FROM Sections WHERE ParentSectionId 
            <=> :parentSectionId 
            UNION
            SELECT s.Id, s.ParentSectionId FROM Sections s
            INNER JOIN SectionsByParentSectionId sbps ON 
            s.ParentSectionId = sbps.Id
            )
            SELECT Id FROM SectionsByParentSectionId');
        $statement->bindValue(':parentSectionId', $parentSectionId);
        $statement->execute();
        $result = [];
        while ($row = $statement->fetch()) {
            $result[] = $row['Id'];
        }
        return $result;
    }

    public static function getSectionsBranchByChildSectionId(
        int $childSectionId
    ): array {
        $statement = DataBaseConnection->prepare('WITH RECURSIVE 
            SectionsByChildSectionId (Id, ParentSectionId) AS ( 
            SELECT Id, ParentSectionId FROM Sections WHERE Id = :childSectionId 
            UNION 
            SELECT s.Id, s.ParentSectionId FROM Sections s 
            INNER JOIN SectionsByChildSectionId sbcs ON 
            s.Id = sbcs.ParentSectionId 
        ) 
        SELECT Id FROM SectionsByChildSectionId');
        $statement->bindValue(':childSectionId', $childSectionId);
        $statement->execute();
        $result = [];
        while ($row = $statement->fetch()) {
            $result[] = new Section($row['Id']);
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

    public function getImage(): File
    {
        return $this->image;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function getParentSectionId(): ?int
    {
        return $this->parentSectionId;
    }

    public function getUrl(): string
    {
        return "/shop/section.php?id=$this->id";
    }
}
