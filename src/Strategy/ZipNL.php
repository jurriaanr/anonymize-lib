<?php
/**
 * User: Jurriaan Ruitenberg
 * Date: 20-11-2019
 * Time: 13:40
 */

namespace Oberon\Anonymize\Strategy;

use Oberon\Anonymize\Annotations\AnonymizeProperty;

final class ZipNL extends AbstractStrategy
{
    /** @return mixed */
    public function handle($entity, AnonymizeProperty $annotation, \ReflectionProperty $property)
    {
        return self::zipNl(
            $property->getValue($entity),
            $annotation->strength,
            $annotation->asValid
        );
    }

    public static function zipNl(string $value, int $strength = 0, bool $asValid = false): string
    {
        $code = (string)round(intval($value), -1 * $strength);
        if ($asValid) {
            $code .= 'AA';
        }
        return $code;
    }
}
