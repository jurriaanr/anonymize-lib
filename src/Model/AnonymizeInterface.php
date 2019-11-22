<?php
/**
 * User: Jurriaan Ruitenberg
 * Date: 22-11-2019
 * Time: 16:34
 */

namespace Oberon\Anonymize\Model;

interface AnonymizeInterface
{
    const DEFAULT_GROUP = 'default';

    // If it is not yet anonymized
    const FIRST_TIME = 'first';
    // Always (This may keep altering the data!)
    const FORCE = 'force';
    // Once a certain period has passed
    const AFTER_DATE = 'date';

    public function getMode(): string;

    public function getGroup(): string;

    public function getDateField(): ?string;

    public function getDateInterval(): ?string;
}
