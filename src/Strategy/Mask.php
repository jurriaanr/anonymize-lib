<?php

namespace Oberon\Anonymize\Strategy;

use Oberon\Anonymize\Model\AnonymizePropertyInterface;

final class Mask extends AbstractStrategy
{
    public function handle($entity, AnonymizePropertyInterface $anonymizeProperty, \ReflectionProperty $property)
    {
        return self::mask(
            $property->getValue($entity),
            $anonymizeProperty->maskCount,
            $anonymizeProperty->replaceCount,
            $anonymizeProperty->char
        );
    }

    public static function mask($value, $maskCount = 4, $replaceCount = 4, $maskChar = '*'): string
    {
        return preg_replace("/.{{$maskCount}}$/", str_repeat($maskChar, $replaceCount), $value);
    }
}
