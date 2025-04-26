<?php

declare(strict_types=1);

namespace Kwaadpepper\ChronopostApiPhp\ObjectValues;

class AccountNumber
{
    /**
     * The account number.
     *
     * @var string
     */
    private string $accountNumber;

    /**
     * Constructor.
     *
     * @param string $accountNumber The account number to validate.
     *
     * @throws \InvalidArgumentException If the account number is invalid.
     *                                   The account number must be 8 digits.
     */
    public function __construct(
        #[\SensitiveParameter] string $accountNumber,
    ) {
        $this->validate($accountNumber);
        $this->accountNumber = $accountNumber;
    }

    /**
     * Returns the account number.
     *
     * @return string
     */
    public function getAccountNumber(): string
    {
        return $this->accountNumber;
    }

    /**
     * Validates the account number.
     *
     * @param string $accountNumber The account number to validate.
     *
     * @return void
     * @throws \InvalidArgumentException If the account number is invalid.
     */
    private function validate(string $accountNumber): void
    {
        if (!preg_match('/^[0-9]{8}$/', $accountNumber)) {
            throw new \InvalidArgumentException('Invalid account number format. Must be 8 digits.');
        }
    }
}
