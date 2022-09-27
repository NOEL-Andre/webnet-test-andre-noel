<?php

namespace App\Entity;

use App\Repository\GameRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: GameRepository::class)]
class Game
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $cardNumber = null;

    #[ORM\Column(type: Types::ARRAY)]
    private array $colorOrder = [];

    #[ORM\Column(type: Types::ARRAY)]
    private array $valueOrder = [];

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCardNumber(): ?int
    {
        return $this->cardNumber;
    }

    public function setCardNumber(int $cardNumber): self
    {
        $this->cardNumber = $cardNumber;

        return $this;
    }

    public function getColorOrder(): array
    {
        return $this->colorOrder;
    }

    public function setColorOrder(array $colorOrder): self
    {
        $this->colorOrder = $colorOrder;

        return $this;
    }

    public function getValueOrder(): array
    {
        return $this->valueOrder;
    }

    public function setValueOrder(array $valueOrder): self
    {
        $this->valueOrder = $valueOrder;

        return $this;
    }
}
