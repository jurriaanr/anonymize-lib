<?php
/**
 * User: Jurriaan Ruitenberg
 * Date: 15-11-2019
 * Time: 13:38
 */

namespace Oberon\Anonymize\Model;

use Oberon\Anonymize\Annotations\AnonymizeProperty;
use ReflectionProperty;

class AnnotatedProperty
{
    /** @var ReflectionProperty */
    private $property;

    /** @var AnonymizeProperty */
    private $annotation;

    public function __construct(ReflectionProperty $property, AnonymizeProperty $annotation)
    {
        $this->property = $property;
        $this->annotation = $annotation;
    }

    public function getAnnotation(): AnonymizeProperty
    {
        return $this->annotation;
    }

    public function setAnnotation(AnonymizeProperty $annotation): void
    {
        $this->annotation = $annotation;
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
