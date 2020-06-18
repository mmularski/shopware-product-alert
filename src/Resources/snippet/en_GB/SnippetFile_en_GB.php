<?php declare(strict_types=1);
/**
 * @package Divante\ProductAlert
 * @author Marek Mularczyk <mmularczyk@divante.pl>
 * @copyright 2020 Divante Sp. z o.o.
 * @license See LICENSE_DIVANTE.txt for license details.
 */

namespace Divante\ProductAlert\Resources\snippet\en_GB;

use Shopware\Core\System\Snippet\Files\SnippetFileInterface;

/**
 * Class SnippetFile_en_GB
 */
class SnippetFile_en_GB implements SnippetFileInterface
{
    /**
     * @return string
     */
    public function getName(): string
    {
        return 'storefront.en-GB';
    }

    /**
     * @return string
     */
    public function getPath(): string
    {
        return __DIR__ . '/storefront.en-GB.json';
    }

    /**
     * @return string
     */
    public function getIso(): string
    {
        return 'en-GB';
    }

    /**
     * @return string
     */
    public function getAuthor(): string
    {
        return 'Marek Mularczyk';
    }

    /**
     * @return bool
     */
    public function isBase(): bool
    {
        return false;
    }
}
