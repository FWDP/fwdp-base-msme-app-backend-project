<?php

namespace Database\Seeders;

use App\Models\User;
use App\Modules\Courses\Models\Course;
use App\Modules\Courses\Models\CourseEnrollment;
use App\Modules\Courses\Models\CourseProgress;
use App\Modules\Courses\Models\LessonCompletion;
use Illuminate\Database\Seeder;

class CourseEnrollmentSimulationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::where('role', 'MSME_USER')->get();
        $courses = Course::all();

        $selectedUsers = $users->shuffle()->take(10);

        foreach ($selectedUsers as $user) {
            $assignedCourses = $courses->shuffle()->take(rand(1, 3));

            foreach ($assignedCourses as $course) {
                $enrollment = CourseEnrollment::firstOrCreate([
                    'course_id' => $course->id,
                    'user_id' => $user->id
                ],[
                    'enroll_date' => now()->subDays(rand(1, 14)),
                ]);

                CourseProgress::firstOrCreate([
                    'course_id' => $course->id,
                    'user_id' => $user->id,
                ], [
                    'percentage' => 0,
                    'last_updated_at' => now()
                ]);

                $lessons = $course->lessons()->get();
                $lessonsCount = $lessons->count();

                if ($lessonsCount > 0) {
                    $lessonsToComplete = $lessons
                        ->shuffle()
                        ->take(rand(0, $lessonsCount));
                } else {
                    $lessonsToComplete = collect(); // empty set
                }

                foreach ($lessonsToComplete as $lesson) {
                    LessonCompletion::firstOrCreate(
                        [
                            'lesson_id' => $lesson->id,
                            'user_id'   => $user->id,
                            'course_id' => $course->id,
                        ],
                        [
                            'completed_at' => now()->subDays(rand(0, 7)),
                        ]
                    );
                }
                $total = $lessonsCount;

                $completed = LessonCompletion::whereHas('lesson', function ($q) use ($course) {
                    $q->where('course_id', $course->id);
                })
                    ->where('user_id', $user->id)
                    ->count();

                $percent = $total > 0
                    ? (int) round(($completed / $total) * 100)
                    : 0;

                CourseProgress::updateOrCreate(
                    [
                        'course_id' => $course->id,
                        'user_id'   => $user->id,
                    ],
                    [
                        'completed_lessons' => $completed,
                        'percentage' => $percent,
                        'last_updated_at'  => now(),
                    ]
                );

                // If fully completed, mark enrollment completed
                if ($percent >= 100) {
                    $enrollment->update([
                        'completed_at' => now(),
                    ]);

                    CourseProgress::where([
                       'course_id' => $course->id,
                       'user_id'   => $user->id,
                    ])->update([
                        'last_updated_at' => now(),
                    ]);
                }

            }
        }
    }
}
