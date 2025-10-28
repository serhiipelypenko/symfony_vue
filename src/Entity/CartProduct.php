<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use App\Repository\CartProductRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ApiResource(operations: [
    new Get(normalizationContext: ['groups' => ['cart_product:item']]),
    new Post(normalizationContext: ['groups' => ['cart_product:write']], security: "is_granted('ROLE_ADMIN')"),
    new GetCollection(normalizationContext: ['groups' => ['cart_product:list']])]
)]
#[ORM\Entity(repositoryClass: CartProductRepository::class)]
class CartProduct
{
    #[Groups(['cart:item', 'cart:list', 'cart_product:item', 'cart_product:list'])]
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Groups(['cart_product:item', 'cart_product:list'])]
    #[ORM\ManyToOne(inversedBy: 'cartProducts')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Cart $cart = null;

    #[Groups(['cart:item', 'cart:list', 'cart_product:item', 'cart_product:list'])]
    #[ORM\ManyToOne(inversedBy: 'cartProducts')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Product $product = null;

    #[Groups(['cart:item', 'cart:list', 'cart_product:item', 'cart_product:list'])]
    #[ORM\Column]
    private ?int $quantity = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCart(): ?Cart
    {
        return $this->cart;
    }

    public function setCart(?Cart $cart): static
    {
        $this->cart = $cart;

        return $this;
    }

    public function getProduct(): ?Product
    {
        return $this->product;
    }

    public function setProduct(?Product $product): static
    {
        $this->product = $product;

        return $this;
    }

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): static
    {
        $this->quantity = $quantity;

        return $this;
    }
}
