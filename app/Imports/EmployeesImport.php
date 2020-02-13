<?php

namespace App\Imports;

use App\Employee;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;

class EmployeesImport implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new Employee([
            'first_name' => $row[0],
            'last_name' => $row[1],
            'email' => $row[2],
            'password' => Hash::make( $row[3] ),
            'company_id' => $row[4],
            'phone' => $row[5]
        ]);
    }
}
