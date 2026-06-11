<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBillItemRequest;
use App\Models\Bill;
use App\Models\Group;
use Illuminate\Support\Facades\Auth;

class BillItemController extends Controller
{
    public function store(StoreBillItemRequest $request, Group $group, Bill $bill)
    {
        $this->authorizeGroupAccess($group);
        abort_unless($bill->group_id === $group->id, 404);

        $validated = $request->validated();

        $item = $bill->items()->create([
            'name' => $validated['name'],
            'price' => $validated['price'],
            'quantity' => $validated['quantity'],
        ]);

        $item->users()->syncWithoutDetaching($validated['user_ids']);

        return back()->with('success', 'Pozycja z paragonu dodana.');
    }

    private function authorizeGroupAccess(Group $group): void
    {
        if (!auth()->user()->isAdmin() && !$group->users->contains(Auth::id())) {
            redirect()
                ->route('groups.index')
                ->with('error', 'Nie masz dostepu do pozycji rachunku w tej grupie.')
                ->throwResponse();
        }
    }
}
