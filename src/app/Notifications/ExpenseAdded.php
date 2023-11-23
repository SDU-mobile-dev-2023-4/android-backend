<?php

namespace App\Notifications;

use App\Models\Expense;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

/**
 * @OA\Schema(
 *   schema="ExpenseAddedNotification",
 *   title="Expense added notification (App\\Notifications\\ExpenseAdded)",
 *   @OA\Property(
 *     property="expense",
 *     type="object",
 *     ref="#/components/schemas/Expense"
 *   ),
 *   @OA\Property(
 *     property="group",
 *     type="object",
 *     ref="#/components/schemas/Group"
 *   ),
 *   @OA\Property(
 *     property="created_by",
 *     type="object",
 *     ref="#/components/schemas/User"
 *   ),
 *   @OA\Property(
 *     property="payer",
 *     type="object",
 *     ref="#/components/schemas/User"
 *   )
 * )
 */
class ExpenseAdded extends Notification
{
    use Queueable;

    private Expense $expense;

    /**
     * Create a new notification instance.
     */
    public function __construct(Expense $expense)
    {
        $this->expense = $expense;
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
            'expense' => $this->expense->unsetRelation('group')->toArray(),
            'group' => $this->expense->group->toArray(),
            'created_by' => $this->expense->createdBy->toArray(),
            'payer' => $this->expense->payer->toArray(),
        ];
    }
}
