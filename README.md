# MethorZ Filesystem

MethorZ Filesystem is a robust and flexible filesystem abstraction library for PHP. It provides a consistent interface for working with various filesystem operations.

## Features

- **File Operations**: Create, read, update, and delete files.
- **Directory Operations**: Create, list, and delete directories.
- **Stream Support**: Work with file streams for efficient file handling.
- **PHP 8.3**: Fully compatible with PHP 8.3.

## Installation

You can install the package via Composer:

```bash
composer require methorz/filesystem
```

Usage
Here's a basic example of how to use the MarkMe library:

```bash
<?php

require 'vendor/autoload.php';

use MethorZ\Filesystem\Filesystem;

$filesystem = new Filesystem();

// Create a new file
$filesystem->write('example.txt', 'Hello, World!');

// Read the file content
$content = $filesystem->read('example.txt');
echo $content; // Outputs: Hello, World!

// Delete the file
$filesystem->delete('example.txt');
```

Directory Operations
You can also perform various directory operations. Here's an example:

```bash
<?php

use MethorZ\Filesystem\Filesystem;

$filesystem = new Filesystem();

// Create a new directory
$filesystem->createDirectory('example-dir');

// List files in a directory
$files = $filesystem->listFiles('example-dir');
print_r($files);

// Delete the directory
$filesystem->deleteDirectory('example-dir');
```

## Contributing
Contributions are welcome! Please submit a pull request or open an issue to discuss your ideas.  

## License
This project is licensed under the proprietary license. See the LICENSE file for more details.  

## Contact
For any inquiries, please contact MethorZ at methorz@spammerz.de.
