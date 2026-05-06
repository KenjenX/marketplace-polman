<?php



namespace App\Http\Controllers;



use App\Models\Cart;

use App\Models\ProductVariant;

use App\Models\PaymentMethod;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;

use Xendit\Configuration;

use Xendit\Invoice\InvoiceApi;

use Xendit\Invoice\CreateInvoiceRequest;


class CheckoutController extends Controller

{

    public function index()

    {

        $cart = Cart::with(['items.variant.product'])

            ->where('user_id', auth()->id())

            ->first();



        if (!$cart || $cart->items->isEmpty()) {

            return redirect()->route('cart.index')->with('error', 'Keranjang masih kosong.');

        }



        $paymentMethods = PaymentMethod::where('is_active', true)->get();



        return view('checkout.index', compact('cart', 'paymentMethods'));

    }

    public function store(Request $request)

    {

        $request->validate([

            'recipient_name' => 'required|max:255',

            'phone' => 'required|max:30',

            'province' => 'required|max:255',

            'city' => 'required|max:255',

            'district' => 'required|max:255',

            'postal_code' => 'nullable|max:20',

            'full_address' => 'required',

            'payment_method_id' => 'required|exists:payment_methods,id',

            'shipping_method' => 'required|string', // 👈 TAMBAHAN ONGKIR

            'notes' => 'nullable',

        ]);



        $cart = Cart::with(['items.variant.product'])

            ->where('user_id', auth()->id())

            ->first();



        if (!$cart || $cart->items->isEmpty()) {

            return redirect()->route('cart.index')->with('error', 'Keranjang masih kosong.');

        }



        DB::beginTransaction();



        try {



            $lockedVariants = [];

            $grandTotal = 0;

            /*

            |------------------------------------------------

            | 1. LOCK & VALIDATE PRODUCT + HITUNG SUBTOTAL

            |------------------------------------------------

            */

            foreach ($cart->items as $item) {



                $variant = ProductVariant::with('product')

                    ->lockForUpdate()

                    ->find($item->product_variant_id);



                if (!$variant) {

                    DB::rollBack();

                    return redirect()->route('cart.index')->with('error', 'Variasi produk tidak ditemukan.');

                }



                if ($variant->status !== 'active' || $variant->product->status !== 'active') {

                    DB::rollBack();

                    return redirect()->route('cart.index')->with('error', 'Ada produk tidak aktif.');

                }



                if ($item->quantity > $variant->stock) {

                    DB::rollBack();

                    return redirect()->route('cart.index')->with('error', 'Stok tidak mencukupi.');

                }



                $lockedVariants[$variant->id] = $variant;



                $grandTotal += $variant->price * $item->quantity;

            }

            /*

            |------------------------------------------------

            | 2. ONGKIR STATIC (TAMBAHAN BARU)

            |------------------------------------------------

            */

            $shippingCost = match ($request->shipping_method) {

                'jne_reg' => 10000,

                'jne_yes' => 20000,

                'jnt' => 12000,

                default => 0

            };

            /*

            |------------------------------------------------

            | 3. VALIDASI PAYMENT METHOD

            |------------------------------------------------

            */

            $paymentMethod = PaymentMethod::where('id', $request->payment_method_id)

                ->where('is_active', true)

                ->first();



            if (!$paymentMethod) {

                DB::rollBack();

                return redirect()->route('checkout.index')

                    ->with('error', 'Metode pembayaran tidak valid.');

            }

            /*

            |------------------------------------------------

            | 4. SIMPAN ALAMAT USER

            |------------------------------------------------

            */

            $address = auth()->user()->addresses()->create([

                'recipient_name' => $request->recipient_name,

                'phone' => $request->phone,

                'province' => $request->province,

                'city' => $request->city,

                'district' => $request->district,

                'postal_code' => $request->postal_code,

                'full_address' => $request->full_address,

            ]);

            /*

            |------------------------------------------------

            | 5. TOTAL FINAL (SUBTOTAL + ONGKIR)

            |------------------------------------------------

            */

            $grandTotal += $shippingCost;

            /*

            |------------------------------------------------

            | 6. CREATE ORDER

            |------------------------------------------------

            */

            $order = auth()->user()->orders()->create([

                'address_id' => $address->id,

                'payment_method_id' => $paymentMethod->id,

                'order_code' => 'ORD-' . now()->format('YmdHis') . '-' . rand(100, 999),



                'total_price' => $grandTotal,


                // 🧾 PAYMENT INFO

                'payment_method' => $paymentMethod->type,

                'payment_method_name' => $paymentMethod->name,

                'payment_bank_name' => $paymentMethod->bank_name,

                'payment_account_number' => $paymentMethod->account_number,

                'payment_account_name' => $paymentMethod->account_name,

                'payment_instruction' => $paymentMethod->instruction,


                // 🚚 SHIPPING INFO (BARU)

                'shipping_method' => $request->shipping_method,

                'shipping_cost' => $shippingCost,



                'status' => 'waiting_payment',

                'payment_deadline_at' => now()->addHours(1), // Batas waktu pembayaran 1 jam

                'notes' => $request->notes,

            ]);

            /*

            |------------------------------------------------

            | 7. CREATE ORDER ITEMS + REDUCE STOCK

            |------------------------------------------------

            */

            foreach ($cart->items as $item) {



                $variant = $lockedVariants[$item->product_variant_id];

                $subtotal = $variant->price * $item->quantity;



                $variant->decrement('stock', $item->quantity);



                $order->items()->create([

                    'product_id' => $variant->product->id,

                    'product_variant_id' => $variant->id,

                    'product_name' => $variant->product->name,

                    'variant_name' => $variant->name,

                    'price' => $variant->price,

                    'quantity' => $item->quantity,

                    'subtotal' => $subtotal,

                ]);

            }

            /*

            |------------------------------------------------

            | 8. CLEAR CART

            |------------------------------------------------

            */

            $cart->items()->delete();

            // CEK APAKAH METODE PEMBAYARAN ADALAH XENDIT
            // Asumsi: Anda sudah menambah kolom 'code' di database dan isinya 'xendit'
            if ($paymentMethod->code === 'xendit') {
                
                Configuration::setXenditKey(config('services.xendit.secret_key'));
                $apiInstance = new InvoiceApi();

                $create_invoice_request = new CreateInvoiceRequest([
                    'external_id' => $order->order_code,
                    'description' => 'Pembayaran Order ' . $order->order_code,
                    'amount' => (double) $order->total_price,
                    'invoice_duration' => 3600, // 1 Jam
                    'customer' => [
                        'given_names' => auth()->user()->display_name ?? auth()->user()->name,
                        'email' => auth()->user()->email,
                    ],
                    'success_redirect_url' => route('orders.show', $order->id),
                    'failure_redirect_url' => route('orders.show', $order->id),
                ]);

                $result = $apiInstance->createInvoice($create_invoice_request);
                
                // Update URL Pembayaran ke database agar bisa diakses nanti di halaman detail order
                $order->update([
                    'payment_url' => $result['invoice_url'],
                    'payment_deadline_at' => now()->addHours(1)
                ]);

                DB::commit();

                // LANGSUNG REDIRECT KE HALAMAN PEMBAYARAN XENDIT
                return redirect($result['invoice_url']);

            } else {
                // JIKA TRANSFER MANUAL (SEPERTI BCA TADI)
                DB::commit();
                return redirect()->route('orders.show', $order->id)
                    ->with('success', 'Pesanan dibuat. Silakan transfer manual sesuai instruksi.');
            }

        } catch (\Throwable $th) {
            DB::rollBack();
            // Tambahkan log untuk memudahkan debug jika error
            \Log::error('Checkout Error: ' . $th->getMessage());
            return redirect()->route('checkout.index')
                ->with('error', 'Checkout gagal: ' . $th->getMessage());
        }

    }

}