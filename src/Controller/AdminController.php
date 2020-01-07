<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Cooperative;
use App\Entity\CooperativeHistory;
use App\Entity\User;
use App\Form\Type\CategoryFormType;
use App\Repository\CooperativeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin")
 */
class AdminController extends AbstractController
{
    /**
     * @Route("/", name="admin")
     */
    public function index(): Response
    {
        return $this->render('admin/index.html.twig');
    }

    /**
     * @Route("/user/", name="admin_user")
     */
    public function userIndex(Request $request, EntityManagerInterface $em): Response
    {
        return $this->render('admin/user_index.html.twig', [
            'users' => $em->getRepository(User::class)->findBy([], ['created_at' => 'DESC']),
        ]);
    }

    /**
     * @Route("/coop/", name="admin_coop")
     */
    public function coopIndex(CooperativeRepository $cooperativeRepository): Response
    {
        return $this->render('admin/coop_index.html.twig', [
            'coops' => $cooperativeRepository->findBy([], ['created_at' => 'DESC']),
        ]);
    }

    /**
     * @Route("/coop/{id}/", name="admin_coop_show")
     */
    public function coopShow(Cooperative $coop, Request $request, EntityManagerInterface $em): Response
    {
        if ($coop->getStatus() == Cooperative::STATUS_PENDING) {
            if ($request->query->has('approve')) {
                $history = new CooperativeHistory();
                $history
                    ->setCooperative($coop)
                    ->setAction(CooperativeHistory::ACTION_UPDATE)
                    ->setUser($this->getUser())
                    ->setOldValue(['status' => $coop->getStatus()])
                    ->setNewValue(['status' => Cooperative::STATUS_ACTIVE])
                ;

                $coop->setStatus(Cooperative::STATUS_ACTIVE);

                $this->addFlash('success', 'Заявка на создание кооператива одобрена');

                $em->persist($history);
                $em->flush();

                return $this->redirectToRoute('admin_coop_show', ['id' => $coop->getId()]);
            }

            if ($request->query->has('decline')) {
                $history = new CooperativeHistory();
                $history
                    ->setCooperative($coop)
                    ->setAction(CooperativeHistory::ACTION_UPDATE)
                    ->setUser($this->getUser())
                    ->setOldValue(['status' => $coop->getStatus()])
                    ->setNewValue(['status' => Cooperative::STATUS_DECLINE])
                ;

                $coop->setStatus(Cooperative::STATUS_DECLINE);

                $this->addFlash('error', 'Заявка на создание кооператива отклонена');

                $em->persist($history);
                $em->flush();

                return $this->redirectToRoute('admin_coop_show', ['id' => $coop->getId()]);
            }
        }

        return $this->render('admin/coop_show.html.twig', [
            'coop' => $coop,
        ]);
    }

    /**
     * @Route("/category/", name="admin_category")
     */
    public function categoryIndex(Request $request, EntityManagerInterface $em): Response
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
     * @Route("/category/{id}/", name="admin_category_edit")
     */
    public function categoryEdit(Category $category, Request $request, EntityManagerInterface $em): Response
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
