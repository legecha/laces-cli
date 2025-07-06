# Laces CLI

> A PHP CLI tool that automates the creation and maintenance of [the Laces starter kit](https://github.com/legecha/laces), an opinionated set of improvements to [the official Laravel starter kit for Livewire](https://github.com/laravel/livewire-starter-kit).

## What this CLI does

- Bootstraps a fresh Laravel project using the official Livewire starter kit
- Applies [various improvements](https://github.com/legecha/laces?tab=readme-ov-file#features) in modular steps
- Force-pushes the final result with atomic commits so inspecting the `legecha/laces` commit history will show exactly the differences between the official starter kit
- Prepares for Packagist release

## Requirements

- PHP 8.2+
- Composer
- Git
- Laravel installer globally available

### Optional

- Flux UI Pro subscription

## Usage

```bash
./bin/laces build
```

### Actions

All the modifications to the official Livewire starter kit are done through actions and are also provided through their own commands too. The build script calls each action as required, or you can cherry pick what you want.

```
Available commands:
  build                        Builds and publishes the Laces starter kit
  list                         List commands
 prepare
  prepare:dependencies         Checks that required dependencies are installed
  prepare:install              Installs Laravel with Livewire Starter Kit to the .working directory
  prepare:laces-versions       Get the latest Laravel and Livewire Starter Kit versions that Laces uses
  prepare:laravel-version      Get the latest Laravel version
  prepare:starter-kit-version  Get the latest Livewire Starter Kit version
  prepare:working-folder       Sets up the temporary working folder
 process
  process:config               Setup config
  process:duster               Install Duster
  process:flux                 Install Flux Pro
  process:password             Strengthen password requirements
  process:prettier             Install Prettier
  process:strict-types         Enforce strict types on all PHP files
  process:testing              Improve testing setup
  process:version              Update Laces versions
  process:views                Improve default views
  process:workflows            Remove the GitHub workflows
```
