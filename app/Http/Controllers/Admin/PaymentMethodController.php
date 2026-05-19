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
            'type' => 'required|in:bank_transfer,virtual_account,e_wallet',
            'bank_name' => 'nullable|max:255',
            'account_number' => 'nullable|max:255',
            'account_name' => 'nullable|max:255',
            'instruction' => 'nullable',
            'is_active' => 'required|boolean',
        ]);

        // PERBAIKAN: Baris logika yang mematikan metode lain dihapus agar admin bisa menambahkan metode pembayaran baru tanpa harus menonaktifkan metode yang sudah ada, sehingga admin bisa mengelola beberapa metode pembayaran sekaligus.
        // agar bisa memiliki lebih dari satu metode aktif.

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
        return view('admin.payment-methods.show', compact('paymentMethod'));
    }

    public function edit(PaymentMethod $paymentMethod)
    {
        return view('admin.payment-methods.edit', compact('paymentMethod'));
    }

    public function update(Request $request, PaymentMethod $paymentMethod)
    {
        $request->validate([
            'name' => 'required|max:255',
            'type' => 'required|in:bank_transfer,virtual_account,e_wallet',
            'bank_name' => 'nullable|max:255',
            'account_number' => 'nullable|max:255',
            'account_name' => 'nullable|max:255',
            'instruction' => 'nullable',
            'is_active' => 'required|boolean',
        ]);

        // PERBAIKAN: Baris "PaymentMethod::where('id', '!=', ...)->update(['is_active' => false]);" yang mematikan metode pembayaran lain dihapus
        // agar admin bisa mengaktifkan beberapa metode pembayaran sekaligus,
        // sehingga tidak perlu menonaktifkan metode yang sudah ada untuk mengaktifkan metode baru.
        // Dengan ini, admin bisa lebih fleksibel dalam mengelola metode pembayaran yang tersedia.
        // dihapus agar saat update status, tidak mematikan metode pembayaran lainnya.

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