<?php

declare(strict_types=1);

namespace App\Menu;

use Knp\Menu\FactoryInterface;
use Knp\Menu\ItemInterface;

class MainMenu
{
    private $factory;

    /**
     * Add any other dependency you need
     */
    public function __construct(FactoryInterface $factory)
    {
        $this->factory = $factory;
    }

    /**
     * Главное меню
     */
    public function top(array $options): ItemInterface
    {
        $menu = $this->factory->createItem('root', [
            'childrenAttributes'    => [
                'class' => 'navbar-nav',
            ],
        ]);

        $menu->addChild('Homepage', ['route' => 'homepage'])
            ->setAttribute('class', 'nav-item')
            ->setLinkAttribute('class', 'nav-link py-0')
        ;

        $menu->addChild('Marketplace', ['route' => 'marketplace'])
            ->setAttribute('class', 'nav-item')
            ->setLinkAttribute('class', 'nav-link py-0')
        ;

        $menu->addChild('Cooperatives', ['route' => 'coop'])
            ->setAttribute('class', 'nav-item')
            ->setLinkAttribute('class', 'nav-link py-0')
        ;

        $menu->addChild('Programs', ['route' => 'program'])
            ->setAttribute('class', 'nav-item')
            ->setLinkAttribute('class', 'nav-link py-0')
        ;

        $menu->addChild('Joint Purchases', ['route' => 'jp'])
            ->setAttribute('class', 'nav-item')
            ->setLinkAttribute('class', 'nav-link py-0')
        ;

        return $menu;
    }

    /**
     * Аккаут пользователя
     */
    public function account(array $options): ItemInterface
    {
        $menu = $this->factory->createItem('root', [
            'childrenAttributes'    => [
                'class' => 'nav _flex-column nav-pills',
            ],
        ]);

        $menu->addChild('My orders',        ['route' => 'account_order']);
        $menu->addChild('My products',      ['route' => 'account_product']);
        $menu->addChild('My payments',      ['route' => 'account_payment']);
        $menu->addChild('My cooperatives',  ['route' => 'account_coop']);
        $menu->addChild('Member book',      ['route' => 'account_balance']);
        $menu->addChild('My profile',       ['route' => 'account_profile']);

        return $menu;
    }

    /**
     * Профиль пользователя
     */
    public function profile(array $options): ItemInterface
    {
        $menu = $this->factory->createItem('root', [
            'childrenAttributes'    => [
                'class' => 'nav _flex-column nav-pills',
            ],
        ]);

        $menu->addChild('My profile',       ['route' => 'account_profile']);
        $menu->addChild('My addresses',     ['route' => 'account_address']);
        $menu->addChild('Profile of intentions', ['route' => 'account_worksheet']);
        $menu->addChild('Telegram',         ['route' => 'account_telegram']);
        $menu->addChild('Geoposition',      ['route' => 'account_geoposition']);
        $menu->addChild('Change password',  ['route' => 'account_password']);

        return $menu;
    }
}
