<?php
/**
 * User: Jurriaan Ruitenberg
 * Date: 15-11-2019
 * Time: 14:30
 */

namespace Oberon\Anonymize\Resolver;

use DateInterval;
use DateTime;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Internal\Hydration\IterableResult;
use Doctrine\ORM\Query;
use Oberon\Anonymize\Annotations\Anonymize;

class DefaultEntityResolver implements EntityResolver
{
    /**
     * @throws \Exception
     */
    public function resolve(string $class, Anonymize $classAnnotation, ObjectManager $manager): IterableResult
    {
        if ($manager instanceof EntityManagerInterface) {
            $query = $this->getQuery($class, $classAnnotation, $manager);
            return $query->iterate();
        } else {
            throw new \Exception('Only the EntityMangerInterface is supported in the default entity resolver');
        }
    }

    protected function getQuery(string $class, Anonymize $classAnnotation, ObjectManager $manager): Query
    {
        $dql = 'SELECT entity FROM ' . $class . ' entity';
        $params = [];

        if ($classAnnotation->getMode() !== Anonymize::FORCE) {
            switch ($classAnnotation->getMode()) {
                case Anonymize::FIRST_TIME:
                    // if there is a property anonymizedAt, f.e. by using the Anonymizable trait, we can optimize the query
                    if (property_exists( $class, 'anonymizedAt')) {
                        // find the column name mapping for the property
                        $dql .= ' WHERE entity.anonymizedAt IS NULL';
                    }
                    break;
                case Anonymize::AFTER_DATE:
                    // if there is a property anonymizedAt, f.e. by using the Anonymizable trait, we can optimize the query
                    if (property_exists( $class, 'anonymizedAt')) {
                        try {
                            $interval = new DateInterval($classAnnotation->getDateInterval());
                            $checkDate = (new DateTime())->sub($interval);

                            // find the column name mapping for the property
                            $dql .= ' WHERE entity.anonymizedAt IS NULL AND entity.' . $classAnnotation->getDateField() . ' <= :date';
                            $params['date'] = $checkDate;
                        } catch (\Exception $e) {}
                    }
                    break;
            }
        }

        return $manager->createQuery($dql)->setParameters($params);
    }
}
