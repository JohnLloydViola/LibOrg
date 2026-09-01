<?php 
declare(strict_types=1);

namespace App;

class Book 
{
  public function __construct(
    private int $id,
    private string $bookId,
    private string $title,
    private string $author,
    private string $category,
    private int $publicationYear,
    private int $quantity,
    private int $availableQuantity
  ) {
  }

  public function getId(): int
  {
    return $this->id;
  }

  public function getBookId(): string
  {
    return $this->bookId;
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
}