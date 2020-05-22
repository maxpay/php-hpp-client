<?php

namespace Maxpay\Lib\Model;

use Maxpay\Lib\Exception\GeneralMaxpayException;
use Maxpay\Lib\Util\Validator;

/**
 * Class UserInfo
 * @package Maxpay\Lib\Model
 */
class UserInfo implements UserInfoInterface
{
    /** @var string */
    private $email;

    /** @var string|null */
    private $firstName;

    /** @var string|null */
    private $lastName;

    /** @var string|null */
    private $ISO3Country;

    /** @var string|null */
    private $city;

    /** @var string|null */
    private $postalCode;

    /** @var string|null */
    private $address;

    /** @var string|null */
    private $phone;

    /**
     * @param string $email
     * @param string|null $firstName
     * @param string|null $lastName
     * @param string|null $ISO3Country
     * @param string|null $city
     * @param string|null $postalCode
     * @param string|null $address
     * @param string|null $phone
     * @throws GeneralMaxpayException
     */
    public function __construct(
        string $email,
        string $firstName = null,
        string $lastName = null,
        string $ISO3Country = null,
        string $city = null,
        string $postalCode = null,
        string $address = null,
        string $phone = null
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

    /**
     * @return array
     */
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
