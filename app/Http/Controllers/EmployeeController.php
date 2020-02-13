<?php

namespace App\Http\Controllers;

use App\Company;
use App\Employee;
use App\Exports\EmployeesExportMapping;
use App\Imports\EmployeesImport;
use Illuminate\Http\Request;
use Datatables;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $employees = Employee::all();
            return Datatables::of($employees)
                ->addIndexColumn()
                ->addColumn('full_name', function($row){
                    return $row->first_name . ' ' . $row->last_name;
                })
                ->addColumn('company', function($row){
                    return $row->company->name;
                })
                ->addColumn('action', function($row){

                        if ( Auth::guard('web')->check() ) {
                            $btn = '<a href="'.route("admin.employees.view", $row->id).'" class="edit btn btn-primary btn-sm mr-1"><i class="far fa-eye"></i></a>';
                            $btn .= '<a href="'.route('admin.employees.edit', $row->id).'" class="edit btn btn-primary btn-sm mr-1"><i class="far fa-edit"></i></a>';
                            $btn .= '<form style="display: inline-block;" method="POST" action="'.route('admin.employees.delete', $row->id).'">';
                            $btn .= csrf_field();
                            $btn .= method_field('DELETE');
                            $btn .= '<button type="submit" class="edit btn btn-danger btn-sm"><i class="far fa-trash-alt"></i></button>';
                            $btn .= '</form>';
                        } else {
                            $btn = '<a href="'.route("employees.view", $row->id).'" class="edit btn btn-primary btn-sm mr-1"><i class="far fa-eye"></i></a>';
                        }
    
                        return $btn;
                })
                ->rawColumns(['full_name','company', 'action'])
                ->make(true);
        }

        $companies = Company::all();
        return view('employee.list')
            ->with([
                'title' => 'Employee List',
                'companies' => $companies
            ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('employee.form')
        ->with([
            'title' => 'Add Employee',
            'action' => 'add',
            'employee' => null,
            'companies' => Company::all()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $request->validate([
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|unique:employees|max:255',
            'password' => 'required',
            'company_id' => 'required'
        ]);
        
        $data = [
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'company_id' => $request->company_id,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone' => $request->phone,
        ];

        $employee = Employee::create($data);

        if ( $employee ) {
            return back()->with([
                'title' => 'Add Company',
                'action' => 'add',
                'message' => 'Added Successfully'
            ]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Employee  $employee
     * @return \Illuminate\Http\Response
     */
    public function show(Employee $employee, $id)
    {
        //
        $employee = Employee::findOrFail($id);

        return view('employee.view')
            ->with([
                'title' => 'View Employee',
                'action' => 'view',
                'employee' => $employee
            ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Employee  $employee
     * @return \Illuminate\Http\Response
     */
    public function edit(Employee $employee, $id)
    {
        if ( Auth::guard('employee')->check() ) {
            if ( Auth::guard('employee')->id() != $id ) {
                return redirect('/employees');
            }
        } 
        $employee = Employee::findOrFail($id);
        $companies = Company::all();

        return view('employee.form')
            ->with([
                'title' => 'Edit Company',
                'action' => 'edit',
                'employee' => $employee,
                'companies' => $companies
            ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Employee  $employee
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Employee $employee)
    {
        //
        $request->validate([
            'first_name' => 'required',
            'last_name' => 'required',
            'password' => 'required',
            'email' => 'required',
        ]);

        $employee = Employee::findOrFail($request->id);

        $employee->update([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'password' => $request->password,
            'phone' => $request->phone ? $request->phone : $employee->phone,
            'company_id' => $request->company_id,
        ]);

        return back()->with('message', 'Updated Successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Employee  $employee
     * @return \Illuminate\Http\Response
     */
    public function destroy(Employee $employee, $id)
    {
        //
        $employee = Employee::findOrFail($id);

        if ( $employee->delete() ) {
            return back()->with( 'success', 'Deleted Successfully');
        }
    }


    public function export($type = 'xlsx', $id = null)
    {
        return Excel::download(new EmployeesExportMapping($id), 'employees.'.$type);
    }

    public function import() 
    {
        return view('employee.import')->with([
            'title' => 'Import Employees',
        ]);
    }

    public function importSave(Request $request) 
    {
        $request->validate([
            'import_file'  => 'required|mimes:csv,xlsx,txt',
        ]);

        Excel::import(new EmployeesImport, $request->import_file);

        return back()->with('message', 'Imported Successfully');
    }
    
}
