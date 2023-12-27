<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use Illuminate\Http\Request;
use PHPUnit\Framework\MockObject\Stub\ReturnCallback;

class ApiController extends Controller
{
    //CREATE api
    //URL http://127.0.0.1:8000/api/add-employee
    public function createEmployee(Request $request)
    {
        //validation
        $request->validate([
            "name" => "required",
            "email" => "required|email|unique:employees",
            "phone_no" => "required",
            "age" => "required",
            "gender" => "required"
        ]);
        //create
        $employee = new Employee();

        $employee->name = $request->name;
        $employee->email = $request->email;
        $employee->phone_no = $request->phone_no;
        $employee->age = $request->age;
        $employee->gender = $request->gender;

        $employee->save();
        //or
        // Employee::create([
        //     "name"=>$request->name
        //     //pass all the variables
        // ])

        //send responds
        return response()->json(
            [
                "status" => 1,
                "message" => "Employee created successfully"
            ]
        );
    }

    //List api
    //URL http://127.0.0.1:8000/api/list-employees
    public function listEmployees(Request $request)
    {
        $employees = Employee::get();

        // print_r($employees);

        return response()->json([
            "status" => 1,
            "message" => "Listing Employees",
            "data" => $employees
        ], 200);
    }

    //SINGLE DETAIL API
    //URL http://127.0.0.1:8000/api/single-employee/{id}
    public function getSingleEmployee($id)
    {
        if (Employee::where("id", $id)->exists()) {
            $employee_details = Employee::where("id", $id)->first();

            return response()->json([
                "status" => 1,
                "message" => "Employee found",
                "data" => $employee_details
            ]);
        } else {
            return response()->json([
                "status" => 0,
                "message" => "Employee not found"
            ], 404);
        }
    }

    //UPDATE API
    //http://127.0.0.1:8000/api/update-employee/{update}
    public function updateEmployee(Request $request, $id)
    {
        if (Employee::where("id", $id)) {
            $employee = Employee::find($id);

            $employee->name = !empty($request->name) ? $request->name : $employee->name;
            $employee->email = !empty($request->email) ? $request->email : $employee->email;
            $employee->phone_no = !empty($request->phone_no) ? $request->phone_no : $employee->phone_no;
            $employee->age = !empty($request->age) ? $request->age : $employee->age;
            $employee->gender = !empty($request->gender) ? $request->gender : $employee->gender;

            $employee->save();

            return response()->json([
                "status" => 1,
                "meassage" => "Employee updated successfully"
            ]);
        } else {
            return response()->json([
                "status" => 0,
                "message" => "Employee not found"
            ], 404);
        }
    }

    //DELETE API
    //http://127.0.0.1:8000/api/delete-employee/{id}
    public function deleteEmployee($id)
    {
        if (Employee::where("id", $id)) {

            $employee = Employee::find($id);

            $employee->delete();

            return response()->json([
                "status" => 1,
                "message" => "Employee deleted successfully"
            ]);
        } else {
            return response()->json([
                "status" => 0,
                "message" => "Employee not found"
            ], 404);
        }
    }
}
