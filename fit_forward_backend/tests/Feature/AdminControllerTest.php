<?php

namespace Tests\Feature;

use App\Models\Admin;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class AdminControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * The admin user for testing.
     *
     * @var \App\Models\Admin
     */
    protected $admin;

    /**
     * Set up a test admin user and log them in before each test.
     */
    public function setUp(): void
    {
        parent::setUp();
        
        // Create a test admin user
        $this->admin = Admin::factory()->create([
            'email' => 'admin@example.com',
            'password' => Hash::make('password123'),
        ]);

        // Log in as the admin user
        $this->actingAs($this->admin);
    }

    /**
     * Test if the index method returns a list of admins.
     *
     * @return void
     */
    public function test_index_shows_admin_list()
    {
        // Create some admins in the database
        Admin::factory()->count(3)->create();

        // Make a GET request to the admin index page
        $response = $this->get(route('admin.index'));

        // Assert the response is successful and view contains 'admins'
        $response->assertStatus(200);
        $response->assertViewIs('admin.index');
        $response->assertViewHas('admins');
    }

    /**
     * Test if the create method returns the create form view.
     *
     * @return void
     */
    public function test_create_shows_create_form()
    {
        // Make a GET request to the admin create page
        $response = $this->get(route('admin.create'));

        // Assert the response is successful and the correct view is returned
        $response->assertStatus(200);
        $response->assertViewIs('admin.create');
    }

    /**
     * Test if the store method creates a new admin with valid data.
     *
     * @return void
     */
    public function test_store_creates_new_admin()
    {
        // Data for the new admin
        $data = [
            'name' => 'Test Admin',
            'email' => 'test@example.com',
            'password' => 'password123',
        ];

        // Make a POST request to store the new admin
        $response = $this->post(route('admin.store'), $data);

        // Assert the response redirects to the admin index page
        $response->assertRedirect(route('admin.index'));

        // Assert the success message
        $response->assertSessionHas('success', 'Admin created successfully.');

        // Assert the new admin was created in the database
        $this->assertDatabaseHas('admins', [
            'name' => 'Test Admin',
            'email' => 'test@example.com',
        ]);
    }

    /**
     * Test if the update method updates an existing admin.
     *
     * @return void
     */
    public function test_update_updates_admin()
    {
        // Create an admin
        $admin = Admin::factory()->create();

        // Data for updating the admin
        $data = [
            'name' => 'Updated Admin',
            'email' => 'updated@example.com',
            'password' => 'newpassword123',
        ];

        // Make a PUT request to update the admin
        $response = $this->put(route('admin.update', $admin->id), $data);

        // Assert the response redirects to the admin index page
        $response->assertRedirect(route('admin.index'));

        // Assert the success message
        $response->assertSessionHas('success', 'Admin updated successfully.');

        // Assert the admin details were updated in the database
        $admin->refresh(); // Refresh the instance from the database
        $this->assertEquals('Updated Admin', $admin->name);
        $this->assertEquals('updated@example.com', $admin->email);
    }

    /**
     * Test if the destroy method deletes an admin.
     *
     * @return void
     */
    public function test_destroy_deletes_admin()
    {
        // Create an admin
        $admin = Admin::factory()->create();

        // Make a DELETE request to delete the admin
        $response = $this->delete(route('admin.destroy', $admin->id));

        // Assert the response redirects to the admin index page
        $response->assertRedirect(route('admin.index'));

        // Assert the success message
        $response->assertSessionHas('success', 'Admin deleted successfully.');

        // Assert the admin was deleted from the database
        $this->assertDatabaseMissing('admins', [
            'id' => $admin->id,
        ]);
    }
}
