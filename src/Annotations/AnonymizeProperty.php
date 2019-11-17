<?php
/**
 * User: Jurriaan Ruitenberg
 * Date: 15-11-2019
 * Time: 18:57
 */

namespace Oberon\Anonymize\Annotations;

abstract class AnonymizeProperty
{
    public function __construct(array $values)
    {
        foreach ($values as $key => $value) {
            $this->$key = $value;
        }
    }

    abstract public function getStrategy(): string;

    /**
     * Error handler for unknown property
     * @throws \BadMethodCallException
     */
    public function __get(string $name): string
    {
        throw new \BadMethodCallException(
            sprintf("Unknown property '%s' on annotation '%s'.", $name, get_class($this))
        );
    }

    /**
     * Error handler for unknown property
     * @throws \BadMethodCallException
     */
    public function __set(string $name, $value)
    {
        throw new \BadMethodCallException(
            sprintf("Unknown property '%s' on annotation '%s'.", $name, get_class($this))
        );
    }
}
