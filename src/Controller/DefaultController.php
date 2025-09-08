<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\EditProductFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
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

    #[Route('/edit-product/{id}', name: 'product-edit', methods: ['GET', 'POST'], requirements:["id"=>"\d+"])]
    #[Route('/add-product', methods: ['GET', 'POST'],  name: 'product-add')]
    public function editProduct(Request $request,EntityManagerInterface $entityManager, int $id = null): Response
    {
        if($id){
            $product = $entityManager->getRepository(Product::class)->find($id);
        }else{
            $product = new Product();
        }
        $form = $this->createForm(EditProductFormType::class, $product);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
           $entityManager->persist($product);
           $entityManager->flush();
           return $this->redirectToRoute('product-edit', ['id' => $product->getId()]);
        }
        return $this->render('main/default/edit_product.html.twig', ['form' => $form->createView()]);
    }
}
