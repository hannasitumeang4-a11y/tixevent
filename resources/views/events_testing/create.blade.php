<h2>Tambah Event</h2>

<form action="/events" method="POST">
    @csrf

    <label>Nama Event</label><br>
    <input type="text" name="name"><br><br>

    <label>Kategori</label><br>
    <select name="category_id">
        @foreach($categories as $cat)
            <option value="{{ $cat->category_id }}">
                {{ $cat->name }}
            </option>
        @endforeach
    </select>

    <br><br>
    <button type="submit">Simpan</button>
</form>