<?php

declare(strict_types = 1);

namespace MethorZ\FileSystem;

use MethorZ\FileSystem\Exception\DirectoryException;

/**
 * Directory representation
 *
 * @package MethorZ\FileSystem
 * @author Thorsten Merz <methorz@spammerz.de>
 * @copyright MethorZ
 */
class Directory extends FileSystem
{
    /**
     * Directory contents
     *
     * @var array<\MethorZ\FileSystem\Directory|\MethorZ\FileSystem\File>
     */
    private array $contents = [];

    /**
     * Constructor
     */
    public function __construct(
        private readonly string $path
    ) {
        // Make sure the path is a directory and that it exists
        if (!is_dir($this->path)) {
            throw new DirectoryException('The provided path is not a directory or does not exist.');
        }

        $this->scanDirectory($this, $this->path, true);
    }

    /**
     * Returns the directory name
     */
    public function getName(): string
    {
        return basename($this->path);
    }

    /**
     * Returns the path of the directory with or without the current directory as part of the path
     */
    public function getPath(bool $includeDirectoryName = true): string
    {
        if ($includeDirectoryName) {
            return $this->path;
        }

        // Only return the path excluding the current directory in the path
        return dirname($this->path);
    }

    /**
     * Returns the contents of the directory
     *
     * @return array<\MethorZ\FileSystem\Directory|\MethorZ\FileSystem\File>
     */
    public function getContents(): array
    {
        return $this->contents;
    }

    /**
     * Adds a file or directory to the contents
     */
    public function addContent(self|File $content): void
    {
        $this->contents[] = $content;
    }

    /**
     * Returns the amount of files in the directory
     */
    public function countFiles(bool $countRecursively = false): int
    {
        $fileCount = 0;

        foreach ($this->contents as $content) {
            if ($content instanceof File) {
                $fileCount++;
            } elseif ($content instanceof self && $countRecursively) {
                $fileCount += $content->countFiles(true);
            }
        }

        return $fileCount;
    }

    /**
     * Returns the amount of directories in the directory
     */
    public function countDirectories(bool $countRecursively = false): int
    {
        $directoryCount = 0;

        foreach ($this->contents as $content) {
            if ($content instanceof self) {
                $directoryCount++;

                if ($countRecursively) {
                    $directoryCount += $content->countDirectories(true);
                }
            }
        }

        return $directoryCount;
    }
}
