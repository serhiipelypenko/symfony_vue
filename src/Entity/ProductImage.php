<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use App\Repository\ProductImageRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ApiResource(operations: [
    new Get(normalizationContext: ['groups' => ['product_image:item']]),
    new GetCollection(normalizationContext: ['groups' => ['product_image:list']])]
)]
#[ORM\Entity(repositoryClass: ProductImageRepository::class)]
class ProductImage
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['product:list','order:item','cart:item', 'cart:list', 'cart_product:item', 'cart_product:list'])]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'productImages')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Product $product = null;

    #[ORM\Column(length: 255)]
    private ?string $filenameBig = null;

    #[ORM\Column(length: 255)]
    private ?string $filenameMiddle = null;

    #[ORM\Column(length: 255)]
    #[Groups(['product:list','order:item','cart:item', 'cart:list', 'cart_product:item', 'cart_product:list'])]
    private ?string $filenameSmall = null;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getFilenameBig(): ?string
    {
        return $this->filenameBig;
    }

    public function setFilenameBig(string $filenameBig): static
    {
        $this->filenameBig = $filenameBig;

        return $this;
    }

    public function getFilenameMiddle(): ?string
    {
        return $this->filenameMiddle;
    }

    public function setFilenameMiddle(string $filenameMiddle): static
    {
        $this->filenameMiddle = $filenameMiddle;

        return $this;
    }

    public function getFilenameSmall(): ?string
    {
        return $this->filenameSmall;
    }

    public function setFilenameSmall(string $filenameSmall): static
    {
        $this->filenameSmall = $filenameSmall;

        return $this;
    }
}
