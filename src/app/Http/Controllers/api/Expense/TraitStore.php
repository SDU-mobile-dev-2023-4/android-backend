<?php

namespace App\Http\Controllers\api\Expense;

use App\Helper\Sanitizer;
use App\Http\Requests\api\Expense\StoreRequest;
use App\Models\Expense;
use App\Models\User;

trait TraitStore
{
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
     *     @OA\RequestBody(
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              @OA\Schema(
     *                  @OA\Property(
     *                      property="group_id",
     *                      type="integer",
     *                      description="The id of the group",
     *                      example="1",
     *                      nullable=false
     *                  ),
     *                  @OA\Property(
     *                      property="payer_id",
     *                      type="integer",
     *                      description="The id of the payer.",
     *                      example="1",
     *                      nullable=false
     *                  ),
     *                  @OA\Property(
     *                      property="name",
     *                      type="string",
     *                      description="The name of the expense",
     *                      example="Train ticket",
     *                      nullable=false,
     *                      minLength=1,
     *                      maxLength=255
     *                  ),
     *                  @OA\Property(
     *                      property="price",
     *                      type="integer",
     *                      description="The price of the expense",
     *                      example="100",
     *                      nullable=false
     *                  ),
     *              )
     *          )
     *      ),
     *      
     *      @OA\Response(
     *          response=200,
     *          description="Expense created successfully",
     *          @OA\JsonContent(ref="#/components/schemas/Expense")
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="Payer is not in this group",
     *          @OA\JsonContent(ref="#/components/schemas/BadRequestError")
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="You are not in this group",
     *          @OA\JsonContent(ref="#/components/schemas/ForbiddenError")
     *      )
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
        $payer_id = Sanitizer::sanitize($data['payer_id']);
        $name = Sanitizer::sanitize($data['name']);
        $price = Sanitizer::sanitize($data['price']);

        // Check if user is in the group
        if (!$user->groups()->where('group_id', $group_id)->exists()) {
            return response()->json(['message' => 'You are not in this group'], 403);
        }

        // Check if payer is in the group
        $payerUser = User::find($payer_id);
        if (!$payerUser->groups()->where('group_id', $group_id)->exists()) {
            return response()->json(['message' => 'Payer is not in this group'], 400);
        }

        // Create expense
        $expense = new Expense();
        $expense->group_id = $group_id;
        $expense->payer_id = $payer_id;
        $expense->created_by = $user->id;
        $expense->name = $name;
        $expense->price = $price;
        $expense->save();

        // Return response
        return response()->json($expense, 200);
    }
}