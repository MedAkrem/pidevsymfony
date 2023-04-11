<?php

namespace App\Controller;
use App\Entity\SubCategory;
use App\Entity\Category;
use App\Entity\Product;
use App\Form\ProductType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\SubCategoryType;

use App\Form\CategoryType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

use App\Repository\CategoryRepository;


use Symfony\Component\Validator\Constraints\Uuid;








//#[Route('/product')]
class ProductController extends AbstractController
{
    #[Route('/addproduct', name: 'addproduct')]
    public function addProduct(Request $request)
    {
        $product = new Product();

        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $subCategoryName = $form->get('SubCategory')->getData()->getName();
            $subCategory = $this->getDoctrine()->getRepository(SubCategory::class)->findOneBy(['name' => $subCategoryName]);
            if (!$subCategory) {
                throw $this->createNotFoundException('Subcategory not found');
            }

            $product->setSubCategory($subCategory);

            $em = $this->getDoctrine()->getManager();
            $em->persist($product);
            $em->flush();

            return $this->redirectToRoute('listproducts');
        }

        return $this->render('product/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    public function getProducts()
    {
        return $this->products;
    }
    #[Route('/listproducts', name: 'listproducts')]
    public function listProducts(): Response
    {
        $products = $this->getDoctrine()->getRepository(Product::class)->findAll();

        return $this->render('product/show.html.twig', ['products' => $products]);
    }

    #[Route('/updateproduct/{id}', name: 'updateproduct')]
    public function update(Request $req, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $product = $em->getRepository(Product::class)->find($id);
        if (!$product) {
            throw $this->createNotFoundException('Product not found');
        }
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($req);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            return $this->redirectToRoute('listproducts');
        }
        return $this->render('product/edit.html.twig', ['formProduct' => $form->createView()]);
    }

    #[Route('/deleteproduit/{id}', name: 'deleteproduit')]
    public function delete($id, EntityManagerInterface $manager)
    {
        $product = $manager->getRepository(Product::class)->find($id);

        if (!$product) {
            throw $this->createNotFoundException('Product not found');
        }

        $manager->remove($product);
        $manager->flush();

        return $this->redirectToRoute('listproducts');
    }














}
 /*   #[Route('/addproduct',name: 'addproduct')]
    public function add(Request $req)
    {
        $product = new Category();

        $form = $this->createForm(productType::class, $product);
        $form->handleRequest($req);
        if ($form->isSubmitted() ){
            $em = $this->getDoctrine()->getManager();


            $em->persist($product);
            $em->flush();
            return $this->redirectToRoute('showproduct');
        }
        return $this->render('product/new.html.twig', ['formClass' => $form->createView()]);
    }

    #[Route('/subcategory/{id}/add-product', name: 'add_product')]
    public function addProduct(Request $request, $id): Response
    {
        $subCategory = $this->getDoctrine()->getRepository(SubCategory::class)->find($id);
        if (!$subCategory) {
            throw $this->createNotFoundException('Subcategory not found');
        }

        $product = new Product();
        $product->setSubCategory($subCategory);

        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($product);
            $entityManager->flush();

            return $this->redirectToRoute('show_subcategory', ['id' => $subCategory->getId()]);
        }

        return $this->render('product/new.html.twig', [
            'form' => $form->createView(),
            'subCategoryId' => $id,
        ]);
    }











}
    /*
    #[Route('/', name: 'app_product_index', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $products = $entityManager
            ->getRepository(Product::class)
            ->findAll();

        return $this->render('product/index.html.twig', [
            'products' => $products,
        ]);
    }

    #[Route('/new', name: 'app_product_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $product = new Product();
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($product);
            $entityManager->flush();

            return $this->redirectToRoute('app_product_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('product/new.html.twig', [
            'product' => $product,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_product_show', methods: ['GET'])]
    public function show(Product $product): Response
    {
        return $this->render('product/show.html.twig', [
            'product' => $product,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_product_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Product $product, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_product_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('product/edit.html.twig', [
            'product' => $product,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_product_delete', methods: ['POST'])]
    public function delete(Request $request, Product $product, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$product->getId(), $request->request->get('_token'))) {
            $entityManager->remove($product);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_product_index', [], Response::HTTP_SEE_OTHER);
    }
}

    */
