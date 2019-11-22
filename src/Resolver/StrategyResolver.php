<?php
/**
 * User: Jurriaan Ruitenberg
 * Date: 15-11-2019
 * Time: 14:02
 */

namespace Oberon\Anonymize\Resolver;

use Oberon\Anonymize\Model\AnonymizeInterface;
use Oberon\Anonymize\Model\AnonymizePropertyInterface;
use Oberon\Anonymize\Strategy\Strategy;

interface StrategyResolver
{
    public function resolve(AnonymizePropertyInterface $anonymizeProperty, AnonymizeInterface $anonymizeInfo): ?Strategy;
}
