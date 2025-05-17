<?php

namespace Tests\Feature;

use App\Models\BmiCategory;
use App\Models\MealPlan;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class MealPlanControllerTest extends TestCase
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
     * Create a test meal plan.
     *
     * @param int|null $bmiCategoryId
     * @return \App\Models\MealPlan
     */
    private function createTestMealPlan(?int $bmiCategoryId = null): MealPlan
    {
        if (!$bmiCategoryId) {
            $bmiCategory = $this->createTestBmiCategory();
            $bmiCategoryId = $bmiCategory->id;
        }

        return MealPlan::create([
            'bmi_category_id' => $bmiCategoryId,
            'protein' => 30,
            'carbs' => 50,
            'fats' => 20,
            'description' => '<p>Breakfast: Oatmeal with fruits</p><p>Lunch: Chicken salad</p>',
        ]);
    }

    /**
     * Test index method displays all meal plans.
     *
     * @return void
     */
    public function test_index_displays_meal_plans()
    {
        // Create some meal plans
        $this->createTestMealPlan();
        $this->createTestMealPlan();

        $response = $this->get(route('meal_plans.index'));

        $response->assertStatus(200);
        $response->assertViewIs('meal_plans.index');
        $response->assertViewHas('meal_plan');
    }

    /**
     * Test store method creates a new meal plan.
     *
     * @return void
     */
    public function test_store_creates_meal_plan()
    {
        $bmiCategory = $this->createTestBmiCategory();

        $mealPlanData = [
            'bmi_category_id' => $bmiCategory->id,
            'protein' => 30,
            'carbs' => 50,
            'fats' => 20,
            'description' => '<p>Breakfast: Oatmeal with fruits</p><p>Lunch: Chicken salad</p>',
        ];

        $response = $this->post(route('meal_plans.store'), $mealPlanData);

        $response->assertRedirect(route('meal_plans.index'));
        $response->assertSessionHas('success', 'Meal Plan created successfully.');

        $this->assertDatabaseHas('meal_plans', [
            'bmi_category_id' => $bmiCategory->id,
            'protein' => 30,
            'carbs' => 50,
            'fats' => 20,
        ]);
    }

    /**
     * Test update method updates a meal plan.
     *
     * @return void
     */
    public function test_update_updates_meal_plan()
    {
        $mealPlan = $this->createTestMealPlan();
        $bmiCategory = BmiCategory::find($mealPlan->bmi_category_id);

        $updatedData = [
            'bmi_category_id' => $bmiCategory->id,
            'protein' => 35,
            'carbs' => 45,
            'fats' => 20,
            'description' => '<p>Updated meal plan description</p>',
        ];

        $response = $this->put(route('meal_plans.update', $mealPlan->id), $updatedData);

        $response->assertRedirect(route('meal_plans.index'));
        $response->assertSessionHas('success', 'Meal Plan updated successfully.');

        $this->assertDatabaseHas('meal_plans', [
            'id' => $mealPlan->id,
            'protein' => 35,
            'carbs' => 45,
            'fats' => 20,
            'description' => '<p>Updated meal plan description</p>',
        ]);
    }

    /**
     * Test destroy method deletes a meal plan.
     *
     * @return void
     */
    public function test_destroy_deletes_meal_plan()
    {
        $mealPlan = $this->createTestMealPlan();

        $response = $this->delete(route('meal_plans.destroy', $mealPlan->id));

        $response->assertRedirect(route('meal_plans.index'));
        $response->assertSessionHas('success', 'Meal Plan deleted.');

        $this->assertDatabaseMissing('meal_plans', [
            'id' => $mealPlan->id,
        ]);
    }

    /**
     * Test validation rules when creating a meal plan.
     *
     * @return void
     */
    public function test_validation_rules_when_creating_meal_plan()
    {
        $response = $this->post(route('meal_plans.store'), [
            'bmi_category_id' => '',
            'protein' => 'not-a-number',
            'carbs' => '',
            'fats' => '',
            'description' => '',
        ]);

        $response->assertSessionHasErrors(['bmi_category_id', 'protein', 'carbs', 'fats', 'description']);
    }

    /**
     * Test validation rules when updating a meal plan.
     *
     * @return void
     */
    public function test_validation_rules_when_updating_meal_plan()
    {
        $mealPlan = $this->createTestMealPlan();

        $response = $this->put(route('meal_plans.update', $mealPlan->id), [
            'bmi_category_id' => '',
            'protein' => 'not-a-number',
            'carbs' => '',
            'fats' => '',
            'description' => '',
        ]);

        $response->assertSessionHasErrors(['bmi_category_id', 'protein', 'carbs', 'fats', 'description']);
    }

    /**
     * Test that meal plans are associated with the correct BMI category.
     *
     * @return void
     */
    public function test_meal_plans_are_associated_with_correct_bmi_category()
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

        $mealPlan1 = $this->createTestMealPlan($bmiCategory1->id);
        $mealPlan2 = $this->createTestMealPlan($bmiCategory2->id);

        $this->assertEquals($bmiCategory1->id, $mealPlan1->bmi_category_id);
        $this->assertEquals($bmiCategory2->id, $mealPlan2->bmi_category_id);
    }
}