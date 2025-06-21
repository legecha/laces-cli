# Laces CLI

A PHP CLI tool that automates the creation and maintenance of [Laces - a custom Laravel starter kit](https://github.com/legecha/laces) based on the official [Livewire starter kit](https://github.com/laravel/livewire-starter-kit).

## What this CLI does

- Bootstraps a fresh Laravel project using the official Livewire starter kit
- Applies custom modifications from the `legecha/laces` repository in modular steps
- Runs tests to confirm integrity
- Force-pushes the final result with atomic commits
- Tags the version for Packagist release

## Requirements

- PHP 8.2+
- Composer
- Git
- Laravel installer globally available (optional)

## Usage

```bash
./bin/console laces:build [version]
```

- version: (optional) Laravel version to target (e.g. 12.7.0) or leave blank for latest stable
