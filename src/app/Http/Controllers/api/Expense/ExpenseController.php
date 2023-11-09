<?php

namespace App\Http\Controllers\api\Expense;

use App\Http\Controllers\Controller;

class ExpenseController extends Controller
{
    use TraitDestroy;
    use TraitIndex;
    use TraitShow;
    use TraitStore;
    use TraitUpdate;
}
