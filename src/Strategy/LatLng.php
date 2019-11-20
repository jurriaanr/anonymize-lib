<?php
/**
 * User: Jurriaan Ruitenberg
 * Date: 20-11-2019
 * Time: 09:41
 */

namespace Oberon\Anonymize\Strategy;

use Oberon\Anonymize\Annotations\AnonymizeProperty;

final class LatLng extends AbstractStrategy
{
    const STREET = 'street'; // 2 digit
    const CITY = 'city'; // 1 digit
    const COUNTRY = 'country'; // 0 digits
    const GLOBALLY = 'globally'; // -1 digits (24.0 becomes 20, 26.0 becomes 30)
    const HEMISPHERE = 'hemisphere'; // changes the value to -1 for Southern / Western hemisphere or +1 for Northern / Eastern

    /** @return mixed */
    public function handle($entity, AnonymizeProperty $annotation, \ReflectionProperty $property)
    {
        $val = self::latLng(
            $property->getValue($entity),
            $annotation->accuracy
        );

        return $annotation->asString ? (string) $val : $val;
    }

    public static function latLng($latLng, $accuracy): float
    {
        $latLng = floatval($latLng);

        switch ($accuracy) {
            case LatLng::STREET:
                return round($latLng, 2);
            case LatLng::CITY:
                return round($latLng, 1);
            case LatLng::COUNTRY:
                return round($latLng, 0);
            case LatLng::GLOBALLY:
                return round($latLng, -1);
            case LatLng::HEMISPHERE:
                return $latLng > 0 ? 1 : -1;
        }
    }
}
