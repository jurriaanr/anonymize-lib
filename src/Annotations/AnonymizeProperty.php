<?php
/**
 * User: Jurriaan Ruitenberg
 * Date: 15-11-2019
 * Time: 18:57
 */

namespace Oberon\Anonymize\Annotations;

use Doctrine\Common\Annotations\Annotation\Enum;
use Doctrine\Common\Annotations\Annotation\Required;
use Doctrine\Common\Annotations\Annotation\Target;

/**
 * @Annotation
 * @Target({"PROPERTY"})
 * @Attributes({
 *   @Attribute("strategy", type = "string"),
 * })
 */
class AnonymizeProperty
{
    /**
     * @Required()
     * @Enum({StrategyInterface::MASK, StrategyInterface::CUSTOM})
     */
    private $strategy;

    public function __construct(array $values)
    {
        $this->strategy = $values['strategy'];
    }

    public function getStrategy(): string
    {
        return $this->strategy;
    }
}
