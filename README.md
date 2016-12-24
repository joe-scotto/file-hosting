# File-Hosting-Script

A simple file hosting plugin that allows you to easily add file hosting to your site. This plugin is intended for people who want to have a place on their website where they can upload files. This is not intended for a full-fledged file hosting website which the sole purpose of said website is hosting files.

# Requirements

* PHP 5.3 or newer
* MySQL Database 

# Installation

1. Clone repository to local disk, we're going to need to configure your website's settings.
2. run **composer install** within the cloned directory.
3. Add the [database file](https://github.com/joe-scotto/file-hosting/blob/master/database/users.sql) to your database. This will soon no longer be necessary once I set it up to automatically create it upon the first launch.
4. Open up [config/config.php](https://github.com/joe-scotto/file-hosting/blob/master/config/config.php).
5. Configure your database settings on lines 4 - 12

    ```php
    $GLOBALS['config'] = [
        'database' => [
            'host' => '127.0.0.1',
            'database' => 'file-hosting',
            'username' => 'root',
            'password' => 'root'
        ],
        'homeURL' => 'website home url' // URL to visit upon clicking "back to home" 
    ];
    ```

6. Configure your main admin on lines 15 - 20

    ```php 
    $admin = [
        'name' => ucwords(trim("first last")),
        'username' => "username",
        'password' => "password",
        'admin' => "1" // The first user should be an admin. 
    ];
    ```

7. Copy all files and folders except [/database](https://github.com/joe-scotto/file-hosting/tree/master/database) and [README.md](https://github.com/joe-scotto/file-hosting/blob/master/README.md) to your web server. 
8. Since the project is built on Slim, your web server must be configured to serve the [/public](https://github.com/joe-scotto/file-hosting/tree/master/public) directory by default. 
9. The last step is to visit the URL that you installed the plugin on and attempt to login with the credentials your setup within [config.php](https://github.com/joe-scotto/file-hosting/tree/master/config/config.php).

# To-Do

- [x] Delete file or folder
- [x] Download Files
- [x] Differentiate files and folders via Bootstrap badge
- [x] Display number of files inside a folder
- [x] Add admin config
- [ ] Automatically create "users" database if it doesn't exist

# Features to be added after initial release

- [ ] Add sorting to panel
- [ ] Download entire folders1
- [ ] Add themes (Dark, Light, Etc)
- [ ] Rename files or folders
- [ ] Delete multiple at once
- [ ] Move files via checkbox
- [ ] Upload folder via input
- [ ] Upload progress indicator11

# Sections to refactor / might need refactor

- [ ] Admin
- [ ] Create User (Admin)
- [ ] Delete Route