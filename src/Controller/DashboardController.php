<?php

namespace App\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DashboardController extends Controller
{
    /**
     * @Route("/", name="dashboard")
     */
    public function number()
    {
        dump($this->get('translator')->trans('Hello World'));
        $number = mt_rand(0, 10);

        return $this->render('dashboard.html.twig', [
            'number' => $number,
            'prularForm' => $this->getPolishPrularForm($number),
        ]);
    }

    private function getPolishPrularForm(int $number)
    {
        if ($number === 0 || $number === 1) {
            return $number;
        } elseif ($number % 10 >= 2 && $number % 10 <= 4 && ($number % 100 < 10 || $number % 100 > 20)) {
            return 2;
        }

        return 3;
    }
}
