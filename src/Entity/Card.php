<?php
/**
 *
 *
 * @Project : alma
 * @File    : Card.php
 * @Author  : Nicolas BOULFROY
 * @Create  : 2020/08/29
 */

namespace App\Entity;


class Card
{
    /**
     * Card's product quantity.
     *
     * @var int|null $quantity
     */
    private $quantity;

    /**
     * Card's total amount.
     *
     * @var float|null $amount
     */
    private $amount;

    /**
     * @return int|null
     */
    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    /**
     * @param int|null $quantity
     */
    public function setQuantity(?int $quantity): void
    {
        $this->quantity = $quantity;
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
     */
    public function setAmount(?float $amount): void
    {
        $this->amount = $amount;
    }

    /**
     * @param float $amount
     */
    public function addAmount(float $amount): void
    {
        $this->amount += $amount;
    }

    /**
     * @param int $quantity
     */
    public function addQuantity(int $quantity): void
    {
        $this->quantity += $quantity;
    }
}