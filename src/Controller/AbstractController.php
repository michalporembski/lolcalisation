<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Class AbstractController
 *
 * @package App\Controller
 */
class AbstractController extends Controller
{
    /**
     * @param $message
     * @param array $parameters
     * @param null $domain
     *
     * @return string
     */
    protected function translateToDefaultLocale($message, array $parameters = [], $domain = null)
    {
        return $this->get('translator')->trans(
            $message,
            $parameters,
            $domain,
            $this->getParameter('kernel.default_locale')
        );
    }
}
