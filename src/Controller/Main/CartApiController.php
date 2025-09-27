<?php

namespace App\Controller\Main;

use App\Entity\Cart;
use App\Entity\CartProduct;
use App\Repository\CartProductRepository;
use App\Repository\CartRepository;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api', name: 'main_api_')]
final class CartApiController extends AbstractController
{
    #[Route('/cart', name: 'cart_save', methods: ['POST'])]
    public function saveCart(Request $request, CartRepository $cartRepository,
        CartProductRepository $cartProductRepository,
        ProductRepository $productRepository,
        EntityManagerInterface $entityManage): Response
    {
        $productId = $request->request->get('productId');
        $phpSessionId = $request->cookies->get('PHPSESSID');


        $product = $productRepository->findOneBy(['uuid' => $productId]);

        $cart = $cartRepository->findOneBy(['sessionId' => $phpSessionId]);
        if(!$cart){
            $cart = new Cart();
            $cart->setSessionId($phpSessionId);
        }

        $cartProduct = $cartProductRepository->findOneBy(['cart' => $cart, 'product' => $product]);
        if(!$cartProduct){
            $cartProduct = new CartProduct();
            $cartProduct->setCart($cart);
            $cartProduct->setQuantity(1);
            $cartProduct->setProduct($product);

            $cart->addCartProduct($cartProduct);
        }else{
            $cartProduct->setQuantity($cartProduct->getQuantity() + 1);
        }

        $entityManage->persist($cart);
        $entityManage->persist($cartProduct);
        $entityManage->flush();

        return new JsonResponse([
            'success' => false,
            'data' => [
                'test' => 123
            ]
        ]);
    }
}
