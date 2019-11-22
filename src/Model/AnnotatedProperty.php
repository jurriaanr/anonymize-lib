<?php
/**
 * User: Jurriaan Ruitenberg
 * Date: 15-11-2019
 * Time: 13:38
 */

namespace Oberon\Anonymize\Model;

use ReflectionProperty;

class AnnotatedProperty
{
    /** @var ReflectionProperty */
    private $property;

    /** @var AnonymizePropertyInterface */
    private $anonymizeProperty;

    public function __construct(ReflectionProperty $property, AnonymizePropertyInterface $anonymizeProperty)
    {
        $this->property = $property;
        $this->anonymizeProperty = $anonymizeProperty;
    }

    public function getPropertyInfo(): AnonymizePropertyInterface
    {
        return $this->anonymizeProperty;
    }

    public function setAnonymizeProperty(AnonymizePropertyInterface $anonymizeProperty): void
    {
        $this->anonymizeProperty = $anonymizeProperty;
    }

    public function getProperty(): ReflectionProperty
    {
        return $this->property;
    }

    public function setProperty(ReflectionProperty $property): void
    {
        $this->property = $property;
    }
}
