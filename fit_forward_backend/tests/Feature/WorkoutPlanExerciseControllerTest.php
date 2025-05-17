<?php

namespace Tests\Feature;

use App\Models\BmiCategory;
use App\Models\Exercise;
use App\Models\WorkoutPlan;
use App\Models\WorkoutPlanExercise;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class WorkoutPlanExerciseControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /**
     * Setup for tests.
     */
    protected function setUp(): void
    {
        parent::setUp();
        
        // Disable middleware for these tests
        $this->withoutMiddleware();
    }

    /**
     * Create a test BMI category.
     *
     * @return \App\Models\BmiCategory
     */
    private function createTestBmiCategory(): BmiCategory
    {
        return BmiCategory::create([
            'name' => 'Normal',
            'min' => 18.5,
            'max' => 24.9,
        ]);
    }

    /**
     * Create a test workout plan.
     *
     * @return \App\Models\WorkoutPlan
     */
    private function createTestWorkoutPlan(): WorkoutPlan
    {
        $bmiCategory = $this->createTestBmiCategory();
        
        return WorkoutPlan::create([
            'bmi_category_id' => $bmiCategory->id,
            'title' => 'Test Workout Plan',
            'description' => '<p>This is a test workout plan description</p>',
        ]);
    }

    /**
     * Create a test exercise.
     *
     * @return \App\Models\Exercise
     */
    private function createTestExercise(): Exercise
    {
        return Exercise::create([
            'name' => 'Test Exercise',
            'instructions' => 'Test instructions',
            'video_url' => 'https://www.youtube.com/watch?v=test',
            'image' => 'exercises/test.jpg'
        ]);
    }

    /**
     * Create a test workout plan exercise.
     *
     * @param int|null $workoutPlanId
     * @param int|null $exerciseId
     * @return \App\Models\WorkoutPlanExercise
     */
    private function createTestWorkoutPlanExercise(?int $workoutPlanId = null, ?int $exerciseId = null): WorkoutPlanExercise
    {
        if (!$workoutPlanId) {
            $workoutPlan = $this->createTestWorkoutPlan();
            $workoutPlanId = $workoutPlan->id;
        }

        if (!$exerciseId) {
            $exercise = $this->createTestExercise();
            $exerciseId = $exercise->id;
        }

        return WorkoutPlanExercise::create([
            'workout_plan_id' => $workoutPlanId,
            'exercise_id' => $exerciseId,
            'sets' => 3,
        ]);
    }

    /**
     * Test index method displays all workout plan exercises.
     *
     * @return void
     */
    public function test_index_displays_workout_plan_exercises()
    {
        $workoutPlan = $this->createTestWorkoutPlan();
        $this->createTestWorkoutPlanExercise($workoutPlan->id);
        $this->createTestWorkoutPlanExercise($workoutPlan->id);

        $response = $this->get(route('workout_plan_exercises.index', $workoutPlan->id));

        $response->assertStatus(200);
        $response->assertViewIs('workout_plan_exercises.index');
        $response->assertViewHas('workout_plan_exercises');
        $response->assertViewHas('id');
    }

    /**
     * Test store method creates a new workout plan exercise.
     *
     * @return void
     */
    public function test_store_creates_workout_plan_exercise()
    {
        $workoutPlan = $this->createTestWorkoutPlan();
        $exercise = $this->createTestExercise();

        $workoutPlanExerciseData = [
            'workout_plan_id' => $workoutPlan->id,
            'exercise_id' => $exercise->id,
            'sets' => 4,
        ];

        $response = $this->post(route('workout_plan_exercises.store', $workoutPlan->id), $workoutPlanExerciseData);

        $response->assertRedirect(route('workout_plan_exercises.index', $workoutPlan->id));
        $response->assertSessionHas('success', 'Workout Plan Exercise created successfully.');

        $this->assertDatabaseHas('workout_plan_exercises', [
            'workout_plan_id' => $workoutPlan->id,
            'exercise_id' => $exercise->id,
            'sets' => 4,
        ]);
    }

    /**
     * Test validation rules when creating a workout plan exercise.
     *
     * @return void
     */
    public function test_validation_rules_when_creating_workout_plan_exercise()
    {
        $workoutPlan = $this->createTestWorkoutPlan();

        $response = $this->post(route('workout_plan_exercises.store', $workoutPlan->id), [
            'workout_plan_id' => '',
            'exercise_id' => '',
            'sets' => '',
        ]);

        $response->assertSessionHasErrors(['workout_plan_id', 'exercise_id', 'sets']);
    }

    /**
     * Test that exercises can be added to multiple workout plans.
     *
     * @return void
     */
    public function test_exercises_can_be_added_to_multiple_workout_plans()
    {
        $workoutPlan1 = $this->createTestWorkoutPlan();
        $workoutPlan2 = $this->createTestWorkoutPlan();
        $exercise = $this->createTestExercise();

        $workoutPlanExercise1 = WorkoutPlanExercise::create([
            'workout_plan_id' => $workoutPlan1->id,
            'exercise_id' => $exercise->id,
            'sets' => 3,
        ]);

        $workoutPlanExercise2 = WorkoutPlanExercise::create([
            'workout_plan_id' => $workoutPlan2->id,
            'exercise_id' => $exercise->id,
            'sets' => 4,
        ]);

        $this->assertDatabaseHas('workout_plan_exercises', [
            'id' => $workoutPlanExercise1->id,
            'workout_plan_id' => $workoutPlan1->id,
            'exercise_id' => $exercise->id,
        ]);

        $this->assertDatabaseHas('workout_plan_exercises', [
            'id' => $workoutPlanExercise2->id,
            'workout_plan_id' => $workoutPlan2->id,
            'exercise_id' => $exercise->id,
        ]);
    }

    /**
     * Test that a workout plan can have multiple exercises.
     *
     * @return void
     */
    public function test_workout_plan_can_have_multiple_exercises()
    {
        $workoutPlan = $this->createTestWorkoutPlan();
        $exercise1 = $this->createTestExercise();
        $exercise2 = Exercise::create([
            'name' => 'Another Exercise',
            'instructions' => 'More test instructions',
            'video_url' => 'https://www.youtube.com/watch?v=test2',
            'image' => 'exercises/test2.jpg'
        ]);

        WorkoutPlanExercise::create([
            'workout_plan_id' => $workoutPlan->id,
            'exercise_id' => $exercise1->id,
            'sets' => 3,
        ]);

        WorkoutPlanExercise::create([
            'workout_plan_id' => $workoutPlan->id,
            'exercise_id' => $exercise2->id,
            'sets' => 4,
        ]);

        $response = $this->get(route('workout_plan_exercises.index', $workoutPlan->id));
        $response->assertStatus(200);
        
        $workoutPlanExercises = $response->viewData('workout_plan_exercises');
        $this->assertEquals(2, $workoutPlanExercises->count());
    }
}
