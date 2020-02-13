<?php

namespace App\Http\Controllers;

use App\Company;
use App\Email;
use App\Employee;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Datatables;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

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
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($row){

                        if ( Auth::guard('web')->check() ) {
                            $btn = '<a href="'.route("admin.companies.view", $row->id).'" class="edit btn btn-primary btn-sm mr-1"><i class="far fa-eye"></i></a>';
                            $btn .= '<a href="'.route('admin.companies.edit', $row->id).'" class="edit btn btn-primary btn-sm mr-1"><i class="far fa-edit"></i></a>';
                            $btn .= '<form style="display: inline-block;" method="POST" action="'.route('admin.companies.delete', $row->id).'">';
                            $btn .= csrf_field();
                            $btn .= method_field('DELETE');
                            $btn .= '<button type="submit" class="edit btn btn-danger btn-sm mr-1"><i class="far fa-trash-alt"></i></button>';
                            $btn .= '</form>';
                        } else {
                            $btn = '<a href="'.route("companies.view", $row->id).'" class="edit btn btn-primary btn-sm mr-1"><i class="far fa-eye"></i></a>';    
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
            'email' => 'unique:companies|max:255',
            'logo' => 'image|mimes:jpeg,png,jpg|max:2048'
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'website' => $request->website,
            'logo' => $request->hasFile('logo') ? $this->saveImage($request->logo) : 'https://image.shutterstock.com/image-vector/abstract-green-swirl-logo-sample-260nw-413126230.jpg',
        ];

        $company = Company::create($data);

        if ( $company ) {

            if ( Auth::guard('web')->check() ) {
                $senderName = Auth::user()->name;
                $senderFrom = Auth::user()->email;
            } else {
                $employee = Employee::find( Auth::guard('employee')->id() );
                $senderName = $employee->first_name . ' ' . $employee->last_name;
                $senderFrom = $employee->email;
            }

            $data = [
                'sender' => $senderName,
                'sender_from' => $senderFrom,
                'receiver' => $request->email,
                'subject' => 'Company Added', 
                'message' =>  'The company successfully added to the listing.',
                'delivered' => true,
                'email_date' => Carbon::now()->format('Y-m-d'),
            ];

            Email::create([
                'sender' => $senderName,
                'receiver' => $request->email,
                'subject' => 'Company Added',
                'delivered' => true,
                'message' =>  'The company successfully added to the listing.',
                'email_date' => Carbon::now()->format('Y-m-d'),
            ]);

            Mail::send('email.reminder', ['data' => $data], function ($m) use ($data) {
                $m->from( $data['sender_from'], 'MINI_CRM');
    
                $m->to($data['receiver'], '')->subject($data['subject']);
            });

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
        $company = Company::findOrFail($id);

        return view('company.form')
            ->with([
                'title' => 'Edit Company',
                'action' => 'edit',
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
            'logo' => $request->hasFile('logo') ? $this->saveImage($request->logo) : $company->logo
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

    protected function saveImage($image) 
    {
        $fileName = rand(1, 999) . $image->getClientOriginalName();

        $image->move(public_path('/images'), $fileName);

        return $fileName;
    }
}
