<?php
/**
 * User: Jurriaan Ruitenberg
 * Date: 15-11-2019
 * Time: 14:29
 */

namespace Oberon\Anonymize\Resolver;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\Internal\Hydration\IterableResult;
use Oberon\Anonymize\Annotations\Anonymize;

interface EntityResolver
{
    public function resolve(string $class, Anonymize $classAnnotation, ObjectManager $manager): IterableResult;
}
