<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Helpers\ResponseHelper;
use App\Models\Company;
Use Auth;
use Illuminate\Support\Facades\Storage;


class CompanyController extends Controller
{
    public function index()
    {
        $data['PageMenu'] = 'Company';
        $data['PageName'] = 'View';
        return view('company.view', compact('data'));
    }

    public function list(Request $request)
    {
        if($request->ajax())
        {
            $limit = $request->length;
            $start = $request->start;
            $SearchKey = $request->search['value'];

            $ResultQuery = Company::where('status','0')->orderBy('created_at','DESC');
            $FilteredData = $TotalData = $ResultQuery->count();

            if($SearchKey==null)
            {
                $ResultData = $ResultQuery->offset($start)->limit($limit)->get();
            }
            else
            {
                $ResultQuery = Company::where('status', '0')
                    ->where(function ($query) use ($SearchKey) {
                        $query->where('name', 'LIKE', '%' . $SearchKey . '%')
                        ->orWhere('email', 'LIKE', '%' . $SearchKey . '%')
                        ->orWhere('website', 'LIKE', '%' . $SearchKey . '%');
                    })
                    ->orderBy('created_at','DESC');

                $ResultData = $ResultQuery->offset($start)->limit($limit)->get();
                $FilteredData = $ResultQuery->count();
            }

            $Results = array();
            $i = $start;
            foreach($ResultData as $DT1)
            {
                $i++;
                $RowData = array();
                $imageSrc = asset('storage/'.$DT1->logo);

                $actions = '<a href="'.url('company/edit/'.$DT1->company_id).'" class="btn btn-warning waves-effect waves-light btn-sm mr5">EDIT</a>';
                $actions .= '<a href="#" onclick="deleteRow('.$DT1->company_id.')" class="btn btn-danger waves-effect waves-light btn-sm mr5">DELETE</a>';

                $image = '<img class="img-fluid tableImageView" src="'.$imageSrc.'" onclick="viewImage(\''.$imageSrc.'\')">';

                $RowData['id'] = $i;
                $RowData['name'] = $DT1->name;
                $RowData['email'] = $DT1->email;
                $RowData['website'] = $DT1->website;
                $RowData['logo'] = $image;
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
            $rules = ['deleteId' => 'required|numeric|exists:companies,company_id'];
            $validator = Validator::make($request->all(), $rules);

            if($validator->fails())
            {
                $errorMessage = implode(', ', $validator->errors()->all());
                return ResponseHelper::error($errorMessage);
            }

            $DeleteData = Company::find($request->deleteId);
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
        $data['PageMenu'] = 'Company';
        $data['PageName'] = 'Company Create';
        return view('company.create', compact('data'));
    }

    public function store(Request $request)
    {
        try {
            $rules = [
                'name' => 'required|string',
                'email' => 'nullable|email',
                'website' => 'nullable|url',
                'logo' => 'nullable|image|mimes:webp|max:100',
            ];

            $validator = Validator::make($request->all(), $rules);

            if($validator->fails())
            {
                $errorMessage = implode(', ', $validator->errors()->all());
                return ResponseHelper::error($errorMessage);
            }

            $createData = new Company();
            $createData->name = ucwords($request->name);
            $createData->email = strtolower($request->email);
            $createData->website = $request->website;
            $createData->save();

            if($request->hasFile('logo'))
            {
                $logoPath = $request->file('logo')->store('company', 'public');
                if($logoPath)
                {
                    $createData->logo = $logoPath;
                    $createData->save();
                }
            }

            return ResponseHelper::success('Created!');
        } catch (\Exception $e) {
            return ResponseHelper::error('An error occurred.');
        }
    }

    public function edit($id)
    {
        $data['PageMenu'] = 'Company';
        $data['PageName'] = 'Company Edit';

        $ResultData = Company::find($id);

        if(empty($ResultData)){
            return back();
        }

        return view('company.edit', compact('data', 'ResultData'));
    }

    public function update(Request $request)
    {
        try {
            $rules = [
                'company_id' => 'required|numeric|exists:companies,company_id',
                'name' => 'required|string',
                'email' => 'nullable|email',
                'website' => 'nullable|url',
                'logo' => 'nullable|image|mimes:webp|max:100',
            ];

            $validator = Validator::make($request->all(), $rules);

            if($validator->fails())
            {
                $errorMessage = implode(', ', $validator->errors()->all());
                return ResponseHelper::error($errorMessage);
            }

            $createData = Company::find($request->company_id);
            $createData->name = ucwords($request->name);
            $createData->email = strtolower($request->email);
            $createData->website = $request->website;
            $createData->save();

            if($request->hasFile('logo'))
            {
                $logoPath = $request->file('logo')->store('company', 'public');
                if($logoPath)
                {
                    $createData->logo = $logoPath;
                    $createData->save();
                }
            }

            return ResponseHelper::success('Upadted!');
        } catch (\Exception $e) {
            return ResponseHelper::error('An error occurred.');
        }
    }
}
