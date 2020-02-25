<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Entity\ShippingMethod;
use App\Form\Type\ShippingMethodFormType;
use App\Shipping\ShippingInterface;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Container\ContainerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/shipping_method")
 */
class ShippingMethodController extends AbstractController
{
    /**
     * @Route("/", name="admin_shipping_method")
     */
    public function index(EntityManagerInterface $em, ContainerInterface $container): Response
    {
        foreach ($container->getParameter('app.shipping.methods') as $service => $class) {
            $method = $em->getRepository(ShippingMethod::class)->findOneBy(['service' => $service]);

            /** @var ShippingInterface $pm */
            $pm = $container->get($service);

            if (empty($method)) {
                $method = new shippingMethod();
                $method
                    ->setTitle($pm->getTitle() ? $pm->getTitle() : $service)
                    ->setService($service)
                    ->setClass($class)
                ;

                $em->persist($method);
                $em->flush();
            }
        }

        return $this->render('admin/shipping_method/index.html.twig', [
            'methods' => $em->getRepository(ShippingMethod::class)->findBy([], ['title' => 'ASC']),
        ]);
    }

    /**
     * @Route("/{id}/", name="admin_shipping_method_edit")
     */
    public function edit(ShippingMethod $method, Request $request, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(ShippingMethodFormType::class, $method);

        if ($request->isMethod('POST')) {
            $form->handleRequest($request);

            if ($form->get('cancel')->isClicked()) {
                return $this->redirectToRoute('admin_shipping_method');
            }

            if ($form->get('update')->isClicked() and $form->isValid()) {
                $em->persist($form->getData());
                $em->flush();

                $this->addFlash('success', 'Метода доставки обновлён');

                return $this->redirectToRoute('admin_shipping_method');
            }
        }

        return $this->render('admin/shipping_method/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
