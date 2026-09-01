<?php 
declare(strict_types=1);

namespace App;

class Transaction 
{
  public function __construct(
    private Member $member,
    private Book $book,
    private string $status
  ) {
  }

  public function getMember(): Member
  {
    return $this->member;
  }

  public function getBook(): Book
  {
    return $this->book;
  }

  public function getStatus(): string
  {
    return $this->status;
  }
}