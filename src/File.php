<?php

declare(strict_types = 1);

namespace MethorZ\FileSystem;

use MethorZ\FileSystem\Exception\FileException;

/**
 * File representation
 *
 * @package MethorZ\FileSystem
 * @author Thorsten Merz <methorz@spammerz.de>
 * @copyright MethorZ
 */
readonly class File
{
    /**
     * Constructor
     */
    public function __construct(
        private string $path
    ) {
        // Make sure the path is a file and that it exists
        if (!is_file($this->path)) {
            throw new FileException('The provided path is not a file or does not exist.');
        }
    }

    /**
     * Returns the filename
     */
    public function getName(bool $stripExtension = false): string
    {
        // Extract the file name without the extension
        if ($stripExtension) {
            return pathinfo($this->path, PATHINFO_FILENAME);
        }

        return pathinfo($this->path, PATHINFO_BASENAME);
    }

    /**
     * Returns the path of the file
     */
    public function getPath(bool $includeFileName = true): string
    {
        if ($includeFileName) {
            return $this->path;
        }

        return pathinfo($this->path, PATHINFO_DIRNAME);
    }

    /**
     * Returns the extension of the file
     */
    public function getExtension(): string
    {
        return pathinfo($this->path, PATHINFO_EXTENSION);
    }

    /**
     * Returns the content of the file
     */
    public function getContent(): string|bool
    {
        return file_get_contents($this->getPath());
    }
}
