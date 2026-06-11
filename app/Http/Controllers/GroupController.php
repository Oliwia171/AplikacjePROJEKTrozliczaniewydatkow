<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddGroupUserRequest;
use App\Http\Requests\BillFiltersRequest;
use App\Http\Requests\GroupFiltersRequest;
use App\Http\Requests\StoreGroupRequest;
use App\Http\Requests\UpdateGroupRequest;
use App\Models\Group;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class GroupController extends Controller
{
    public function index(GroupFiltersRequest $request)
    {
        $filters = $request->validated();
        $user = $request->user();

        $query = $user?->isAdmin()
            ? Group::query()
            : ($user ? $user->groups() : Group::query());

        $groups = $query
            ->with('owner')
            ->withCount(['users', 'bills'])
            ->when($filters['search'] ?? null, function ($query, string $search) {
                $query->where(function ($query) use ($search) {
                    $query->where('name', 'like', "%{$search}%")
                        ->orWhere('description', 'like', "%{$search}%");
                });
            })
            ->when(($filters['owner'] ?? null) && $user?->isAdmin(), function ($query) use ($filters) {
                $query->whereHas('owner', function ($query) use ($filters) {
                    $query->where('name', 'like', '%'.$filters['owner'].'%')
                        ->orWhere('email', 'like', '%'.$filters['owner'].'%');
                });
            })
            ->orderBy('name')
            ->paginate(8)
            ->withQueryString();

        return view('groups.index', [
            'groups' => $groups,
            'filters' => $filters,
        ]);
    }

    public function store(StoreGroupRequest $request)
    {
        $validated = $request->validated();

        $group = Group::create([
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'owner_id' => Auth::id(),
        ]);

        $group->users()->syncWithoutDetaching([Auth::id()]);

        return redirect()->route('groups.index')->with('success', 'Grupa utworzona!');
    }

    public function show(BillFiltersRequest $request, Group $group)
    {
        $filters = $request->validated();

        $group->load(['owner', 'users', 'bills.splits.user']);

        $bills = $group->bills()
            ->with(['payer', 'items.users', 'splits.user'])
            ->when($filters['bill_search'] ?? null, function ($query, string $search) {
                $query->where('description', 'like', "%{$search}%");
            })
            ->when($filters['payer_id'] ?? null, function ($query, int $payerId) {
                $query->where('payer_id', $payerId);
            })
            ->when($filters['amount_from'] ?? null, function ($query, $amountFrom) {
                $query->where('amount', '>=', $amountFrom);
            })
            ->when($filters['amount_to'] ?? null, function ($query, $amountTo) {
                $query->where('amount', '<=', $amountTo);
            })
            ->when($filters['date_from'] ?? null, function ($query, string $dateFrom) {
                $query->whereDate('date', '>=', $dateFrom);
            })
            ->when($filters['date_to'] ?? null, function ($query, string $dateTo) {
                $query->whereDate('date', '<=', $dateTo);
            })
            ->orderByDesc('date')
            ->orderByDesc('id')
            ->paginate(5)
            ->withQueryString();

        return view('groups.show', [
            'group' => $group,
            'bills' => $bills,
            'filters' => $filters,
        ]);
    }

    public function edit(Group $group)
    {
        $this->authorizeGroupOwnerOrAdmin($group);

        return view('groups.edit', compact('group'));
    }

    public function update(UpdateGroupRequest $request, Group $group)
    {
        $this->authorizeGroupOwnerOrAdmin($group);

        $validated = $request->validated();

        $group->update([
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
        ]);

        return redirect()->route('groups.show', $group)->with('success', 'Grupa zaktualizowana.');
    }

    public function addUser(AddGroupUserRequest $request, Group $group)
    {
        $this->authorizeGroupAccess($group);

        $validated = $request->validated();

        $userToAdd = User::where('email', $validated['email'])->first();

        if ($group->users->contains($userToAdd->id)) {
            return back()->withInput()->with('error', 'Ten uzytkownik juz jest w grupie!');
        }

        $group->users()->syncWithoutDetaching([$userToAdd->id]);

        return back()->with('success', 'Dodano uzytkownika: ' . $userToAdd->name);
    }

    public function destroy(Group $group)
    {
        $this->authorizeGroupOwnerOrAdmin($group);
        $group->delete();

        return redirect()->route('groups.index')->with('success', 'Grupa usunieta.');
    }

    private function authorizeGroupAccess(Group $group): void
    {
        if (!auth()->user()->isAdmin() && !$group->users->contains(Auth::id())) {
            redirect()
                ->route('groups.index')
                ->with('error', 'Nie masz dostepu do tej akcji w wybranej grupie.')
                ->throwResponse();
        }
    }

    private function authorizeGroupOwnerOrAdmin(Group $group): void
    {
        if (!auth()->user()->isAdmin() && $group->owner_id !== Auth::id()) {
            redirect()
                ->route('groups.index')
                ->with('error', 'Tylko wlasciciel grupy albo administrator moze wykonac te akcje.')
                ->throwResponse();
        }
    }
}
