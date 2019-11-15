<?php
/**
 * User: Jurriaan Ruitenberg
 * Date: 15-11-2019
 * Time: 15:07
 */

namespace Oberon\Anonymize\Model;

use Oberon\Anonymize\Traits\Anonymizable;

class AbstractAnonymizable implements AnonymizableInterface
{
    use Anonymizable;
}
