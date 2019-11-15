<?php
/**
 * User: Jurriaan Ruitenberg
 * Date: 15-11-2019
 * Time: 10:53
 */

namespace Oberon\Anonymize\Service;

use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\Common\Persistence\ObjectManager;

interface Anonymizer
{
    /* Find all entities with an Anonymize annotation and perform tha anonymize strategy for all managers */
    public function anonymizeAll(ManagerRegistry $managerRegistry): void;
    /* Find all entities with an Anonymize annotation and perform tha anonymize strategy for a specific manager */
    public function anonymize(ObjectManager $manager): void;
}
