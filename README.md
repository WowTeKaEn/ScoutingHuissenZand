# Scouting Website

I'm gonna keep this short hoping that no one really ever needs to read this but me.

NPM and composer required.
Hopefully I designated all the dev requirements correctly otherwise you will have to figure that out for yourself.



---
### Initial setup

Assuming you are in the root folder:

```
npm install
composer install -d api 
```



---

### Front end

The front end requires to know where the backend is. This is defined in the .env files one for each state *production* & *development*: 

**.env.development**

```
VUE_APP_ROOT_API=http://192.168.178.109/
```

There is a built in php function which preloads some required information. This function can only be used on an actual php server so can only be used when the application is built. Because the transpiler changes all quotes into double ones  you have to replace them manually when pushing the website publically.



this:

```HTML
<div id="app" data-model="<?php require_once "./api/helpers/functions.php"; echo json_encode(getInfoPHP()) ?>"></div>
```

should be changed into:

```HTML
<div id="app" data-model='<?php require_once "./api/helpers/functions.php"; echo json_encode(getInfoPHP()) ?>'></div>
```



I do recommend doing this as it can positively reduce the amount of api calls by 30 percent depending on the pages visited.

---
### Back end

The back end requires a list of env settings that are loaded in through one file: env.php
Env.php should be created in `api/helpers/.`  The list of envs required are:

```php
<?php 

$_ENV["ADMIN_MAIL_TO"] = "admin@scoutinghuissenzand.nl";
$_ENV["ADMIN_NAME_TO"] = "Scouting Huissen Zand Admin";
$_ENV["ADMIN_MAIL_FROM"] = "noreply@scoutinghuissenzand.nl";
$_ENV["ADMIN_NAME_FROM"] = "Noreply Scouting Huissen Zand";
$_ENV["ADMIN_MAIL_PASSWORD"] = "PASSWORD";
$_ENV["MAX_MAILER_RETRIES"] = 5;
$_ENV["DATABASE_URL"] = "mysql:host=HOSTNAME;dbname=DATABASE";
$_ENV["DATABESE_USER"] = "USERNAME";
$_ENV["DATABASE_PASS"] = "PASSWORD";
$_ENV["CLOUDINARY_CLOUD_NAME"] = "CLOUDNAME";
$_ENV["CLOUDINARY_API_KEY"] = "API_KEY";
$_ENV["CLOUDINARY_API_SECRET"] = "API_SECRET";
$_ENV["YANDEX_OAUTH"] = "OAuth OAUTHKEY";
$_ENV["YANDEX_ORG_ID"] = "X-Org-ID YANDEX_ORG_ID";
$_ENV["CRON_USER"] = "CRONJOB_USERNAME"; // not essential
$_ENV["CRON_PASS"] = "CRONJOB_PASSWORD"; // not essential

```

For local development the following code is required in order to ensure there are no cors errors.

```php
header("Access-Control-Allow-Origin: http://localhost:8080");
header("Access-Control-Allow-Credentials: true");

$method = $_SERVER['REQUEST_METHOD'];
if ($method == "OPTIONS") {
    header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method,Access-Control-Request-Headers, Authorization, cache-control");
    header("Access-Control-Allow-Methods: DELETE, GET, POST, PUT, OPTIONS");

    header("HTTP/1.1 200 OK");
    die();
}
```

This code should be **removed** on **production**. I couldn't be bothered to write a script for it so this should suffice.

### NPM scripts

| Script              | Description                                                  |
| ------------------- | ------------------------------------------------------------ |
| serve               | standard vue-cli serve                                       |
| servep              | standard vue-cli serve in production mode                    |
| build               | standard vue-cli build                                       |
| publish "{MESSAGE}" | builds and commits with the given commit message             |
| postpublish         | pushes automatically after publish                           |
| nocommentpublish    | same as above only without commit message. **Not recommended** |
| lint                | standard vue-cli lint                                        |
| stage               | standard vue-cli build in development mode                   |

