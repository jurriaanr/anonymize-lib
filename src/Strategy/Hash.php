<?php
/**
 * User: Jurriaan Ruitenberg
 * Date: 18-11-2019
 * Time: 17:00
 */

namespace Oberon\Anonymize\Strategy;

use Oberon\Anonymize\Annotations\AnonymizeProperty;

class Hash extends AbstractStrategy
{
    public function handle($entity, AnonymizeProperty $annotation, \ReflectionProperty $property)
    {
        return self::hash(
            $property->getValue($entity),
            $annotation->algorithm
        );
    }

    public static function hash($value, $algorithm = 'md5'): string
    {
        return hash($algorithm, $value);
    }
}
