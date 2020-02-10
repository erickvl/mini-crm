<?php

namespace App\Http\Controllers;

use App\Company;
use Illuminate\Http\Request;
use Datatables;
use Illuminate\Support\Facades\Auth;

class CompanyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        if ($request->ajax()) {
            $data = Company::latest()->get();
            // $btn .= '<a href="'.route('companies.delete', $row->id).'" class="edit btn btn-danger btn-sm mr-1"><i class="far fa-trash-alt"></i></a>';
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($row){

                        $btn = '<a href="'.route("companies.view", $row->id).'" class="edit btn btn-primary btn-sm mr-1"><i class="far fa-eye"></i></a>';
                        if ( Auth::user()->id ) {
                            $btn .= '<a href="'.route('companies.edit', $row->id).'" class="edit btn btn-primary btn-sm mr-1"><i class="far fa-edit"></i></a>';
                            $btn .= '<form style="display: inline-block;" method="POST" action="'.route('companies.delete', $row->id).'">';
                            $btn .= csrf_field();
                            $btn .= method_field('DELETE');
                            $btn .= '<button type="submit" class="edit btn btn-danger btn-sm mr-1"><i class="far fa-trash-alt"></i></button>';
                            $btn .= '</form>';
                        }

                        return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('company.list')
            ->with([
                'title' => 'Company List'
            ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('company.form')
            ->with([
                'title' => 'Add Company',
                'action' => 'add',
                'company' => null
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
        $request->validate([
            'name' => 'required|unique:companies|max:255',
            'email' => 'required|unique:companies|max:255',
            'website' => 'required',
            'logo' => 'required'
        ]);

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
     * @param  \App\Company  $company
     * @return \Illuminate\Http\Response
     */
    public function show(Company $company, $id)
    {
        $company = Company::findOrFail($id);

        return view('company.view')
            ->with([
                'title' => 'View Company',
                'action' => 'view',
                'company' => $company
            ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Company  $company
     * @return \Illuminate\Http\Response
     */
    public function edit(Company $company, $id)
    {
        //
        $data = [
            'title' => 'Edit Company',
            'action' => 'edit'
        ];

        $company = Company::findOrFail($id);

        return view('company.form')
            ->with([
                'data' => $data,
                'company' => $company
            ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Company  $company
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Company $company)
    {
        //
        $request->validate([
            'name' => 'required',
            'email' => 'required',
        ]);

        $company = Company::findOrFail($request->id);

        $company->update([
            'name' => $request->name,
            'email' => $request->email,
            'website' => $request->website ? $request->website : $company->website,
            'logo' => $request->logo ? $request->logo : $company->logo
        ]);

        return back()->with('message', 'Updated Successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Company  $company
     * @return \Illuminate\Http\Response
     */
    public function destroy(Company $company, $id)
    {
        $company = Company::findOrFail($id);

        if ( $company->delete() ) {
            return back()->with( 'success', 'Deleted Successfully');
        }
    }
}
