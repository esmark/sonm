<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Smart\CoreBundle\Doctrine\ColumnTrait;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * Способы оплаты
 *
 * @ORM\Entity()
 * @ORM\Table(name="payments_methods",
 *      indexes={
 *          @ORM\Index(columns={"is_enabled"}),
 *      }
 * )
 *
 * @ORM\HasLifecycleCallbacks()
 */
class PaymentMethod
{
    use ColumnTrait\Id;
    use ColumnTrait\IsEnabled;
    use ColumnTrait\CreatedAt;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=100, unique=true)
     */
    protected $title;

    /**
     * Имя сервиса. Например: 'app.payment.cash' или 'App\Payment\CashPayment'
     *
     * @var string
     *
     * @ORM\Column(type="string", length=100, nullable=true, unique=true)
     */
    protected $service;

    /**
     * Имя класса. Например: 'App\Payment\CashPayment'
     *
     * @var string
     *
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    protected $class;

    /**
     * @var Cooperative[]|Collection
     *
     * @ORM\ManyToMany(targetEntity="Cooperative", mappedBy="paymentMethods")
     */
    protected $cooperatives;

    /**
     * PaymentMethod constructor.
     */
    public function __construct()
    {
        $this->created_at   = new \DateTime();
        $this->is_enabled   = false;
        $this->cooperatives = new ArrayCollection();
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->title;
    }

    /**
     * @return string
     */
    public function getService(): string
    {
        return $this->service;
    }

    /**
     * @param string $service
     *
     * @return $this
     */
    public function setService(string $service): self
    {
        $this->service = $service;

        return $this;
    }

    /**
     * @return string
     */
    public function getClass(): string
    {
        return $this->class;
    }

    /**
     * @param string $class
     *
     * @return $this
     */
    public function setClass(string $class): self
    {
        $this->class = $class;

        return $this;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @param string $title
     *
     * @return $this
     */
    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @return Cooperative[]|Collection
     */
    public function getCooperatives()
    {
        return $this->cooperatives;
    }

    /**
     * @param Cooperative[]|Collection $cooperatives
     *
     * @return $this
     */
    public function setCooperatives($cooperatives): self
    {
        $this->cooperatives = $cooperatives;

        return $this;
    }
}
