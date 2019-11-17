<?php

namespace Oberon\Anonymize\Annotations;

use Doctrine\Common\Annotations\Annotation\Required;
use Oberon\Anonymize\Strategy\Strategy;

/**
 * @Annotation
 * @Target({"PROPERTY"})
 * @Attributes({
 *   @Attribute("strategy", type = "string"),
 * })
 */
final class Custom extends AnonymizeProperty
{
    /** @Required() */
    public $strategy;
    private $data = [];

    public function getStrategy(): string
    {
        return $this->strategy;
    }

    /**
     * Error handler for unknown property
     * @throws \BadMethodCallException
     */
    public function __get(string $name): string
    {
        if (isset($this->data[$name])) {
            return $this->data[$name];
        }

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
        $this->data[$name] = $value;
    }
}
