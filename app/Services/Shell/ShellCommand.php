<?php

namespace App\Services\Shell;

/**
 * Class ShellCommand
 */
class ShellCommand
{
    /** @var string */
    private $executor;

    /** @var bool */
    private $isExecuted = false;

    /** @var string */
    private $shellResult = null;

    /** @var ShellSettingInterface[] */
    private $settings = null;

    /**
     * @var bool
     */
    private $isCheckRealpathExecutor;

    public function __construct(string $executor, bool $isCheckRealpathExecutor = true)
    {
        $this->executor = $executor;
        $this->isCheckRealpathExecutor = $isCheckRealpathExecutor;
    }

    /**
     * @param string $executor
     * @param bool $isCheckRealpathExecutor
     * @return static
     */
    public static function getInstance(string $executor, bool $isCheckRealpathExecutor = true): self
    {
        return new self($executor, $isCheckRealpathExecutor);
    }

    /**
     * Выполнить программу
     *
     * @return self
     */
    public function execute(): self
    {
        $this->isExecuted = true;

        $this->shellResult = shell_exec($this->prepareShellCommand());

        return $this;
    }

    /**
     * Добавить параметр в командную строку
     *
     * @param $value
     * @return $this
     */
    public function addParameter($value): self
    {
        $this->settings[] = new ShellCommandParameter($value);

        return $this;
    }

    /**
     * Добавить опцию в командную строку
     *
     * @param $value
     * @return $this
     */
    public function addOption($value): self
    {
        $this->settings[] = new ShellCommandOption($value);

        return $this;
    }

    /**
     * Получить результат выполнения программы
     *
     * @return string|null
     */
    public function getShellResult()
    {
        if ($this->shellResult === null && $this->isExecuted === false) {
            $this->execute();
        }

        return $this->shellResult;
    }

    /**
     * Подготовить шелл-команду
     *
     * @return string
     */
    private function prepareShellCommand(): string
    {
        $settings = array_map(function (ShellSettingInterface $setting) {
            return $setting->getString();
        }, $this->settings);

        return implode(' ', array_merge(
            [$this->isCheckRealpathExecutor ? realpath($this->executor) : $this->executor],
            $settings
        ));
    }

    /**
     * @return bool
     */
    public function isExecuted(): bool
    {
        return $this->isExecuted;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->prepareShellCommand();
    }
}