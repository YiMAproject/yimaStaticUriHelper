<?php
use yimaStaticUriHelper\StaticUri;

return array(
    /**
     * Setter Class Options
     * it means:
     *   variables  - convert to -> setVariables(x)
     *   .
     *   .
     *   @see StaticUri
     */
    'statics.uri' => array(
        // Uri Variables
        'variables' => array(
            # 'client' => APP_PROFILE
        ),
        // Uri Paths
        'paths' => array(
            # 'Key.To.Static.Uri'  => '//cdn.raya-media.com/[or-file.ext]',
            # 'Key.To.Dynamic.Uri' => '//cdn.raya-media.com/$client/',
        ),
    ),
);
