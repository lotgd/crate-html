<?php
declare(strict_types=1);


namespace LotGD\Crate\WWW\Twig;


use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigTest;

/**
 * Adds custom filters.
 * @package LotGD\Crate\WWW\Twig
 */
class AppExtension extends AbstractExtension
{
    public function getTests()
    {
        return [
            new TwigTest("instanceOf", [$this, "_instanceOf"]),
        ];
    }

    public function getFilters()
    {
        return [
            new TwigFilter("viewpoint_format", [$this, "formatContent"]),
        ];
    }

    public function _instanceOf($object, $class)
    {
        if (!is_object($object)) {
            return false;
        }

        if ($object instanceof $class) {
            return true;
        }

        return false;
    }

    public function formatContent($text)
    {
        $text = explode("\n\n", $text);
        $text = implode("</p><p>", $text);
        return "<p>{$text}</p>";
    }
}