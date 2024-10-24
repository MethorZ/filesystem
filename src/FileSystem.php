<?php

declare(strict_types = 1);

namespace MethorZ\FileSystem;

use FilesystemIterator;
use RecursiveDirectoryIterator;

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
     * List of ignored extensions
     *
     * @var array<string>
     */
    private static array $ignoredExtensions = [];

    /**
     * List of ignored directories
     *
     * @var array<string>
     */
    private static array $ignoredDirectories = [];

    /**
     * List of ignored files
     *
     * @var array<string>
     */
    private static array $ignoredFiles = [];

    /**
     * Sets the list of ignored extensions
     *
     * @param array<string> $ignoredExtensions
     */
    public static function ignoreExtensions(array $ignoredExtensions): void
    {
        self::$ignoredExtensions = $ignoredExtensions;
    }

    /**
     * Sets the list of ignored directories
     *
     * @param array<string> $ignoredDirectories
 */
    public static function ignoreDirectories(array $ignoredDirectories): void
    {
        self::$ignoredDirectories = $ignoredDirectories;
    }

    /**
     * Sets the list of ignored files
     *
     * @param array<string> $ignoredFiles
     */
    public static function ignoreFiles(array $ignoredFiles): void
    {
        self::$ignoredFiles = $ignoredFiles;
    }

    /**
     * Checks if the provided path is a directory
     */
    public static function isDirectory(string $path): bool
    {
        return is_dir($path);
    }

    /**
     * Checks if the provided path is a file
     */
    public static function isFile(string $path): bool
    {
        return is_file($path);
    }

    /**
     * Create a directory allowing recursive creation
     */
    public static function createDirectory(string $path, int $permissions = 0777, bool $recursiveCreation = true): bool
    {
        if (!file_exists($path)) {
            return mkdir($path, $permissions, $recursiveCreation);
        }

        return true;
    }

    /**
     * Remove a file or directory recursively
     */
    public static function remove(string $path): bool
    {
        return is_dir($path)
            ? self::removeDirectory($path)
            : unlink($path);
    }

    /**
     * Copy a file or directory recursively while making sure the destination directory exists
     */
    public static function copy(string $source, string $destination, int $permissions = 0777): void
    {
        if (is_dir($source)) {
            self::copyDirectory($source, $destination, $permissions);
        } else {
            $destinationDirectory = dirname($destination);

            if (!is_dir($destinationDirectory)) {
                mkdir($destinationDirectory, $permissions, true);
            }

            copy($source, $destination);
        }
    }

    /**
     * Rename a file or directory
     */
    public static function rename(string $source, string $destination): bool
    {
        return rename($source, $destination);
    }

    /**
     * Recursively scans the directory and adds files and subdirectories to the Directory object
     */
    protected function scanDirectory(Directory $directory, string $path, bool $recursiveScan): void
    {
        $iterator = new RecursiveDirectoryIterator($path, FilesystemIterator::SKIP_DOTS);

        foreach ($iterator as $fileInfo) {
            $filePath = $fileInfo->getPathname();
            $fileName = $fileInfo->getFilename();

            // Skip ignored directories
            if ($fileInfo->isDir() && in_array($fileName, self::$ignoredDirectories, true)) {
                continue;
            }

            // Skip ignored files
            if ($fileInfo->isFile() && in_array($fileName, self::$ignoredFiles, true)) {
                continue;
            }

            // Skip files with ignored extensions
            if (
                $fileInfo->isFile()
                && !empty(self::$ignoredExtensions)
                && in_array($fileInfo->getExtension(), self::$ignoredExtensions, true)
            ) {
                continue;
            }

            if ($fileInfo->isDir() && $recursiveScan) {
                $subDirectory = new Directory($filePath);
                $directory->addContent($subDirectory);
            } elseif (
                $fileInfo->isFile()
                && (
                    empty($extensions)
                    || in_array($fileInfo->getExtension(), $extensions, true)
                )
            ) {
                $file = new File($filePath);
                $directory->addContent($file);
            } elseif ($fileInfo->isDir() && !$recursiveScan) {
                $subDirectory = new Directory($filePath);
                $directory->addContent($subDirectory);
            }
        }
    }

    /**
     * Copy a directory recursively
     */
    private static function copyDirectory(string $source, string $destination, int $permissions = 0777): void
    {
        $iterator = new RecursiveDirectoryIterator($source, FilesystemIterator::SKIP_DOTS);

        foreach ($iterator as $item) {
            $destPath = $destination . DIRECTORY_SEPARATOR . $iterator->getSubPathname();

            if ($item->isDir()) {
                if (!file_exists($destPath)) {
                    mkdir($destPath, $permissions, true);
                }
            } else {
                $destinationPath = dirname($destPath);

                if (!is_dir($destinationPath)) {
                    mkdir($destinationPath, $permissions, true);
                }

                copy($item->getPathname(), $destPath);
            }
        }
    }

    /**
     * Remove a directory recursively
     */
    private static function removeDirectory(string $path): bool
    {
        $iterator = new RecursiveDirectoryIterator($path, FilesystemIterator::SKIP_DOTS);

        foreach ($iterator as $item) {
            if ($item->isDir()) {
                self::removeDirectory($item->getPathname());
            } else {
                unlink($item->getPathname());
            }
        }

        return rmdir($path);
    }
}
