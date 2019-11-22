<?php
/**
 * User: Jurriaan Ruitenberg
 * Date: 22-11-2019
 * Time: 16:50
 */

namespace Oberon\Anonymize\Annotations;

use Oberon\Anonymize\Model\AbstractAnonymizeProperty;
use Oberon\Anonymize\Strategy\Strategy;

/**
 * @Annotation
 * @Target({"PROPERTY"})
 * @Attributes({
 *   @Attribute("char", type = "string"),
 * })
 */
class Email extends AbstractAnonymizeProperty
{
    /** @var string */
    public $char = '_';

    public function getStrategy(): string
    {
        return Strategy::EMAIL;
    }
}
