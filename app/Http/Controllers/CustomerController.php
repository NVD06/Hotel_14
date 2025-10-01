<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index(){ $customers = Customer::latest()->paginate(12); return view('customers.index', compact('customers')); }
    public function create(){ return view('customers.create'); }
    public function store(Request $r){
        $data = $r->validate([
            'full_name'=>['required','max:150'],
            'email'=>['nullable','email','max:150','unique:customers,email'],
            'phone'=>['nullable','max:30'],
            'id_number'=>['nullable','max:50'],
            'address'=>['nullable','max:255'],
            'date_of_birth'=>['nullable','date'],
            'nationality'=>['nullable','max:100'],
            'notes'=>['nullable','string'],
        ]);
        Customer::create($data);
        return redirect()->route('customers.index')->with('ok','Đã thêm khách hàng');
    }
    public function edit(Customer $customer){ return view('customers.edit', compact('customer')); }
    public function update(Request $r, Customer $customer){
        $data = $r->validate([
            'full_name'=>['required','max:150'],
            'email'=>['nullable','email','max:150','unique:customers,email,'.$customer->id],
            'phone'=>['nullable','max:30'],
            'id_number'=>['nullable','max:50'],
            'address'=>['nullable','max:255'],
            'date_of_birth'=>['nullable','date'],
            'nationality'=>['nullable','max:100'],
            'notes'=>['nullable','string'],
        ]);
        $customer->update($data);
        return redirect()->route('customers.index')->with('ok','Đã cập nhật');
    }
    public function destroy(Customer $customer){ $customer->delete(); return back()->with('ok','Đã xoá'); }
}
