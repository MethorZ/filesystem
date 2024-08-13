<?php

declare(strict_types = 1);

namespace MethorZ\FileSystem;

/**
 * Directory representation
 *
 * @package MethorZ\FileSystem
 * @author Thorsten Merz <methorz@spammerz.de>
 * @copyright MethorZ
 */
readonly class Directory
{
    /**
     * Constructor
     *
     * @param array<\MethorZ\FileSystem\Directory|\MethorZ\FileSystem\File> $content
     */
    public function __construct(
        private readonly string $path,
        private readonly string $name,
        private readonly array $content = []
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
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Returns the content of the directory
     *
     * @return array<\MethorZ\FileSystem\Directory|\MethorZ\FileSystem\File>
     */
    public function getContent(): array
    {
        return $this->content;
    }

    /**
     * Checks if the directory has contents
     */
    public function hasContents(): bool
    {
        return count($this->content) > 0;
    }

    /**
     * Checks if the directory has directories
     */
    public function hasDirectories(): bool
    {
        return count(array_filter($this->content, static fn ($item) => $item instanceof self)) > 0;
    }

    /**
     * Checks if the directory has files
     */
    public function hasFiles(): bool
    {
        return count(array_filter($this->content, static fn ($item) => $item instanceof File)) > 0;
    }

    /**
     * Ease of use method to check if the object is a directory
     */
    public function isDirectory(): bool
    {
        return true;
    }

    /**
     * Ease of use method to check if the object is a directory
     */
    public function isFile(): bool
    {
        return false;
    }
}
