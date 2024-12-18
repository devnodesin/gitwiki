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

## Requirements

1. PHP 8.2+

## Installation

- Clone the repository
- Run `composer install`
- Install nodejs packages and build

refer <https://nodejs.org/en/download/package-manager> for nodejs install instructions

```
npm install
npm run build
```

- Create .env file

```
cp .env.example .env
```

- Setup laravel

```
php artisan key:generate
php artisan migrate
php artisan db:seed
```

## Git Repository Management

GitWiki provides command-line tools to manage your Git repositories:

```
# Clone a repository (defaults to 'main' branch)
php artisan wiki:clone https://github.com/devnodesin/gitwiki-doc.git

# Clone with specific branch
php artisan wiki:clone https://github.com/user/repo.git --branch=develop

# Pull latest changes
php artisan wiki:pull
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

## TODO

- [ ] Login rate limit
- [ ] Block image for nonlogged in users
- [ ] Settings to block search engine indexing
- [ ] Settings to block robots
- [ ] Settings to make wiki private

## Reference

- <https://www.cloudways.com/blog/install-laravel-on-server/>
