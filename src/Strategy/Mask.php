<?php

namespace Oberon\Anonymize\Strategy;

use Oberon\Anonymize\Annotations\AnonymizeProperty;

final class Mask extends AbstractStrategy
{
    public function handle($entity, AnonymizeProperty $annotation, \ReflectionProperty $property)
    {
        return self::mask(
            $property->getValue($entity),
            $annotation->maskCount,
            $annotation->replaceCount,
            $annotation->char
        );
    }

    public static function mask($value, $maskCount = 4, $replaceCount = 4, $maskChar = '*'): string
    {
        return preg_replace("/.{{$maskCount}}$/", str_repeat($maskChar, $replaceCount), $value);
    }
}
