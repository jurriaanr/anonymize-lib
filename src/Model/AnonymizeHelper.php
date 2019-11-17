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
     * Get all registered Entity classes from the manager
     *
     * @param ObjectManager $manager
     * @return string[]
     */
    public function getEntityClasses(ObjectManager $manager): array
    {
        return array_map(function (ClassMetadata $meta) {
            return $meta->getName();
        }, $manager->getMetadataFactory()->getAllMetadata());
    }

    /**
     * Go over all properties of the given glass to see if there are
     * Anonymize annotations on it.
     *
     * @param string $class
     * @return AnnotatedProperty[]
     * @throws AnnotationException
     */
    public function getAnnotatedPropertiesForClass(string $class): array
    {
        try {
            // create reflection class to get properties
            $reflClass = new ReflectionClass($class);
            // get all properties
            $properties = $reflClass->getProperties(\ReflectionProperty::IS_PRIVATE | \ReflectionProperty::IS_PROTECTED | \ReflectionProperty::IS_PUBLIC);

            // loop over properties to see if there are any anonymize annotations
            return array_reduce($properties, function (array $acc, \ReflectionProperty $prop) {
                /** @var AnonymizeProperty|null $annotation */
                $annotation = $this->reader->getPropertyAnnotation($prop, AnonymizeProperty::class);
                if ($annotation) {
                    $acc[] = new AnnotatedProperty($prop, $annotation);
                }
                return $acc;
            }, []);
        } catch (\ReflectionException $e) {}
        return [];
    }

    public function getClassAnnotation(string $class): ?Anonymize
    {
        try {
            // create reflection class to get properties
            $reflClass = new ReflectionClass($class);
            return $this->reader->getClassAnnotation($reflClass, Anonymize::class);
        } catch (\ReflectionException $e) {}

        return null;
    }
}
