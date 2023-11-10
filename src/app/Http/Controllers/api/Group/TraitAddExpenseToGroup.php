<?php

namespace App\Http\Controllers\api\Group;

use App\Helper\Sanitizer;
use App\Http\Requests\api\Group\AddUserToGroupRequest;
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
     *         @OA\Parameter(
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
    public function addExpenseToGroup(AddUserToGroupRequest $request, Group $group)
    {
        // Validated data
        $data = $request->validated();

        // Get User
        $user = User::find(auth('sanctum')->user()->id);

        // Sanitize data
        $payee_id = Sanitizer::sanitize($data['payee_id']);
        $name = Sanitizer::sanitize($data['name']);
        $price = Sanitizer::sanitize($data['price']);

        // Check if user is authorized to add expense to this group
        if (!$group->user($user->id)->exists()) {
            return response()->json(['message' => 'You are not in this group'], 403);
        }

        // Check if payee is in the group
        $payeeUser = User::find($payee_id);
        if (!$group->user($payeeUser->id)->exists()) {
            return response()->json(['message' => 'Payee is not in this group'], 400);
        }

        // Create expense
        $expense = new Expense();
        $expense->group_id = $group->id;
        $expense->payee_id = $payee_id;
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
