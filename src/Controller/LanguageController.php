<?php

namespace App\Controller;

use App\Services\Locale;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class LanguageController.
 *
 * @Route("/language")
 */
class LanguageController extends Controller
{
    /**
     * time until cookies will expire
     */
    const LOCALE_EXPIRATION_TIME = 604800;

    /**
     * Action for changing locale
     *
     * @Route("/change/{locale}", name="language_change")
     * @Method({"GET"})
     *
     * @param Request $request
     * @param string $locale
     *
     * @return RedirectResponse
     */
    public function changeLanguageAction(Request $request, string $locale)
    {
        $response = $this->redirectToRoute('dashboard');
        if (in_array($locale, array_keys(Locale::AVAILABLE_LOCALES))) {
            $response->headers->setCookie($this->createCookie($locale));
        } else {
            $this->addFlash(
                'error',
                $this->get('translator')->trans('localisation.errors.incorrect-locale', ['%locale%' => $locale])
            );
        }

        $request->setLocale($locale);
        $request->getSession()->set('_locale', $locale);

        return $response;
    }

    /**
     * @param $locale
     *
     * @return Cookie
     */
    private function createCookie($locale): Cookie
    {
        return new Cookie(
            '_locale',
            $locale,
            time() + self::LOCALE_EXPIRATION_TIME,
            '/',
            null,
            false,
            false
        );
    }
}
