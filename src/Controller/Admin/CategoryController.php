<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Entity\Category;
use App\Form\Type\CategoryFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/category")
 */
class CategoryController extends AbstractController
{
    /**
     * @Route("/", name="admin_category")
     */
    public function index(Request $request, EntityManagerInterface $em): Response
    {
        $categories = $em->getRepository(Category::class)->findBy([], ['position' => 'ASC', 'title' => 'ASC']);

        $form = $this->createForm(CategoryFormType::class, new Category());
        $form->remove('update');
        $form->remove('cancel');

        if ($request->isMethod('POST')) {
            $form->handleRequest($request);

            if ($form->get('create')->isClicked() and $form->isValid()) {
                $em->persist($form->getData());
                $em->flush();

                $this->addFlash('success', 'Категория создана');

                return $this->redirectToRoute('admin_category');
            }
        }

        return $this->render('admin/category/index.html.twig', [
            'categories' => $categories,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/", name="admin_category_edit")
     */
    public function edit(Category $category, Request $request, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(CategoryFormType::class, $category);
        $form->remove('create');

        if ($request->isMethod('POST')) {
            $form->handleRequest($request);

            if ($form->get('cancel')->isClicked()) {
                return $this->redirectToRoute('admin_category');
            }

            if ($form->get('update')->isClicked() and $form->isValid()) {
                $em->persist($category);
                $em->flush();

                $this->addFlash('success', 'Категория обновлена');

                return $this->redirectToRoute('admin_category');
            }
        }

        return $this->render('admin/category/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
