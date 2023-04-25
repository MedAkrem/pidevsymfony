<?php

namespace App\Controller;
use App\Entity\Category;
use App\Form\CategoryType;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\Uuid;
//#[Route('/category')]
class CategoryController extends AbstractController
{
    #[Route('/addcategory',name: 'addcategory')]
    public function add(Request $req)
    {
        $category = new Category();

        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($req);
        if ($form->isSubmitted() ){
            $em = $this->getDoctrine()->getManager();


            $em->persist($category);
            $em->flush();
            return $this->redirectToRoute('showcategory');
        }
        return $this->render('category/new.html.twig', ['form' => $form->createView()]);
    }


    #[Route('/showcategory', name: 'showcategory')]
    public function list(CategoryRepository $c):Response
    {
        $Categorys = $this->getDoctrine()->getRepository(Category::class)->findAll();
        $Categorys = $c->findAll();
        return $this->render('category/show.html.twig',['list'=>$Categorys]);
    }

    #[Route('/admin/showcategory', name: 'admin/showcategory')]
    public function listadmin(CategoryRepository $c):Response
    {
        $Categorys = $this->getDoctrine()->getRepository(Category::class)->findAll();
        $Categorys = $c->findAll();
        return $this->render('category/frontcategory.html.twig',['list'=>$Categorys]);
    }


    #[Route('/updatecategory/{id}', name: 'updatecategory')]

    public function update(Request $req,$id,CategoryRepository $rep)
    {/* $this->getDoctrine()->getRepository(Classroom::class)*/
        $class = $rep->find($id);
        $form = $this->createForm(CategoryType::class,$class);
        $form->handleRequest($req);
        if($form->isSubmitted()){
            $em = $this->getDoctrine()->getManager();
            $em->flush();
            return $this->redirectToRoute('showcategory');
        }
        return $this->render('category/edit.html.twig',['formClass'=>$form->createView()]);
    }

    #[Route('/deletecategory/{id}', name :'deletecategory')]

    public function delete($id,EntityManagerInterface $manager)
    {
        $manager->remove($this->getDoctrine()->getRepository(category::class)->find($id));
        $manager->flush();
        return $this->redirectToRoute('showcategory');
    }

    #[Route('/addsubcategory/{id}', name: 'addsubcategory')]
    public function addSubCategory(Request $request, $id): Response
    {
        $category = $this->getDoctrine()->getRepository(Category::class)->find($id);
        if (!$category) {
            throw $this->createNotFoundException('Category not found');
        }

        $subCategory = new SubCategory();
        $subCategory->setCategory($category);

        $form = $this->createForm(SubCategoryType::class, $subCategory);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($subCategory);
            $entityManager->flush();

            return $this->redirectToRoute('showsubcategory');
        }

        return $this->render('subcategory/new.html.twig', [
            'form' => $form->createView(),
            'category' => $category
        ]);
    }






}

/*
    #[Route('/', name: 'app_category_index', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $categories = $entityManager
            ->getRepository(Category::class)
            ->findAll();

        return $this->render('category/index.html.twig', [
            'categories' => $categories,
        ]);
    }

    #[Route('/new', name: 'app_category_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $category = new Category();
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($category);
            $entityManager->flush();

            return $this->redirectToRoute('app_category_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('category/new.html.twig', [
            'category' => $category,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_category_show', methods: ['GET'])]
    public function show(Category $category): Response
    {
        return $this->render('category/show.html.twig', [
            'category' => $category,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_category_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Category $category, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_category_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('category/edit.html.twig', [
            'category' => $category,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_category_delete', methods: ['POST'])]
    public function delete(Request $request, Category $category, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$category->getId(), $request->request->get('_token'))) {
            $entityManager->remove($category);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_category_index', [], Response::HTTP_SEE_OTHER);
    }
}
*/