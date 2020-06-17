<?php

return [
    '__name' => 'site-product-category',
    '__version' => '0.0.1',
    '__git' => 'git@github.com:getmim/site-product-category.git',
    '__license' => 'MIT',
    '__author' => [
        'name' => 'Iqbal Fauzi',
        'email' => 'iqbalfawz@gmail.com',
        'website' => 'http://iqbalfn.com/'
    ],
    '__files' => [
        'modules/site-product-category' => ['install','update','remove'],
        'theme/site/product-category' => ['install','remove'],
        'app/site-product-category/controller' => ['install','remove']
    ],
    '__dependencies' => [
        'required' => [
            [
                'product' => NULL
            ],
            [
                'product-category' => NULL
            ],
            [
                'site' => NULL
            ],
            [
                'site-meta' => NULL
            ],
            [
                'lib-formatter' => NULL
            ]
        ],
        'optional' => [
            [
                'lib-event' => NULL
            ],
            [
                'lib-cache-output' => NULL
            ]
        ]
    ],
    'autoload' => [
        'classes' => [
            'SiteProductCategory\\Controller' => [
                'type' => 'file',
                'base' => [
                    'app/site-product-category/controller',
                    'modules/site-product-category/controller'
                ]
            ],
            'SiteProductCategory\\Library' => [
                'type' => 'file',
                'base' => 'modules/site-product-category/library'
            ]
        ],
        'files' => []
    ],
    'routes' => [
        'site' => [
            'siteProductCategorySingle' => [
                'path' => [
                    'value' => '/product/category/(:slug)',
                    'params' => [
                        'slug' => 'slug'
                    ]
                ],
                'method' => 'GET',
                'handler' => 'SiteProductCategory\\Controller\\Category::single'
            ],
            'siteProductCategorySingleFeed' => [
                'path' => [
                    'value' => '/product/category/(:slug)/feed.xml',
                    'params' => [
                        'slug' => 'slug'
                    ]
                ],
                'method' => 'GET',
                'handler' => 'SiteProductCategory\\Controller\\Robot::feed'
            ]
        ]
    ],
    'libFormatter' => [
        'formats' => [
            'product-category' => [
                'page' => [
                    'type' => 'router',
                    'router' => [
                        'name' => 'siteProductCategorySingle',
                        'params' => [
                            'slug' => '$slug'
                        ]
                    ]
                ]
            ]
        ]
    ],
    'libEvent' => [
        'events' => [
            'product-category:created' => [
                'SiteProductCategory\\Library\\Event::clear' => TRUE
            ],
            'product-category:deleted' => [
                'SiteProductCategory\\Library\\Event::clear' => TRUE
            ],
            'product-category:updated' => [
                'SiteProductCategory\\Library\\Event::clear' => TRUE
            ]
        ]
    ],
    'site' => [
        'robot' => [
            'feed' => [
                'SiteProductCategory\\Library\\Robot::feed' => TRUE
            ],
            'sitemap' => [
                'SiteProductCategory\\Library\\Robot::sitemap' => TRUE
            ]
        ]
    ]
];