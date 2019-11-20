<?php
/**
 * User: Jurriaan Ruitenberg
 * Date: 20-11-2019
 * Time: 13:40
 */

namespace Oberon\Anonymize\Annotations;

use Oberon\Anonymize\Strategy\Strategy;

/**
 * Will remove the 2 characters at the end of zipcode and round the zipcode1
 *
 * @Annotation
 * @Target({"PROPERTY"})
 * @Attributes({
 *   @Attribute("strength", type = "int"),
 *   @Attribute("asValid", type = "bool"),
 * })
 */
final class ZipNL extends AnonymizeProperty
{
    /**
     * 0 is streetlevel (numbers remain as the are)
     * 1 is neighbourhood (last number is rounded up/down)
     * 2 is city (last 2 numbers are rounded up/down)
     * 3 is province (last 3 numbers are rounded up/down)
     * @Enum({0, 1, 2, 3})
     */
    public $strength = 0;
    /**
     * Add AA to the end of the anonymized zipcode, to remain a valid zipcode
     * @var bool
     */
    public $asValid = false;

    public function getStrategy(): string
    {
        return Strategy::ZIP_NL;
    }
}
