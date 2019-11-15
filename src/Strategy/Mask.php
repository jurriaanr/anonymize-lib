<?php

namespace Oberon\Anonymize\Strategy;

use Oberon\Anonymize\Annotations\AnonymizeProperty;

class Mask extends AbstractStrategy
{
    public function handle($entity, AnonymizeProperty $annotation, \ReflectionProperty $property): void
    {
        $property->setValue($entity, preg_replace("/.{4}$/", "****", $property->getValue($entity)));
    }
}
