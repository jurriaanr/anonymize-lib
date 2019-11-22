<?php
/**
 * User: Jurriaan Ruitenberg
 * Date: 15-11-2019
 * Time: 15:00
 */

namespace Oberon\Anonymize\Strategy;

use \DateTime;
use \DateInterval;
use Oberon\Anonymize\Model\AnonymizableInterface;
use Oberon\Anonymize\Model\AnonymizeInterface;
use Oberon\Anonymize\Model\AnonymizePropertyInterface;
use Symfony\Component\PropertyAccess\PropertyAccessor;

abstract class AbstractStrategy implements Strategy
{
    /** @var AnonymizeInterface */
    protected $anonymizeInfo;

    /** @return mixed */
    abstract public function handle($entity, AnonymizePropertyInterface $anonymizeProperty, \ReflectionProperty $property);

    /**
     * @throws \Exception
     */
    public function anonymize($entity, AnonymizePropertyInterface $anonymizeProperty, \ReflectionProperty $property): void
    {
        if ($this->shouldAnonymize($entity, $anonymizeProperty)) {
            $value = $this->handle($entity, $anonymizeProperty, $property);
            $property->setValue($entity, $value);
        }
    }

    /**
     * @throws \Exception
     */
    protected function shouldAnonymize($entity, AnonymizePropertyInterface $annotation): bool
    {
        if ($entity instanceof AnonymizableInterface) {
            // if the mode is not force, than only anonymize if it has not been anonymized yet
            if ($entity->isAnonymized() && $this->getAnonymizeInfo()->getMode() !== AnonymizeInterface::FORCE) {
                return false;
            } else if (!$entity->isAnonymized() && $this->getAnonymizeInfo()->getMode() === AnonymizeInterface::AFTER_DATE) {
                // if the entity is not yet anonymized but only should be anonymized after a certain date, check the date
                // for this a field on the entity that returns a DateTimeInterface should be set.
                // Optionally also an interval can be set (defaults to 3 months)
                if(!$this->getAnonymizeInfo()->getDateField()) {
                    throw new \RuntimeException('When the mode is \'date\' a field should be set to compare the date to');
                }

                // look for the value of the field, this is the date to check against
                $propertyAccessor =  new PropertyAccessor();
                $date = $propertyAccessor->getValue($entity, $this->getAnonymizeInfo()->getDateField());

                if ($date && $date instanceof \DateTimeInterface) {
                    // subtract the interval from the current date
                    $checkDate = (new DateTime())->sub(new DateInterval($this->getAnonymizeInfo()->getDateInterval()));
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

    public function setAnonymizeInfo(AnonymizeInterface $anonymizeInfo): void
    {
        $this->anonymizeInfo = $anonymizeInfo;
    }

    public function getAnonymizeInfo(): ?AnonymizeInterface
    {
        return $this->anonymizeInfo;
    }
}
