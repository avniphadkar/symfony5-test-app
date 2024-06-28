<?php

namespace TestApp\Event\Listener;

use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
use TestApp\Entity\User;

/**
 * Stores the locale of the user in the session after the login.
 * If no locale is defined (if the user doesn't change it from the login screen), override it with the user's config one.
 *
 */
class UserLocaleListener
{
    private SessionInterface $session;

    public function __construct(SessionInterface $session)
    {
        $this->session = $session;
    }

    public function onInteractiveLogin(InteractiveLoginEvent $event)
    {
        $user = $event->getAuthenticationToken()->getUser();
        \assert($user instanceof User);

        if (null !== $user->getConfig()->getLanguage() && null === $this->session->get('_locale')) {
            $this->session->set('_locale', $user->getConfig()->getLanguage());
        }
    }
}
