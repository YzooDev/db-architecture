<?php

namespace App\Entity;

class Project
{
    private ?int $id;
    private string $name;
    private string $description;
    private \DateTime $createdAt;
    private string $location;
    private string $images;
    private bool $built;

    public function __construct(
        string $name,
        string $description,
        \DateTime $createdAt,
        string $location,
        string $images,
        bool $built
    ) {
        $this->name = $name;
        $this->description = $description;
        $this->createdAt = $createdAt;
        $this->location = $location;
        $this->images = $images;
        $this->built = $built;
    }

    public function getId(): ?int {
        return $this->id;
    }

    // RETURN SELF cf projet_todo
    // public function setId(?int $id): self
    // {
    //     $this->id = $id;
    //     return $this;
    // }

    public function setId(?int $id): void {
        $this->id = $id;
    }

    public function getName(): string {
        return $this->name;
    }

    public function setName(string $name): void {
        $this->name = $name;
    }
    
    public function getDescription(): string {
        return $this->description;
    }

    // RETURN SELF cf projet_todo
    // public function setDescription(string $description): self
    // {
    //     $this->description = $description;
    //     return $this;
    // }

    public function setDescription(string $description): void {
        $this->description = $description;
    }

    public function getCreatedAt(): \DateTime {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTime $createdAt): void {
        $this->createdAt = $createdAt;
    }

    public function getLocation(): string {
        return $this->location;
    }

    public function setLocation(string $location): void {
        $this->location = $location;
    }

    public function getImages(): string {
        return $this->images;
    }

    public function setImages(string $images): void {
        $this->images = $images;
    }

    public function getBuilt(): bool {
        return $this->built;
    }

    public function setBuilt(bool $built): void {
        $this->built = $built;
    }
}