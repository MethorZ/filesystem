<?php

declare(strict_types = 1);

namespace MethorZ\FileSystem;

use FilesystemIterator;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use SplFileInfo;

/**
 * Filesystem representation
 *
 * @package MethorZ\FileSystem
 * @author Thorsten Merz <methorz@spammerz.de>
 * @copyright MethorZ
 */
class FileSystem
{
    /**
     * Reads the directory tree specified by the provided path
     *
     * @param array<string> $extensions
     * @return array<\MethorZ\FileSystem\Directory|\MethorZ\FileSystem\File>
     */
    public function read(string $path, array $extensions = []): array
    {
        $iterator = $this->createRecursiveIterator($path);
        $tree = $this->initializeTree($path);
        $rootKey = basename($path) . '_folder';

        foreach ($iterator as $filePath => $fileInfo) {
            $this->processFile($tree[$rootKey]['contents'], $path, $filePath, $fileInfo);
        }

        return $this->buildTreeRecursively($tree, $extensions);
    }

    /**
     * Creates a recursive iterator
     */
    private function createRecursiveIterator(string $path): RecursiveIteratorIterator
    {
        return new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($path, FilesystemIterator::SKIP_DOTS),
            RecursiveIteratorIterator::SELF_FIRST
        );
    }

    /**
     * Initializes the tree structure
     */
    private function initializeTree(string $path): array
    {
        $baseName = basename($path);
        return [
            $baseName . '_folder' => [
                'contents' => [],
                'filename' => $baseName,
                'path' => $path,
                'type' => 'folder',
            ]
        ];
    }

    /**
     * Processes the file and updates the tree structure
     */
    private function processFile(array &$treeContents, string $basePath, string $filePath, SplFileInfo $fileInfo): void
    {
        $relativePath = substr($filePath, strlen($basePath) + 1);
        $parts = explode(DIRECTORY_SEPARATOR, $relativePath);
        $current = &$treeContents;

        $pathSoFar = $basePath;
        foreach ($parts as $index => $part) {
            $isLastPart = ($index === count($parts) - 1);
            $pathSoFar .= DIRECTORY_SEPARATOR . $part;
            $name = pathinfo($part, PATHINFO_FILENAME);
            $type = $isLastPart ? ($fileInfo->isDir() ? 'folder' : 'file') : 'folder';
            $uniqueKey = $name . '_' . $type;

            if (!isset($current[$uniqueKey])) {
                $current[$uniqueKey] = $this->createNewEntry($name, $pathSoFar, $type);
            }

            $this->updateTreeStructure($current[$uniqueKey], $name, $pathSoFar, $fileInfo, $isLastPart);

            if (!$isLastPart) {
                if (!isset($current[$uniqueKey]['contents'])) {
                    $current[$uniqueKey]['contents'] = [];
                }
                $current = &$current[$uniqueKey]['contents'];
            }
        }
    }

    /**
     * Builds the path based on the parts
     */
    private function buildPath(string $basePath, array $parts, int $index, bool $isLastPart, bool $isFile): string
    {
        $path = $basePath . DIRECTORY_SEPARATOR . implode(DIRECTORY_SEPARATOR, array_slice($parts, 0, $index + 1));

        return $isLastPart && $isFile ? dirname($path) : $path;
    }

    /**
     * Updates the tree structure
     */
    private function updateTreeStructure(array &$current, string $name, string $path, SplFileInfo $fileInfo, bool $isLastPart): void
    {
        $type = $isLastPart ? ($fileInfo->isDir() ? 'folder' : 'file') : 'folder';

        $current['filename'] = $name;
        $current['path'] = $type === 'file' ? dirname($path) : $path;
        $current['type'] = $type;

        if ($type === 'file') {
            $current['extension'] = pathinfo($fileInfo->getFilename(), PATHINFO_EXTENSION);
        } elseif (!isset($current['contents'])) {
            $current['contents'] = [];
        }
    }

    /**
     * Creates a new entry for the tree structure
     */
    private function createNewEntry(string $name, string $path, string $type): array
    {
        $entry = [
            'filename' => $name,
            'path' => $path,
            'type' => $type,
        ];

        if ($type === 'folder') {
            $entry['contents'] = [];
        }

        return $entry;
    }

    /**
     * Writes the content to the filesystem
     */
    public function write(string $path, string $filename, string $content): void
    {
        $fullPath = $path . DIRECTORY_SEPARATOR . $filename;

        if (!file_exists($path)) {
            mkdir($path, 0777, true);
        }

        file_put_contents($fullPath, $content);
    }

    /**
     * Copies the source folder to the destination
     */
    public function copy(string $source, string $destination): void
    {
        $this->copyRecursively($source, $destination);
    }

    /**
     * Recursively builds the tree based on directory and file objects
     *
     * @param array<array<mixed>> $tree
     * @param array<string> $extensions
     * @return array<\MethorZ\FileSystem\Directory|\MethorZ\FileSystem\File>
     */
    private function buildTreeRecursively(array $tree, array $extensions): array
    {
        $fileSystem = [];

        // Build the directory structure
        foreach ($tree as $treeItem) {
            // Skip files that are not part of the allowed extensions
            if (
                $treeItem['type'] === 'file'
                && !empty($extensions)
                && !in_array($treeItem['extension'] ?? '', $extensions, true)
            ) {
                continue;
            }

            $fileSystem[] =
                $treeItem['type'] === 'file'
                    ? new File($treeItem['path'], $treeItem['filename'], $treeItem['extension'] ?? null)
                    : new Directory(
                    $treeItem['path'],
                    $treeItem['filename'],
                    $this->buildTreeRecursively(
                        $treeItem['contents'] ?? [],
                        $extensions
                    )
                );
        }

        return $fileSystem;
    }

    /**
     * Recursively copy the source folder to the destination
     */
    private function copyRecursively(string $source, string $destination): void
    {
        $iterator = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($source, FilesystemIterator::SKIP_DOTS),
            RecursiveIteratorIterator::SELF_FIRST
        );

        foreach ($iterator as $item) {
            $destPath = $destination . DIRECTORY_SEPARATOR . $iterator->getSubPathName();

            if ($item->isDir()) {
                if (!file_exists($destPath)) {
                    mkdir($destPath, 0777, true);
                }
            } else {
                copy($item->getPathname(), $destPath);
            }
        }
    }
}
