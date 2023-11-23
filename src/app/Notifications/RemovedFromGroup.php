<?php

namespace App\Notifications;

use App\Models\Group;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

/**
 * @OA\Schema(
 *   schema="RemovedFromGroupNotification",
 *   title="Removed from group notification (App\\Notifications\\RemovedFromGroup)",
 *   
 *   @OA\Property(
 *     property="group",
 *     type="object",
 *     ref="#/components/schemas/Group"
 *   ),
 *   @OA\Property(
 *     property="removed_by",
 *     type="object",
 *     ref="#/components/schemas/User"
 *   )
 * )
 */
class RemovedFromGroup extends Notification
{
    use Queueable;

    private Group $group;
    private User $removedBy;

    /**
     * Create a new notification instance.
     */
    public function __construct(Group $group, User $removedBy)
    {
        $this->group = $group;
        $this->removedBy = $removedBy;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'group' => $this->group,
            'removed_by' => $this->removedBy,
        ];
    }
}
