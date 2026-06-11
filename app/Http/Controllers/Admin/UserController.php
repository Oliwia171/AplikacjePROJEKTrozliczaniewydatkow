<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateUserRequest;
use App\Http\Requests\Admin\UserFiltersRequest;
use App\Models\Bill;
use App\Models\Group;
use App\Models\User;

class UserController extends Controller
{
    public function index(UserFiltersRequest $request)
    {
        $filters = $request->validated();

        $users = User::query()
            ->withCount('groups')
            ->when($filters['search'] ?? null, function ($query, string $search) {
                $query->where(function ($query) use ($search) {
                    $query->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                });
            })
            ->when($filters['role'] ?? null, function ($query, string $role) {
                $query->where('role', $role);
            })
            ->orderBy('name')
            ->paginate(10)
            ->withQueryString();

        $stats = [
            'users' => User::count(),
            'admins' => User::where('role', 'admin')->count(),
            'groups' => Group::count(),
            'bills' => Bill::count(),
        ];

        return view('admin.users.index', [
            'users' => $users,
            'filters' => $filters,
            'stats' => $stats,
        ]);
    }

    public function update(UpdateUserRequest $request, User $user)
    {
        $validated = $request->validated();

        if ($user->id === auth()->id() && $validated['role'] !== 'admin') {
            return back()->with('error', 'Nie mozesz odebrac sobie dostepu administratora.');
        }

        $user->update([
            'name' => $validated['name'],
            'role' => $validated['role'],
        ]);

        return back()->with('success', 'Profil uzytkownika zaktualizowany.');
    }

    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Nie mozesz usunac wlasnego konta.');
        }

        $user->delete();

        return back()->with('success', 'Uzytkownik usuniety.');
    }
}
