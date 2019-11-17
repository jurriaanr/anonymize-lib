<?php

namespace Oberon\Anonymize\Strategy;

use Oberon\Anonymize\Annotations\AnonymizeProperty;

class Regex extends AbstractStrategy
{
    public function handle($entity, AnonymizeProperty $annotation, \ReflectionProperty $property)
    {
        return self::regex(
            $property->getValue($entity),
            $annotation->regex,
            $annotation->replace,
            $annotation->options,
            $annotation->delimiter
        );
    }

    public static function regex($value, string $regex, string $replace = '', string $options = '', string $delimiter = '/'): string
    {
        return preg_replace($delimiter . $regex . $delimiter . $options, $replace, $value);
    }
}
