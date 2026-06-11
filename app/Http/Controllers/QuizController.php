<?php

namespace App\Http\Controllers;

use App\Models\Quiz;
use App\Models\QuizCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class QuizController extends Controller
{
    /**
     * Display a listing of quiz categories.
     */
    public function index()
    {
        $categories = QuizCategory::all();
        return view('quizzes.categories', compact('categories'));
    }

    /**
     * Display quizzes within a specific category.
     */
    public function showCategoryQuizzes(QuizCategory $quizCategory)
    {
        $quizzes = $quizCategory->quizzes()->get();
        return view('quizzes.show-category-quizzes', compact('quizCategory', 'quizzes'));
    }

    /**
     * Display the quiz taking interface.
     */
    public function takeQuiz(Quiz $quiz)
    {
        // Eager load questions and shuffle them for the test
        $questions = $quiz->questions()->inRandomOrder()->get()->map(function ($question) {
            // Shuffle options if they are an associative array
            $options = $question->options;
            shuffle($options); // Shuffle the order of options
            $question->options = $options;
            return $question;
        });
        return view('quizzes.take', compact('quiz', 'questions'));
    }

    /**
     * Handle quiz submission and calculate score.
     */
    public function submitQuiz(Request $request, Quiz $quiz)
    {
        // Validate the incoming request data
        $request->validate([
            'userAnswers' => 'required|array',
            'userAnswers.*' => 'nullable|string|max:1', // Assuming option keys are single characters
        ]);

        $userAnswers = $request->input('userAnswers'); // This will be an object: {question_id: 'selected_option_key'}

        // Fetch correct answers for all questions in this quiz
        $correctAnswers = $quiz->questions()->pluck('correct_option', 'id')->toArray();

        $score = 0;
        $totalQuestions = count($correctAnswers);
        $results = []; // Optional: to store detailed results per question

        foreach ($correctAnswers as $questionId => $correctOption) {
            $selectedAnswer = $userAnswers[$questionId] ?? null;
            $isCorrect = ($selectedAnswer === $correctOption);

            if ($isCorrect) {
                $score++;
            }
            // Optionally store $results[$questionId] = ['selected_answer' => $selectedAnswer, 'correct_answer' => $correctOption, 'is_correct' => $isCorrect];
        }

        // You might want to store these results in a database table (e.g., QuizResult)
        return response()->json(['score' => $score, 'total_questions' => $totalQuestions, 'message' => 'Quiz submitted successfully!']);
    }
}