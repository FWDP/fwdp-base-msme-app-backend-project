<?php

return [
    App\Providers\AppServiceProvider::class,
    App\Modules\Membership\Providers\MembershipServiceProvider::class,
    App\Modules\Membership\Providers\AuthServiceProvider::class,
    App\Modules\Profile\Providers\ProfileServiceProvider::class,
    App\Modules\Courses\Providers\CoursesServiceProvider::class,
];
