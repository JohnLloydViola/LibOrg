<?php 
declare(strict_types=1);

namespace App;

class Book implements Borrowable
{
  private int $availableQuantity;

  public function __construct(
    private string $title,
    private string $author,
    private string $category,
    private int $publicationYear,
    private int $quantity
  ) {
    $this->availableQuantity = $this->quantity;
  }
  
  public function getTitle(): string
  {
    return $this->title;
  }

  public function getAuthor(): string
  {
    return $this->author;
  }

  public function getCategory(): string
  {
    return $this->category;
  }

  public function getPublicationYear(): int
  {
    return $this->publicationYear;
  }

  public function getQuantity(): int
  {
    return $this->quantity;
  }

  public function getAvailableQuantity(): int
  {
    return $this->availableQuantity;
  }

  public function borrow(): void
  {
    if ($this->availableQuantity <= 0) {
      throw new ItemNotAvailableException();
    }

    $this->availableQuantity--;
  }

  public function returnItem(): void
  {
    $this->availableQuantity++;
  }
}