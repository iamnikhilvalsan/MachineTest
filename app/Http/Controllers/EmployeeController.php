<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Helpers\ResponseHelper;
use App\Models\Company;
use App\Models\Employee;
Use Auth;


class EmployeeController extends Controller
{
    public function index()
    {
        $data['PageMenu'] = 'Employees';
        $data['PageName'] = 'Employees View';
        return view('employees.view', compact('data'));
    }

    public function list(Request $request)
    {
        if($request->ajax())
        {
            $limit = $request->length;
            $start = $request->start;
            $SearchKey = $request->search['value'];

            $ResultQuery = Employee::select('companies.name', 'employees.*')
                ->where('employees.status','0')
                ->orderBy('employees.created_at','DESC')
                ->leftjoin('companies', 'companies.company_id', 'employees.company_id');

            $FilteredData = $TotalData = $ResultQuery->count();

            if($SearchKey==null)
            {
                $ResultData = $ResultQuery->offset($start)->limit($limit)->get();
            }
            else
            {
                $ResultQuery = Employee::select('companies.name', 'employees.*')
                    ->where('employees.status','0')
                    ->leftjoin('companies', 'companies.company_id', 'employees.company_id')
                    ->where(function ($query) use ($SearchKey) {
                        $query->where('employees.first_name', 'LIKE', '%' . $SearchKey . '%')
                        ->orWhere('employees.last_name', 'LIKE', '%' . $SearchKey . '%')
                        ->orWhere('companies.name', 'LIKE', '%' . $SearchKey . '%')
                        ->orWhere('employees.email', 'LIKE', '%' . $SearchKey . '%')
                        ->orWhere('employees.phone', 'LIKE', '%' . $SearchKey . '%');
                    })
                    ->orderBy('employees.created_at','DESC');

                $ResultData = $ResultQuery->offset($start)->limit($limit)->get();
                $FilteredData = $ResultQuery->count();
            }

            $Results = array();
            $i = $start;
            foreach($ResultData as $DT1)
            {
                $i++;
                $RowData = array();
                $actions = '<a href="'.url('employee/edit/'.$DT1->employee_id).'" class="btn btn-warning waves-effect waves-light btn-sm mr5">EDIT</a>';
                $actions .= '<a href="#" onclick="deleteRow('.$DT1->employee_id.')" class="btn btn-danger waves-effect waves-light btn-sm mr5">DELETE</a>';

                $RowData['id'] = $i;
                $RowData['name'] = $DT1->first_name.' '.$DT1->last_name;
                $RowData['company'] = $DT1->name;
                $RowData['email'] = $DT1->email;
                $RowData['phone'] = $DT1->phone;
                $RowData['created_at'] = date('d M, Y', strtotime($DT1->created_at));
                $RowData['actions'] = $actions;
                $Results[] = $RowData;
            }

            // Finalising response
            $Response = array();
            $Response['draw'] = intval($request->draw);
            $Response['recordsTotal'] = intval($TotalData);
            $Response['recordsFiltered'] = intval($FilteredData);
            $Response['data'] = $Results;

            echo json_encode($Response);
        }
    }

    public function delete(Request $request)
    {
        try {
            $rules = ['deleteId' => 'required|numeric|exists:employees,employee_id'];
            $validator = Validator::make($request->all(), $rules);

            if($validator->fails())
            {
                $errorMessage = implode(', ', $validator->errors()->all());
                return ResponseHelper::error($errorMessage);
            }

            $DeleteData = Employee::find($request->deleteId);
            if(!$DeleteData)
            {
                return ResponseHelper::error('Invalid data.');
            }

            $DeleteData->status = '1';
            $DeleteData->save();

            return ResponseHelper::success('Deleted!');
        } catch (\Exception $e) {
            return ResponseHelper::error('An error occurred.');
        }
    }

    public function create()
    {
        $data['PageMenu'] = 'Employee';
        $data['PageName'] = 'Employee Create';

        $CompanyList = Company::orderBy('name', 'ASC')->get();
        return view('employees.create', compact('data', 'CompanyList'));
    }

    public function store(Request $request)
    {
        try {
            $rules = [
                'company_id' => 'required|numeric|exists:companies,company_id',
                'first_name' => 'required|string',
                'last_name' => 'required|string',
                'email' => 'nullable|email',
                'phone' => 'nullable|numeric',
            ];

            $validator = Validator::make($request->all(), $rules);

            if($validator->fails())
            {
                $errorMessage = implode(', ', $validator->errors()->all());
                return ResponseHelper::error($errorMessage);
            }

            $createData = new Employee();
            $createData->company_id = $request->company_id;
            $createData->first_name = ucwords($request->first_name);
            $createData->last_name = ucwords($request->last_name);
            $createData->email = strtolower($request->email);
            $createData->phone = $request->phone;
            $createData->save();

            return ResponseHelper::success('Created!');
        } catch (\Exception $e) {
            return ResponseHelper::error('An error occurred.');
        }
    }

    public function edit($id)
    {
        $data['PageMenu'] = 'Employee';
        $data['PageName'] = 'Employee Edit';

        $ResultData = Employee::find($id);
        $CompanyList = Company::orderBy('name', 'ASC')->get();

        if(empty($ResultData)){
            return back();
        }

        return view('employees.edit', compact('data', 'ResultData', 'CompanyList'));
    }

    public function update(Request $request)
    {
        try {
            $rules = [
                'employee_id' => 'required|numeric|exists:employees,employee_id',
                'company_id' => 'required|numeric|exists:companies,company_id',
                'first_name' => 'required|string',
                'last_name' => 'required|string',
                'email' => 'nullable|email',
                'phone' => 'nullable|numeric',
            ];

            $validator = Validator::make($request->all(), $rules);

            if($validator->fails())
            {
                $errorMessage = implode(', ', $validator->errors()->all());
                return ResponseHelper::error($errorMessage);
            }

            $createData = Employee::find($request->employee_id);
            $createData->company_id = $request->company_id;
            $createData->first_name = ucwords($request->first_name);
            $createData->last_name = ucwords($request->last_name);
            $createData->email = strtolower($request->email);
            $createData->phone = $request->phone;
            $createData->save();

            return ResponseHelper::success('Upadted!');
        } catch (\Exception $e) {
            return ResponseHelper::error('An error occurred.');
        }
    }
}
