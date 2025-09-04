<?php
return [
    [
        'title' => '조직',
        'url' => '/organization',
        'active' => request()->is('organization') || request()->is('organization/*')
    ]
];
