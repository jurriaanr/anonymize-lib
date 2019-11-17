<?php

namespace Oberon\Anonymize\Annotations;

use Oberon\Anonymize\Strategy\Strategy;

/**
 *  The meter was originally defined so that ten million of them would take you from the equator to a pole.
 *  That's 90 degrees, so one degree of latitude covers about 10^7/90 = 111,111 meters.
 *
 * @Annotation
 * @Target({"PROPERTY"})
 * @Attributes({
 *   @Attribute("char", type = "string"),
 *   @Attribute("maskCount", type = "int"),
 *   @Attribute("replaceCount", type = "int"),
 * })
 */
class Lattitude extends AnonymizeProperty
{
    public $char = '*';
    public $maskCount = 4;
    public $replaceCount = 4;

    public function getStrategy(): string
    {
        return Strategy::MASK;
    }
}