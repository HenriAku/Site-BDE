<?php
class Article {

    public function __construct(
        private ?int $n_produit,
        private string $name,
        private int $stock,
        private string $category,
        private float $price,
        private string $description,
        private string $color,
        private string $size){}

    public function getn_produit(): ?int {
        return $this->n_produit;
    }

    public function getName(): string {
        return $this->name;
    }

    public function getStock(): int {
        return $this->stock;
    }

    public function getCategory(): string {
        return $this->category;
    }

    public function getPrice(): float {
        return $this->price;
    }

    public function getDescription(): string {
        return $this->description;
    }

    public function getColor(): string {
        return $this->color;
    }

    public function getSize(): string {
        return $this->size;
    }


    public function setName(string $name): void {
        $this->name = $name;
    }

    public function setStock(int $stock): void {
        $this->stock = $stock;
    }

    public function setCategory(string $category): void {
        $this->category = $category;
    }

    public function setPrice(float $price): void {
        $this->price = $price;
    }

    public function setDescription(string $description): void {
        $this->description = $description;
    }

    public function setColor(string $color): void {
        $this->color = $color;
    }

    public function setSize(string $size): void {
        $this->size = $size;
    }
}
?>
