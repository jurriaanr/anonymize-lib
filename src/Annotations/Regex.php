<?php

namespace Oberon\Anonymize\Annotations;

use Doctrine\Common\Annotations\Annotation\Required;
use Oberon\Anonymize\Model\AbstractAnonymizeProperty;
use Oberon\Anonymize\Strategy\Strategy;

/**
 * @Annotation
 * @Target({"PROPERTY"})
 * @Attributes({
 *   @Attribute("regex", type = "string"),
 *   @Attribute("replace", type = "string"),
 *   @Attribute("options", type = "string"),
 *   @Attribute("delimiter", type = "string"),
 * })
 */
final class Regex extends AbstractAnonymizeProperty
{
    /** @Required() */
    public $regex;
    /** @Required() */
    public $replace;
    public $options = '';
    public $delimiter = '/';

    public function getStrategy(): string
    {
        return Strategy::REGEX;
    }
}
