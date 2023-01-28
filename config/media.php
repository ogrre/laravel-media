<?php

return [
    'models' => [

        /*
         *
         */

        'media' => Ogrre\Media\Models\Media::class,

        /*
         *
         */

        'media_file' => Ogrre\Media\Models\MediaFile::class,

    ],

    'attributes' => [

        /*
         * name
        */

        'name' => 'media',

        /*
         * mime type
        */

        'mime_type' => ['png', 'jpg'],

        /*
         * storage disk
        */

        'disk' => 'public'
    ]
];
