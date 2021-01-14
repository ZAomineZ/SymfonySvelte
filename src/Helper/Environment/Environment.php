<?php


namespace App\Helper\Environment;


class Environment
{
    /**
     * @return string
     */
    public function getEnv(): string
    {
        return $_SERVER['APP_ENV'];
    }
}