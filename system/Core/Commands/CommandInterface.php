<?php
namespace System\Core\Commands;

interface CommandInterface
{
    /**
     * Handle the command
     * @param array $args
     */
    public function handle(array $args): void;
}
