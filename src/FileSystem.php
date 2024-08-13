<?php

declare(strict_types = 1);

namespace MethorZ\FileSystem;

use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;

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
        $iterator = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($path, RecursiveDirectoryIterator::SKIP_DOTS),
            RecursiveIteratorIterator::SELF_FIRST
        );

        $tree = [
            'contents' => [],
            'filename' => basename($path),
            'path' => $path,
            'type' => 'folder',
        ];

        foreach ($iterator as $filePath => $fileInfo) {
            $relativePath = substr($filePath, strlen($path) + 1);
            $parts = explode(DIRECTORY_SEPARATOR, $relativePath);
            $current = &$tree['contents'];

            foreach ($parts as $part) {
                $name = $fileInfo->isFile()
                    ? pathinfo($part, PATHINFO_FILENAME)
                    : $part;

                $pathWithoutFile = $fileInfo->isFile()
                    ? $path . (
                        dirname($relativePath) === '.'
                            ? ''
                            : DIRECTORY_SEPARATOR . dirname($relativePath)
                    )
                    : $path . DIRECTORY_SEPARATOR . $relativePath;

                if (!isset($current[$name])) {
                    $current[$name] = $fileInfo->isDir()
                        ? [
                            'contents' => [],
                            'filename' => $name,
                            'path' => $pathWithoutFile,
                            'type' => 'folder',
                        ]
                        : [
                            'extension' => pathinfo($part, PATHINFO_EXTENSION),
                            'filename' => pathinfo($part, PATHINFO_FILENAME),
                            'path' => $pathWithoutFile,
                            'type' => 'file',
                        ];
                }

                $current = &$current[$name]['contents'];
            }
        }

        return $this->buildTreeRecursively(
            [$tree],
            $extensions
        );
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
            $fileSystem[] =
                $treeItem['type'] === 'file'
                &&
                (
                    empty($extensions)
                    || in_array($treeItem['extension'], $extensions, true)
                )
                    ? new File($treeItem['path'], $treeItem['filename'], $treeItem['extension'])
                    : new Directory(
                        $treeItem['path'],
                        $treeItem['filename'],
                        $this->buildTreeRecursively(
                            $treeItem['contents'],
                            $extensions
                        )
                    );
        }

        return $fileSystem;
    }
}
