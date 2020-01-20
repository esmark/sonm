<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Cooperative;
use App\Entity\CooperativeHistory;
use App\Entity\PickUpLocation;
use App\Entity\Program;
use App\Entity\User;
use App\Form\Type\CategoryFormType;
use App\Form\Type\PickUpLocationFormType;
use App\Form\Type\ProgramFormType;
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

                $this->addFlash('success', 'Заявка на добавление кооператива одобрена');

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

                $this->addFlash('error', 'Заявка на добавление кооператива отклонена');

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

    /**
     * @Route("/pick_up_location/", name="admin_pick_up_location")
     */
    public function pickUpLocation(EntityManagerInterface $em): Response
    {
        return $this->render('admin/pick_up_location_index.html.twig', [
            'pick_up_locations' => $em->getRepository(PickUpLocation::class)->findBy([], ['title' => 'ASC']),
        ]);
    }

    /**
     * @Route("/pick_up_location/new/", name="admin_pick_up_location_new")
     */
    public function pickUpLocationNew(Request $request, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(PickUpLocationFormType::class, new PickUpLocation());
        $form->remove('update');

        if ($request->isMethod('POST')) {
            $form->handleRequest($request);

            if ($form->get('cancel')->isClicked()) {
                return $this->redirectToRoute('admin_pick_up_location');
            }

            if ($form->get('create')->isClicked() and $form->isValid()) {
                $form->getData()->setUser($this->getUser());
                $em->persist($form->getData());
                $em->flush();

                $this->addFlash('success', 'Точка выдачи добавлена');

                return $this->redirectToRoute('admin_pick_up_location');
            }
        }

        return $this->render('admin/pick_up_location_new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/pick_up_location/{id}/", name="admin_pick_up_location_edit")
     */
    public function pickUpLocationEdit(PickUpLocation $pickUpLocation, Request $request, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(PickUpLocationFormType::class, $pickUpLocation);
        $form->remove('create');

        if ($request->isMethod('POST')) {
            $form->handleRequest($request);

            if ($form->get('cancel')->isClicked()) {
                return $this->redirectToRoute('admin_pick_up_location');
            }

            if ($form->get('update')->isClicked() and $form->isValid()) {
                $em->persist($form->getData());
                $em->flush();

                $this->addFlash('success', 'Точка выдачи обновлена');

                return $this->redirectToRoute('admin_pick_up_location');
            }
        }

        return $this->render('admin/pick_up_location_edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/program/", name="admin_program")
     */
    public function program(EntityManagerInterface $em): Response
    {
        return $this->render('admin/program_index.html.twig', [
            'programs' => $em->getRepository(Program::class)->findBy([], ['title' => 'ASC']),
        ]);
    }

    /**
     * @Route("/program/new/", name="admin_program_new")
     */
    public function programNew(Request $request, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(ProgramFormType::class, new Program());
        $form->remove('update');

        if ($request->isMethod('POST')) {
            $form->handleRequest($request);

            if ($form->get('cancel')->isClicked()) {
                return $this->redirectToRoute('admin_program');
            }

            if ($form->get('create')->isClicked() and $form->isValid()) {
                $form->getData()->setUser($this->getUser());
                $em->persist($form->getData());
                $em->flush();

                $this->addFlash('success', 'Целевая программа добавлена');

                return $this->redirectToRoute('admin_program');
            }
        }

        return $this->render('admin/program_new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/program/{id}/", name="admin_program_edit")
     */
    public function programEdit(Program $program, Request $request, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(ProgramFormType::class, $program);
        $form->remove('create');

        if ($request->isMethod('POST')) {
            $form->handleRequest($request);

            if ($form->get('cancel')->isClicked()) {
                return $this->redirectToRoute('admin_program');
            }

            if ($form->get('update')->isClicked() and $form->isValid()) {
                $em->persist($form->getData());
                $em->flush();

                $this->addFlash('success', 'Целевая программа обновлена');

                return $this->redirectToRoute('admin_program');
            }
        }

        return $this->render('admin/program_edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
