<?php
/**
 * User: Jurriaan Ruitenberg
 * Date: 18-11-2019
 * Time: 16:59
 */

namespace Oberon\Anonymize\Annotations;

use Oberon\Anonymize\Strategy\Strategy;

/**
 * @Annotation
 * @Target({"PROPERTY"})
 * @Attributes({
 *   @Attribute("algorithm", type = "string"),
 *   @Attribute("salt", type = "string"),
 * })
 */
final class Hash extends AnonymizeProperty
{
    /**
     * Any of the algorithms listed by hash_algos()
     * @var string
     */
    public $algorithm = 'md5';
    /** @var string */
    public $salt = '';

    public function getStrategy(): string
    {
        return Strategy::HASH;
    }
}
