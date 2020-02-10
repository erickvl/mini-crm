<?php

namespace App\Http\Controllers;

use App\Company;
use App\Employee;
use Illuminate\Http\Request;
use Datatables;
use Illuminate\Support\Facades\Auth;

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

                        $btn = '<a href="'.route("employees.view", $row->id).'" class="edit btn btn-primary btn-sm mr-1"><i class="far fa-eye"></i></a>';
                        if ( Auth::user()->id ) {
                            $btn .= '<a href="'.route('employees.edit', $row->id).'" class="edit btn btn-primary btn-sm mr-1"><i class="far fa-edit"></i></a>';
                            $btn .= '<form style="display: inline-block;" method="POST" action="'.route('employees.delete', $row->id).'">';
                            $btn .= csrf_field();
                            $btn .= method_field('DELETE');
                            $btn .= '<button type="submit" class="edit btn btn-danger btn-sm mr-1"><i class="far fa-trash-alt"></i></button>';
                            $btn .= '</form>';
                        }
    
                        return $btn;
                })
                ->rawColumns(['full_name','company', 'action'])
                ->make(true);
        }


        return view('employee.list')
            ->with([
                'title' => 'Employee List'
            ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
            'email' => 'required|unique:companies|max:255',
            'password' => 'required',
            'phone' => 'required',
            'company' => 'required'
        ]);
        
        $data = [
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'password' => $request->password,
            'phone' => $request->phone,
            'company' => $request->company
        ];
        dd($data);

        $company = Company::create($request->all());

        return back()->with([
            'title' => 'Add Company',
            'action' => 'add',
            'message' => 'Added Successfully'
        ]);
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
    public function edit(Employee $employee)
    {
        //
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
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Employee  $employee
     * @return \Illuminate\Http\Response
     */
    public function destroy(Employee $employee)
    {
        //
    }
}
