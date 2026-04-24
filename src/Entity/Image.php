<?php

namespace App\Entity;

class Image
{
    private ?int $id;
    private int $projectId;
    private string $filename;
    private string $altText;
    private bool $isCover;
    private int $sortOrder;
    private \DateTime $uploadedAt;

    public function __construct(
        int $projectId = 0,
        string $filename = '',
        string $altText = '',
        bool $isCover = false,
        int $sortOrder = 0,
        ?int $id = null
    ) {
        $this->id         = $id;
        $this->projectId  = $projectId;
        $this->filename   = $filename;
        $this->altText    = $altText;
        $this->isCover    = $isCover;
        $this->sortOrder  = $sortOrder;
        $this->uploadedAt = new \DateTime();
    }

    // --- Getters ---

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getProjectId(): int
    {
        return $this->projectId;
    }

    public function getFilename(): string
    {
        return $this->filename;
    }

    public function getAltText(): string
    {
        return $this->altText;
    }

    public function isCover(): bool
    {
        return $this->isCover;
    }

    public function getSortOrder(): int
    {
        return $this->sortOrder;
    }

    public function getUploadedAt(): \DateTime
    {
        return $this->uploadedAt;
    }

    public function getPath(): string
    {
        return $_ENV['UPLOAD_DIRECTORY'] . $this->filename;
    }

    // --- Setters ---

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function setProjectId(int $projectId): void
    {
        $this->projectId = $projectId;
    }

    public function setFilename(string $filename): void
    {
        $this->filename = $filename;
    }

    public function setAltText(string $altText): void
    {
        $this->altText = $altText;
    }

    public function setIsCover(bool $isCover): void
    {
        $this->isCover = $isCover;
    }

    public function setSortOrder(int $sortOrder): void
    {
        $this->sortOrder = $sortOrder;
    }

    public function setUploadedAt(\DateTime $uploadedAt): void
    {
        $this->uploadedAt = $uploadedAt;
    }
}