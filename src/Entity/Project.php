<?php

namespace App\Entity;

use App\Entity\Image;

class Project
{
    private ?int $id;
    private string $name;
    private string $description;
    private string $location;
    private int $year;
    private string $category;
    private \DateTime $createdAt;
    private bool $built;
    private array $images;

    public function __construct(
        string $name,
        string $description,
        string $location,
        int $year,
        string $category,
    ) {
        $this->name = $name;
        $this->description = $description;
        $this->location = $location;
        $this->year = $year;
        $this->category = $category;
        $this->createdAt = new \DateTime;
        $this->built = 0;
        $this->images = [];
    }

    public function getId(): ?int 
    {
        return $this->id;
    }

    public function setId(?int $id): void 
    {
        $this->id = $id;
    }

    public function getName(): string 
    {
        return $this->name;
    }

    public function setName(string $name): void 
    {
        $this->name = $name;
    }
    
    public function getDescription(): string 
    {
        return $this->description;
    }

    public function setDescription(string $description): void 
    {
        $this->description = $description;
    }

    public function getLocation(): string 
    {
        return $this->location;
    }

    public function setLocation(string $location): void 
    {
        $this->location = $location;
    }

    public function getYear(): int 
    {
        return $this->year;
    }

    public function setYear(int $year): void 
    {
        $this->year = $year;
    }

    public function getCreatedAt(): \DateTime 
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTime $createdAt): void 
    {
        $this->createdAt = $createdAt;
    }

    public function getCategory(): string 
    {
        return $this->category;
    }

    public function setCategory(string $category): void 
    {
        $this->category = $category;
    }

    public function getBuilt(): bool 
    {
        return $this->built;
    }

    public function setBuilt(bool $built): void 
    {
        $this->built = $built;
    }

    public function getImages(): array
    {
        return $this->images;
    }

    public function setImage(Image $image): void
    {
        $this->images[] = $image;
    }

    public function unsetImage(Image $image): void
    {
        unset($this->images[array_search($image, $this->images)]);
        sort($this->images);
    }
}