<?php

namespace App\Http\Controllers\api\Expense;

use App\Http\Requests\Expense\StoreRequest;
use App\Models\Expense;
use App\Models\User;
use Illuminate\Http\Request;

trait TraitStore {
    /**
     * Store a newly created resource in storage.
     * 
     * @OA\Post(
     *     path="/api/expenses",
     *     tags={"Expenses"},
     *     summary="Create a new expense",
     *     description="This endpoint is used to create a new expense.",
     *     operationId="expenseStore",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="group_id",
     *         in="query",
     *         required=true,
     *         description="The id of the group",
     *         @OA\Schema(
     *             type="integer"
     *         ),
     *     ),
     *     @OA\Parameter(
     *         name="payee_id",
     *         in="query",
     *         required=true,
     *         description="The id of the payee",
     *         @OA\Schema(
     *             type="integer"
     *         ),
     *     ),
     *     @OA\Parameter(
     *         name="name",
     *         in="query",
     *         required=true,
     *         description="The name of the expense",
     *         @OA\Schema(
     *             type="string"
     *         ),
     *     ),
     *     @OA\Parameter(
     *         name="price",
     *         in="query",
     *         required=true,
     *         description="The price of the expense",
     *         @OA\Schema(
     *             type="integer"
     *         ),
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Expense created successfully",
     *         @OA\JsonContent(
     *             allOf={
     *                 @OA\Schema(
     *                     @OA\Property(ref="#/components/schemas/Expense")
     *                 )
     *             }
     *         ),
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Payee is not in this group",
     *         @OA\JsonContent(ref="#/components/schemas/BadRequestError")
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="You are not in this group",
     *         @OA\JsonContent(ref="#/components/schemas/ForbiddenError")
     *     )
     * )
     */
    public function store(StoreRequest $request)
    {
        $user = auth('sanctum')->user();
        $group_id = $request->group_id;
        $payee_id = $request->payee_id;
        $name = $request->name;
        $price = $request->price;

        $payeeUser = User::find($payee_id);

        // Check if user is in the group
        if (!$user->groups()->where('group_id', $group_id)->exists()) {
            return response()->json(['message' => 'You are not in this group'], 403);
        }

        // Check if payee is in the group
        // IKKE DONE ENDNU
        if (!$payeeUser->groups()->where('group_id', $group_id)->exists()) {
            return response()->json(['message' => 'Payee is not in this group'], 400);
        }

        $expense = new Expense();
        $expense->group_id = $group_id;
        $expense->payee_id = $payee_id;
        $expense->created_by = $user->id;
        $expense->name = $name;
        $expense->price = $price;
        $expense->save();

        return response()->json($expense, 200);



    }
}