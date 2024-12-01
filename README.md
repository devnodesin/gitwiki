# GitWiki

A secure Laravel-based wiki application designed for small and medium-sized businesses (SMB) and teams to securely share content with team members. It features authentication, secure access, Git-based storage.

## Features

- **Git-Backed Storage**: All content is version-controlled using Git
- **Secure Access**: Content is only accessible after user authentication
- **Read-Only Interface**: No direct edit functionality in the web interface
- **Git Integration**: Ability to pull Git repositories on demand
- **Markdown Rendering**: Renders markdown files from `/storage/git` directory

## Technical Details

- Built with Laravel PHP Framework
- Content stored in `/storage/git` directory
- Authentication required for viewing wiki pages
- Git-based version control for content management

## Installation

1. Clone the repository
2. Run `composer install`
3. Run `php artisan migrate`
4. Run `php artisan db:seed`

## Configuration

The application can be configured using environment variables:

```env
APP_NAME=hub
APP_URL=http://hub.test
```

## Security

- Authentication required for all wiki pages
- Content secured in Git repositories
- No direct content modification through web interface

## Storage Structure

Wiki content is stored in:
```
/storage/git/
```

## License

This software is licensed under the MIT License (MIT). See the LICENSE file for details.