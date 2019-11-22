<?php

namespace Oberon\Anonymize\Service;

use Doctrine\Common\Annotations\AnnotationException;
use Doctrine\Common\Persistence\ObjectManager;
use Oberon\Anonymize\Model\AnonymizeHelper;
use Oberon\Anonymize\Resolver\EntityResolver;
use Oberon\Anonymize\Resolver\StrategyResolver;

class AnnotationAnonymizer extends AbstractAnonymizer
{
    /** @var AnonymizeHelper */
    private $helper;

    public function __construct(StrategyResolver $strategyResolver, EntityResolver $entityResolver)
    {
        parent::__construct($strategyResolver, $entityResolver);
        $this->helper = new AnonymizeHelper();
    }

    /**
     * {@inheritdoc}
     * @throws AnnotationException
     */
    public function anonymize(string $class, ObjectManager $manager): void
    {
        $classAnnotations = $this->helper->getClassAnnotations($class);
        foreach ($classAnnotations as $classAnnotation) {
            $annotatedProperties = $this->helper->getAnnotatedPropertiesForClass($class, $classAnnotation->getGroup());
            if ($annotatedProperties) {
                $this->handleAnnotations($class, $classAnnotation, $annotatedProperties, $manager);
            }
        }
    }
}
