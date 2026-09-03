<?php
declare(strict_types=1);

namespace App;

interface Borrowable
{
    public function borrow(): void;

    public function returnItem(): void;
}