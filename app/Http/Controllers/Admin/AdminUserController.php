<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Believer;
use App\Models\Team;
use App\Services\ActivityLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class AdminUserController extends Controller
{
    public function index(Request $request)
    {
        $users = User::with(['believer', 'roles'])
            ->when($request->search, function ($q) use ($request) {
                $q->where('name', 'like', "%{$request->search}%")
                  ->orWhere('username', 'like', "%{$request->search}%");
            })
            ->when($request->role, function ($q) use ($request) {
                $q->whereHas('roles', fn($r) => $r->where('name', $request->role));
            })
            ->orderBy('name')
            ->paginate(20)
            ->withQueryString();

        $roles = Role::orderBy('name')->get();

        return view('admin.users.index', compact('users', 'roles'));
    }

    public function create()
    {
        $believers = Believer::whereNotIn('status', ['depart', 'deces'])
            ->whereDoesntHave('user')
            ->orderBy('lastname')
            ->get(['id', 'lastname', 'firstname']);

        $roles = Role::orderBy('name')->get();
        $teams = Team::orderBy('name')->get();

        return view('admin.users.create', compact('believers', 'roles', 'teams'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'believer_id' => 'required|exists:believers,id',
            'role'        => 'required|exists:roles,name',
            'team_id'     => 'nullable|exists:teams,id',
        ], [
            'believer_id.required' => 'Veuillez sélectionner un fidèle.',
            'role.required'        => 'Veuillez attribuer un rôle.',
        ]);

        $believer = Believer::findOrFail($request->believer_id);

        // Vérifier que ce fidèle n'a pas déjà un compte
        if ($believer->user) {
            return redirect()->back()
                ->withErrors(['believer_id' => 'Ce fidèle possède déjà un compte utilisateur.'])
                ->withInput();
        }

        // Générer username et mot de passe temporaire
        $username    = User::generateUsername($believer->firstname, $believer->lastname);
        $tempPassword = User::generateTempPassword();

        $user = User::create([
            'believer_id'          => $believer->id,
            'name'                 => $believer->full_name,
            'username'             => $username,
            'email'                => $believer->address?->email ?? "{$username}@eglise.local",
            'password'             => Hash::make($tempPassword),
            'is_active'            => true,
            'must_change_password' => true,
        ]);

        ActivityLogger::log("A créé le compte utilisateur {$user->username} (rôle : {$request->role})");

        // Attribuer le rôle
        $user->assignRole($request->role);

        // Attribuer à une équipe si précisé
        if ($request->filled('team_id')) {
            $believer->teams()->syncWithoutDetaching([$request->team_id]);
        }

        return redirect()->route('users.index')
            ->with('user_created', [
                'name'     => $believer->full_name,
                'username' => $username,
                'password' => $tempPassword,
                'role'     => $request->role,
            ]);  
    }

    public function edit(User $user)
    {
        $user->load('believer', 'roles');
        $roles = Role::orderBy('name')->get();
        $teams = Team::orderBy('name')->get();

        return view('admin.users.edit', compact('user', 'roles', 'teams'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'role'    => 'required|exists:roles,name',
            'team_id' => 'nullable|exists:teams,id',
        ]);

        // Mettre à jour le rôle
        $user->syncRoles([$request->role]);

        // Mettre à jour l'équipe
        if ($request->filled('team_id') && $user->believer) {
            $user->believer->teams()->syncWithoutDetaching([$request->team_id]);
        }

        return redirect()->route('users.index')
            ->with('success', "Compte de {$user->name} mis à jour.");
    }

    public function toggleActive(User $user)
    {
        $user->update(['is_active' => !$user->is_active]);

        $status = $user->is_active ? 'activé' : 'désactivé';
        ActivityLogger::log("A {$status} le compte {$user->username}");

        return redirect()->back()
            ->with('success', "Compte de {$user->name} {$status}.");
    }

    public function resetPassword(User $user)
    {
        $tempPassword = User::generateTempPassword();

        $user->update([
            'password'             => Hash::make($tempPassword),
            'must_change_password' => true,
            'password_changed_at'  => null,
        ]);
        
        ActivityLogger::log("A réinitialisé le mot de passe de {$user->username}");

        return redirect()->back()
            ->with('password_reset', [
                'name'     => $user->name,
                'username' => $user->username,
                'password' => $tempPassword,
            ]);
    }

    public function destroy(User $user)
    {
        if ($user->hasRole('admin') && User::role('admin')->count() <= 1) {
            return redirect()->back()
                ->with('error', 'Impossible de supprimer le seul administrateur.');
        }

        ActivityLogger::log("A supprimé le compte utilisateur {$user->username}");

        $user->delete();

        return redirect()->route('users.index')
            ->with('success', "Compte supprimé.");
    }
}