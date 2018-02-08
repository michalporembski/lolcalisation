<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class DashboardController extends Controller
{
    /**
     * @Route("/", name="dashboard")
     */
    public function number()
    {
        dump($this->get('translator')->trans('Hello World'));
        $number = mt_rand(0, 100);

        return $this->render('dashboard.html.twig', [
            'number' => $number,
        ]);
    }
}
