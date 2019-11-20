<?php

namespace Oberon\Anonymize\Strategy;

use Oberon\Anonymize\Annotations\Anonymize;
use Oberon\Anonymize\Annotations\AnonymizeProperty;

interface Strategy
{
    const MASK = Mask::class;
    const REGEX = Regex::class;
    const HASH = Hash::class;
    const IP = Ip::class;
    const LAT_LNG = LatLng::class;
    const ZIP_NL = ZipNL::class;

    public function setClassAnnotation(Anonymize $classAnnotation): void;
    public function anonymize($entity, AnonymizeProperty $annotation, \ReflectionProperty $property): void;
}
