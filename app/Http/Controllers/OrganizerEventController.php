<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\Category;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon; 

class OrganizerEventController extends Controller
{
    // 1. DASHBOARD UTAMA PROMOTOR
    public function dashboard()
    {
        $organizerId = auth()->id();

        $myEvents = Event::where('organizer_id', $organizerId)->latest()->get();
        $totalEvent = $myEvents->count();
        $recentEvents = Event::where('organizer_id', $organizerId)->latest()->take(5)->get();

        $totalOrders = DB::table('orders')
            ->where('order_status', 'paid')
            ->whereIn('event_id', function($query) use ($organizerId) {
                $query->select('event_id')->from('events')->where('organizer_id', $organizerId);
            })
            ->count();

        $totalRevenue = DB::table('orders')
            ->where('order_status', 'paid')
            ->whereIn('event_id', function($query) use ($organizerId) {
                $query->select('event_id')->from('events')->where('organizer_id', $organizerId);
            })
            ->sum('total_amount');

        $totalUsers = DB::table('orders')
            ->where('order_status', 'paid')
            ->whereIn('event_id', function($query) use ($organizerId) {
                $query->select('event_id')->from('events')->where('organizer_id', $organizerId);
            })
            ->distinct('user_id')
            ->count('user_id');

        $recentOrders = DB::table('orders')
            ->join('events', 'orders.event_id', '=', 'events.event_id')
            ->join('users', 'orders.user_id', '=', 'users.user_id')
            ->where('events.organizer_id', $organizerId)
            ->where('orders.order_status', 'paid') 
            ->select(
                'orders.order_id', 
                'orders.created_at', 
                'users.name as user_name', 
                'events.title as event_title'
            )
            ->latest('orders.created_at')
            ->take(5)
            ->get();

        return view('pages.organizer.dashboard', compact(
            'myEvents', 'totalEvent', 'recentEvents', 'totalOrders', 'totalRevenue', 'totalUsers', 'recentOrders'
        ));
    }

    // 2. FORM BUAT EVENT
    public function create()
    {
        $categories = Category::all();
        return view('pages.organizer.create_event', compact('categories'));
    }

    // 3. PROSES SIMPAN EVENT BARU (FIXED STOCK)
    public function store(Request $request)
    {
        $request->validate([
            'title'         => 'required|string|max:255',
            'description'   => 'required|string',
            'location'      => 'required|string',
            'event_date'    => 'required|date',
            'start_time'    => 'required',
            'end_time'      => 'required',
            'category_id'   => 'required|exists:categories,category_id',
            'image'         => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'price_vip'     => 'required|numeric|min:0',
            'stock_vip'     => 'required|integer|min:1',
            'price_regular' => 'required|numeric|min:0',
            'stock_regular' => 'required|integer|min:1',
            'price_presale' => 'required|numeric|min:0',
            'stock_presale' => 'required|integer|min:1',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imageName = time() . '_' . Str::slug($request->title) . '.' . $request->image->extension();
            $request->image->move(public_path('assets/posters'), $imageName);
            $imagePath = 'assets/posters/' . $imageName;
        }

        $event = Event::create([
            'organizer_id' => auth()->id(), 
            'title'        => $request->title,
            'slug'         => Str::slug($request->title),
            'description'  => $request->description,
            'location'     => $request->location,
            'event_date'   => $request->event_date,
            'start_time'   => $request->start_time, 
            'end_time'     => $request->end_time,   
            'price'        => $request->price_regular, 
            'category_id'  => $request->category_id,  
            'status'       => 'published' 
        ]);

        if ($imagePath && method_exists($event, 'images')) {
            $event->images()->create([
                'image_path' => $imagePath,
                'is_primary' => 1 
            ]);
        }

        $this->generateManualTickets(
            $event, 
            $request->price_vip, $request->price_regular, $request->price_presale,
            $request->stock_vip, $request->stock_regular, $request->stock_presale
        );

        return redirect()->route('organizer.dashboard')->with('success', 'Event baru berhasil diterbitkan!');
    }

    // 4. MANIFES PEMBELI & STATISTIK PENJUALAN
    public function show($id)
    {
        $event = Event::where('organizer_id', auth()->id())->findOrFail($id);

        $manifests = DB::table('orders')
            ->join('users', 'orders.user_id', '=', 'users.user_id')
            ->join('event_tickets', 'orders.ticket_id', '=', 'event_tickets.event_ticket_id')
            ->where('orders.event_id', $event->event_id)
            ->where('orders.order_status', 'paid') 
            ->select(
                'orders.order_id',
                'users.name as user_name',
                'event_tickets.ticket_type',
                'orders.quantity',
                'orders.total_amount as subtotal',
                'orders.created_at'
            )
            ->orderBy('orders.created_at', 'desc')
            ->get();

        $stats = [
            'total_revenue' => $manifests->sum('subtotal'),
            'total_tickets' => $manifests->sum('quantity'),
            'total_buyers'  => $manifests->unique('order_id')->count(),
        ];

        return view('pages.organizer.show_manifest', compact('event', 'manifests', 'stats'));
    }

    // 5. FORM EDIT EVENT
    public function edit($id)
    {
        $event = Event::where('organizer_id', auth()->id())->findOrFail($id);
        $categories = Category::all();
        
        return view('pages.organizer.edit_event', compact('event', 'categories'));
    }

    // 6. UPDATE DATA EVENT (FIXED STOCK)
    public function update(Request $request, $id)
    {
        $request->validate([
            'title'         => 'required|string|max:255',
            'description'   => 'required|string',
            'location'      => 'required|string',
            'event_date'    => 'required|date',
            'start_time'    => 'required',
            'end_time'      => 'required',
            'category_id'   => 'required|exists:categories,category_id',
            'image'         => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'price_vip'     => 'required|numeric|min:0',
            'stock_vip'     => 'required|integer|min:1',
            'price_regular' => 'required|numeric|min:0',
            'stock_regular' => 'required|integer|min:1',
            'price_presale' => 'required|numeric|min:0',
            'stock_presale' => 'required|integer|min:1',
        ]);

        $event = Event::where('organizer_id', auth()->id())->findOrFail($id);

        if ($request->hasFile('image')) {
            $oldImage = method_exists($event, 'images') ? $event->images()->first() : null;
            
            if ($oldImage) {
                if (file_exists(public_path($oldImage->image_path))) {
                    @unlink(public_path($oldImage->image_path));
                }
                $oldImage->delete();
            }

            $imageName = time() . '_' . Str::slug($request->title) . '.' . $request->image->extension();
            $request->image->move(public_path('assets/posters'), $imageName);
            
            if (method_exists($event, 'images')) {
                $event->images()->create([
                    'image_path' => 'assets/posters/' . $imageName,
                    'is_primary' => 1
                ]);
            }
        }

        $event->update([
            'title'       => $request->title,
            'slug'        => Str::slug($request->title),
            'description' => $request->description,
            'location'    => $request->location,
            'event_date'  => $request->event_date,
            'start_time'  => $request->start_time,
            'end_time'    => $request->end_time,
            'category_id' => $request->category_id,
            'price'       => $request->price_regular, 
            'status'      => $event->status ?? 'published'
        ]);

        DB::table('event_tickets')->where('event_id', $event->event_id)->delete();
        
        $this->generateManualTickets(
            $event, 
            $request->price_vip, $request->price_regular, $request->price_presale,
            $request->stock_vip, $request->stock_regular, $request->stock_presale
        );

        return redirect()->route('organizer.dashboard')->with('success', 'Data konser berhasil diperbarui!');
    }

    // 7. SUBSISTEM GENERATE TIKET (Stok Dinamis Diterapkan)
    private function generateManualTickets($event, $vipPrice, $regularPrice, $presalePrice, $vipStock, $regularStock, $presaleStock)
    {
        $ticketCategories = [
            [
                'event_id'          => $event->event_id,
                'ticket_type'       => 'VIP',
                'price'             => (float) $vipPrice, 
                'stock'             => (int) $vipStock, // <-- Stok dinamis
                'max_buy_per_order' => 5,
                'status'            => 'available',
                'created_at'        => now(),
                'updated_at'        => now()
            ],
            [
                'event_id'          => $event->event_id,
                'ticket_type'       => 'REGULAR',
                'price'             => (float) $regularPrice, 
                'stock'             => (int) $regularStock, // <-- Stok dinamis
                'max_buy_per_order' => 5,
                'status'            => 'available',
                'created_at'        => now(),
                'updated_at'        => now()
            ],
            [
                'event_id'          => $event->event_id,
                'ticket_type'       => 'PRESALE',
                'price'             => (float) $presalePrice, 
                'stock'             => (int) $presaleStock, // <-- Stok dinamis
                'max_buy_per_order' => 3,
                'status'            => 'available',
                'created_at'        => now(),
                'updated_at'        => now()
            ]
        ];

        DB::table('event_tickets')->insert($ticketCategories);
    }

    // 8. EXPORT DATA MANIFES
    public function exportManifest($id)
    {
        $event = Event::where('organizer_id', auth()->id())->findOrFail($id);

        $manifests = DB::table('orders')
            ->join('users', 'orders.user_id', '=', 'users.user_id')
            ->join('event_tickets', 'orders.ticket_id', '=', 'event_tickets.event_ticket_id')
            ->where('orders.event_id', $event->event_id)
            ->where('orders.order_status', 'paid') 
            ->select(
                'orders.order_id',
                'users.name as user_name',
                'users.email as user_email',
                'event_tickets.ticket_type',
                'orders.quantity',
                'orders.total_amount as subtotal',
                'orders.created_at'
            )
            ->orderBy('orders.created_at', 'desc')
            ->get();

        $fileName = 'Laporan_Penjualan_' . Str::slug($event->title) . '.csv';

        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $callback = function() use($manifests) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['ID Order', 'Nama Penonton', 'Email', 'Kategori', 'Quantity', 'Subtotal', 'Tanggal']);

            foreach ($manifests as $item) {
                fputcsv($file, [
                    '#' . $item->order_id,
                    $item->user_name,
                    $item->user_email,
                    $item->ticket_type,
                    $item->quantity . 'x',
                    'Rp ' . number_format($item->subtotal, 0, ',', '.'),
                    Carbon::parse($item->created_at)->format('d M Y, H:i')
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    // 9. HAPUS DATA EVENT DAN TIKETNYA
    public function destroy($id)
    {
        $event = Event::where('organizer_id', auth()->id())->findOrFail($id);

        if (method_exists($event, 'images')) {
            $oldImage = $event->images()->first();
            if ($oldImage) {
                $imagePath = public_path($oldImage->image_path);
                if (file_exists($imagePath)) {
                    @unlink($imagePath);
                }
                $event->images()->delete();
            }
        }

        DB::table('event_tickets')->where('event_id', $event->event_id)->delete();
        $event->delete();

        return redirect()->route('organizer.dashboard')->with('success', 'Event berhasil dihapus secara permanen!');
    }
}