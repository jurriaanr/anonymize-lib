<?php
/**
 * User: Jurriaan Ruitenberg
 * Date: 22-11-2019
 * Time: 16:45
 */

namespace Oberon\Anonymize\Service;

use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\Mapping\ClassMetadata;
use Oberon\Anonymize\Model\AnnotatedProperty;
use Oberon\Anonymize\Model\AnonymizableInterface;
use Oberon\Anonymize\Model\AnonymizeInterface;
use Oberon\Anonymize\Resolver\EntityResolver;
use Oberon\Anonymize\Resolver\StrategyResolver;
use Oberon\Anonymize\Strategy\Strategy;

abstract class AbstractAnonymizer implements Anonymizer
{
    /** @var StrategyResolver */
    private $strategyResolver;
    /** @var EntityResolver */
    private $entityResolver;

    public function __construct(StrategyResolver $strategyResolver, EntityResolver $entityResolver)
    {
        $this->strategyResolver = $strategyResolver;
        $this->entityResolver = $entityResolver;
    }

    /**
     * {@inheritdoc}
     */
    public function anonymizeAll(ManagerRegistry $managerRegistry): void
    {
        // it is possible there are multiple managers
        foreach ($managerRegistry->getManagers() as $manager) {
            $this->anonymizeManager($manager);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function anonymizeManager(ObjectManager $manager): void
    {
        // Get all registered entity classes from the manager
        $classes = $this->getEntityClasses($manager);
        // for each of these classes, find if there are annotations and if so, handle them
        foreach ($classes as $class) {
            $this->anonymize($class, $manager);
        }
    }

    abstract public function anonymize(string $class, ObjectManager $manager): void;

    protected function handleAnnotations(string $class, AnonymizeInterface $anonymizeInfo, array $annotatedProperties, ObjectManager $manager, int $batchSize = 100): void
    {
        // get all entities for the given Entity class
        $entities = $this->entityResolver->resolve($class, $anonymizeInfo, $manager);

        // perform flush in batchesf
        $batchIndex = 0;

        // It has no use to go through the troubles if there are no entities
        while (($row = $entities->next()) !== false) {
            // do stuff with the data in the row, $row[0] is always the object
            $entity = $row[0];

            // foreach annotated property find the strategy to do the anonymization and perform it if found
            array_walk($annotatedProperties, function (AnnotatedProperty $annotatedProperty) use ($entity, $anonymizeInfo) {
                $strategy = $this->strategyResolver->resolve($annotatedProperty->getPropertyInfo(), $anonymizeInfo);
                if ($strategy && $strategy instanceof Strategy) {
                    $strategy->anonymize($entity, $annotatedProperty->getPropertyInfo(), $annotatedProperty->getProperty());
                }
            });

            // update the anonymization date
            if ($entity instanceof AnonymizableInterface) {
                $entity->setAnonymizedAt(new \DateTime());
            }

            if (++$batchIndex % $batchSize === 0) {
                // save changes to entity
                $manager->flush();

                // detach from Doctrine, so that it can be GC'd immediately
                $manager->clear($class);
            }
        }

        // final cleanup
        // save changes to entity
        $manager->flush();

        // detach from Doctrine, so that it can be GC'd immediately
        $manager->clear($class);
    }

    /**
     * Get all registered Entity classes from the manager
     *
     * @param ObjectManager $manager
     * @return string[]
     */
    public function getEntityClasses(ObjectManager $manager): array
    {
        return array_map(function (ClassMetadata $meta) {
            return $meta->getName();
        }, $manager->getMetadataFactory()->getAllMetadata());
    }
}
