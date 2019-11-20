<?php
/**
 * User: Jurriaan Ruitenberg
 * Date: 20-11-2019
 * Time: 09:40
 */

namespace Oberon\Anonymize\Annotations;

use Oberon\Anonymize\Strategy\Strategy;

/**
 * @Annotation
 * @Target({"PROPERTY"})
 * @Attributes({
 * })
 */
final class Ip extends AnonymizeProperty
{
    public function getStrategy(): string
    {
        return Strategy::IP;
    }
}
