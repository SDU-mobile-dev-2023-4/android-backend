<?php

namespace App\Http\Controllers\api\Group;

use App\Http\Controllers\Controller;

class GroupController extends Controller
{
    use TraitIndex;
    use TraitStore;
    use TraitShow;
    use TraitDestroy;
    use TraitUpdate;
    use TraitAddUserToGroup;
    use TraitRemoveUserFromGroup;
}
