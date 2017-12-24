CURD operation using Facebook Graph API

## Technology Stack

* PHP
* CodeIgniter
* codeigniter-restserver
* php-graph-sdk

## Deployment

Step 1:
Clone this repo
```
https://github.com/kalyanakannan/facebook-page-app.git
```


step 2:
Config facebook app key in config file *config/facebook.php*

```
// facebook app id
$config['app_id'] = 'your facebook app id';

// facebook app secret
$config['app_secret'] = 'your facebook app secret';

// defaukt facebook grap api version
$config['default_graph_version'] = 'v2.10';

//default access token
$config['access_token'] = 'your default access token';

$config['days'] = 30;
```


step 3:
Run migration
```
http://localhost/{project-folder}/index.php/migrate/index/1
```


step 4:
Run the apllication
```
http://localhost/{project-folder}/
```

## API URLS
```
base url = http://localhost/{project-folder}/index.php
```

for getting post from facebook page
```
post api/getData

parametter

page_id
```

for deleting post
```
post api/deletePost

parametter

post_id
```

for updatePost post
```
post api/updatePost

parametter

post_id
title
description
```

for get all post
```
get api/fetchData

querystring parametter

posts_count
comment_count
sort_by
```

for search based on description
```
get api/searchPosts

querystring parametter

q
```

