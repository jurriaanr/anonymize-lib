<?php
/**
 * User: Jurriaan Ruitenberg
 * Date: 15-11-2019
 * Time: 14:30
 */

namespace Oberon\Anonymize\Resolver;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Internal\Hydration\IterableResult;

class DefaultEntityResolver implements EntityResolver
{
    /**
     * @throws \Exception
     */
    public function resolve(string $class, ObjectManager $manager): IterableResult
    {
        if ($manager instanceof EntityManagerInterface) {
            $query = $manager->createQuery('select e from ' . $class . ' e');
            return $query->iterate();
        } else {
            throw new \Exception('Only the EntityMangerInterface is supported in the default entity resolver');
        }
    }
}
