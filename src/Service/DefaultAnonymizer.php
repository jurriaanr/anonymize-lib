<?php

namespace Oberon\Anonymize\Service;

use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\Common\Persistence\ObjectManager;
use Oberon\Anonymize\Annotations\Anonymize;
use Oberon\Anonymize\Model\AnnotatedProperty;
use Oberon\Anonymize\Model\AnonymizableInterface;
use Oberon\Anonymize\Model\AnonymizeHelper;
use Oberon\Anonymize\Resolver\EntityResolver;
use Oberon\Anonymize\Resolver\StrategyResolver;
use Oberon\Anonymize\Strategy\Strategy;

class DefaultAnonymizer implements Anonymizer
{
    /** @var StrategyResolver */
    private $strategyResolver;
    /** @var EntityResolver */
    private $entityResolver;
    /** @var AnonymizeHelper */
    private $helper;

    public function __construct(StrategyResolver $strategyResolver, EntityResolver $entityResolver)
    {
        $this->strategyResolver = $strategyResolver;
        $this->entityResolver = $entityResolver;
        $this->helper = new AnonymizeHelper();
    }

    /**
     * {@inheritdoc}
     */
    public function anonymizeAll(ManagerRegistry $managerRegistry): void
    {
        // it is possible there are multiple managers
        foreach ($managerRegistry->getManagers() as $manager) {
            $this->anonymize($manager);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function anonymize(ObjectManager $manager): void
    {
        // Get all registered entity classes from the manager
        $classes = $this->helper->getEntityClasses($manager);
        // for each of these classes, find if there are annotations and if so, handle them
        array_walk($classes, function (string $class) use ($manager) {
            $classAnnotation = $this->helper->getClassAnnotation($class);
            $annotatedProperties = $this->helper->getAnnotatedPPropertiesForClass($class);
            if ($classAnnotation && $annotatedProperties) {
                $this->handleAnnotations($class, $classAnnotation, $annotatedProperties, $manager);
            }
        });
        // save any changes to any of the entities of this manager
        $manager->flush();
    }

    private function handleAnnotations(string $class, Anonymize $classAnnotation, array $annotatedProperties, ObjectManager $manager, int $batchSize = 100): void
    {
        // get all entities for the given Entity class
        $entities = $this->entityResolver->resolve($class, $manager);

        $batchIndex = 0;

        // It has no use to go through the troubles if there are no entities
        while (($row = $entities->next()) !== false) {
            // do stuff with the data in the row, $row[0] is always the object
            $entity = $row[0];

            // foreach annotated property find the strategy to do the anonymization and perform it if found
            array_walk($annotatedProperties, function (AnnotatedProperty $annotatedProperty) use ($entity, $classAnnotation) {
                $strategy = $this->strategyResolver->resolve($annotatedProperty->getAnnotation(), $classAnnotation);
                if ($strategy && $strategy instanceof Strategy) {
                   $strategy->anonymize($entity, $annotatedProperty->getAnnotation(), $annotatedProperty->getProperty());
                }
            });

            // update the anonymization date
            if ($entity instanceof AnonymizableInterface) {
                $entity->setAnonymizedAt(new \DateTime());
            }

            if ($batchIndex === $batchSize) {
                // save changes to entity
                $manager->flush();

                // detach from Doctrine, so that it can be GC'd immediately
                $manager->clear($class);
            } else {
                $batchIndex++;
            }
        }

        // final cleanup
        // save changes to entity
        $manager->flush();

        // detach from Doctrine, so that it can be GC'd immediately
        $manager->clear($class);
    }
}
