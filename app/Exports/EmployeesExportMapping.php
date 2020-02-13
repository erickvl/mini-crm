<?php

namespace App\Exports;

use App\Employee;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class EmployeesExportMapping implements FromCollection, WithMapping, WithHeadings
{

    public function __construct($id)
    {
        $this->id = $id;
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        if ( !$this->id ) {
            return Employee::with('company')->get();
        }
        
        return Employee::where('company_id', $this->id)->with('company')->get();
    }

    public function map($employee) : array {
        return [
            $employee->id,
            $employee->first_name,
            $employee->last_name,
            $employee->email,
            $employee->phone,
            $employee->company->name
        ] ;
 
 
    }
 
    public function headings() : array {
        return [
           'Employee ID',
           'First Name',
           'Last Name',
           'Email',
           'Phone',
           'Company'
        ] ;
    }
}
