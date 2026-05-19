<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\Category;
use App\Models\User;
use App\Models\Order;
use App\Models\EventTicket;

class PageController extends Controller
{

    /*
    |--------------------------------------------------------------------------
    | FRONTEND
    |--------------------------------------------------------------------------
    */

    public function home(Request $request)
    {
        $categories = Category::all();

        $query = Event::with([
            'category',
            'images',
            'tickets'
        ]);

        // FILTER KATEGORI
        if (
            $request->filled('category')
            &&
            $request->category != 'all'
        ) {
            $query->where(
                'category_id',
                $request->category
            );
        }

        // FILTER SEARCH
        if (
            $request->filled('search')
        ) {
            $query->where(function ($q) use ($request) {

                $q->where(
                    'title',
                    'like',
                    '%' . $request->search . '%'
                )

                    ->orWhere(
                        'location',
                        'like',
                        '%' . $request->search . '%'
                    )

                    ->orWhere(
                        'description',
                        'like',
                        '%' . $request->search . '%'
                    );
            });
        }

        $events = $query
            ->where(
                'status',
                'published'
            )
            ->orderBy(
                'event_date',
                'desc'
            )
            ->paginate(12);

        $popular = Event::where(
            'status',
            'published'
        )
            ->latest()
            ->take(3)
            ->get();

        return view(
            'pages.home',
            compact(
                'events',
                'categories',
                'popular'
            )
        );
    }

    public function detail($id)
    {
        $event = Event::with([
            'category',
            'images',
            'tickets'
        ])->findOrFail($id);

        return view(
            'pages.detail',
            compact(
                'event'
            )
        );
    }

    public function history()
    {
        if (!auth()->check()) {
            return redirect()
                ->route('login');
        }

        $orders =
            Order::where(
                'user_id',
                auth()->user()->user_id
            )
            ->latest()
            ->paginate(10);

        return view(
            'pages.history',
            compact(
                'orders'
            )
        );
    }

    /*
    |--------------------------------------------------------------------------
    | CHECKOUT
    |--------------------------------------------------------------------------
    */

    public function checkout(Request $request)
    {

        if (
            !$request->event
            ||
            !$request->ticket
        ) {
            return redirect()
                ->route('home')
                ->with(
                    'error',
                    'Silakan pilih tiket terlebih dahulu'
                );
        }

        $event =
            Event::findOrFail(
                $request->event
            );

        $ticket =
            EventTicket::findOrFail(
                $request->ticket
            );

        return view(
            'pages.checkout',
            compact(
                'event',
                'ticket'
            )
        );
    }

    public function processPayment(
        Request $request
    ) {

        $request->validate([

            'name' => 'required',

            'email' => 'required|email',

            'phone' => 'required',

            'payment_method' => 'required',

            'ticket_id' => 'required',

            'payment_proof' => 'nullable|image|mimes:jpg,png,jpeg|max:2048'

        ]);

        if (!auth()->check()) {
            return redirect()
                ->route('login');
        }

        $ticket =
            EventTicket::findOrFail(
                $request->ticket_id
            );

        $total =
            $ticket->price;

        $payment = 'transfer';

        if (
            $request->payment_method == 'qris'
        ) {
            $payment = 'ewallet';
        }

        $file = null;

        if (
            $request->hasFile(
                'payment_proof'
            )
        ) {

            $file =
                $request
                ->file(
                    'payment_proof'
                )
                ->store(
                    'payments',
                    'public'
                );
        }

        $order =
            Order::create([

                'order_code' =>
                'ORD-' . time(),

                'user_id' =>
                auth()->user()->user_id,

                'total_amount' =>
                $total,

                'payment_method' =>
                $payment,

                'order_status' =>
                'paid'

            ]);

        return redirect()
            ->route(
                'invoice',
                $order->order_id
            )
            ->with(
                'success',
                'Pembayaran berhasil'
            );
    }

    public function invoice($id)
    {

        $order =
            Order::where(
                'user_id',
                auth()->user()->user_id
            )
            ->findOrFail($id);

        return view(
            'pages.invoice',
            compact(
                'order'
            )
        );
    }

    public function success()
    {
        return view(
            'pages.success'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | AUTH
    |--------------------------------------------------------------------------
    */

    public function login()
    {
        return view(
            'auth.login'
        );
    }

    public function register()
    {
        return view(
            'auth.register'
        );
    }

    public function profile()
    {

        if (!auth()->check()) {
            return redirect()
                ->route('login');
        }

        $orders =
            Order::where(
                'user_id',
                auth()->user()->user_id
            )
            ->latest()
            ->get();

        return view(
            'pages.profile',
            compact(
                'orders'
            )
        );
    }

    /*
    |--------------------------------------------------------------------------
    | ADMIN
    |--------------------------------------------------------------------------
    */

    public function dashboard()
    {

        $totalEvent =
            Event::count();

        $totalUsers =
            User::count();

        $totalOrders =
            Order::count();

        $totalRevenue =
            Order::sum(
                'total_amount'
            );

        $recentOrders =
            Order::latest()
            ->take(5)
            ->get();

        $recentEvents =
            Event::latest()
            ->take(5)
            ->get();

        return view(
            'admin.dashboard',
            compact(
                'totalEvent',
                'totalUsers',
                'totalOrders',
                'totalRevenue',
                'recentOrders',
                'recentEvents'
            )
        );
    }

    public function usermanage()
    {
        return view(
            'admin.users'
        );
    }

    public function eventmanage()
    {
        return view(
            'admin.events'
        );
    }
}