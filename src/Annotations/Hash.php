<?php
/**
 * User: Jurriaan Ruitenberg
 * Date: 18-11-2019
 * Time: 16:59
 */

namespace Oberon\Anonymize\Annotations;

class Hash extends AnonymizeProperty
{
    public $algorithm;

    public function getStrategy(): string
    {
        return '';
    }
}
