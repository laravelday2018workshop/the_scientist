<?php

declare(strict_types=1);

namespace Acme\Academic\UseCase\RegisterAcademic;

use Acme\Academic\ValueObject\BirthDate;
use Acme\Academic\ValueObject\Email;
use Acme\Academic\ValueObject\FirstName;
use Acme\Academic\ValueObject\LastName;
use Acme\Academic\ValueObject\Major;
use Acme\Academic\ValueObject\Password;

final class RegisterAcademicCommand
{
    /**
     * @var FirstName
     */
    private $firstName;
    /**
     * @var LastName
     */
    private $lastName;
    /**
     * @var BirthDate
     */
    private $birthDate;
    /**
     * @var Major
     */
    private $major;
    /**
     * @var Email
     */
    private $email;
    /**
     * @var Password
     */
    private $password;

    public function __construct(
        FirstName $firstName,
        LastName $lastName,
        Email $email,
        Password $password,
        BirthDate $birthDate,
        Major $major
    ) {
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->email = $email;
        $this->password = $password;
        $this->birthDate = $birthDate;
        $this->major = $major;
    }

    public function getFirstName(): FirstName
    {
        return $this->firstName;
    }

    public function getLastName(): LastName
    {
        return $this->lastName;
    }

    public function getEmail(): Email
    {
        return $this->email;
    }

    public function getPassword(): Password
    {
        return $this->password;
    }

    public function getBirthDate(): BirthDate
    {
        return $this->birthDate;
    }

    public function getMajor(): Major
    {
        return $this->major;
    }
}
