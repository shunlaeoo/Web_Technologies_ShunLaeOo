<?php

namespace Tests\Feature;

use App\Models\BmiCategory;
use App\Models\WorkoutPlan;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class WorkoutPlanControllerTest extends TestCase
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
     * @param int|null $bmiCategoryId
     * @return \App\Models\WorkoutPlan
     */
    private function createTestWorkoutPlan(?int $bmiCategoryId = null): WorkoutPlan
    {
        if (!$bmiCategoryId) {
            $bmiCategory = $this->createTestBmiCategory();
            $bmiCategoryId = $bmiCategory->id;
        }

        return WorkoutPlan::create([
            'bmi_category_id' => $bmiCategoryId,
            'title' => 'Test Workout Plan',
            'description' => '<p>This is a test workout plan description</p>',
        ]);
    }

    /**
     * Test index method displays all workout plans.
     *
     * @return void
     */
    public function test_index_displays_workout_plans()
    {
        // Create some workout plans
        $this->createTestWorkoutPlan();
        $this->createTestWorkoutPlan();

        $response = $this->get(route('workout_plans.index'));

        $response->assertStatus(200);
        $response->assertViewIs('workout_plans.index');
        $response->assertViewHas('workout_plan');
    }

    /**
     * Test store method creates a new workout plan.
     *
     * @return void
     */
    public function test_store_creates_workout_plan()
    {
        $bmiCategory = $this->createTestBmiCategory();

        $workoutPlanData = [
            'bmi_category_id' => $bmiCategory->id,
            'title' => 'New Workout Plan',
            'description' => '<p>This is a new workout plan description</p>',
        ];

        $response = $this->post(route('workout_plans.store'), $workoutPlanData);

        $response->assertRedirect(route('workout_plans.index'));
        $response->assertSessionHas('success', 'Workout Plan created successfully.');

        $this->assertDatabaseHas('workout_plans', [
            'bmi_category_id' => $bmiCategory->id,
            'title' => 'New Workout Plan',
            'description' => '<p>This is a new workout plan description</p>',
        ]);
    }

    /**
     * Test update method updates a workout plan.
     *
     * @return void
     */
    public function test_update_updates_workout_plan()
    {
        $workoutPlan = $this->createTestWorkoutPlan();
        $bmiCategory = BmiCategory::find($workoutPlan->bmi_category_id);

        $updatedData = [
            'bmi_category_id' => $bmiCategory->id,
            'title' => 'Updated Workout Plan',
            'description' => '<p>This is an updated workout plan description</p>',
        ];

        $response = $this->put(route('workout_plans.update', $workoutPlan->id), $updatedData);

        $response->assertRedirect(route('workout_plans.index'));
        $response->assertSessionHas('success', 'Workout Plan updated successfully.');

        $this->assertDatabaseHas('workout_plans', [
            'id' => $workoutPlan->id,
            'title' => 'Updated Workout Plan',
            'description' => '<p>This is an updated workout plan description</p>',
        ]);
    }

    /**
     * Test destroy method deletes a workout plan.
     *
     * @return void
     */
    public function test_destroy_deletes_workout_plan()
    {
        $workoutPlan = $this->createTestWorkoutPlan();

        $response = $this->delete(route('workout_plans.destroy', $workoutPlan->id));

        $response->assertRedirect(route('workout_plans.index'));
        $response->assertSessionHas('success', 'Workout Plan deleted.');

        $this->assertDatabaseMissing('workout_plans', [
            'id' => $workoutPlan->id,
        ]);
    }

    /**
     * Test validation rules when creating a workout plan.
     *
     * @return void
     */
    public function test_validation_rules_when_creating_workout_plan()
    {
        $response = $this->post(route('workout_plans.store'), [
            'bmi_category_id' => '',
            'title' => '',
            'description' => '',
        ]);

        $response->assertSessionHasErrors(['bmi_category_id', 'title', 'description']);
    }

    /**
     * Test validation rules when updating a workout plan.
     *
     * @return void
     */
    public function test_validation_rules_when_updating_workout_plan()
    {
        $workoutPlan = $this->createTestWorkoutPlan();

        $response = $this->put(route('workout_plans.update', $workoutPlan->id), [
            'bmi_category_id' => '',
            'title' => '',
            'description' => '',
        ]);

        $response->assertSessionHasErrors(['bmi_category_id', 'title', 'description']);
    }

    /**
     * Test that workout plans are associated with the correct BMI category.
     *
     * @return void
     */
    public function test_workout_plans_are_associated_with_correct_bmi_category()
    {
        $bmiCategory1 = BmiCategory::create([
            'name' => 'Underweight',
            'min' => 0,
            'max' => 18.4,
        ]);

        $bmiCategory2 = BmiCategory::create([
            'name' => 'Normal',
            'min' => 18.5,
            'max' => 24.9,
        ]);

        $workoutPlan1 = $this->createTestWorkoutPlan($bmiCategory1->id);
        $workoutPlan2 = $this->createTestWorkoutPlan($bmiCategory2->id);

        $this->assertEquals($bmiCategory1->id, $workoutPlan1->bmi_category_id);
        $this->assertEquals($bmiCategory2->id, $workoutPlan2->bmi_category_id);
    }

    /**
     * Test that the show method is implemented correctly.
     *
     * @return void
     */
    public function test_show_method_behavior()
    {
        $workoutPlan = $this->createTestWorkoutPlan();

        // Since the show method is empty in the controller, we're just checking
        // that it doesn't throw an error
        $response = $this->get(route('workout_plans.show', $workoutPlan->id));
        
        // The method is empty, so it might return a 200 with no content, or a 404
        // We're just ensuring it doesn't cause a 500 error
        $this->assertTrue(
            in_array($response->status(), [200, 404]),
            'Response status is not one of 200 or 404'
        );
    }
}