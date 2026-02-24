<?php

declare(strict_types=1);

namespace Maxpay\Lib\Model;

use Maxpay\Lib\Util\Validator;

class UserInfo implements UserInfoInterface
{
    private string $email;

    private ?string $firstName = null;

    private ?string $lastName = null;

    private ?string $ISO3Country = null;

    private ?string $city = null;

    private ?string $postalCode = null;

    private ?string $address  = null;

    private ?string $phone  = null;

    public function __construct(
        string $email,
        ?string $firstName = null,
        ?string $lastName = null,
        ?string $ISO3Country = null,
        ?string $city = null,
        ?string $postalCode = null,
        ?string $address = null,
        ?string $phone = null
    ) {
        $validator = new Validator();
        $this->email = $validator->validateString('email', $email);
        if (!is_null($firstName)) {
            $this->firstName = $validator->validateString('firstName', $firstName);
        }
        if (!is_null($lastName)) {
            $this->lastName = $validator->validateString('lastName', $lastName);
        }
        if (!is_null($ISO3Country)) {
            $this->ISO3Country = $validator->validateString('ISO3Country', $ISO3Country, 3, 3);
        }
        if (!is_null($city)) {
            $this->city = $validator->validateString('city', $city);
        }
        if (!is_null($postalCode)) {
            $this->postalCode = $validator->validateString('postalCode', $postalCode);
        }
        if (!is_null($address)) {
            $this->address = $validator->validateString('address', $address);
        }
        if (!is_null($phone)) {
            $this->phone = $validator->validateString('phone', $phone);
        }
    }

    public function toHashMap(): array
    {
        $result = [
            'email' => $this->email,
        ];

        if (!is_null($this->firstName)) {
            $result['firstName'] = $this->firstName;
        }
        if (!is_null($this->lastName)) {
            $result['lastName'] = $this->lastName;
        }
        if (!is_null($this->ISO3Country)) {
            $result['country'] = $this->ISO3Country;
        }
        if (!is_null($this->city)) {
            $result['city'] = $this->city;
        }
        if (!is_null($this->address)) {
            $result['address'] = $this->address;
        }
        if (!is_null($this->postalCode)) {
            $result['zip'] = $this->postalCode;
        }
        if (!is_null($this->phone)) {
            $result['phone'] = $this->phone;
        }

        return $result;
    }
}
