<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\Category;
use App\Models\EventTicket; // <-- WAJIB IMPORT MODEL TIKET KAMU DI SINI
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
            'title' => 'required|string|max:255',
            'category_id' => 'required',
            'price' => 'required|numeric|min:0',
            'event_date' => 'required|date',
            'location' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048' // Jika ada upload poster
        ]);

        // Handle upload image jika ada form file poster
        $imagePath = null;
        if ($request->hasFile('image')) {
            $imageName = time() . '_' . Str::slug($request->title) . '.' . $request->image->extension();
            $request->image->move(public_path('assets/posters'), $imageName);
            $imagePath = 'assets/posters/' . $imageName;
        }

        // Simpan data Event Utama
        $event = Event::create([
            'title' => $request->title,
            'slug' => Str::slug($request->title),
            'category_id' => $request->category_id,
            'location' => $request->location,
            'event_date' => $request->event_date,
            'price' => $request->price,
        ]);

        // Simpan relasi gambar jika model kamu memisahkan table image
        if ($imagePath && method_exists($event, 'images')) {
            $event->images()->create(['image_path' => $imagePath]);
        }

        // ========================================================
        // LOGIKA AUTO-GENERATE TIKET SAAT EVENT BARU DIBUAT
        // ========================================================
        $this->generateDefaultTickets($event, $request->price);

        return redirect()->route('organizer.dashboard')->with('success', 'Event baru dan variasi tiket berhasil diterbitkan!');
    }

    // 3. TAMPILKAN FORM EDIT (MENGOBATI FORM GEPENG)
    public function edit($id)
    {
        $event = Event::findOrFail($id);
        $categories = Category::all();
        
        // Mengarah ke file edit view yang barusan di-upgrade
        return view('pages.organizer.events.edit', compact('event', 'categories'));
    }

    // 4. UPDATE DATA EVENT + RE-GENERATE TIKET OTOMATIS
    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'event_date' => 'required|date',
            'location' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048'
        ]);

        $event = Event::findOrFail($id);

        // Handle ganti poster baru jika ada
        if ($request->hasFile('image')) {
            if ($event->images && $event->images->first() && file_exists(public_path($event->images->first()->image_path))) {
                @unlink(public_path($event->images->first()->image_path));
                $event->images()->first()->delete();
            }

            $imageName = time() . '_' . Str::slug($request->title) . '.' . $request->image->extension();
            $request->image->move(public_path('assets/posters'), $imageName);
            $event->images()->create(['image_path' => 'assets/posters/' . $imageName]);
        }

        // Update Event Utama
        $event->update([
            'title' => $request->title,
            'slug' => Str::slug($request->title),
            'location' => $request->location,
            'event_date' => $request->event_date,
            'price' => $request->price,
        ]);

        // ========================================================
        // LOGIKA RE-GENERATE TIKET (HAPUS LALU BUAT BARU)
        // ========================================================
        if (method_exists($event, 'tickets')) {
            $event->tickets()->delete(); // Hapus data tiket lama penonton agar tidak duplikat stack
        } else {
            // Jika relasi tickets belum didefinisikan, gunakan query manual
            DB::table('event_tickets')->where('event_id', $event->event_id)->delete();
        }

        $this->generateDefaultTickets($event, $request->price);

        return redirect()->route('organizer.dashboard')->with('success', 'Data konser dan kategori tiket berhasil disinkronisasi!');
    }

    // 5. HELPER FUNCTION: STRUKTUR FORMAT TIKET (VIP, REGULAR, PRESALE)
    private function generateDefaultTickets($event, $basePrice)
    {
        // Menyusun skema 3 kategori berjenjang sesuai struktur kolom database kamu
        $ticketCategories = [
            [
                'event_id'          => $event->event_id,
                'ticket_type'       => 'VIP',
                'price'             => $basePrice * 1.5, // VIP lebih mahal 50%
                'stock'             => 50,
                'max_buy_per_order' => 5,
                'status'            => 'available',
                'created_at'        => now(),
                'updated_at'        => now()
            ],
            [
                'event_id'          => $event->event_id,
                'ticket_type'       => 'REGULAR',
                'price'             => $basePrice,       // Regular = Harga dasar acuan
                'stock'             => 150,
                'max_buy_per_order' => 5,
                'status'            => 'available',
                'created_at'        => now(),
                'updated_at'        => now()
            ],
            [
                'event_id'          => $event->event_id,
                'ticket_type'       => 'PRESALE',
                'price'             => $basePrice * 0.7, // Presale diskon 30% lebih murah
                'stock'             => 100,
                'max_buy_per_order' => 3,
                'status'            => 'available',
                'created_at'        => now(),
                'updated_at'        => now()
            ]
        ];

        // Insert data ke tabel menggunakan Query Builder agar lebih aman dari problem mass-assignment
        DB::table('event_tickets')->insert($ticketCategories);
    }

    // 6. MENAMPILKAN HOME UTAMA (DRAFT TETAP MUNCUL + FIX COMPONENT FILTER)
    public function index(Request $request)
    {
        // Menyusun query dasar (Menggunakan eager loading 'category' dan 'images' jika ada)
        $query = Event::with(['category', 'images']);

        // JIKA KAMU MEMILIKI KOLOM STATUS:
        // Pastikan baik 'published' maupun 'draft' ditarik oleh sistem
        // $query->whereIn('status', ['published', 'draft']); 

        // Filter 1: Berdasarkan Kategori dari Sidebar Accordion
        if ($request->filled('category') && $request->category !== 'all') {
            $query->where('category_id', $request->category);
        }

        // Filter 2: Berdasarkan Keyword Pencarian (Search Bar)
        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        // Filter 3: Berdasarkan Opsi Lokasi Dropdown
        if ($request->filled('filter_location') && $request->filter_location !== 'all') {
            $query->where('location', $request->filter_location);
        }

        // Filter 4: Berdasarkan Batas Harga Minimum
        if ($request->filled('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }

        // Filter 5: Berdasarkan Batas Harga Maksimum
        if ($request->filled('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }

        // Eksekusi data untuk katalog utama dengan pagination (menyesuaikan ke 9 data agar rapi grid-3)
        $events = $query->latest()->paginate(9)->withQueryString();

        // Mengambil data penunjang untuk komponen filter di file Blade
        $categories = Category::all();
        $locations = Event::select('location')->distinct()->pluck('location');

        // Mengambil data untuk section "Sedang Tren Minggu Ini" (DRAFT ikut muncul)
        $popular = Event::with(['category', 'images'])
                        ->latest() // atau ubah jadi ->orderBy('views', 'desc') jika ada counter views
                        ->take(3)
                        ->get();

        return view('home', compact('events', 'categories', 'locations', 'popular'));
    }

    // 7. DETAIL EVENT DI SISI USER (SEKARANG KATEGORI TIKET AKAN MUNCUL)
    public function show($slug)
    {
        // Kita ikut sertakan (eager load) relasi 'tickets' agar view user bisa membaca loop pilihan tiket
        $event = Event::with(['category', 'tickets', 'images'])
            ->where('slug', $slug)
            ->orWhere('event_id', $slug)
            ->firstOrFail();

        return view('events.show', compact('event'));
    }
}