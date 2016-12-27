# File-Hosting-Script

A simple file hosting plugin that allows you to easily add file hosting to your site. This plugin is intended for people who want to have a place on their website where they can upload files. This is not intended for a full-fledged file hosting website which the sole purpose of said website is hosting files.

# Video 

<a href="http://www.youtube.com/watch?feature=player_embedded&v=uaWqvplSM3c" target="_blank"><img src="https://img.youtube.com/vi/uaWqvplSM3c/0.jpg" alt="Video" width="240" height="180" border="10" /></a>

# Requirements

* PHP 5.3 or newer
* MySQL Database 
* Webserver capable of having a custom document root

# Installation

1. Clone repository to local disk, we're going to need to configure your website's settings.
2. run **composer install** within the cloned directory.
3. Open up [config/config.php](https://github.com/joe-scotto/file-hosting/blob/master/config/config.php).
4. Configure your database settings on lines 4 - 13

    ```php
    $GLOBALS['config'] = [
        'database' => [
            'host' => 'db-host-name',
            'database' => 'db-name',
            'username' => 'db-username',
            'password' => 'db-password',
            'table' => 'table-name' // `users` is the default, change this value if you know what you want for your database name 
        ],
        'homeURL' => 'website-home-url' // URL to visit upon clicking "back to home" 
    ];
    ```

5. Configure your main admin on lines 15 - 20

    ```php 
    $admin = [
        'name' => ucwords(trim("first last")),
        'username' => "username",
        'password' => "password",
        'admin' => "1" // The first user should be an admin. 
    ];
    ```

6. Copy all files and folders except [/database](https://github.com/joe-scotto/file-hosting/tree/master/database) and [README.md](https://github.com/joe-scotto/file-hosting/blob/master/README.md) to your web server. 
7. Since the project is built on Slim, your web server must be configured to serve the [/public](https://github.com/joe-scotto/file-hosting/tree/master/public) directory by default. 
8. The last step is to visit the URL that you installed the plugin on and attempt to login with the credentials your setup within [config.php](https://github.com/joe-scotto/file-hosting/tree/master/config/config.php).

# Options

If you wish, you can add other users as either an admin or non-admin by visiting your **yoururl/admin**. In order to create other users, you must be an admin. 

# To-Do

- [x] Delete file or folder
- [x] Download Files
- [x] Differentiate files and folders via Bootstrap badge
- [x] Display number of files inside a folder
- [x] Add admin config
- [x] Automatically create "users" database if it doesn't exist

# Features to be added after initial release

- [ ] Add sorting to panel
- [ ] Download entire folders
- [ ] Add themes (Dark, Light, Etc)
- [ ] Rename files or folders
- [ ] Delete multiple at once
- [ ] Move files via checkbox
- [ ] Upload folder via input
- [ ] Upload progress indicator

# Sections to refactor / might need refactor

- [ ] Admin
- [ ] Create User (Admin)
- [ ] Delete Route
