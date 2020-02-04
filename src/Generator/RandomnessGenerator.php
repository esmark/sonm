<?php

declare(strict_types=1);

namespace App\Generator;

class RandomnessGenerator
{
    /** @var string */
    private $uriSafeAlphabet;

    /** @var string */
    private $digits;

    public function __construct()
    {
        $this->digits = implode(range(0, 9));

        $this->uriSafeAlphabet =
            implode(range(0, 9))
            . implode(range('a', 'z'))
            . implode(range('A', 'Z'))
            . implode(['-', '_', '~'])
        ;
    }

    public function generateUriSafeString(int $length): string
    {
        return $this->generateStringOfLength($length, $this->uriSafeAlphabet);
    }

    public function generateNumeric(int $length): string
    {
        return $this->generateStringOfLength($length, $this->digits);
    }

    public function generateInt(int $min, int $max): int
    {
        return random_int($min, $max);
    }

    private function generateStringOfLength(int $length, string $alphabet): string
    {
        $alphabetMaxIndex = strlen($alphabet) - 1;
        $randomString = '';

        for ($i = 0; $i < $length; ++$i) {
            $index = random_int(0, $alphabetMaxIndex);
            $randomString .= $alphabet[$index];
        }

        return $randomString;
    }
}
