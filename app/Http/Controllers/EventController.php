<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\Category;
use App\Models\EventTicket;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class EventController extends Controller
{
    // 1. TAMPILKAN FORM BUAT EVENT
    public function create()
    {
        $categories = Category::all();
        return view('events.create', compact('categories'));
    }

    // 2. SIMPAN EVENT BARU + AUTO GENERATE TIKET PERTAMA KALI
    public function store(Request $request)
    {
        $request->validate([
            'title'       => 'required|string|max:255',
            'category_id' => 'required',
            'price'       => 'required|numeric|min:0',
            'event_date'  => 'required|date',
            'location'    => 'required|string',
            'image'       => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048'
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imageName = time() . '_' . Str::slug($request->title) . '.' . $request->image->extension();
            $request->image->move(public_path('assets/posters'), $imageName);
            $imagePath = 'assets/posters/' . $imageName;
        }

        $event = Event::create([
            'title'       => $request->title,
            'slug'        => Str::slug($request->title),
            'category_id' => $request->category_id,
            'location'    => $request->location,
            'event_date'  => $request->event_date,
            'price'       => $request->price,
        ]);

        if ($imagePath && method_exists($event, 'images')) {
            $event->images()->create(['image_path' => $imagePath]);
        }

        // Generate tiket default jika pakai EventController murni (bukan OrganizerEventController)
        $this->generateDefaultTickets($event, $request->price);

        return redirect()->route('organizer.dashboard')->with('success', 'Event baru dan variasi tiket berhasil diterbitkan!');
    }

    // 3. TAMPILKAN FORM EDIT 
    public function edit($id)
    {
        $event = Event::findOrFail($id);
        $categories = Category::all();
        
        return view('pages.organizer.events.edit', compact('event', 'categories'));
    }

    // 4. UPDATE DATA EVENT + RE-GENERATE TIKET OTOMATIS
    public function update(Request $request, $id)
    {
        $request->validate([
            'title'      => 'required|string|max:255',
            'price'      => 'required|numeric|min:0',
            'event_date' => 'required|date',
            'location'   => 'required|string',
            'image'      => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048'
        ]);

        $event = Event::findOrFail($id);

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
                $event->images()->create(['image_path' => 'assets/posters/' . $imageName]);
            }
        }

        $event->update([
            'title'      => $request->title,
            'slug'       => Str::slug($request->title),
            'location'   => $request->location,
            'event_date' => $request->event_date,
            'price'      => $request->price,
        ]);

        if (method_exists($event, 'tickets')) {
            $event->tickets()->delete(); 
        } else {
            DB::table('event_tickets')->where('event_id', $event->event_id)->delete();
        }

        $this->generateDefaultTickets($event, $request->price);

        return redirect()->route('organizer.dashboard')->with('success', 'Data konser dan kategori tiket berhasil disinkronisasi!');
    }

    // 5. HELPER FUNCTION (Stok default diset dinamis lewat array)
    private function generateDefaultTickets($event, $basePrice, $stocks = ['VIP' => 50, 'REGULAR' => 150, 'PRESALE' => 100])
    {
        $ticketCategories = [
            [
                'event_id'          => $event->event_id,
                'ticket_type'       => 'VIP',
                'price'             => $basePrice * 1.5,
                'stock'             => $stocks['VIP'],
                'max_buy_per_order' => 5,
                'status'            => 'available',
                'created_at'        => now(),
                'updated_at'        => now()
            ],
            [
                'event_id'          => $event->event_id,
                'ticket_type'       => 'REGULAR',
                'price'             => $basePrice,
                'stock'             => $stocks['REGULAR'],
                'max_buy_per_order' => 5,
                'status'            => 'available',
                'created_at'        => now(),
                'updated_at'        => now()
            ],
            [
                'event_id'          => $event->event_id,
                'ticket_type'       => 'PRESALE',
                'price'             => $basePrice * 0.7,
                'stock'             => $stocks['PRESALE'],
                'max_buy_per_order' => 3,
                'status'            => 'available',
                'created_at'        => now(),
                'updated_at'        => now()
            ]
        ];

        DB::table('event_tickets')->insert($ticketCategories);
    }

    // 6. MENAMPILKAN HOME UTAMA (DRAFT TETAP MUNCUL + FIX COMPONENT FILTER)
    public function index(Request $request)
    {
        $query = Event::with(['category', 'images']);

        if ($request->filled('category') && $request->category !== 'all') {
            $query->where('category_id', $request->category);
        }

        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('filter_location') && $request->filter_location !== 'all') {
            $query->where('location', $request->filter_location);
        }

        if ($request->filled('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }

        if ($request->filled('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }

        $events = $query->latest()->paginate(9)->withQueryString();

        $categories = Category::all();
        $locations = Event::select('location')->distinct()->pluck('location');

        $popular = Event::with(['category', 'images'])
                        ->latest() 
                        ->take(3)
                        ->get();

        return view('home', compact('events', 'categories', 'locations', 'popular'));
    }

    // 7. DETAIL EVENT DI SISI USER
    public function show($slug)
    {
        $event = Event::with(['category', 'tickets', 'images'])
            ->where('slug', $slug)
            ->orWhere('event_id', $slug)
            ->firstOrFail();

        return view('events.show', compact('event'));
    }
}