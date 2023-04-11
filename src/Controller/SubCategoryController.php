<?php

namespace App\Controller;
use App\Entity\SubCategory;
use App\Entity\sub_category;
use App\Form\SubCategoryType;
use App\Entity\Category;
use App\Form\CategoryType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\CategoryRepository;

use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\Validator\Constraints\Uuid;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class SubCategoryController extends AbstractController
{






    private function getCategoryIdByName($categoryName)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $category = $entityManager->getRepository(Category::class)->findOneBy(['name' => $categoryName]);
        if (!$category) {
            throw $this->createNotFoundException('Category not found');
        }
        return $category->getId();
    }
    #[Route('/addsubcategory', name: 'addsubcategory')]
    public function addss(Request $req)
    {
        $subcategory = new SubCategory();

        $form = $this->createForm(SubCategoryType::class, $subcategory);
        $form->handleRequest($req);
        if ($form->isSubmitted() && $form->isValid()) {
            $categoryName = $form->get('category')->getData()->getName();
            $categoryId = $this->getCategoryIdByName($categoryName);
            $category = $this->getDoctrine()->getRepository(Category::class)->find($categoryId);
            if (!$category) {
                throw $this->createNotFoundException('Category not found');
            }

            $subcategory->setCategory($category);

            $em = $this->getDoctrine()->getManager();
            $em->persist($subcategory);
            $em->flush();
            return $this->redirectToRoute('showsubcategory');
        }
        return $this->render('sub_category/new.html.twig', ['form' => $form->createView()]);
    }
    #[Route('/showsubcategory', name: 'showsubcategory')]
    public function list(): Response
    {
        $subCategories = $this->getDoctrine()->getRepository(SubCategory::class)->findAll();

        return $this->render('sub_category/show.html.twig', ['list' => $subCategories]);
    }

    #[Route('/updatesubcategory/{id}', name: 'updatesubcategory')]
    public function update(Request $req, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $subCategory = $em->getRepository(SubCategory::class)->find($id);
        if (!$subCategory) {
            throw $this->createNotFoundException('SubCategory not found');
        }
        $form = $this->createForm(SubCategoryType::class, $subCategory);
        $form->handleRequest($req);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            return $this->redirectToRoute('showsubcategory');
        }
        return $this->render('sub_category/edit.html.twig', ['formClass' => $form->createView()]);
    }

    #[Route('/deletesubcategory/{id}', name: 'deletesubcategory')]
    public function delete($id, EntityManagerInterface $manager)
    {
        $subcategory = $manager->getRepository(SubCategory::class)->find($id);

        if (!$subcategory) {
            throw $this->createNotFoundException('Subcategory not found');
        }

        $manager->remove($subcategory);
        $manager->flush();

        return $this->redirectToRoute('showsubcategory');
    }


}


