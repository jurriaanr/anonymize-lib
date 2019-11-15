<?php
/**
 * User: Jurriaan Ruitenberg
 * Date: 15-11-2019
 * Time: 14:21
 */

namespace Oberon\Anonymize\Traits;

use \DateTime;
use Doctrine\ORM\Mapping as ORM;
use Oberon\Anonymize\Model\AnonymizableInterface;

trait Anonymizable
{
    /**
     * @var DateTime
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $anonymizedAt;

    public function getAnonymizedAt(): ?DateTime
    {
        return $this->anonymizedAt;
    }

    /**
     * @param DateTime|null $anonymizedAt
     * @return $this
     */
    public function setAnonymizedAt(?DateTime $anonymizedAt)
    {
        $this->anonymizedAt = $anonymizedAt;
        return $this;
    }

    public function isAnonymized(): bool
    {
        return $this->anonymizedAt !== null;
    }
}
