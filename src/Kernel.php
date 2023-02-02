<?php

namespace App;

use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\HttpKernel\Kernel as BaseKernel;

class Kernel extends BaseKernel
{
    use MicroKernelTrait;

    // configuracion del kernel para setear la zona horaria del proyecto
    public function boot(): void
    {
        parent::boot();
        date_default_timezone_set($this->getContainer()->getParameter('timezone'));
    }
}
