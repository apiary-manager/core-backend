<?php

namespace App\Services\Shell;

class ShellCommandParameter implements ShellSettingInterface
{
    /** @var string */
    private $string;

    /** @var bool */
    private $quotes = false;

    /**
     * ShellCommandParameter constructor.
     * @param string $string
     * @param bool $quotes
     */
    public function __construct(string $string, bool $quotes = false)
    {
        $this->string = $string;
        $this->quotes = $quotes;
    }

    /**
     * @return string
     */
    public function getString(): string
    {
        return $this->quotes ? ('"' . $this->string . '"') : $this->string;
    }
}