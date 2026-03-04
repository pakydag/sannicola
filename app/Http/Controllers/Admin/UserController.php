<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Mail\AdminUserCreated;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::where('role', 'admin')->get();
        return view('admin.users.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.users.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'surname' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'is_super_admin' => 'boolean',
            'can_manage_site' => 'boolean',
            'can_manage_shop' => 'boolean',
            'can_manage_booking' => 'boolean',
        ]);

        $password = $this->generatePassword();

        $user = User::create([
            'name' => $request->name,
            'surname' => $request->surname,
            'email' => $request->email,
            'password' => Hash::make($password),
            'role' => 'admin',
            'is_super_admin' => $request->has('is_super_admin'),
            'can_manage_site' => $request->has('can_manage_site'),
            'can_manage_shop' => $request->has('can_manage_shop'),
            'can_manage_booking' => $request->has('can_manage_booking'),
        ]);

        Mail::to($user->email)->send(new AdminUserCreated($user, $password));

        return redirect()->route('admin.users.index')->with('success', 'Amministratore creato con successo e notificato via email.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'surname' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'is_super_admin' => 'boolean',
            'can_manage_site' => 'boolean',
            'can_manage_shop' => 'boolean',
            'can_manage_booking' => 'boolean',
        ]);

        $user->update([
            'name' => $request->name,
            'surname' => $request->surname,
            'email' => $request->email,
            'is_super_admin' => $request->has('is_super_admin'),
            'can_manage_site' => $request->has('can_manage_site'),
            'can_manage_shop' => $request->has('can_manage_shop'),
            'can_manage_booking' => $request->has('can_manage_booking'),
        ]);

        return redirect()->route('admin.users.index')->with('success', 'Amministratore aggiornato con successo.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        if ($user->email === 'admin@admin.com') {
            return back()->with('error', 'Impossibile eliminare l\'amministratore principale.');
        }

        $user->delete();
        return redirect()->route('admin.users.index')->with('success', 'Amministratore eliminato con successo.');
    }

    /**
     * Generate a secure random password.
     */
    private function generatePassword($length = 12)
    {
        $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()-_=+';
        $password = '';
        
        // Ensure at least one uppercase, one lowercase, one number, and one special character
        $password .= $characters[rand(26, 51)]; // Uppercase
        $password .= $characters[rand(0, 25)];  // Lowercase
        $password .= $characters[rand(52, 61)]; // Number
        $password .= $characters[rand(62, strlen($characters) - 1)]; // Special
        
        for ($i = 0; $i < $length - 4; $i++) {
            $password .= $characters[rand(0, strlen($characters) - 1)];
        }
        
        return str_shuffle($password);
    }
}
