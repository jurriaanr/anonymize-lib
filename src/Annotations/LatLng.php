<?php

namespace Oberon\Anonymize\Annotations;

use Doctrine\Common\Annotations\Annotation\Enum;
use Oberon\Anonymize\Model\AbstractAnonymizeProperty;
use Oberon\Anonymize\Strategy\LatLng as StratLatLng;
use Oberon\Anonymize\Strategy\Strategy;

/**
 * https://xkcd.com/2170/
 *
 * @Annotation
 * @Target({"PROPERTY"})
 * @Attributes({
 *   @Attribute("accuracy", type = "string"),
 *   @Attribute("asString", type = "boolean"),
 * })
 */
final class LatLng extends AbstractAnonymizeProperty
{
    /**
     * @Enum({StratLatLng::STREET, StratLatLng::CITY, StratLatLng::COUNTRY, StratLatLng::GLOBALLY, StratLatLng::HEMISPHERE})
     */
    public $accuracy = StratLatLng::CITY;
    public $asString = false;

    public function getStrategy(): string
    {
        return Strategy::LAT_LNG;
    }
}
