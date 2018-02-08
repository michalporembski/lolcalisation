<?php

namespace App\EventSubscriber;

use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * Class LocaleSubscriber
 *
 * @package App\EventSubscriber
 */
class LocaleSubscriber implements EventSubscriberInterface
{
    /**
     * @var Router
     */
    private $router;

    /**
     * @var string
     */
    private $defaultLocale;

    /**
     * LocaleSubscriber constructor.
     *
     * @param Router $router
     * @param string $defaultLocale
     */
    public function __construct(Router $router, string $defaultLocale)
    {
        $this->router = $router;
        $this->defaultLocale = $defaultLocale;
    }

    /**
     * @param GetResponseEvent $event
     */
    public function onKernelRequest(GetResponseEvent $event)
    {
        $request = $event->getRequest();
        if (!$request->hasPreviousSession()) {
            return;
        }
        $locale = $request->getSession()->get('_locale');
        $request->setLocale($locale);
        $request->getSession()->set('_locale', $locale);
    }

    /**
     * @return array
     */
    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::REQUEST => ['onKernelRequest', 15],
        ];
    }
}
