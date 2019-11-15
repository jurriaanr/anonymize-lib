<?php
/**
 * User: Jurriaan Ruitenberg
 * Date: 15-11-2019
 * Time: 15:00
 */

namespace Oberon\Anonymize\Strategy;

use \DateTime;
use \DateInterval;
use Oberon\Anonymize\Annotations\Anonymize;
use Oberon\Anonymize\Annotations\AnonymizeProperty;
use Oberon\Anonymize\Model\AnonymizableInterface;
use Symfony\Component\PropertyAccess\PropertyAccessor;

abstract class AbstractStrategy implements Strategy
{
    /** @var Anonymize */
    protected $classAnnotation;

    abstract public function handle($entity, AnonymizeProperty $annotation, \ReflectionProperty $property): void;

    /**
     * @throws \Exception
     */
    public function anonymize($entity, AnonymizeProperty $annotation, \ReflectionProperty $property): void
    {
        if ($this->shouldAnonymize($entity, $annotation)) {
            $this->handle($entity, $annotation, $property);
        }
    }

    /**
     * @throws \Exception
     */
    protected function shouldAnonymize($entity, AnonymizeProperty $annotation): bool
    {
        if ($entity instanceof AnonymizableInterface) {
            // if the mode is not force, than only anonymize if it has not been anonymized yet
            if ($entity->isAnonymized() && $this->getClassAnnotation()->getMode() !== Anonymize::FORCE) {
                return false;
            } else if (!$entity->isAnonymized() && $this->getClassAnnotation()->getMode() === Anonymize::AFTER_DATE) {
                // if the entity is not yet anonymized but only should be anonymized after a certain date, check the date
                // for this a field on the entity that returns a DateTimeInterface should be set.
                // Optionally also an interval can be set (defaults to 3 months)
                if(!$this->getClassAnnotation()->getDateField()) {
                    throw new \RuntimeException('When the mode is \'date\' a field should be set to compare the date to');
                }

                // look for the value of the field, this is the date to check against
                $propertyAccessor =  new PropertyAccessor();
                $date = $propertyAccessor->getValue($entity, $this->getClassAnnotation()->getDateField());

                if ($date && $date instanceof \DateTimeInterface) {
                    // subtract the interval from the current date
                    $checkDate = (new DateTime())->sub(new DateInterval($this->getClassAnnotation()->getDateInterval()));
                    // if the current date - the interval is bigger than the date to check against, the period has passed
                    // and the data should be anonymized
                    return $checkDate > $date;
                } else {
                    throw new \RuntimeException('When the mode is \'date\' the field should return an instance of DateTimeInterface');
                }
            }
        }

        return true;
    }

    public function setClassAnnotation(Anonymize $classAnnotation): void
    {
        $this->classAnnotation = $classAnnotation;
    }

    public function getClassAnnotation(): ?Anonymize
    {
        return $this->classAnnotation;
    }
}
