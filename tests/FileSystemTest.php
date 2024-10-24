<?php

declare(strict_types = 1);

namespace MethorZ\FileSystemTest;

use MethorZ\FileSystem\FileSystem;
use PHPUnit\Framework\TestCase;

/**
 * Tests for FileSystem
 *
 * @package MethorZ\FileSystemTest
 * @author Thorsten Merz <methorz@spammerz.de>
 * @copyright MethorZ
 */
class FileSystemTest extends TestCase
{
    /**
     * Test deletion of a directory
     */
    public function testCreateDirectory(): void
    {
        self::assertDirectoryDoesNotExist(__DIR__ . '/temp_assets/deleteMe/Inline');

        FileSystem::createDirectory(__DIR__ . '/temp_assets/deleteMe/Inline');

        self::assertDirectoryExists(__DIR__ . '/temp_assets/deleteMe/Inline');
    }

    /**
     * Test deleting a directory recursively
     */
    public function testDeleteDirectory(): void
    {
        self::assertDirectoryExists(__DIR__ . '/temp_assets/deleteMe');

        FileSystem::remove(__DIR__ . '/temp_assets/deleteMe');

        self::assertDirectoryDoesNotExist(__DIR__ . '/temp_assets/deleteMe');
    }

    /**
     * Test deleting a file
     */
    public function testDeleteFile(): void
    {
        self::assertFileExists(__DIR__ . '/temp_assets/deleteMe/deleteMe.txt');

        FileSystem::remove(__DIR__ . '/temp_assets/deleteMe/deleteMe.txt');

        self::assertFileDoesNotExist(__DIR__ . '/temp_assets/deleteMe/deleteMe.txt');
    }

    /**
     * Test copying a file
     */
    public function testCopyFile(): void
    {
        self::assertFileExists(__DIR__ . '/temp_assets/deleteMe/deleteMe.txt');
        self::assertFileDoesNotExist(__DIR__ . '/temp_assets/deleteMe/MeToo/deleteMeCopy.txt');

        FileSystem::copy(__DIR__ . '/temp_assets/deleteMe/deleteMe.txt', __DIR__ . '/temp_assets/deleteMe/MeToo/deleteMeCopy.txt');

        self::assertFileExists(__DIR__ . '/temp_assets/deleteMe/MeToo/deleteMeCopy.txt');
    }

    /**
     * Test copying a directory recursively
     */
    public function testCopyDirectory(): void
    {
        self::assertDirectoryExists(__DIR__ . '/temp_assets/deleteMe');
        self::assertDirectoryDoesNotExist(__DIR__ . '/temp_assets/deleteMe/deleteMeCopy');

        FileSystem::copy(__DIR__ . '/temp_assets/deleteMe', __DIR__ . '/temp_assets/deleteMe/deleteMeCopy');

        self::assertDirectoryExists(__DIR__ . '/temp_assets/deleteMe/deleteMeCopy');
    }

    /**
     * Test moving a file
     */
    public function testMoveFile(): void
    {
        self::assertFileExists(__DIR__ . '/temp_assets/deleteMe/deleteMe.txt');
        self::assertFileDoesNotExist(__DIR__ . '/temp_assets/deleteMe/MeToo/deleteMeMoved.txt');

        FileSystem::rename(__DIR__ . '/temp_assets/deleteMe/deleteMe.txt', __DIR__ . '/temp_assets/deleteMe/MeToo/deleteMeMoved.txt');

        self::assertFileExists(__DIR__ . '/temp_assets/deleteMe/MeToo/deleteMeMoved.txt');
        self::assertFileDoesNotExist(__DIR__ . '/temp_assets/deleteMe/deleteMe.txt');
    }

    /**
     * Test moving a directory
     */
    public function testMoveDirectory(): void
    {
        self::assertDirectoryExists(__DIR__ . '/temp_assets/deleteMe/MeToo');
        self::assertDirectoryDoesNotExist(__DIR__ . '//temp_assets/deleteMe/MeTooMoved');

        FileSystem::rename(__DIR__ . '/temp_assets/deleteMe/MeToo', __DIR__ . '/temp_assets/deleteMe/MeTooMoved');

        self::assertDirectoryExists(__DIR__ . '/temp_assets/deleteMe/MeTooMoved');
        self::assertDirectoryDoesNotExist(__DIR__ . '/temp_assets/deleteMe/MeToo');
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
        !is_file(__DIR__ . '/temp_assets/deleteMe/MeToo/deleteMeCopy.txt') || unlink(__DIR__ . '/temp_assets/deleteMe/MeToo/deleteMeCopy.txt');
        !is_file(__DIR__ . '/temp_assets/deleteMe/MeToo/deleteMeMoved.txt') || unlink(__DIR__ . '/temp_assets/deleteMe/MeToo/deleteMeMoved.txt');
        !is_file(__DIR__ . '/temp_assets/deleteMe/MeTooMoved/deleteMeToo.txt') || unlink(__DIR__ . '/temp_assets/deleteMe/MeTooMoved/deleteMeToo.txt');
        !is_file(__DIR__ . '/temp_assets/deleteMe/deleteMeCopy/deleteMe.txt') || unlink(__DIR__ . '/temp_assets/deleteMe/deleteMeCopy/deleteMe.txt');
        !is_dir(__DIR__ . '/temp_assets/deleteMe/deleteMeCopy/MeToo') || rmdir(__DIR__ . '/temp_assets/deleteMe/deleteMeCopy/MeToo');
        !is_dir(__DIR__ . '/temp_assets/deleteMe/deleteMeCopy') || rmdir(__DIR__ . '/temp_assets/deleteMe/deleteMeCopy');
        !is_dir(__DIR__ . '/temp_assets/deleteMe/deleteMeMoved') || rmdir(__DIR__ . '/temp_assets/deleteMe/deleteMeMoved');
        !is_dir(__DIR__ . '/temp_assets/deleteMe/MeToo') || rmdir(__DIR__ . '/temp_assets/deleteMe/MeToo');
        !is_dir(__DIR__ . '/temp_assets/deleteMe/MeTooMoved') || rmdir(__DIR__ . '/temp_assets/deleteMe/MeTooMoved');
        !is_dir(__DIR__ . '/temp_assets/deleteMe/Inline') || rmdir(__DIR__ . '/temp_assets/deleteMe/Inline');
        !is_dir(__DIR__ . '/temp_assets/deleteMe') || rmdir(__DIR__ . '/temp_assets/deleteMe');
    }
}
