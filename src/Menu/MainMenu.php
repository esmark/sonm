<?php

declare(strict_types=1);

namespace App\Menu;

use Knp\Menu\FactoryInterface;

class MainMenu
{
    private $factory;

    /**
     * @param FactoryInterface $factory
     *
     * Add any other dependency you need
     */
    public function __construct(FactoryInterface $factory)
    {
        $this->factory = $factory;
    }

    /**
     * Главное меню
     *
     * @param array $options
     *
     * @return \Knp\Menu\ItemInterface
     */
    public function top(array $options)
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

        return $menu;
    }

    /**
     * Профиль пользователя
     *
     * @param array $options
     *
     * @return \Knp\Menu\ItemInterface
     */
    public function account(array $options)
    {
        $menu = $this->factory->createItem('root', [
            'childrenAttributes'    => [
                'class' => 'nav _flex-column nav-pills',
            ],
        ]);

        $menu->addChild('My orders',        ['route' => 'account_order']);
        $menu->addChild('My payments',        ['route' => 'account_payment']);
        $menu->addChild('My balance',       ['route' => 'account_balance']);
        $menu->addChild('My cooperatives',  ['route' => 'account_coop']);
        $menu->addChild('My addresses',     ['route' => 'account_address']);
        $menu->addChild('Profile of intentions', ['route' => 'account_worksheet']);
        $menu->addChild('Profile',          ['route' => 'account_profile']);
        $menu->addChild('Telegram',         ['route' => 'account_telegram']);
        $menu->addChild('Geoposition',      ['route' => 'account_geoposition']);
        $menu->addChild('Change password',  ['route' => 'account_password']);

        return $menu;
    }
}
