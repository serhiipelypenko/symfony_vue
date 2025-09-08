<?php

namespace App\Controller;

use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class DefaultController extends AbstractController
{
    #[Route('/', name: 'homepage')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $productList = $entityManager->getRepository(Product::class)->findAll();
        dd($productList);

        return $this->render('main/default/index.html.twig', [
            'controller_name' => 'DefaultController',
        ]);
    }

    #[Route('/product-add', name: 'product-add')]
    public function productAdd(EntityManagerInterface $entityManager): Response
    {
        $product = new Product();
        $product->setTitle('Product '.rand(1, 100));
        $product->setDescription('Product Description '.rand(1, 100));
        $product->setPrice(rand(1, 100));
        $product->setQuantity(rand(1, 30));

        $entityManager->persist($product);
        $entityManager->flush();

        return $this->redirectToRoute('homepage');
    }
}
