<?php 
// src/Twig/CustomTwigExtension.php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class CustomTwigExtension extends AbstractExtension
{
    public function getFunctions(): array
    {
        return [
            new TwigFunction('getCampo', [$this, 'getCampo']),
        ];
    }

    public function getCampo(string $serializado, string $campo): string
    {
        $pos = strpos($serializado , $campo);
        $c = substr($serializado,$pos, strlen($serializado));
        $e = explode('"',$c);
        return $e[2];
    }
    
    public function getName():string
    {
        return 'CustomTwigExtension_Campo';
    }
}

?>