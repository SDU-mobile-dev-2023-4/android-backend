<?php

namespace Tests\Feature\app\Http\Controllers\api\Group;

use App\Models\Expense;
use App\Models\Group;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GroupNotifyTest extends TestCase
{
    use RefreshDatabase;

    private Group $group;
    private User $user;
    private User $user2;
    private Expense $expense;

    public function setUp(): void
    {
        parent::setUp();

        // Create a group
        $group = new Group([
            'name' => 'Test Group',
            'description' => 'Test Group Description',
        ]);
        $group->save();

        // Create a user
        $user = new User([
            'name' => 'Test User',
            'email' => 'example@uomi.ringhus.dk',
            'password' => 'password',
        ]);
        $user->save();

        \Auth::login($user, 'sanctum');

        // Add the user to the group
        $group->users()->attach($user->id);

        // Create an expense
        $expense = new Expense([
            'name' => 'Test Expense',
            'description' => 'Test Expense Description',
        ]);
        $expense->payer()->associate($user);
        $expense->createdBy()->associate($user);
        $expense->group()->associate($group);
        $expense->price = 100;
        $expense->save();

        // Create another user, not in the group
        $user2 = new User([
            'name' => 'Test User 2',
            'email' => 'example2@uomi.ringhus.dk',
            'password' => 'password',
        ]);
        $user2->save();

        // Insert data into variables
        $this->group = $group;
        $this->user = $user;
        $this->user2 = $user2;
        $this->expense = $expense;
    }

    /**
     * Test that a user can notify the group members of unpaid expenses.
     */
    public function test_a_user_can_notify_the_group_members_of_unpaid_expenses(): void
    {
        // Attach a second user to the group
        $this->group->users()->attach($this->user2);

        // Login the user with sanctum
        $this->actingAs($this->user, 'sanctum');

        // Notify the group members
        $response = $this->postJson('/api/groups/' . $this->group->id . '/notify');

        // Check that the response is correct
        $response->assertStatus(200);

        // Check that the group members have been notified
        $this->assertDatabaseHas('notifications', [
            'type' => 'App\\Notifications\\MissingPayment',
            'notifiable_id' => $this->user2->id,
            'notifiable_type' => 'App\Models\User',
        ]);
        $this->assertDatabaseMissing('notifications', [
            'type' => 'App\\Notifications\\MissingPayment',
            'notifiable_id' => $this->user->id,
            'notifiable_type' => 'App\Models\User',
        ]);
    }

    /**
     * Test that it is only possible to notify the group members if the user is a member of the group.
     */
    public function test_it_is_only_possible_to_notify_the_group_members_if_the_user_is_a_member_of_the_group(): void
    {
        // Login the user with sanctum
        $this->actingAs($this->user2, 'sanctum');

        // Notify the group members
        $response = $this->postJson('/api/groups/' . $this->group->id . '/notify');

        // Check that the response is correct
        $response->assertStatus(403);

        // Check that the group members have not been notified
        $this->assertDatabaseMissing('notifications', [
            'type' => 'App\\Notifications\\MissingPayment',
            'notifiable_id' => $this->user2->id,
            'notifiable_type' => 'App\Models\User',
        ]);
        $this->assertDatabaseMissing('notifications', [
            'type' => 'App\\Notifications\\MissingPayment',
        ]);
    }
}
