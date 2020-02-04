<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Entity\TaxRate;
use App\Form\Type\TaxRateFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/tax")
 */
class TaxController extends AbstractController
{
    /**
     * @Route("/", name="admin_tax")
     */
    public function index(EntityManagerInterface $em): Response
    {
        return $this->render('admin/tax/index.html.twig', [
            'taxes' => $em->getRepository(TaxRate::class)->findBy([], ['percent' => 'ASC']),
        ]);
    }

    /**
     * @Route("/create/", name="admin_tax_create")
     */
    public function create(Request $request, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(TaxRateFormType::class, new TaxRate());
        $form->remove('update');

        if ($request->isMethod('POST')) {
            $form->handleRequest($request);

            if ($form->get('cancel')->isClicked()) {
                return $this->redirectToRoute('admin_tax');
            }

            if ($form->get('create')->isClicked() and $form->isValid()) {
                $em->persist($form->getData());
                $em->flush();

                $this->addFlash('success', 'Налоговая ставка добавлена');

                return $this->redirectToRoute('admin_tax');
            }
        }

        return $this->render('admin/tax/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/", name="admin_tax_edit")
     */
    public function edit(TaxRate $tax, Request $request, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(TaxRateFormType::class, $tax);
        $form->remove('create');

        if ($request->isMethod('POST')) {
            $form->handleRequest($request);

            if ($form->get('cancel')->isClicked()) {
                return $this->redirectToRoute('admin_tax');
            }

            if ($form->get('update')->isClicked() and $form->isValid()) {
                $em->persist($form->getData());
                $em->flush();

                $this->addFlash('success', 'Налоговая ставка обновлена');

                return $this->redirectToRoute('admin_tax');
            }
        }

        return $this->render('admin/tax/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
