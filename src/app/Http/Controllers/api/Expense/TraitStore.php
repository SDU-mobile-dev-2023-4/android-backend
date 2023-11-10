<?php

namespace App\Http\Controllers\api\Expense;

use App\Helper\Sanitizer;
use App\Http\Requests\api\Expense\StoreRequest;
use App\Models\Expense;
use App\Models\User;

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
     *     
     *     @OA\Parameter(
     *         name="group_id",
     *         in="query",
     *         required=true,
     *         description="The id of the group",
     *         example="1",
     *         @OA\Schema(
     *              type="integer",
     *              format="int64",
     *              example="1",
     *              nullable=false,
     *         ),
     *     ),
     *     @OA\Parameter(
     *         name="payee_id",
     *         in="query",
     *         required=true,
     *         description="The id of the payee",
     *         example="1",
     *         @OA\Schema(
     *              type="integer",
     *              format="int64",
     *              example="1",
     *              nullable=false,
     *         ),
     *     ),
     *     @OA\Parameter(
     *         name="name",
     *         in="query",
     *         required=true,
     *         description="The name of the expense",
     *         example="Train ticket",
     *         @OA\Schema(
     *              type="string",
     *              minLength=1,
     *              maxLength=255,
     *              example="Europe trip",
     *              nullable=false
     *         ),
     *     ),
     *     @OA\Parameter(
     *         name="price",
     *         in="query",
     *         required=true,
     *         description="The price of the expense",
     *         example="100",
     *         @OA\Schema(
     *              type="integer",
     *              format="int64",
     *              example="1",
     *              nullable=false,
     *         ),
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Expense created successfully",
     *         @OA\JsonContent(ref="#/components/schemas/Expense")
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
        // Validated data
        $data = $request->validated();

        // Get User
        $user = User::find(auth('sanctum')->user()->id);

        // Sanitize data
        $group_id = Sanitizer::sanitize($data['group_id']);
        $payee_id = Sanitizer::sanitize($data['payee_id']);
        $name = Sanitizer::sanitize($data['name']);
        $price = Sanitizer::sanitize($data['price']);


        // Check if user is in the group
        
        if (!$user->groups()->where('group_id', $group_id)->exists()) {
            return response()->json(['message' => 'You are not in this group'], 403);
        }

        // Check if payee is in the group
        $payeeUser = User::find($payee_id);
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