<?php

namespace Oberon\Anonymize\Annotations;

use Oberon\Anonymize\Strategy\Strategy;

/**
 *  A degree of longitude (east-west) is about the same or less in length than a degree of latitude, because the circles
 *  of latitude shrink down to the earth's axis as we move from the equator towards either pole.
 *  Therefore, it's always safe to figure that the sixth decimal place in one decimal degree has
 *  111,111/10^6 = about 1/9 meter = about 11.1111 cm
 *
 * @Annotation
 * @Target({"PROPERTY"})
 * @Attributes({
 *   @Attribute("char", type = "string"),
 *   @Attribute("maskCount", type = "int"),
 *   @Attribute("replaceCount", type = "int"),
 * })
 */
class Longitude extends AnonymizeProperty
{
    public $char = '*';
    public $maskCount = 4;
    public $replaceCount = 4;

    public function getStrategy(): string
    {
        return Strategy::MASK;
    }
}