# CS-Cart Workbench
CS-Cart Command Line Tool for Developing Addons.

## Purposes
This is based on the original [CS-Cart SDK](https://github.com/cscart/sdk) created by CS-Cart.
This tool is more opinionated with a set of addon stub files to get things going very fast.
At present this is for my own needs to the addon stub files contain my employers branding for my own convenience.

## Usage
### Installing
You'll need [Composer](https://getcomposer.org) installed in your system. Check out its [installation guide](https://getcomposer.org/doc/00-intro.md#globally) if you haven't done that before.

When the Composer is installed, just execute this command in your console:
```bash
$ composer global require "grumpysi/cscart-workbench:*"
```

### Executing commands

```bash
$ bench command:name
```

### Command list

##### new
Scaffold a new addon in the workbench folder, allowing you to develop and store add-on files in a separate Git repository.

```
$ bench new --help
Usage:
  new [options] [--] <name> 

Arguments:
  name                       Add-on ID (name)

Options:
  -h, --help                 Display this help message
  -q, --quiet                Do not output any message
  -V, --version              Display this application version
      --ansi                 Force ANSI output
      --no-ansi              Disable ANSI output
  -n, --no-interaction       Do not ask any interactive question
  -v|vv|vvv, --verbose       Increase the verbosity of messages: 1 for normal output, 2 for more verbose output and 3 for debug

Help:
 Scaffold a new addon in the workbench folder, allowing you to develop and store add-on files in a separate Git repository.
```

#### Preparing local development environment

After cloning the forked repository, you'll want to be able to run the `bench` command to test things locally.
In order to do that, you'll need to install the Composer package from local path.

Add these lines to your global composer configuration file located at `~/.composer/composer.json` path:
```json
{
    "repositories": [
        {
            "type": "path",
            "url": "/path/to/cloned/repository/directory"
        }
    ],
    "require": {
        "cscart/sdk": "*"
    }
}
```
Don't forget to specify path to the correct directory where you cloned your fork of a repo.

After that, execute this command:

```sh
$ composer global require "grumpysi/cscart-workbench:*"
```

You need to do this only once; there is no need to re-install the local package every time you make a change in code. Directory with forked repository will be symlinked to your globally installed Composer packages directory.

You're now can test your changes by executing globally available `bench` command.

