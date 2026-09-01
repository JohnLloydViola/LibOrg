<?php 
declare(strict_types=1);

namespace App;

class Member 
{
  public function __construct(
    private string $fullName,
    private string $email,
    private string $phoneNumber,
    private string $address
  ) {
  }
  public function getFullName(): string
  {
    return $this->fullName;
  }

  public function getEmail(): string
  {
    return $this->email;
  }

  public function getPhoneNumber(): string
  {
    return $this->phoneNumber;
  }

  public function getAddress(): string
  {
    return $this->address;
  }
}

