<?php

namespace Oberon\Anonymize\Annotations;

use Oberon\Anonymize\Model\AbstractAnonymizeProperty;
use Oberon\Anonymize\Strategy\Strategy;

/**
 * @Annotation
 * @Target({"PROPERTY"})
 * @Attributes({
 *   @Attribute("char", type = "string"),
 *   @Attribute("maskCount", type = "int"),
 *   @Attribute("replaceCount", type = "int"),
 * })
 */
final class Mask extends AbstractAnonymizeProperty
{
    public $char = '*';
    public $maskCount = 4;
    public $replaceCount = 4;

    public function getStrategy(): string
    {
        return Strategy::MASK;
    }
}
