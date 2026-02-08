<?php

return [
    App\Providers\AppServiceProvider::class,
    \App\Core\Membership\Providers\MembershipServiceProvider::class,
    \App\Core\Membership\Providers\AuthServiceProvider::class,
    \App\Core\Profile\Providers\ProfileServiceProvider::class,
    App\Modules\Courses\Providers\CoursesServiceProvider::class,
];
