<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Http\Requests\Customer\StoreCustomerRequest;
use App\Http\Requests\Customer\UpdateCustomerRequest;

class CustomerController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:view customer')->only(['index', 'show']);
        $this->middleware('permission:create customer')->only(['create', 'store']);
        $this->middleware('permission:update customer')->only(['edit', 'update']);
        $this->middleware('permission:delete customer')->only(['destroy']);
        $this->middleware('deny.demo')->only(['create', 'store', 'edit', 'update', 'destroy']);
    }

    public function index()
    {
        $customers = Customer::all();

        return view('customers.index', [
            'customers' => $customers
        ]);
    }

    public function create()
    {
        return view('customers.create');
    }

    public function store(StoreCustomerRequest $request)
    {
        $customer = Customer::create($request->all());

        /**
         * Handle upload an image
         */
        if ($request->hasFile('photo')) {
            $file = $request->file('photo');
            $filename = hexdec(uniqid()) . '.' . $file->getClientOriginalExtension();

            $file->storeAs('customers/', $filename, 'public');
            $customer->update([
                'photo' => $filename
            ]);
        }

        return redirect()
            ->route('customers.index')
            ->with('success', 'New customer has been created!');
    }

    public function show(Customer $customer)
    {
        $customer->loadMissing(['quotations', 'orders'])->get();

        return view('customers.show', [
            'customer' => $customer
        ]);
    }

    public function edit(Customer $customer)
    {
        return view('customers.edit', [
            'customer' => $customer
        ]);
    }

    public function update(UpdateCustomerRequest $request, Customer $customer)
    {
        //
        $customer->update($request->except('photo'));

        if ($request->hasFile('photo')) {

            // Delete Old Photo
            if ($customer->photo) {
                unlink(public_path('storage/customers/') . $customer->photo);
            }

            // Prepare New Photo
            $file = $request->file('photo');
            $fileName = hexdec(uniqid()) . '.' . $file->getClientOriginalExtension();

            // Store an image to Storage
            $file->storeAs('customers/', $fileName, 'public');

            // Save DB
            $customer->update([
                'photo' => $fileName
            ]);
        }

        return redirect()
            ->route('customers.index')
            ->with('success', 'Customer has been updated!');
    }

    public function destroy(Customer $customer)
    {
        // Check if customer has related orders
        if ($customer->orders()->count() > 0) {
            // If there are related orders, return an error message
            return redirect()
                ->route('customers.index')
                ->with('error', 'This customer has related orders. Deletion is not allowed.');
        }

        if ($customer->photo) {
            unlink(public_path('storage/customers/') . $customer->photo);
        }

        $customer->delete();

        return redirect()
            ->back()
            ->with('success', 'Customer has been deleted!');
    }
}
