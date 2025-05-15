<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

class UserFlowTest extends TestCase
{
    public function test_user_can_be_created(): array
    {
        $user = ['id' => 1, 'name' => 'John Doe'];
        $this->assertIsArray($user);
        $this->assertArrayHasKey('id', $user);
        
        return $user;
    }

    /**
     * @depends test_user_can_be_created
     */
    public function test_user_can_be_updated(array $user): void
    {
        $user['name'] = 'Jane Doe';
        $this->assertEquals('Jane Doe', $user['name']);
    }
}