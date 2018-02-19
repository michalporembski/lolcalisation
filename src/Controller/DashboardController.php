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
        return $this->render('dashboard.html.twig');
    }

    /**
     * @param int $number
     *
     * @return int
     */
    private function getPolishPluralForm(int $number): int
    {
        if ($number === 0 || $number === 1) {
            return $number;
        } elseif ($number % 10 >= 2 && $number % 10 <= 4 && ($number % 100 < 10 || $number % 100 > 20)) {
            return 2;
        }

        return 3;
    }
}
