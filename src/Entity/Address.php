<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Smart\CoreBundle\Doctrine\ColumnTrait;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity()
 * @ORM\Table(name="address")
 */
class Address
{
    use ColumnTrait\Id;
    use ColumnTrait\CreatedAt;
    use ColumnTrait\UserNotNull;

    /**
     * Имя
     *
     * @var string
     *
     * @ORM\Column(type="string", length=30)
     * @Assert\NotNull(message="This value is not valid.")
     * @Assert\Length(max = 30)
     */
    protected $firstname;

    /**
     * Фамилия
     *
     * @var string
     *
     * @ORM\Column(type="string", length=30)
     * @Assert\NotNull(message="This value is not valid.")
     * @Assert\Length(max = 30)
     */
    protected $lastname;

    /**
     * Отчество
     *
     * @var string|null
     *
     * @ORM\Column(type="string", length=30, nullable=true)
     * @Assert\NotNull(message="This value is not valid.")
     * @Assert\Length(max = 30)
     */
    protected $patronymic;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string", length=190, nullable=true)
     * @Assert\Length(max = 190)
     */
    protected $phone;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=50)
     * @Assert\Length(max = 50)
     */
    protected $postcode;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=50)
     * @Assert\Length(max = 50)
     */
    protected $country_code;

    /**
     * Область
     *
     * @var string
     *
     * @ORM\Column(type="string", length=100)
     * @Assert\Length(max = 100)
     */
    protected $province;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=100)
     * @Assert\Length(max = 100)
     */
    protected $city;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=190)
     * @Assert\Length(max = 190)
     */
    protected $street;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=20)
     * @Assert\Length(max = 20)
     */
    protected $house;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string", length=10, nullable=true)
     * @Assert\Length(max = 10)
     */
    protected $flat;


    /**
     * Address constructor.
     */
    public function __construct()
    {
        $this->created_at   = new \DateTime();
        $this->city         = '';
        $this->country_code = 'RU';
        $this->house        = '';
        $this->province     = '';
        $this->postcode     = '';
        $this->street       = '';
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        $str = $this->postcode. ', '.$this->province.' обл, '.$this->city.', ул.'.$this->street.', дом.'.$this->house;

        if (!empty($this->flat)) {
            $str .= ', кв.'.$this->flat;
        }

        return $str;
    }

    /**
     * @return string
     */
    public function getFirstname(): string
    {
        return $this->firstname;
    }

    /**
     * @param string $firstname
     *
     * @return $this
     */
    public function setFirstname(string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    /**
     * @return string
     */
    public function getLastname(): string
    {
        return $this->lastname;
    }

    /**
     * @param string $lastname
     *
     * @return $this
     */
    public function setLastname(string $lastname): self
    {
        $this->lastname = $lastname;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getPatronymic(): ?string
    {
        return $this->patronymic;
    }

    /**
     * @param string|null $patronymic
     *
     * @return $this
     */
    public function setPatronymic(?string $patronymic): self
    {
        $this->patronymic = $patronymic;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getPhone(): ?string
    {
        return $this->phone;
    }

    /**
     * @param string|null $phone
     *
     * @return $this
     */
    public function setPhone(?string $phone): self
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * @return string
     */
    public function getPostcode(): string
    {
        return $this->postcode;
    }

    /**
     * @param string $postcode
     *
     * @return $this
     */
    public function setPostcode(string $postcode): self
    {
        $this->postcode = $postcode;

        return $this;
    }

    /**
     * @return string
     */
    public function getCountryCode(): string
    {
        return $this->country_code;
    }

    /**
     * @param string $country_code
     *
     * @return $this
     */
    public function setCountryCode(string $country_code): self
    {
        $this->country_code = $country_code;

        return $this;
    }

    /**
     * @return string
     */
    public function getProvince(): string
    {
        return $this->province;
    }

    /**
     * @param string $province
     *
     * @return $this
     */
    public function setProvince(string $province): self
    {
        $this->province = $province;

        return $this;
    }

    /**
     * @return string
     */
    public function getCity(): string
    {
        return $this->city;
    }

    /**
     * @param string $city
     *
     * @return $this
     */
    public function setCity(string $city): self
    {
        $this->city = $city;

        return $this;
    }

    /**
     * @return string
     */
    public function getStreet(): string
    {
        return $this->street;
    }

    /**
     * @param string $street
     *
     * @return $this
     */
    public function setStreet(string $street): self
    {
        $this->street = $street;

        return $this;
    }

    /**
     * @return string
     */
    public function getHouse(): string
    {
        return $this->house;
    }

    /**
     * @param string $house
     *
     * @return $this
     */
    public function setHouse(string $house): self
    {
        $this->house = $house;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getFlat(): ?string
    {
        return $this->flat;
    }

    /**
     * @param string|null $flat
     *
     * @return $this
     */
    public function setFlat(?string $flat): self
    {
        $this->flat = $flat;

        return $this;
    }
}
