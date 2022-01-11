<?php

return [
    'document_name'  => 'LRD',
    'document_name'  => 'LOGOS API DOC',

    /*
    * Route where request docs will be served from
    * localhost:8080/request-docs
    */
    'url' => 'docs/api',
    'middlewares' => [
        //Example
        // \App\Http\Middleware\NotFoundWhenProduction::class,
    ],
    /**
     * Path to to static HTML if using command line.
     */
    'docs_path' => base_path('docs/api'),

    /**
     * Sorting route by and there is two types default(route methods), route_names.
     */
    'sort_by' => 'default',
    'sort_by' => 'route_names',

    'hide_matching' => [
        "#^telescope#",
        "#^docs#",
        "#^request-docs#",
        "#^admin#",
        "#^oauth#",
    ]
];
