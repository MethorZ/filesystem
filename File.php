<?php

declare(strict_types = 1);

namespace MethorZ\FileSystem;

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
        private readonly string $path,
        private readonly string $name,
        private readonly string $extension
    ) {
    }

    /**
     * Returns the path of the file
     */
    public function getPath(): string
    {
        return $this->path;
    }

    /**
     * Returns the filename
     */
    public function getName(bool $stripExtension = false): string
    {
        if ($stripExtension) {
            return $this->name;
        }

        return implode('.', [
            $this->name,
            $this->getExtension(),
        ]);
    }

    /**
     * Returns the extension of the file
     */
    public function getExtension(): string
    {
        return $this->extension;
    }

    /**
     * Returns the contents of the file
     */
    public function getContents(): string|bool
    {
        return file_get_contents(implode('/', [$this->path, $this->getName()]));
    }

    /**
     * Ease of use method to check if the object is a directory
     */
    public function isDirectory(): bool
    {
        return false;
    }

    /**
     * Ease of use method to check if the object is a directory
     */
    public function isFile(): bool
    {
        return true;
    }
}
