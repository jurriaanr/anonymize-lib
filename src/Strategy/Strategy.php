<?php

namespace Oberon\Anonymize\Strategy;

use Oberon\Anonymize\Model\AnonymizeInterface;
use Oberon\Anonymize\Model\AnonymizePropertyInterface;

interface Strategy
{
    const EMAIL = Email::class;
    const HASH = Hash::class;
    const IP = Ip::class;
    const LAT_LNG = LatLng::class;
    const MASK = Mask::class;
    const REGEX = Regex::class;
    const ZIP_NL = ZipNL::class;

    public function setAnonymizeInfo(AnonymizeInterface $anonymizeInfo): void;
    public function anonymize($entity, AnonymizePropertyInterface $anonymizeProperty, \ReflectionProperty $property): void;
}
