# Laravel Research Kit

## Overview
The `haminh7036/laravel-research-kit` package provides reusable mini toolkit for research. It is designed to integrate seamlessly with Laravel projects.

## Features
- Check for missing schemas and tables across multiple servers.
- Export missing schemas and tables as TSV files.
- Easy integration with Laravel controllers and services.

## Installation

### Step 1: Add the Package
Ensure the package is included in your Laravel project's `composer.json` file:

```json
"require": {
    "haminh7036/laravel-research-kit": "dev-main"
},
"repositories": [
    {
        "type": "vcs",
        "url": "https://github.com/haminh7036/laravel-research-kit.git"
    }
]
```

Run the following command to install the package:

```bash
composer require haminh7036/laravel-research-kit
```

### Step 2: Regenerate Autoload Files
After installation, regenerate the autoload files:

```bash
composer dump-autoload
```

## Usage

### Controller Integration
Replace the existing `CheckSchemaTableController` in your Laravel project with the one provided by the package:

```php
use HaMinh7036\Controllers\CheckSchemaTableController;
```

### Service Integration
The `CheckSchemaTableService` is available for checking missing tables and schemas:

```php
use HaMinh7036\Services\CheckSchemaTableService;

$service = new CheckSchemaTableService();
$missing = $service->checkMissingTables($servers, $tables);
```

### Export Missing Tables
The controller includes a method to export missing schemas and tables as TSV files:

```php
public function export(Request $request)
```

## Testing
Run the following command to execute the package's unit tests:

```bash
composer test
```

## Contributing
Contributions are welcome! Please fork the repository and submit a pull request.

## License
This package is open-source and available under the MIT license.