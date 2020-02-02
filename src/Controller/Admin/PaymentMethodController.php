<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Entity\Category;
use App\Entity\PaymentMethod;
use App\Entity\TaxRate;
use App\Form\Type\CategoryFormType;
use App\Form\Type\PaymentMethodFormType;
use App\Form\Type\TaxRateFormType;
use App\Payment\PaymentInterface;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Container\ContainerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/payment_method")
 */
class PaymentMethodController extends AbstractController
{
    /**
     * @Route("/", name="admin_payment_method")
     */
    public function index(EntityManagerInterface $em, ContainerInterface $container): Response
    {
        foreach ($container->getParameter('app.payment.methods') as $service => $class) {
            $paymentMethod = $em->getRepository(PaymentMethod::class)->findOneBy(['service' => $service]);

            /** @var PaymentInterface $pm */
            $pm = $container->get($service);

            if (empty($paymentMethod)) {
                $paymentMethod = new PaymentMethod();
                $paymentMethod
                    ->setTitle($pm->getTitle() ? $pm->getTitle() : $service)
                    ->setService($service)
                    ->setClass($class)
                ;

                $em->persist($paymentMethod);
                $em->flush();
            }
        }

        return $this->render('admin/payment_method/index.html.twig', [
            'methods' => $em->getRepository(PaymentMethod::class)->findBy([], ['title' => 'ASC']),
        ]);
    }

    /**
     * @Route("/{id}/", name="admin_payment_method_edit")
     */
    public function edit(PaymentMethod $paymentMethod, Request $request, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(PaymentMethodFormType::class, $paymentMethod);

        if ($request->isMethod('POST')) {
            $form->handleRequest($request);

            if ($form->get('cancel')->isClicked()) {
                return $this->redirectToRoute('admin_payment_method');
            }

            if ($form->get('update')->isClicked() and $form->isValid()) {
                $em->persist($form->getData());
                $em->flush();

                $this->addFlash('success', 'Метода платежа обновлён');

                return $this->redirectToRoute('admin_payment_method');
            }
        }

        return $this->render('admin/payment_method/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
