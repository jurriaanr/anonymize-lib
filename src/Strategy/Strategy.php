<?php

namespace Oberon\Anonymize\Strategy;

use Oberon\Anonymize\Annotations\Anonymize;
use Oberon\Anonymize\Annotations\AnonymizeProperty;

interface Strategy
{
    const MASK = Mask::class;

    public function setClassAnnotation(Anonymize $classAnnotation): void;
    public function anonymize($entity, AnonymizeProperty $annotation, \ReflectionProperty $property): void;
}
