<?php
/**
 * User: Jurriaan Ruitenberg
 * Date: 20-11-2019
 * Time: 09:40
 */

namespace Oberon\Anonymize\Strategy;

use Oberon\Anonymize\Annotations\AnonymizeProperty;
use Symfony\Component\HttpFoundation\IpUtils;

final class Ip extends AbstractStrategy
{

    /** @return mixed */
    public function handle($entity, AnonymizeProperty $annotation, \ReflectionProperty $property)
    {
        return self::ip(
            $property->getValue($entity)
        );
    }

    public static function ip($ip)
    {
        $wrappedIPv6 = false;
        if ('[' === substr($ip, 0, 1) && ']' === substr($ip, -1, 1)) {
            $wrappedIPv6 = true;
            $ip = substr($ip, 1, -1);
        }
        $packedAddress = inet_pton($ip);
        if (4 === \strlen($packedAddress)) {
            $mask = '255.255.255.0';
        } elseif ($ip === inet_ntop($packedAddress & inet_pton('::ffff:ffff:ffff'))) {
            $mask = '::ffff:ffff:ff00';
        } elseif ($ip === inet_ntop($packedAddress & inet_pton('::ffff:ffff'))) {
            $mask = '::ffff:ff00';
        } else {
            $mask = 'ffff:ffff:ffff:ffff:0000:0000:0000:0000';
        }
        $ip = inet_ntop($packedAddress & inet_pton($mask));
        if ($wrappedIPv6) {
            $ip = '['.$ip.']';
        }
        return $ip;
    }
}
