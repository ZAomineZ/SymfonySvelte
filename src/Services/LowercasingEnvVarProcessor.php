<?php

namespace App\Services;

use Closure;
use Symfony\Component\DependencyInjection\EnvVarProcessorInterface;

class LowercasingEnvVarProcessor implements EnvVarProcessorInterface
{

    /**
     * @param string $prefix
     * @param string $name
     * @param Closure $getEnv
     * @return mixed|void
     */
    public function getEnv(string $prefix, string $name, Closure $getEnv)
    {
        $env = $getEnv($name);

        return strtolower($env);
    }

    /**
     * @return string[]
     */
    public static function getProvidedTypes(): array
    {
        return [
            'lowercase' => 'string'
        ];
    }
}