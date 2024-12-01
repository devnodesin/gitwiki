
## Laravel Pint - code styling tool

```
# Dry run to see what would be changed
./vendor/bin/pint --dirty

# Preview changes without applying them
./vendor/bin/pint --test


# Fix code style for entire project
./vendor/bin/pint

# Fix specific files or directories
./vendor/bin/pint app/Models
./vendor/bin/pint app/Http/Controllers/UserController.php

```

# PHPStan - static analysis

```
# Analyze entire project
./vendor/bin/phpstan analyse

# Analyze specific directory
./vendor/bin/phpstan analyse app
```
