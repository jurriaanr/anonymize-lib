<?php
/**
 * User: Jurriaan Ruitenberg
 * Date: 15-11-2019
 * Time: 14:01
 */

namespace Oberon\Anonymize\Resolver;

use Oberon\Anonymize\Annotations\Anonymize;
use Oberon\Anonymize\Annotations\AnonymizeProperty;
use Oberon\Anonymize\Strategy\Strategy;
use Psr\Container\ContainerInterface;

class DefaultStrategyResolver implements StrategyResolver
{
    /** @var ContainerInterface */
    private $container;
    /** @var string[] */
    private $cache;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
        $this->cache = [];
    }

    public function resolve(AnonymizeProperty $anonymizeProperty, Anonymize $classAnnotation): ?Strategy
    {
        if (isset($this->cache[$anonymizeProperty->getStrategy()])) {
            return $this->cache[$anonymizeProperty->getStrategy()];
        }

        /** @var Strategy|null $strategy */
        $strategy = null;
        $strategyClassName = $anonymizeProperty->getStrategy();
        if ($this->container->has($strategyClassName)) {
            $strategy = $this->container->get($strategyClassName);
        } else if (class_exists($strategyClassName)) {
            $strategy = new $strategyClassName();
        }

        if ($strategy) {
            $strategy->setClassAnnotation($classAnnotation);
        }

        $this->cache[$anonymizeProperty->getStrategy()] = $strategy;
        return $strategy;
    }
}
