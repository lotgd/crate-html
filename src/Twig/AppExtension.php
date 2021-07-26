<?php
declare(strict_types=1);


namespace LotGD\Crate\WWW\Twig;


use Octicons\Octicon;
use Octicons\Options;
use Twig\Extension\AbstractExtension;
use Twig\Markup;
use Twig\TwigFilter;
use Twig\TwigFunction;
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

    public function getFunctions()
    {
        return [
            new TwigFunction("octicon", [$this, "octiconFunction"])
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
        // For \r\n making it into the database from windows
        $text = str_replace("\r\n","\n", $text);
        // Shouldn't be necessary, but replaces old mac format, too.
        $text = str_replace("\r","\n", $text);

        // Make breaks at every linebreak
        $text = explode("\n", $text);
        $newText = "";
        foreach ($text as $textLine) {
            if (trim($textLine) == "") {
                $newText .= "\n\t</p><p>\n";
            } else {
                $newText .= $textLine . "\n";
            }
        }

        return "<p>{$newText}</p>";
    }

    /**
     * octicon function, code released originally under MIT, available from EdwinHoksberg/octicons-php/src/Twig/OcticonTwigExtension.php
     * @param string $name
     * @param string $classes
     * @param int $ratio
     * @author EdwinHoksberg/octicons-php
     * @return Markup
     * @throws \Octicons\Exceptions\DataFileException
     * @throws \Octicons\Exceptions\OcticonException
     * @see https://github.com/EdwinHoksberg/octicons-php/blob/master/src/Twig/OcticonTwigExtension.php
     */
    public function octiconFunction(string $name, $classes = '', int $ratio=1)
    {
        $octicon = new Octicon();
        $options = new Options();

        if (!empty($classes)) {
            $options->addClass($classes);
        }

        $options->setRatio($ratio);

        return new Markup($octicon->icon($name, $options)->toSvg(), 'UTF-8');
    }
}