<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Tenant\Employee;
use Illuminate\Http\Request;

class EmployeeAuthController extends Controller
{
    public function login()
    {
        return view('employee.login');
    }

    public function postLogin(Request $request)
    {
        $request->validate([
            'email' => 'required|exists:employees,email',
            'password' => 'required',
        ]);

        $employee = Employee::where('email', $request->email)->first();
        if (!$employee) {
            return back()->with('error', 'Email not found')->withInput();
        }

        if (($employee->status ?? null) !== 'active') {
            return back()->with('error', 'Your account is not active. Please contact HR.')->withInput();
        }

        if (!auth(EMPLOYEES_GUARD)->attempt($request->only(['email', 'password']))) {
            return back()->with('error', 'Incorrect password!')->withInput();
        }

        return redirect()->route('employee.dashboard');
    }

    public function logout()
    {
        auth(EMPLOYEES_GUARD)->logout();
        session()->flush();

        return redirect()->route('employee.login');
    }
}
