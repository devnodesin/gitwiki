# About GitWiki

**GitWiki** is a secure, Laravel-based wiki application tailored for small and medium-sized teams to securely sharing content among team members. It features authentication, secure access, Git-based storage.

## Key Features

- **Git-Backed Storage:** All content is version-controlled using Git, ensuring a reliable and trackable history of changes.
- **Secure Access:** Content is accessible only after user authentication, ensuring that only authorized users can view the information.
- **Read-Only Interface:** The web interface is read-only, preventing direct edits and maintaining content integrity.
- **Git Integration:** Users can pull Git repositories on demand, keeping the content up-to-date with the latest changes.
- **Markdown Rendering:** The application renders markdown files from the git directory, providing a clean and readable format for documentation.


## Technical Details

- Built with Laravel 11 PHP Framework
- Configurable as Public or Private wiki
- Pages can be protected
- Content stored in `/storage/git` directory
- Git-based version control for content management

## Requirements

1. PHP 8.2+
1. Nodejs

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
