<?php

namespace App\Http\Controllers;

use App\Email;
use App\Employee;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class EmailController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        return view('email.form')
            ->with([
                'title' => 'Compose Email',
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
            'receiver' => 'required',
            'subject' => 'required',
            'message' => 'required',
        ]);

        $deliveredVal = false;

        if ( !$request->email_schedule || Carbon::now()->isSameDay( Carbon::parse( $request->email_schedule ) ) ) {
            $deliveredVal = true;
        }

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
            'receiver' => $request->receiver,
            'subject' => $request->subject, 
            'message' =>  $request->message,
            'delivered' => $deliveredVal,
            'email_date' => !$request->email_schedule ? Carbon::now()->format('Y-m-d') : Carbon::parse( $request->email_schedule )->format('Y-m-d'),
        ];

        $email = Email::create([
            'sender' => $senderName,
            'receiver' => $request->receiver,
            'subject' => $request->subject, 
            'message' =>  $request->message,
            'delivered' => $deliveredVal,
            'email_date' => !$request->email_schedule ? Carbon::now()->format('Y-m-d') : Carbon::parse( $request->email_schedule )->format('Y-m-d'),
        ]);

        if ( $email ) {
            // Check if email was scheduled
            if ( !Carbon::now()->isSameDay( Carbon::parse($data['email_date']) ) ) {
                return back()->with([
                    'message' => 'Email Scheduled Successfully'
                ]);
            }

            Mail::send('email.reminder', ['data' => $data], function ($m) use ($data) {
                $m->from( env('MAIL_FROM_ADDRESS', 'hello@example.com'), env('APP_NAME', 'MINI_CRM'));
    
                $m->to($data['receiver'], '')->subject($data['subject']);
            });

            return back()->with([
                'message' => 'Email Successfully Sent'
            ]);

            
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Email  $email
     * @return \Illuminate\Http\Response
     */
    public function show(Email $email)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Email  $email
     * @return \Illuminate\Http\Response
     */
    public function edit(Email $email)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Email  $email
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Email $email)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Email  $email
     * @return \Illuminate\Http\Response
     */
    public function destroy(Email $email)
    {
        //
    }
}
