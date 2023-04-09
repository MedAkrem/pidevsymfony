<?php

namespace App\Controller;
use App\Entity\SubCategory;
use App\Entity\sub_category;
use App\Form\SubCategoryType;
use App\Entity\Category;
use App\Form\CategoryType;

use Doctrine\ORM\EntityManagerInterface;
use App\Repository\CategoryRepository;

use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\Validator\Constraints\Uuid;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SubCategoryController extends AbstractController
{


    #[Route('/subaddcategory',name: 'subaddcategory')]
    public function adds(Request $req)
    {
        $subcategory = new SubCategory();

        $form = $this->createForm(SubCategoryType::class, $subcategory);
        $form->handleRequest($req);
        if ($form->isSubmitted() ){
            $em = $this->getDoctrine()->getManager();


            $em->persist($subcategory);
            $em->flush();
            return $this->redirectToRoute('showcategory');
        }
        return $this->render('sub_category/new.html.twig', ['formClass' => $form->createView()]);
    }
    public function __toString()
    {
        return $this->getName(); // or whatever property you want to use as the string representation
    }
    #[Route('/update/{id}', name: 'update')]
    public function update(Request $req,$id,StudentRepository $rep)
    {/* $this->getDoctrine()->getRepository(Student::class)*/
        $class = $rep->find($id);
        $form = $this->createForm(StudentType::class,$class);
        $form->handleRequest($req);
        if($form->isSubmitted()){
            $em = $this->getDoctrine()->getManager();
            $em->flush();
            return $this->redirectToRoute('listStudent');
        }
        return $this->render('Student/update.html.twig',['formClass'=>$form->createView()]);
    }

    #[Route('/addsubcategory/{id}', name: 'addsubcategory')]
    public function add(Request $req, $id)
    {
        $subcategory = new SubCategory();

        $form = $this->createForm(SubCategoryType::class, $subcategory);
        $form->handleRequest($req);
        if ($form->isSubmitted() && $form->isValid()) {
            $category = $this->getDoctrine()->getRepository(Category::class)->find($id);
            if (!$category) {
                throw $this->createNotFoundException('Category not found');
            }

            $subcategory->setCategory($category);

            $em = $this->getDoctrine()->getManager();
            $em->persist($subcategory);
            $em->flush();
            return $this->redirectToRoute('showsubcategory');
        }
        return $this->render('subcategory/new.html.twig', ['formClass' => $form->createView()]);
    }
}


