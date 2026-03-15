<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Budget;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ClientAreaController extends Controller
{
    // Mostrar login
    public function showLoginForm()
    {
        return view('client-area.login');
    }

    // Procesar login
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Buscar cliente con ese email y que pueda login
        $client = Client::where('email', $credentials['email'])
            ->where('can_login', true)
            ->first();

        if ($client && Hash::check($credentials['password'], $client->password)) {
            Auth::guard('client')->login($client);
            return redirect()->intended('/mi-area');
        }

        return back()->withErrors([
            'email' => 'Las credenciales no son correctas o no tienes acceso al área privada.',
        ]);
    }

    // Registro
    public function showRegisterForm()
    {
        return view('client-area.register');
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:clients',
            'nif_cif' => 'required|string|max:20',
            'phone' => 'nullable|string|max:20',
            'password' => 'required|min:8|confirmed',
        ]);

        $client = Client::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'nif_cif' => $validated['nif_cif'],
            'phone' => $validated['phone'] ?? null,
            'password' => Hash::make($validated['password']),
            'can_login' => true,
        ]);

        Auth::guard('client')->login($client);

        return redirect('/mi-area')->with('success', '¡Registro completado!');
    }

    // Dashboard del cliente
    public function dashboard()
    {
        $client = Auth::guard('client')->user();
        $budgets = $client->budgets()
            ->with('lines')
            ->latest()
            ->paginate(10);

        return view('client-area.dashboard', compact('budgets'));
    }

    // Ver presupuesto
    public function viewBudget($id)
    {
        $client = Auth::guard('client')->user();
        $budget = $client->budgets()
            ->with(['lines.material'])
            ->findOrFail($id);

        return view('client-area.budget-view', compact('budget'));
    }

    // Aceptar presupuesto
    public function acceptBudget($id)
    {
        $client = Auth::guard('client')->user();
        $budget = $client->budgets()->findOrFail($id);
        $budget->update(['status' => 'approved']);

        return back()->with('success', 'Presupuesto aceptado correctamente.');
    }

    // Rechazar presupuesto
    public function rejectBudget($id)
    {
        $client = Auth::guard('client')->user();
        $budget = $client->budgets()->findOrFail($id);
        $budget->update(['status' => 'rejected']);

        return back()->with('success', 'Presupuesto rechazado.');
    }

    // Descargar PDF
    public function downloadPdf($id)
    {
        $client = Auth::guard('client')->user();
        $budget = $client->budgets()->findOrFail($id);

        return redirect()->route('public.budget.pdf', ['token' => $budget->public_token]);
    }

    // Logout
    public function logout()
    {
        Auth::guard('client')->logout();
        return redirect('/client-login')->with('success', 'Sesión cerrada correctamente.');
    }
}