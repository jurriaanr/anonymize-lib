<?php
/**
 * User: Jurriaan Ruitenberg
 * Date: 15-11-2019
 * Time: 13:57
 */

namespace Oberon\Anonymize\Model;

use Doctrine\Common\Annotations\AnnotationException;
use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\Mapping\ClassMetadata;
use Oberon\Anonymize\Annotations\Anonymize;
use Oberon\Anonymize\Annotations\AnonymizeProperty;
use ReflectionClass;

class AnonymizeHelper
{
    /** @var AnnotationReader */
    private $reader;

    public function __construct()
    {
        $this->reader = new AnnotationReader();
    }

    /**
     * Go over all properties of the given glass to see if there are
     * Anonymize annotations on it.
     *
     * @param string $class
     * @return AnnotatedProperty[]
     * @throws AnnotationException
     */
    public function getAnnotatedPropertiesForClass(string $class, $group = Anonymize::DEFAULT_GROUP): array
    {
        try {
            // create reflection class to get properties
            $reflClass = new ReflectionClass($class);
            // get all properties
            $properties = $reflClass->getProperties(\ReflectionProperty::IS_PRIVATE | \ReflectionProperty::IS_PROTECTED | \ReflectionProperty::IS_PUBLIC);

            // loop over properties to see if there are any anonymize annotations
            return array_reduce($properties, function (array $acc, \ReflectionProperty $prop) use ($group) {
                /** @var AbstractAnonymizeProperty|null $annotation */
                $annotations = $this->reader->getPropertyAnnotations($prop);
                foreach ($annotations as $annotation) {
                    if ($annotation instanceof AnonymizePropertyInterface && $annotation->getGroup() === $group) {
                        $acc[] = new AnnotatedProperty($prop, $annotation);
                    }
                }
                return $acc;
            }, []);
        } catch (\ReflectionException $e) {}
        return [];
    }

    /**
     * @param string $class
     * @return Anonymize[]
     */
    public function getClassAnnotations(string $class): ?array
    {
        try {
            // create reflection class to get properties
            $reflClass = new ReflectionClass($class);
            $annotations = $this->reader->getClassAnnotations($reflClass);
            return array_filter($annotations, function ($annotation) {
                return $annotation instanceof Anonymize;
            });
        } catch (\ReflectionException $e) {}

        return null;
    }
}
