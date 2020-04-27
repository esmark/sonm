<?php

declare(strict_types=1);

namespace App\Twig;

use Doctrine\ORM\EntityManagerInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class AppExtension extends AbstractExtension
{
    protected $tgBotName;
    protected $em;

    public function __construct(EntityManagerInterface $em, string $tgBotName)
    {
        $this->em          = $em;
        $this->tgBotName   = $tgBotName;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return 'twig.app';
    }

    /**
     * Returns a list of functions to add to the existing list.
     */
    public function getFunctions(): array
    {
        return [
            new TwigFunction('app_tg_bot_name',    [$this, 'getTgBotName']),
        ];
    }

    public function getTgBotName(): ?string
    {
        return $this->tgBotName;
    }
}
