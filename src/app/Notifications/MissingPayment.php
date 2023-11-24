<?php

namespace App\Notifications;

use App\Models\Group;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

/**
 * @OA\Schema(
 *   schema="MissingPaymentNotification",
 *   title="Missing payment notification (App\\Notifications\\MissingPayment)",
 *   
 *   @OA\Property(
 *     property="group",
 *     type="object",
 *     ref="#/components/schemas/Group"
 *   ),
 *   @OA\Property(
 *     property="added_by",
 *     type="object",
 *     ref="#/components/schemas/User"
 *   )
 * )
 */
class MissingPayment extends Notification
{
    use Queueable;

    private Group $group;
    private User $addedBy;

    /**
     * Create a new notification instance.
     */
    public function __construct(Group $group, User $addedBy)
    {
        $this->group = $group;
        $this->addedBy = $addedBy;
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
            'added_by' => $this->addedBy,
        ];
    }
}
