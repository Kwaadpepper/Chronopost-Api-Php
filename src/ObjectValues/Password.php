<?php

declare(strict_types=1);

namespace Kwaadpepper\ChronopostApiPhp\ObjectValues;

class Password
{
    /**
     * The password.
     *
     * @var string
     */
    private string $password;


    /**
     * Constructor.
     *
     * @param string $password The password to validate.
     *
     * @throws \InvalidArgumentException If the password is invalid.
     *                                   The password must be 6 digits.
     */
    public function __construct(
        #[\SensitiveParameter] string $password,
    ) {
        $this->validate($password);
        $this->password = $password;
    }


    /**
     * Returns the password.
     *
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * Validates the password.
     *
     * @param string $password The password to validate.
     *
     * @return void
     * @throws \InvalidArgumentException If the password is invalid.
     */
    private function validate(string $password): void
    {
        if (!preg_match('/^[0-9]{6}$/', $password)) {
            throw new \InvalidArgumentException('Invalid password format. Must be 6 digits.');
        }
    }
}
