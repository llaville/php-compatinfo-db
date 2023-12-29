<!-- markdownlint-disable MD013 -->
# Console CLI

```text
Database handler for CompatInfo version 6.1.0

Usage:
  command [options] [arguments]

Options:
  -h, --help            Display help for the given command. When no command is given display help for the list command
  -q, --quiet           Do not output any message
  -V, --version         Display this application version
      --ansi|--no-ansi  Force (or disable --no-ansi) ANSI output
  -n, --no-interaction  Do not ask any interactive question
  -c, --config=CONFIG   Read configuration from PHP file
      --profile         Display timing and memory usage information
  -v|vv|vvv, --verbose  Increase the verbosity of messages: 1 for normal output, 2 for more verbose output and 3 for debug

Available commands:
  about        Shows short information about this package
  completion   Dump the shell completion script
  diagnose     Diagnoses the system to identify common errors
  doctor       Checks the current installation
  help         Display help for a command
  list         List commands
 db
  db:create    Create the database schema
  db:diagram   Draws ER diagram of the database
  db:init      Load JSON file(s) into database
  db:list      List all references supported in the Database
  db:new       Create the database schema and load its contents from JSON files
  db:polyfill  Add new Polyfill elements
  db:show      Show details of a reference supported in the Database
```
