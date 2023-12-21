<?php
namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Models\Employee;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            $token = $user->createToken('authToken')->plainTextToken;

            return response()->json(['token' => $token], 200);
        }

        return response()->json(['message' => 'Unauthorized'], 401);
    }

    public function user_listing(Request $request)
    {
        $ResultData = Employee::select('employee_id','first_name','last_name','employees.email as employee_email','employees.phone as employee_phone','companies.company_id','companies.name as company_name','companies.email as company_email','companies.logo as company_logo','companies.website as company_website')
            ->where('employees.status','0')
            ->orderBy('employees.created_at','DESC')
            ->leftjoin('companies', 'companies.company_id', 'employees.company_id')
            ->get();


        return response()->json($ResultData, 200);
    }
}
