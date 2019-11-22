<?php
/**
 * User: Jurriaan Ruitenberg
 * Date: 22-11-2019
 * Time: 16:39
 */

namespace Oberon\Anonymize\Model;

interface AnonymizePropertyInterface
{
    public function getStrategy(): string;
    public function getGroup(): string;
}
