<?php

return [
    /**
     * Glide
     */
    'render/<path:[\w\/\.]+>' => 'glide/default/render',

    '<module:[\w\-]+>' => '<module>',
    '<module:[\w\-]+>/<controller:[\w\-]+>' => '<module>/<controller>',
    '<module:[\w\-]+>/<controller:[\w\-]+>/<action:[\w\-]+>/<p:\d+>/<per:\d+>' => '<module>/<controller>/<action>',
    '<module:[\w\-]+>/<controller:[\w\-]+>/<action:[\w\-]+>/<id:\d+>' => '<module>/<controller>/<action>',
    '<module:[\w\-]+>/<controller:[\w\-]+>/<action:[\w\-]+>' => '<module>/<controller>/<action>',
];