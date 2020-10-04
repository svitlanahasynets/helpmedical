<?php

return [
    'menu' => [
        'B' => [
            'dashboard' => [
                'title' => '첫페지',
                'icon' => 'home'
            ],
            'schedule' => [
                'title' => '경기일정편집',
                'icon' => 'calendar',
            ],
            'result' => [
                'title' => '경기결과편집',
                'icon' => 'trophy',
            ],
        ],
        'C' => [
            'dashboard' => [
                'title' => '첫페지',
                'icon' => 'home'
            ],
            'schedule' => [
                'title' => '경기일정편집',
                'icon' => 'calendar',
            ],
            'schedule.schedule_view' => [
                'title' => '경기일정보기',
                'proxy' => 'schedule',
                'hidden' => true
            ],
            'schedule.schedule_edit' => [
                'title' => '경기일정편집',
                'proxy' => 'schedule',
                'hidden' => true
            ],
            'result' => [
                'title' => '경기결과편집',
                'icon' => 'trophy',
            ],

            'employee.index' => [
                'title' => '종업원관리',
                'icon' => 'users',
            ],
            'employee.edit' => [
                'title' => '종업원편집',
                'proxy' => 'employee.index',
                'hidden' => true
            ], 
            'profile' => [
                'title' => '관리자정보',
                'icon' => 'user',
            ],
        ],
        'M' => [
            'dashboard' => [
                'title' => '첫페지',
                'icon' => 'home'
            ],
            'schedule' => [
                'title' => '경기일정편집',
                'icon' => 'calendar',
            ],
            'schedule.schedule_view' => [
                'title' => '경기일정보기',
                'proxy' => 'schedule',
                'hidden' => true
            ],
            'schedule.schedule_edit' => [
                'title' => '경기일정편집',
                'proxy' => 'schedule',
                'hidden' => true
            ],
            'result' => [
                'title' => '경기결과편집',
                'icon' => 'trophy',
            ],

            'employee.index' => [
                'title' => '종업원관리',
                'icon' => 'users',
            ],
            'employee.edit' => [
                'title' => '종업원편집',
                'proxy' => 'employee.index',
                'hidden' => true
            ], 
            'profile' => [
                'title' => '관리자정보',
                'icon' => 'user',
            ],
        ],
        'D' => [
            'dashboard' => [
                'title' => '첫페지',
                'icon' => 'home'
            ],
            'profile' => [
                'title' => '관리자정보',
                'icon' => 'user',
            ],
        ],
    ]
];
