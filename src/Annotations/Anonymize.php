<?php

namespace Oberon\Anonymize\Annotations;

use Doctrine\Common\Annotations\Annotation\Enum;
use Doctrine\Common\Annotations\Annotation\Target;

/**
 * @Annotation
 * @Target({"CLASS"})
 * @Attributes({
 *   @Attribute("mode", type = "string"),
 *   @Attribute("dateField", type = "string"),
 *   @Attribute("dateInterval", type = "string"),
 * })
 */
class Anonymize
{
    // If it is not yet anonymized
    const FIRST_TIME = 'first';
    // Always (This may keep altering the data!)
    const FORCE = 'force';
    // Once a certain period has passed
    const AFTER_DATE = 'date';

    /**
     * @Enum({Anonymize::FORCE, Anonymize::FIRST_TIME, Anonymize::AFTER_DATE})
     */
    private $mode;
    /**
     * The field where anonymizer should look to see if the date interval has passed. The field should return a DateTimeInterface
     * @var string
     */
    private $dateField;
    /**
     * The field where anonymizer should look to see the date interval to check if the date has passed after which it should be anonymized
     * The interval should be a relative time string in the format that DateTime's constructor supports
     *
     * @var string
     */
    private $dateInterval;

    public function __construct(array $values)
    {
        $this->mode = isset($values['mode']) ? $values['mode'] : self::FIRST_TIME;
        $this->dateField = isset($values['dateField']) ? $values['dateField'] : '';
        $this->dateInterval = isset($values['dateInterval']) ? $values['dateInterval'] : 'P3M';
    }

    public function getMode()
    {
        return $this->mode;
    }

    public function getDateField(): string
    {
        return $this->dateField;
    }

    public function getDateInterval(): string
    {
        return $this->dateInterval;
    }
}
