<?php

namespace App\Http\Controllers\Siswa;

use DOMDocument;
use App\Models\Forum;
use App\Models\Komentar;
use Illuminate\Support\Str;
use App\Models\Pembelajaran;
use Illuminate\Http\Request;
use App\Models\ProfilSekolah;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use App\Notifications\ForumDiskusiNotification;

class ForumController extends Controller
{
    // public function index()
    // {
    //     $forum = Forum::orderBy('created_at', 'desc')->paginate(10);

    //     $profileSekolah = ProfilSekolah::first();
    //     $pembelajaran = \App\Models\Pembelajaran::all(); // Tambahkan ini
    //     return view('pages.siswa.forum.index', compact(['forum', 'profileSekolah', 'pembelajaran']));
    // }

    // public function create(Request $request)
    // {

    //     $request->request->add(['user_id' => Auth::id()]);
    //     $forum = Forum::create($request->all());
    //     return redirect()->back()->with('success', 'Forum Berhasil ditambahkan');
    // }

    // public function view(Forum $forum)
    // {
    //     $profileSekolah = ProfilSekolah::first();

    //     return view('pages.siswa.forum.view', compact('forum', 'profileSekolah'));
    // }

    public function create(Request $request)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'konten' => 'required',
            'pembelajaran_id' => 'required|exists:pembelajaran,id',
        ]);

        // Tambah ID user yang login
        $request->request->add(['user_id' => Auth::id()]);

        $konten = $request->konten;

        // Proses upload gambar base64 di konten
        $dom = new DOMDocument();
        libxml_use_internal_errors(true); // Hindari warning HTML
        $dom->loadHTML($konten, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
        libxml_clear_errors();

        $images = $dom->getElementsByTagName('img');

        foreach ($images as $key => $img) {
            $src = $img->getAttribute('src');

            if (Str::startsWith($src, 'data:image')) {
                $data = base64_decode(explode(',', explode(';', $src)[1])[1]);
                $imageName = '/upload/forum/' . time() . $key . '.png';

                // Simpan file ke public/upload/forum/
                file_put_contents(public_path($imageName), $data);

                $img->removeAttribute('src');
                $img->setAttribute('src', $imageName);
            }
        }

        $konten = $dom->saveHTML();

        // Simpan forum
        $forum = Forum::create([
            'user_id' => $request->user_id,
            'pembelajaran_id' => $request->pembelajaran_id,
            'judul' => $request->judul,
            'slug' => Str::slug($request->judul),
            'konten' => $konten,
        ]);

        // Kirim notifikasi ke siswa dan guru yang terkait
        $pembelajaran = $forum->pembelajaran()->with(['enrollments.siswa', 'guru'])->first();

        // Kirim ke siswa yang enroll
        foreach ($pembelajaran->enrollments as $enrollment) {
            if ($enrollment->status === 'approved') {
                $siswa = $enrollment->siswa;
                if ($siswa && $siswa->email) {
                    $siswa->notify(new ForumDiskusiNotification($forum));
                }
            }
        }

        // Kirim ke guru pembelajaran
        $guru = $pembelajaran->guru;
        if ($guru && $guru->email) {
            $guru->notify(new ForumDiskusiNotification($forum));
        }


        return redirect()->back()->with('success', 'Forum berhasil ditambahkan');
    }

    public function postKomentar(Request $request)
    {

        $request->request->add(['user_id' => Auth::id()]);
        $komentar = Komentar::create($request->all());
        return redirect()->back()->with('success', 'Komentar Berhasil ditambahkan');
    }


    // coba lagi
    public function forumDiskusiIndex($mapel, $kelas, $tahunAjaran, $semester)
    {

        // Cari pembelajaran berdasarkan URL slug
        $pembelajaran = Pembelajaran::whereRaw("LOWER(REPLACE(nama_mapel, ' ', '-')) = ?", [$mapel])
            ->whereHas('kelas', function ($query) use ($kelas) {
                $query->whereRaw("LOWER(REPLACE(nama_kelas, ' ', '-')) = ?", [$kelas]);
            })
            ->whereHas('tahunAjaran', function ($query) use ($tahunAjaran) {
                $query->whereRaw("REPLACE(nama_tahun, '/', '-') = ?", [$tahunAjaran]);
            })
            ->where('semester', Str::upper(str_replace('-', ' ', $semester)))
            ->firstOrFail();

        $forum = Forum::orderBy('created_at', 'desc')->paginate(10);

        return view('pages.forumDiskusi.index', compact('pembelajaran', 'forum'));
    }

    public function forumDiskusiView($mapel, $kelas, $tahunAjaran, $semester, Forum $forum)
    {

        // Cari pembelajaran berdasarkan URL slug
        $pembelajaran = Pembelajaran::whereRaw("LOWER(REPLACE(nama_mapel, ' ', '-')) = ?", [$mapel])
            ->whereHas('kelas', function ($query) use ($kelas) {
                $query->whereRaw("LOWER(REPLACE(nama_kelas, ' ', '-')) = ?", [$kelas]);
            })
            ->whereHas('tahunAjaran', function ($query) use ($tahunAjaran) {
                $query->whereRaw("REPLACE(nama_tahun, '/', '-') = ?", [$tahunAjaran]);
            })
            ->where('semester', Str::upper(str_replace('-', ' ', $semester)))
            ->firstOrFail();

        return view('pages.forumDiskusi.view', compact('forum', 'pembelajaran'));
    }

    // public function update(Request $request, $id)
    // {
    //     $request->validate([
    //         'judul' => 'required|string|max:255',
    //         'konten' => 'required|string',
    //     ]);

    //     $forum = Forum::findOrFail($id);
    //     $forum->update([
    //         'judul' => $request->judul,
    //         'konten' => $request->konten,
    //     ]);

    //     return redirect()->back()->with('success', 'Forum berhasil diperbarui.');
    // }

    // public function destroy($id)
    // {
    //     $forum = Forum::findOrFail($id);

    //     // Pastikan hanya pemilik yang bisa menghapus
    //     if ($forum->user_id !== Auth::id()) {
    //         abort(403, 'Kamu tidak memiliki izin untuk menghapus forum ini.');
    //     }

    //     $forum->delete();

    //     return redirect()->back()->with('success', 'Forum berhasil dihapus.');
    // }

    public function update(Request $request, $id)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'konten' => 'required|string',
        ]);

        $forum = Forum::findOrFail($id);

        // Ambil gambar lama sebelum update
        $oldContent = $forum->konten;
        preg_match_all('/<img[^>]+src="([^">]+)"/i', $oldContent, $oldImages);
        $oldImagePaths = $oldImages[1];

        // Proses gambar baru dari summernote (base64)
        $dom = new DOMDocument();
        libxml_use_internal_errors(true);
        $dom->loadHTML($request->konten, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
        libxml_clear_errors();

        $images = $dom->getElementsByTagName('img');
        $newImagePaths = [];

        foreach ($images as $key => $img) {
            $src = $img->getAttribute('src');

            if (Str::startsWith($src, 'data:image')) {
                $data = base64_decode(explode(',', explode(';', $src)[1])[1]);
                $imageName = '/upload/forum/' . time() . $key . '.png';
                file_put_contents(public_path($imageName), $data);

                $img->removeAttribute('src');
                $img->setAttribute('src', $imageName);
                $newImagePaths[] = $imageName;
            } else {
                $newImagePaths[] = $src;
            }
        }

        $kontenBaru = $dom->saveHTML();

        // Hapus gambar lama yang tidak digunakan lagi
        foreach ($oldImagePaths as $oldImgPath) {
            if (!in_array($oldImgPath, $newImagePaths)) {
                $fullPath = public_path($oldImgPath);
                if (File::exists($fullPath)) {
                    File::delete($fullPath);
                }
            }
        }

        // Update data forum
        $forum->update([
            'judul' => $request->judul,
            'konten' => $kontenBaru,
        ]);

        return redirect()->back()->with('success', 'Forum berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $forum = Forum::findOrFail($id);

        // Cek kepemilikan
        if ($forum->user_id !== Auth::id()) {
            abort(403, 'Kamu tidak memiliki izin untuk menghapus forum ini.');
        }

        // Ambil semua gambar dari konten
        preg_match_all('/<img[^>]+src="([^">]+)"/i', $forum->konten, $matches);
        $imagePaths = $matches[1];

        // Hapus semua file gambar
        foreach ($imagePaths as $imgPath) {
            $fullPath = public_path($imgPath);
            if (File::exists($fullPath)) {
                File::delete($fullPath);
            }
        }

        $forum->delete();

        return redirect()->back()->with('success', 'Forum berhasil dihapus.');
    }
}
