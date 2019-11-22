<?php
/**
 * User: Jurriaan Ruitenberg
 * Date: 22-11-2019
 * Time: 17:41
 */

namespace Oberon\Anonymize\Strategy;

use Oberon\Anonymize\Model\AnonymizePropertyInterface;

final class Email extends AbstractStrategy
{
    public function handle($entity, AnonymizePropertyInterface $anonymizeProperty, \ReflectionProperty $property)
    {
        return self::email(
            $property->getValue($entity),
            $anonymizeProperty->char
        );
    }

    public static function email($value, $maskChar = '_'): string
    {
        $emailParts = explode("@", $value);
        $domain = array_pop($emailParts);

        $emailParts = array_map(function ($part) use ($maskChar) {
            return self::mix($part, $maskChar);
        }, $emailParts);

        $domainParts = explode(".", $domain);
        $domainName = self::mix(array_shift($domainParts), $maskChar);

        return join('@', $emailParts) . '@' . $domainName . '.' . join('.', $domainParts);
    }

    private static function mix($word, $maskChar = '_'):string
    {
        $letters = str_split($word);
        return array_reduce(array_keys($letters), function ($acc, $index) use ($letters, $maskChar) {
            return $acc .= $index % 2 === 0 ? $maskChar : $letters[$index];
        }, '');
    }
}
