<?php

namespace App\Entity;

class Image
{
    private ?int $id = null;
    private string $filename;
    private string $altText;
    private bool $isCover;
    private int $sortOrder;
    private \DateTime $uploadedAt;
    private int $projectId;

    public function __construct(
        string $filename  = '',
        bool   $isCover   = false,
        int    $sortOrder = 0,
        int    $projectId = 0
    ) {
        $this->filename   = $filename;
        $this->altText    = $filename;
        $this->isCover    = $isCover;
        $this->sortOrder  = $sortOrder;
        $this->projectId  = $projectId;
        $this->uploadedAt = new \DateTime();
    }

    public function getId(): ?int           
    { 
        return $this->id;
    }

    public function setId(int $id): void
    { 
        $this->id = $id;
    }

    public function getFilename(): string
    {
        return $this->filename;
    }

    public function setFilename(string $filename): void  
    {
        $this->filename = $filename;
    }

    public function getAltText(): string
    {
        return $this->altText;
    }

    public function setAltText(string $altText): void
    {
        $this->altText = $altText;
    }

    public function getIsCover(): bool
    {
        return $this->isCover;
    }

    public function setIsCover(bool $isCover): void
    {
        $this->isCover = $isCover;
    }

    public function getSortOrder(): int
    {
        return $this->sortOrder;
    }

    public function setSortOrder(int $sortOrder): void
    {
        $this->sortOrder = $sortOrder;
    }

    public function getUploadedAt(): \DateTime
    {
        return $this->uploadedAt;
    }

    public function setUploadedAt(\DateTime $uploadedAt): void
    {
        $this->uploadedAt = $uploadedAt;
    }

    public function getProjectId(): int
    {
        return $this->projectId;
    }

    public function setProjectId(int $projectId): void
    {
        $this->projectId = $projectId;
    }

    public function getWebPath(): string
    {
        return '/assets/media/' . $this->filename;
    }

    public function getPath(): string
    {
        return $_ENV['UPLOAD_DIRECTORY'] . $this->filename;
    }
}
