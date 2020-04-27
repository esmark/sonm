<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
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
     * @var Order[]|Collection
     *
     * @ORM\OneToMany(targetEntity="Order", mappedBy="shippingAddress", cascade={"persist"}, fetch="EXTRA_LAZY")
     * @ORM\OrderBy({"created_at" = "DESC"})
     */
    protected $orders;

    public function __construct()
    {
        $this->created_at   = new \DateTime();
        $this->city         = '';
        $this->country_code = 'RU';
        $this->house        = '';
        $this->province     = '';
        $this->postcode     = '';
        $this->street       = '';
        $this->orders       = new ArrayCollection();
    }

    public function __toString(): string
    {
        $str = $this->postcode. ', '.$this->province.' обл, '.$this->city.', ул.'.$this->street.', дом.'.$this->house;

        if (!empty($this->flat)) {
            $str .= ', кв.'.$this->flat;
        }

        return $str;
    }

    public function getFirstname(): string
    {
        return $this->firstname;
    }

    public function setFirstname(?string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname(): string
    {
        return $this->lastname;
    }

    public function setLastname(?string $lastname): self
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getPatronymic(): ?string
    {
        return $this->patronymic;
    }

    public function setPatronymic(?string $patronymic): self
    {
        $this->patronymic = $patronymic;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(?string $phone): self
    {
        $this->phone = $phone;

        return $this;
    }

    public function getPostcode(): string
    {
        return $this->postcode;
    }

    public function setPostcode(?string $postcode): self
    {
        $this->postcode = $postcode;

        return $this;
    }

    public function getCountryCode(): string
    {
        return $this->country_code;
    }

    public function setCountryCode(string $country_code): self
    {
        $this->country_code = $country_code;

        return $this;
    }

    public function getProvince(): string
    {
        return $this->province;
    }

    public function setProvince(?string $province): self
    {
        $this->province = $province;

        return $this;
    }

    public function getCity(): string
    {
        return $this->city;
    }

    public function setCity(?string $city): self
    {
        $this->city = $city;

        return $this;
    }

    public function getStreet(): string
    {
        return $this->street;
    }

    public function setStreet(?string $street): self
    {
        $this->street = $street;

        return $this;
    }

    public function getHouse(): string
    {
        return $this->house;
    }

    public function setHouse(?string $house): self
    {
        $this->house = $house;

        return $this;
    }

    public function getFlat(): ?string
    {
        return $this->flat;
    }

    public function setFlat(?string $flat): self
    {
        $this->flat = $flat;

        return $this;
    }

    /**
     * @return Order[]|Collection
     */
    public function getOrders()
    {
        return $this->orders;
    }

    /**
     * @param Order[]|Collection $orders
     *
     * @return $this
     */
    public function setOrders($orders): self
    {
        $this->orders = $orders;

        return $this;
    }
}
