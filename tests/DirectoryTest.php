<?php

declare(strict_types = 1);

namespace MethorZ\FileSystemTest;

use MethorZ\FileSystem\Directory;
use MethorZ\FileSystem\FileSystem;
use PHPUnit\Framework\TestCase;

/**
 * Directory tests
 *
 * @package MethorZ\FileSystemTest
 * @author Thorsten Merz <methorz@spammerz.de>
 * @copyright MethorZ
 */
class DirectoryTest extends TestCase
{
    /**
     * Test reading an existing directory
     */
    public function testDirectoryInstantiation(): void
    {
        $directory = new Directory(__DIR__ . '/assets');

        self::assertInstanceOf(Directory::class, $directory);
    }

    /**
     * Test retrieving the directory name
     */
    public function testGetDirectoryName(): void
    {
        $directory = new Directory(__DIR__ . '/assets');

        self::assertEquals('assets', $directory->getName());
    }

    /**
     * Test retrieval the directory name of a sub directory
     */
    public function testGetDirectoryNameSubDirectory(): void
    {
        $directory = new Directory(__DIR__ . '/assets/topLevelDirectory1');

        self::assertEquals('topLevelDirectory1', $directory->getName());
    }

    /**
     * Test retrieving the path of a directory
     */
    public function testGetPath(): void
    {
        $directory = new Directory(__DIR__ . '/assets');

        self::assertEquals(__DIR__ . '/assets', $directory->getPath());
    }

    /**
     * Test retrieving the path of a sub directory
     */
    public function testGetPathSubDirectory(): void
    {
        $directory = new Directory(__DIR__ . '/assets/topLevelDirectory1');

        self::assertEquals(__DIR__ . '/assets/topLevelDirectory1', $directory->getPath());
    }

    /**
     * Test retrieving the path of a directory without the current directory
     */
    public function testGetPathWithoutCurrentDirectory(): void
    {
        $directory = new Directory(__DIR__ . '/assets');

        self::assertEquals(__DIR__, $directory->getPath(false));
    }

    /**
     * Test retrieving the path of a sub directory without the current directory
     */
    public function testGetPathSubDirectoryWithoutCurrentDirectory(): void
    {
        $directory = new Directory(__DIR__ . '/assets/topLevelDirectory1');

        self::assertEquals(__DIR__ . '/assets', $directory->getPath(false));
    }

    /**
     * Test retrieving the contents of a directory
     */
    public function testGetContents(): void
    {
        $directory = new Directory(__DIR__ . '/assets');

        self::assertIsArray($directory->getContents());
    }

    /**
     * Test reading a directory ignoring extensions
     */
    public function testIgnoreExtensions(): void
    {
        FileSystem::ignoreExtensions(['skip']);
        $directory = new Directory(__DIR__ . '/assets');
        FileSystem::ignoreExtensions([]);

        self::assertEquals(6, $directory->countFiles(true));
        self::assertEquals(6, $directory->countDirectories(true));
    }

    /**
     * Test reading a directory ignoring directories
     */
    public function testIgnoreDirectories(): void
    {
        FileSystem::ignoreDirectories(['secondLevelDirectory2-1']);
        $directory = new Directory(__DIR__ . '/assets');
        FileSystem::ignoreDirectories([]);

        self::assertEquals(4, $directory->countFiles(true));
        self::assertEquals(5, $directory->countDirectories(true));
    }

    /**
     * Test reading a directory ignoring files
     */
    public function testIgnoreFiles(): void
    {
        FileSystem::ignoreFiles(['anotherFileInHere.txt']);
        $directory = new Directory(__DIR__ . '/assets');
        FileSystem::ignoreFiles([]);

        self::assertEquals(6, $directory->countFiles(true));
        self::assertEquals(6, $directory->countDirectories(true));
    }

    /**
     * Test counting the directories in a directory
     */
    public function testCountDirectories(): void
    {
        $directory = new Directory(__DIR__ . '/assets');

        self::assertEquals(3, $directory->countDirectories());
    }

    /**
     * Test counting the directories in a directory recursively
     */
    public function testCountDirectoriesRecursively(): void
    {
        $directory = new Directory(__DIR__ . '/assets');

        self::assertEquals(6, $directory->countDirectories(true));
    }

    /**
     * Test counting the files in a directory
     */
    public function testCountFiles(): void
    {
        $directory = new Directory(__DIR__ . '/assets');

        self::assertEquals(2, $directory->countFiles());
    }

    /**
     * Test counting the files in a directory recursively
     */
    public function testCountFilesRecursively(): void
    {
        $directory = new Directory(__DIR__ . '/assets');

        self::assertEquals(7, $directory->countFiles(true));
    }

    /**
     * Set up
     */
    protected function setUp(): void
    {
        parent::setUp();

        is_dir(__DIR__ . '/temp_assets/deleteMe') || mkdir(__DIR__ . '/temp_assets/deleteMe');
        is_dir(__DIR__ . '/temp_assets/deleteMe/MeToo') || mkdir(__DIR__ . '/temp_assets/deleteMe/MeToo');
        file_put_contents(__DIR__ . '/temp_assets/deleteMe/deleteMe.txt', 'Delete me!');
        file_put_contents(__DIR__ . '/temp_assets/deleteMe/MeToo/deleteMeToo.txt', 'Delete me too!');
    }

    /**
     * Tear down
     */
    protected function tearDown(): void
    {
        parent::tearDown();

        !is_file(__DIR__ . '/temp_assets/deleteMe/deleteMe.txt') || unlink(__DIR__ . '/temp_assets/deleteMe/deleteMe.txt');
        !is_file(__DIR__ . '/temp_assets/deleteMe/MeToo/deleteMeToo.txt') || unlink(__DIR__ . '/temp_assets/deleteMe/MeToo/deleteMeToo.txt');
        !is_dir(__DIR__ . '/temp_assets/deleteMe/MeToo') || rmdir(__DIR__ . '/temp_assets/deleteMe/MeToo');
        !is_dir(__DIR__ . '/temp_assets/deleteMe/Inline') || rmdir(__DIR__ . '/temp_assets/deleteMe/Inline');
        !is_dir(__DIR__ . '/temp_assets/deleteMe') || rmdir(__DIR__ . '/temp_assets/deleteMe');
    }
}
