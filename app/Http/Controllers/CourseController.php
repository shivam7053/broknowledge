<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Topic;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    public function show(Course $course, ?Topic $topic = null)
    {
        // Load topics for the sidebar, ordered by our sort_order
        $course->load(['topics' => fn($query) => $query->orderBy('sort_order')]);

        // If no specific topic is requested, show the first one
        if (!$topic) {
            $topic = $course->topics->first();
        }

        return view('courses.show', [
            'course' => $course,
            'topics' => $course->topics,
            'currentTopic' => $topic,
        ]);
    }
}