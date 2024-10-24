# FileSystem Library

## Overview

The FileSystem Library is a PHP package that provides a comprehensive representation of files and directories. It includes functionality for reading, scanning, copying, renaming, and removing files and directories, with support for recursive operations and filtering based on extensions, directories, and file names.

## Features

- **File and Directory Representation**: Classes to represent files and directories.
- **Recursive Operations**: Support for recursive scanning, copying, and removing of directories.
- **Filtering**: Ability to ignore specific file extensions, directories, and files during operations.
- **Unit Tests**: Comprehensive unit tests to ensure the functionality of the library.

## Installation

Install the package via Composer:

```sh
composer require methorz/filesystem
```

## Usage
Reading a File or Directory

```php
use MethorZ\FileSystem\FileSystem;
use MethorZ\FileSystem\Directory;
use MethorZ\FileSystem\File;

$path = '/path/to/your/file/or/directory';
$recursiveScan = true;
$extensions = ['txt', 'php'];

$fsObject = FileSystem::read($path, $recursiveScan, $extensions);

if ($fsObject instanceof Directory) {
    echo "It's a directory!";
} elseif ($fsObject instanceof File) {
    echo "It's a file!";
}
```

Copying Files and Directories

```php
use MethorZ\FileSystem\FileSystem;

$source = '/path/to/source';
$destination = '/path/to/destination';

FileSystem::copy($source, $destination);
```

Ignoring Specific Files, Directories, and Extensions

```php
use MethorZ\FileSystem\FileSystem;

FileSystem::ignoreExtensions(['log', 'tmp']);
FileSystem::ignoreDirectories(['cache', 'logs']);
FileSystem::ignoreFiles(['README.md', '.gitignore']);
```

Creating and Removing Directories

```php
use MethorZ\FileSystem\FileSystem;

$path = '/path/to/new/directory';
FileSystem::createDirectory($path);

FileSystem::remove($path);
```

## License
This project is licensed under the MIT License. See the LICENSE file for details.  

## Author
MethorZ - methorz@spammerz.de  

## Contributing
Contributions are welcome! Please open an issue or submit a pull request for any changes.  <hr></hr> This README provides an overview of the FileSystem Library, including installation instructions, usage examples, and information on running unit tests. For more detailed documentation, please refer to the source code and comments within the library.
