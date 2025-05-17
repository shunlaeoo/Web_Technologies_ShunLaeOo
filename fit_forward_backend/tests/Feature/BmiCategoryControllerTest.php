<?php

namespace Tests\Feature;

use App\Models\BmiCategory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BmiCategoryControllerTest extends TestCase
{
    use RefreshDatabase; // This ensures a fresh database for each test

    /**
     * Setup for tests.
     */
    protected function setUp(): void
    {
        parent::setUp();
        
        // Disable all middleware for these tests
        $this->withoutMiddleware();
    }

    /**
     * Test if the index method returns a list of BMI categories.
     *
     * @return void
     */
    public function test_index_shows_bmi_categories()
    {
        // Create some BMI categories in the database
        BmiCategory::factory()->count(3)->create();

        // Make a GET request to the BMI categories index page
        $response = $this->get(route('bmi_category.index'));

        // Assert the response is successful and the correct view is returned
        $response->assertStatus(200);
        $response->assertViewIs('bmi_categories.index');
        $response->assertViewHas('categories');
    }

    /**
     * Test if the store method creates a new BMI category with valid data.
     *
     * @return void
     */
    public function test_store_creates_bmi_category()
    {
        // Data for the new BMI category
        $data = [
            'name' => 'Normal',
            'min' => 18.5,
            'max' => 24.9,
        ];

        // Make a POST request to store the new BMI category
        $response = $this->post(route('bmi_category.store'), $data);

        // Assert the response redirects to the BMI category index page
        $response->assertRedirect(route('bmi_category.index'));

        // Assert the success message
        $response->assertSessionHas('success', 'BMI Category created successfully.');

        // Assert the new BMI category was created in the database
        $this->assertDatabaseHas('bmi_categories', [
            'name' => 'Normal',
            'min' => 18.5,
            'max' => 24.9,
        ]);
    }

    /**
     * Test if the update method updates a BMI category.
     *
     * @return void
     */
    public function test_update_updates_bmi_category()
    {
        // Create a BMI category
        $bmiCategory = BmiCategory::factory()->create();

        // Data for updating the BMI category
        $data = [
            'name' => 'Overweight',
            'min' => 25,
            'max' => 29.9,
        ];

        // Make a PUT request to update the BMI category
        $response = $this->put(route('bmi_category.update', $bmiCategory->id), $data);

        // Assert the response redirects to the BMI category index page
        $response->assertRedirect(route('bmi_category.index'));

        // Assert the success message
        $response->assertSessionHas('success', 'BMI Category updated successfully.');

        // Assert the BMI category details were updated in the database
        $bmiCategory->refresh(); // Refresh the instance from the database
        $this->assertEquals('Overweight', $bmiCategory->name);
        $this->assertEquals(25, $bmiCategory->min);
        $this->assertEquals(29.9, $bmiCategory->max);
    }

    /**
     * Test if the destroy method deletes a BMI category.
     *
     * @return void
     */
    public function test_destroy_deletes_bmi_category()
    {
        // Create a BMI category
        $bmiCategory = BmiCategory::factory()->create();

        // Make a DELETE request to delete the BMI category
        $response = $this->delete(route('bmi_category.destroy', $bmiCategory->id));

        // Assert the response redirects to the BMI category index page
        $response->assertRedirect(route('bmi_category.index'));

        // Assert the success message
        $response->assertSessionHas('success', 'BMI Category deleted.');

        // Assert the BMI category was deleted from the database
        $this->assertDatabaseMissing('bmi_categories', [
            'id' => $bmiCategory->id,
        ]);
    }
}
