<?php

declare(strict_types = 1);

namespace MethorZ\FileSystemTest;

use MethorZ\FileSystem\Exception\FileException;
use MethorZ\FileSystem\File;
use PHPUnit\Framework\TestCase;

/**
 * File tests
 *
 * @package MethorZ\FileSystemTest
 * @author Thorsten Merz <methorz@spammerz.de>
 * @copyright MethorZ
 */
class FileTest extends TestCase
{
    /**
     * Test reading an existing file
     */
    public function testFileInstantiation(): void
    {
        $file = new File(__DIR__ . '/assets/readFileTesting.txt');
        self::assertInstanceOf(File::class, $file);
    }

    /**
     * Test reading the content of a file
     */
    public function testFileContent(): void
    {
        $file = new File(__DIR__ . '/assets/readFileTesting.txt');
        self::assertEquals("This file is being read by the unit test!\n", $file->getContent());
    }

    /**
     * Test retrieving the filename of a file with extension
     */
    public function testGetFileNameWithExtension(): void
    {
        $file = new File(__DIR__ . '/assets/readFileTesting.txt');
        self::assertEquals('readFileTesting.txt', $file->getName());
    }

    /**
     * Test retrieving the filename of a file without extension
     */
    public function testGetFileNameWithoutExtension(): void
    {
        $file = new File(__DIR__ . '/assets/readFileTesting.txt');
        self::assertEquals('readFileTesting', $file->getName(true));
    }

    /**
     * Test retrieving the path of a file with filename
     */
    public function testGetPathWithFileName(): void
    {
        $file = new File(__DIR__ . '/assets/readFileTesting.txt');
        self::assertEquals(__DIR__ . '/assets/readFileTesting.txt', $file->getPath());
    }

    /**
     * Test retrieving the path of a file without filename
     */
    public function testGetPathWithoutFileName(): void
    {
        $file = new File(__DIR__ . '/assets/readFileTesting.txt');
        self::assertEquals(__DIR__ . '/assets', $file->getPath(false));
    }

    /**
     * Test retrieving the extension of a file
     */
    public function testGetExtension(): void
    {
        $file = new File(__DIR__ . '/assets/readFileTesting.txt');
        self::assertEquals('txt', $file->getExtension());
    }

    /**
     * Test exception when trying to read a non-existing file
     */
    public function testNonExistingFile(): void
    {
        $this->expectException(FileException::class);
        new File(__DIR__ . '/assets/nonExistingFile.txt');
    }

    /**
     * Test exception when trying to read a directory
     */
    public function testDirectoryException(): void
    {
        $this->expectException(FileException::class);
        new File(__DIR__ . '/assets');
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
        !is_dir(__DIR__ . '/temp_assets/deleteMe') || rmdir(__DIR__ . '/temp_assets/deleteMe');
    }
}
