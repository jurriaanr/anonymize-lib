<?php
/**
 * User: Jurriaan Ruitenberg
 * Date: 15-11-2019
 * Time: 15:05
 */

namespace Oberon\Anonymize\Model;

use \DateTime;

interface AnonymizableInterface
{
    public function getAnonymizedAt(): ?DateTime;
    /** @return self */
    public function setAnonymizedAt(?DateTime $anonymizedAt);
    public function isAnonymized(): bool;
}
