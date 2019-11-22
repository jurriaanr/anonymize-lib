<?php
/**
 * User: Jurriaan Ruitenberg
 * Date: 18-11-2019
 * Time: 17:00
 */

namespace Oberon\Anonymize\Strategy;

use Oberon\Anonymize\Model\AnonymizePropertyInterface;

final class Hash extends AbstractStrategy
{
    public function handle($entity, AnonymizePropertyInterface $anonymizeProperty, \ReflectionProperty $property)
    {
        return self::hash(
            $property->getValue($entity),
            $anonymizeProperty->algorithm,
            $anonymizeProperty->salt
        );
    }

    public static function hash($value, $algorithm = 'md5', string $salt = ''): string
    {
        return hash($algorithm, $salt.$value);
    }
}
