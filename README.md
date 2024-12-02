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

## Git Repository Management

GitWiki provides command-line tools to manage your Git repositories:

```bash
# Clone a repository (defaults to 'main' branch)
php artisan gitwiki:clone https://github.com/devnodesin/gitwiki-doc.git

# Clone with specific branch
php artisan gitwiki:clone https://github.com/user/repo.git --branch=develop

# Pull latest changes
php artisan gitwiki:pull
```

The repositories are stored in the `/storage/git` directory. Wiki pages and images should be organized as follows:
- Markdown files: `/storage/git/pages/`
- Images: `/storage/git/images/`

## Configuration

The application can be configured using environment variables:

```env
APP_NAME=GitWiki
APP_URL=http://gitwiki.test
```

## Security

- Authentication required for all wiki pages
- Content secured in Git repositories
- No direct content modification through web interface

## License

This software is licensed under the MIT License (MIT). See the LICENSE file for details.