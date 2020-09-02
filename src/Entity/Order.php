<?php
/**
 * Order entity.
 *
 * @Project : alma
 * @File    : Order.php
 * @Author  : Nicolas BOULFROY
 * @Create  : 2020/08/29
 * @Update  : 2020/09/02
 */

namespace App\Entity;

class Order
{
    /**
     * Order ID.
     *
     * @var int|null $id
     */
    private $id;

    /**
     * Order ID in Alma web service.
     *
     * @var string|null $almaId
     */
    private $almaId;

    /**
     * Customer's first name.
     *
     * @var string|null $firstName
     */
    private $firstName;

    /**
     * Customer's last name.
     *
     * @var string|null $lastName
     */
    private $lastName;

    /**
     * Customer's address.
     *
     * @var string|null $address
     */
    private $address;

    /**
     * Customer's email.
     *
     * @var string|null $email
     */
    private $email;

    /**
     * Customer's phone number.
     *
     * @var string|null $phoneNumber
     */
    private $phoneNumber;

    /**
     * Order's total amount.
     *
     * @var float|null $amount
     */
    private $amount;

    /**
     * Number of payments for the order.
     *
     * @var int|null $paymentPlan
     */
    private $paymentPlan;

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return string|null
     */
    public function getAlmaId(): ?string
    {
        return $this->almaId;
    }

    /**
     * @param string|null $almaId
     *
     * @return self
     */
    public function setAlmaId(?string $almaId): self
    {
        $this->almaId = $almaId;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    /**
     * @param string|null $firstName
     *
     * @return self
     */
    public function setFirstName(?string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    /**
     * @param string|null $lastName
     *
     * @return self
     */
    public function setLastName(?string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getAddress(): ?string
    {
        return $this->address;
    }

    /**
     * @param string|null $address
     *
     * @return self
     */
    public function setAddress(?string $address): self
    {
        $this->address = $address;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * @param string|null $email
     *
     * @return self
     */
    public function setEmail(?string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getPhoneNumber(): ?string
    {
        return $this->phoneNumber;
    }

    /**
     * @param string|null $phoneNumber
     *
     * @return self
     */
    public function setPhoneNumber(?string $phoneNumber): self
    {
        $this->phoneNumber = $phoneNumber;

        return $this;
    }

    /**
     * @return float|null
     */
    public function getAmount(): ?float
    {
        return $this->amount;
    }

    /**
     * @param float|null $amount
     *
     * @return self
     */
    public function setAmount(?float $amount): self
    {
        $this->amount = $amount;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getPaymentPlan(): ?int
    {
        return $this->paymentPlan;
    }

    /**
     * @param int|null $paymentPlan
     *
     * @return self
     */
    public function setPaymentPlan(?int $paymentPlan): self
    {
        $this->paymentPlan = $paymentPlan;

        return $this;
    }
}