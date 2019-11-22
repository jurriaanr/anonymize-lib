<?php

namespace Oberon\Anonymize\Strategy;

use Oberon\Anonymize\Model\AnonymizePropertyInterface;

final class Regex extends AbstractStrategy
{
    public function handle($entity, AnonymizePropertyInterface $anonymizeProperty, \ReflectionProperty $property)
    {
        return self::regex(
            $property->getValue($entity),
            $anonymizeProperty->regex,
            $anonymizeProperty->replace,
            $anonymizeProperty->options,
            $anonymizeProperty->delimiter
        );
    }

    public static function regex($value, string $regex, string $replace = '', string $options = '', string $delimiter = '/'): string
    {
        return preg_replace($delimiter . $regex . $delimiter . $options, $replace, $value);
    }
}
