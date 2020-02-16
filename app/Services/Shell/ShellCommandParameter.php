<?php

namespace App\Services\Shell;

class ShellCommandParameter implements ShellSettingInterface
{
    /** @var string */
    private $string;

    /**
     * ShellCommandParameter constructor.
     * @param string $string
     */
    public function __construct(string $string)
    {
        $this->string = $string;
    }

    /**
     * @return string
     */
    public function getString(): string
    {
        return $this->string;
    }
}
