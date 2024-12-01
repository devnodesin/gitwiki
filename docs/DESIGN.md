
# Authentication: 

* using email/password login, with remember me
* user roles: admin, user
* Add artisan command 
    * adding a new user "php artisan hub:useradd"
    * deleting a user "php artisan hub:userdel {email}"
    * password reset "php artisan hub:passwd {email}"
* No email verification, No registration & No password reset option
* Routes:
    * / - (guest, route name: home) this is login page
    * /login (guest, route name: login)
    * /logout (authenticated, route name: logout)
    * /admin (authenticated, route name: dashboard)
* No other features are currently planned for the authentication system.

# Wiki

* File backed storage
* Markdown file are stored in /storage/git/wiki/pages
* Images are stored in /storage/git/wiki/images
* Use CommonMark to render markdown
* Routes: (prefix /admin/wiki)
    * /admin/wiki/ - (route name: wiki) index should list all pages grouped by folder.
    * /admin/wiki/{page} - (route name: wiki.page) view page 
 
# GitService

* git repository to clone
* Ablity to push a git repository
* show git log