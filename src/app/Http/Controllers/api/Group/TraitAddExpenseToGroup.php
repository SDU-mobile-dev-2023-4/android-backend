<?php

namespace App\Http\Controllers\api\Group;

use App\Helper\Sanitizer;
use App\Http\Requests\api\Expense\GroupStoreExpenseRequest;
use App\Models\Expense;
use App\Models\Group;
use App\Models\User;

trait TraitAddExpenseToGroup
{
    /**
     * Add a user with a specific email to the group.
     *
     * @OA\Post(
     *     path="/api/groups/{id}/expenses",
     *     tags={"Groups"},
     *     summary="Add a expense to a group",
     *     description="This endpoint is used to add a expense to a group.",
     *     operationId="addExpenseToGroup",
     *     security={{"bearerAuth":{}}},
     *     
     *     @OA\Parameter(
     *          name="id",
     *          in="path",
     *          required=true,
     *          description="The id of the group",
     *          @OA\Schema(
     *              type="integer",
     *              format="int64",
     *              example="1",
     *              nullable=false,
     *          ),
     *     ),
     *     
     *     @OA\RequestBody(
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              @OA\Schema(
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
     *         response=200,
     *         description="Expense added to group successfully",
     *         @OA\JsonContent(ref="#/components/schemas/GroupWithUsersAndExpenses")
     *      ),
     *      @OA\Response(
     *         response=400,
     *         description="Bad Request",
     *         @OA\JsonContent(ref="#/components/schemas/BadRequestError")
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthorized",
     *          @OA\JsonContent(ref="#/components/schemas/UnauthorizedError")
     *      ),
     *      @OA\Response(
     *         response=403,
     *         description="Forbidden",
     *         @OA\JsonContent(ref="#/components/schemas/ForbiddenError")
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Not Found",
     *          @OA\JsonContent(ref="#/components/schemas/NotFoundError")
     *      ),
     *      @OA\Response(
     *        response=422,
     *        description="Bad Request - One or more errors with the input data",
     *        @OA\JsonContent(ref="#/components/schemas/BadRequestError")
     *      ),
     * )
     */
    public function addExpenseToGroup(GroupStoreExpenseRequest $request, Group $group)
    {
        // Validated data
        $data = $request->validated();

        // Get User
        $user = User::find(auth('sanctum')->user()->id);

        // Sanitize data
        $payer_id = Sanitizer::sanitize($data['payer_id']);
        $name = Sanitizer::sanitize($data['name']);
        $price = Sanitizer::sanitize($data['price']);

        // Check if user is authorized to add expense to this group
        if (!$group->user($user->id)->exists()) {
            return response()->json(['message' => 'You are not in this group'], 403);
        }

        // Check if payer is in the group
        $payerUser = User::find($payer_id);
        if (!$group->user($payerUser->id)->exists()) {
            return response()->json(['message' => 'Payer is not in this group'], 400);
        }

        // Create expense
        $expense = new Expense();
        $expense->group_id = $group->id;
        $expense->payer_id = $payer_id;
        $expense->created_by = $user->id;
        $expense->name = $name;
        $expense->price = $price;
        $expense->save();

        // Load group data
        $group->load('users', 'expenses');

        // Return group
        return $group;
    }
}
