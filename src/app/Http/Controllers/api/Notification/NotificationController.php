<?php

namespace App\Http\Controllers\api\Notification;

use App\Http\Controllers\Controller;

class NotificationController extends Controller
{
    /**
     * Display a listing of the resource.
     * 
     * @OA\Schema(
     *   schema="Notification",
     *   
     *   @OA\Property(
     *     property="id",
     *     description="The id of the notification, formatted as a UUID.",
     *     type="string",
     *     format="uuid",
     *     example="123e4567-e89b-12d3-a456-426614174000"
     *   ),
     *   @OA\Property(
     *     property="type",
     *     description="The type of the notification.",
     *     type="string",
     *     example="App\Notifications\ExpenseAdded"
     *   ),
     *   @OA\Property(
     *     property="notifiable_type",
     *     description="The type of the notifiable model.",
     *     type="string",
     *     example="App\Models\User"
     *   ),
     *   @OA\Property(
     *     property="notifiable_id",
     *     description="The id of the notifiable model. This would usually be the id of the authenticated user.",
     *     type="integer",
     *     example="1"
     *   ),
     *   @OA\Property(
     *     property="data",
     *     description="The data of the notification. This data is dynamic according to the type of the notification.",
     *     type="object",
     *     oneOf={
     *       @OA\Schema(
     *         description="The data of the notification when the type is App\Notifications\ExpenseAdded.",
     *         ref="#/components/schemas/ExpenseAddedNotification"
     *       ),
     *       @OA\Schema(
     *         description="The data of the notification when the type is App\Notifications\AddedToGroup.",
     *         ref="#/components/schemas/AddedToGroupNotification"
     *       ),
     *       @OA\Schema(
     *         description="The data of the notification when the type is App\Notifications\RemovedFromGroup.",
     *         ref="#/components/schemas/RemovedFromGroupNotification"
     *       )
     *     }
     *   ),
     *   @OA\Property(
     *     property="read_at",
     *     description="The date and time when the notification was read.",
     *     type="string",
     *     format="date-time",
     *     example="2020-01-01 00:00:00"
     *   ),
     *   @OA\Property(
     *     property="created_at",
     *     description="The date and time when the notification was created.",
     *     type="string",
     *     format="date-time",
     *     example="2020-01-01 00:00:00"
     *   ),
     *   @OA\Property(
     *     property="updated_at",
     *     description="The date and time when the notification was updated.",
     *     type="string",
     *     format="date-time",
     *     example="2020-01-01 00:00:00"
     *   ),
     * )
     * 
     * @OA\Get(
     *   path="/api/notifications",
     *   tags={"Notifications"},
     *   summary="Get notifications",
     *   description="Get the oldest 5 notifications visible to the authenticated user, that is, all notifications that have not been read yet. There may be no notifications at all.",
     *   operationId="notificationIndex",
     *   security={{"bearerAuth":{}}},
     *   
     *   @OA\Response(
     *     response=200,
     *     description="Success",
     *     @OA\JsonContent(
     *       @OA\Property(
     *         property="notifications",
     *         type="array",
     *         @OA\Items(
     *           ref="#/components/schemas/Notification" 
     *         )
     *       )
     *     )
     *   ),
     *   @OA\Response(
     *     response=401,
     *     description="Unauthorized",
     *     @OA\JsonContent(ref="#/components/schemas/UnauthorizedError")
     *   )
     * )  
     */
    public function index()
    {
        $user = auth('sanctum')->user();

        $notifications = $user->notifications()->unRead()->oldest()->take(5)->get();

        $notifications->transform(function ($notification) {
            $notification->markAsRead();
            return $notification->toArray();
        });

        return response([
            'notifications' => $notifications->toArray(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(): void
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(): void
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(): void
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(): void
    {
        //
    }
}