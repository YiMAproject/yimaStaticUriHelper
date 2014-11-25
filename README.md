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
      'statics.uri' => array(
          'Twitter.Bootstrap' => '//cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.1.0/js/bootstrap.min.js',
      ),
      // ...
  );
 ```
 ```php
 /*
  * Set from any where that we can get helper object
  */
 $staticsUri = $this->staticsUri('self'); // from within view you can get self object like this
 $staticsUri->setPath('Key.Of.Uri', 'uri/path/to/target');

 ```

#### Stored Key Path
 ```php
 echo $this->staticsUri('Twitter.Bootstrap');
 // output: //cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.1.0/js/bootstrap.min.js

 ```

#### Default Stored Key Path
 ```php
 echo $this->staticsUri('basepath');  // output in exp.: /app_dir
 echo $this->staticsUri('serverurl'); // output in exp.: http://raya-media.com/

 ```

#### Generate Dynamic Uri
 ```php
 $this->staticsUri('self')
    ->setPath('ondemand.rayamedia.server', '$protocol://raya-media.com');

 echo $this->staticsUri('ondemand/rayamedia/server', array('protocol' => 'http'));
 // output: http://raya-media.com

 ```

#### Generate Dynamic Uri With Default Values

```$basepath``` and ```$serverurl``` are default values.

 ```php
 $siteUser = 'payam';
 $this->staticsUri('self')
    ->setVariable('user', $siteUser); // register default variable

 // this uri not registered path
 // we also can use this for registered path
 echo $this->staticsUri(
    '$basepath/www/$user/some/$folder',
    array('folder' => 'media')
 );
 // output: /app_dir/www/payam/some/media

 echo $this->staticsUri(
     '$basepath/www/$user/some/$folder',
     array('basepath' => '/alterpath', 'folder' => 'media')
  );
 // output: /alterpath/www/payam/some/media


 /* Pass variables by order
  * in this format helper don't support default variables
  */
 echo $this->staticsUri(
     '$var1/www/$var2/some/$var3',
     '/alterpath', $user, $media
  );
 // output: /alterpath/www/payam/some/media

 ```

Installation
-----------

Composer installation:

require ```rayamedia/yima-static-uri-helper``` in your ```composer.json```

Or clone to modules folder

Enable module in application config


## Support ##
To report bugs or request features, please visit the [Issue Tracker](https://github.com/RayaMedia/yimastaticsUriHelper/wiki).

*Please feel free to contribute with new issues, requests and code fixes or new features.*
