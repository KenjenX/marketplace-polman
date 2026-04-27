<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PaymentMethod;
use Illuminate\Http\Request;

class PaymentMethodController extends Controller
{
    public function index()
    {
        $paymentMethods = PaymentMethod::latest()->get();
        return view('admin.payment-methods.index', compact('paymentMethods'));
    }

    public function create()
    {
        return view('admin.payment-methods.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:255',
            'type' => 'required|in:bank_transfer',
            'bank_name' => 'nullable|max:255',
            'account_number' => 'nullable|max:255',
            'account_name' => 'nullable|max:255',
            'instruction' => 'nullable',
            'is_active' => 'required|boolean',
        ]);

        if ((bool) $request->is_active === true) {
            PaymentMethod::query()->update(['is_active' => false]);
        }

        PaymentMethod::create([
            'name' => $request->name,
            'type' => $request->type,
            'bank_name' => $request->bank_name,
            'account_number' => $request->account_number,
            'account_name' => $request->account_name,
            'instruction' => $request->instruction,
            'is_active' => $request->is_active,
        ]);

        return redirect()->route('admin.payment-methods.index')
            ->with('success', 'Metode pembayaran berhasil ditambahkan.');
    }

    public function show(PaymentMethod $paymentMethod)
    {
        //
    }

    public function edit(PaymentMethod $paymentMethod)
    {
        return view('admin.payment-methods.edit', compact('paymentMethod'));
    }

    public function update(Request $request, PaymentMethod $paymentMethod)
    {
        $request->validate([
            'name' => 'required|max:255',
            'type' => 'required|in:bank_transfer',
            'bank_name' => 'nullable|max:255',
            'account_number' => 'nullable|max:255',
            'account_name' => 'nullable|max:255',
            'instruction' => 'nullable',
            'is_active' => 'required|boolean',
        ]);

        if ((bool) $request->is_active === true) {
            PaymentMethod::where('id', '!=', $paymentMethod->id)->update(['is_active' => false]);
        }

        $paymentMethod->update([
            'name' => $request->name,
            'type' => $request->type,
            'bank_name' => $request->bank_name,
            'account_number' => $request->account_number,
            'account_name' => $request->account_name,
            'instruction' => $request->instruction,
            'is_active' => $request->is_active,
        ]);

        return redirect()->route('admin.payment-methods.index')
            ->with('success', 'Metode pembayaran berhasil diupdate.');
    }

    public function destroy(PaymentMethod $paymentMethod)
    {
        $paymentMethod->delete();

        return redirect()->route('admin.payment-methods.index')
            ->with('success', 'Metode pembayaran berhasil dihapus.');
    }
}