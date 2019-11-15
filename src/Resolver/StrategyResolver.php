<?php
/**
 * User: Jurriaan Ruitenberg
 * Date: 15-11-2019
 * Time: 14:02
 */

namespace Oberon\Anonymize\Resolver;

use Oberon\Anonymize\Annotations\Anonymize;
use Oberon\Anonymize\Annotations\AnonymizeProperty;
use Oberon\Anonymize\Strategy\Strategy;

interface StrategyResolver
{
    public function resolve(AnonymizeProperty $anonymizeProperty, Anonymize $classAnnotation): ?Strategy;
}