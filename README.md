Static Uri Helper Module
==============

*this module is part of Yima Application Framework*

Why this module?
------------

#### Configurable Statics Assets

Modules that need some assets can use a key for asset and url for source.
 ```php
 /*
  * Set from merged config
  */
  return array(
      'static_uri_helper' => array(
          'Twitter.Bootstrap' => '//cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.1.0/js/bootstrap.min.js',
      ),
      // ...
  );
 ```
 ```php
 /*
  * Set from any where that we can get helper object
  */
 $staticUri = $this->staticUri('self'); // from within view you can get self object like this
 $staticUri->setPath('Key.Of.Uri', 'uri/path/to/target');

 ```

#### Stored Key Path
 ```php
 echo $this->staticUri('Twitter.Bootstrap');
 // output: //cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.1.0/js/bootstrap.min.js

 ```

#### Default Stored Key Path
 ```php
 echo $this->staticUri('basepath');  // output in exp.: /app_dir
 echo $this->staticUri('serverurl'); // output in exp.: http://raya-media.com/

 ```

#### Generate Dynamic Uri
 ```php
 $this->staticUri('self')
    ->setPath('ondemand.rayamedia.server', '$protocol://raya-media.com');

 echo $this->staticUri('ondemand/rayamedia/server', array('protocol' => 'http'));
 // output: http://raya-media.com

 ```

#### Generate Dynamic Uri With Default Values

```$basepath`` and ```$serverurl``` are default values.

 ```php
 $siteUser = 'payam';
 $this->staticUri('self')
    ->setVariable('user', $siteUser); // register default variable

 // this uri not registered path
 // we also can use this for registered path
 echo $this->staticUri(
    '$basepath/www/$user/some/$folder',
    array('folder' => 'media')
 );
 // output: /app_dir/www/payam/media

 ```

Installation
-----------

Composer installation:

require ```rayamedia/yima-static-uri-helper``` in your ```composer.json```

Or clone to modules folder

Enable module in application config


## Support ##
To report bugs or request features, please visit the [Issue Tracker](https://github.com/RayaMedia/yimaStaticUriHelper/wiki).

*Please feel free to contribute with new issues, requests and code fixes or new features.*
