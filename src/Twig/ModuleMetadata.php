<?php
declare(strict_types=1);


namespace LotGD\Crate\WWW\Twig;


use Composer\Package\CompletePackageInterface;

/**
 * Class to abstract the module metadata for twig.
 * @package LotGD\Crate\WWW\Model
 */
class ModuleMetadata
{
    private $package;

    public function __construct(CompletePackageInterface $package)
    {
        $this->package = $package;
    }

    public function getName(): string
    {
        return $this->package->getName();
    }

    public function getVersion(): string
    {
        return $this->package->getPrettyVersion();
    }

    public function getAuthors(): string
    {
        return $this->formatAuthors($this->package->getAuthors());
    }

    /**
     * Returns a string of a list of authors.
     * @param array $authors
     * @return array
     */
    protected function formatAuthors(array $authors = null): string
    {
        if ($authors === null) {
            return "unknown";
        }

        $list = "";
        foreach ($authors as $author) {
            if (isset($author['email']) && isset($author["name"])) {
                $list .= "${author['name']} (${author['email']}), ";
            } elseif (isset($author["name"])) {
                $list .= "${author['name']}, ";
            } elseif (isset($author["email"])) {
                $list .= "${author['email']}, ";
            } else {
                continue;
            }
        }

        return substr($list, 0, -2);
    }
}