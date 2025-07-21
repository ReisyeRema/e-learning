<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProfileController;
use App\Http\Middleware\PreventBackHistory;
use App\Http\Middleware\RedirectIfNotSiswa;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Admin\KuisController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\Admin\TugasController;
use App\Http\Controllers\Siswa\ForumController;
use App\Http\Controllers\Admin\MateriController;
use App\Http\Controllers\Siswa\EnrollController;
use App\Http\Controllers\Admin\AbsensiController;
use App\Http\Controllers\Admin\SoalKuisController;
use App\Http\Controllers\Admin\GuruAdminController;
use App\Http\Controllers\Admin\PertemuanController;
use App\Http\Controllers\Admin\WaliKelasController;
use App\Http\Controllers\Auth\RoleSelectController;
use App\Http\Controllers\Siswa\KuisSiswaController;
use App\Http\Controllers\Admin\EnrollmentController;
use App\Http\Controllers\Admin\KelasAdminController;
use App\Http\Controllers\Admin\SiswaAdminController;
use App\Http\Controllers\Siswa\KelasSiswaController;
use App\Http\Controllers\Siswa\SertifikatController;
use App\Http\Controllers\Siswa\TugasSiswaController;
use App\Http\Controllers\Admin\TahunAjaranController;
use App\Http\Controllers\Siswa\SubmitTugasController;
use App\Http\Controllers\Admin\HalamanWalasController;
use App\Http\Controllers\Admin\PembelajaranController;
use App\Http\Controllers\Admin\DetailAbsensiController;
use App\Http\Controllers\Admin\PertemuanKuisController;
use App\Http\Controllers\Siswa\MataPelajaranController;
use App\Http\Controllers\Admin\KurikulumAdminController;
use App\Http\Controllers\Admin\PertemuanTugasController;
use App\Http\Controllers\Admin\ProfileSekolahController;
use App\Http\Controllers\Frontend\LandingPageController;
use App\Http\Controllers\Siswa\DashboardSiswaController;
use App\Http\Controllers\Admin\PertemuanMateriController;
use App\Http\Controllers\Siswa\SiswaKuisSessionController;

Route::get('/', function () {
    if (Auth::check()) {
        if (Auth::user()->roles->contains('name', 'Siswa')) {
            return redirect()->route('dashboard-siswa.index');
        }
        return redirect()->route('dashboard');
    }
    return redirect()->route('landing-page.index');
});


Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified', PreventBackHistory::class, 'role.selected'])
    ->name('dashboard');


Route::get('/landing-page', [LandingPageController::class, 'index'])->name('landing-page.index');

// ppilih role
Route::get('/choose-role', [RoleSelectController::class, 'show'])->name('choose-role');
Route::post('/choose-role', [RoleSelectController::class, 'select'])->name('choose-role.submit');


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Super Admin
Route::middleware(['auth', 'role:Super Admin'])->prefix('superadmin')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit_super_admin'])->name('profile-super-admin.edit');
    Route::put('/profile', [ProfileController::class, 'update_super_admin'])->name('profile-super-admin.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    //User / Operator
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
    Route::post('/users/store', [UserController::class, 'store'])->name('users.store');
    Route::get('/users/{users}/edit', [UserController::class, 'edit'])->name('users.edit');
    Route::put('/users/{users}/update', [UserController::class, 'update'])->name('users.update');
    Route::delete('/users/{users}/destroy', [UserController::class, 'destroy'])->name('users.destroy');

    //Admin
    Route::get('/admin', [UserController::class, 'index'])->name('admin.index');
    Route::get('/admin/create', [UserController::class, 'create'])->name('admin.create');
    Route::post('/admin/store', [UserController::class, 'store'])->name('admin.store');
    Route::get('/admin/{admin}/edit', [UserController::class, 'edit'])->name('admin.edit');
    Route::put('/admin/{admin}/update', [UserController::class, 'update'])->name('admin.update');
    Route::delete('/admin/{admin}/destroy', [UserController::class, 'destroy'])->name('admin.destroy');
    Route::get('/operator/export/excel', [UserController::class, 'export_excel'])->name('operator.export');


    //Permissions
    Route::get('/permissions', [PermissionController::class, 'index'])->name('permissions.index');
    Route::get('/permissions/create', [PermissionController::class, 'create'])->name('permissions.create');
    Route::post('/permissions/store', [PermissionController::class, 'store'])->name('permissions.store');
    Route::get('/permissions/{permissions}/edit', [PermissionController::class, 'edit'])->name('permissions.edit');
    Route::put('/permissions/{permissions}/update', [PermissionController::class, 'update'])->name('permissions.update');
    Route::delete('/permissions/{permissions}/destroy', [PermissionController::class, 'destroy'])->name('permissions.destroy');

    //Roles
    Route::get('/roles', [RoleController::class, 'index'])->name('roles.index');
    Route::get('/roles/create', [RoleController::class, 'create'])->name('roles.create');
    Route::post('/roles/store', [RoleController::class, 'store'])->name('roles.store');
    Route::get('/roles/{roles}/edit', [RoleController::class, 'edit'])->name('roles.edit');
    Route::put('/roles/{roles}/update', [RoleController::class, 'update'])->name('roles.update');
    Route::delete('/roles/{roles}/destroy', [RoleController::class, 'destroy'])->name('roles.destroy');
    Route::get('roles/{roles}/give-permissions', [RoleController::class, 'addPermissionToRole'])->name('roles.addPermissionToRole');
    Route::put('roles/{roles}/give-permissions', [RoleController::class, 'givePermissionToRole'])->name('roles.givePermissionToRole');
});

// Admin
Route::middleware(['auth', 'role:Admin'])->prefix('admin')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit_admin'])->name('profile-admin.edit');
    Route::put('/profile', [ProfileController::class, 'update_admin'])->name('profile-admin.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    //kelas admin
    Route::get('/kelas', [KelasAdminController::class, 'index'])->name('kelas.index');
    Route::post('/kelas/store', [KelasAdminController::class, 'store'])->name('kelas.store');
    Route::put('/kelas/{kelas}/update', [KelasAdminController::class, 'update'])->name('kelas.update');
    Route::delete('/kelas/{kelas}/destroy', [KelasAdminController::class, 'destroy'])->name('kelas.destroy');

    //tahun ajaran admin
    Route::get('/tahun-ajaran', [TahunAjaranController::class, 'index'])->name('tahun-ajaran.index');
    Route::post('/tahun-ajaran/store', [TahunAjaranController::class, 'store'])->name('tahun-ajaran.store');
    Route::put('/tahun-ajaran/{tahunAjaran}/update', [TahunAjaranController::class, 'update'])->name('tahun-ajaran.update');
    Route::delete('/tahun-ajaran/{tahunAjaran}/destroy', [TahunAjaranController::class, 'destroy'])->name('tahun-ajaran.destroy');
    
    // guru admin
    Route::get('/guru', [GuruAdminController::class, 'index'])->name('guru.index');
    Route::get('/guru/create', [GuruAdminController::class, 'create'])->name('guru.create');
    Route::post('/guru/store', [GuruAdminController::class, 'store'])->name('guru.store');
    Route::get('/guru/{guru}/edit', [GuruAdminController::class, 'edit'])->name('guru.edit');
    Route::put('/guru/{guru}/update', [GuruAdminController::class, 'update'])->name('guru.update');
    Route::delete('/guru/{guru}/destroy', [GuruAdminController::class, 'destroy'])->name('guru.destroy');
    Route::get('/guru/export/excel', [GuruAdminController::class, 'export_excel'])->name('guru.export');
    
    //Wali Kelas
    Route::get('/wali-kelas', [WaliKelasController::class, 'index'])->name('wali-kelas.index');
    Route::post('/wali-kelas/store', [WaliKelasController::class, 'store'])->name('wali-kelas.store');
    Route::put('/wali-kelas/{waliKelas}/update', [WaliKelasController::class, 'update'])->name('wali-kelas.update');
    Route::delete('/wali-kelas/{waliKelas}/destroy', [WaliKelasController::class, 'destroy'])->name('wali-kelas.destroy');
    Route::put('/wali-kelas/{id}/toggle-aktif', [WaliKelasController::class, 'toggleAktif'])->name('wali-kelas.toggleAktif');
    Route::get('/wali-kelas/export/excel', [WaliKelasController::class, 'export_excel'])->name('wali-kelas.export');
    

    // siswa admin
    Route::get('/siswa', [SiswaAdminController::class, 'index'])->name('siswa.index');
    Route::get('/siswa/create', [SiswaAdminController::class, 'create'])->name('siswa.create');
    Route::post('/siswa/store', [SiswaAdminController::class, 'store'])->name('siswa.store');
    Route::get('/siswa/{siswa}/edit', [SiswaAdminController::class, 'edit'])->name('siswa.edit');
    Route::put('/siswa/{siswa}/update', [SiswaAdminController::class, 'update'])->name('siswa.update');
    Route::delete('/siswa/{siswa}/destroy', [SiswaAdminController::class, 'destroy'])->name('siswa.destroy');
    Route::get('/siswa/export/excel', [SiswaAdminController::class, 'export_excel'])->name('siswa.export');


    // profile sekolah admin
    Route::get('/profile-sekolah', [ProfileSekolahController::class, 'index'])->name('profilesekolah.index');
    Route::get('/profile-sekolah/first', [ProfileSekolahController::class, 'show'])->name('profilesekolah.show');
    Route::post('/profile-sekolah', [ProfileSekolahController::class, 'update'])->name('profilesekolah.update');

    // Kurikulum admin
    Route::get('/kurikulum', [KurikulumAdminController::class, 'index'])->name('kurikulum.index');
    Route::post('/kurikulum/store', [KurikulumAdminController::class, 'store'])->name('kurikulum.store');
    Route::put('/kurikulum/{kurikulum}/update', [KurikulumAdminController::class, 'update'])->name('kurikulum.update');
    Route::delete('/kurikulum/{kurikulum}/destroy', [KurikulumAdminController::class, 'destroy'])->name('kurikulum.destroy');

    // Pembelajaran admin
    Route::get('/pembelajaran', [PembelajaranController::class, 'index'])->name('pembelajaran.index');
    Route::get('/pembelajaran/create', [PembelajaranController::class, 'create'])->name('pembelajaran.create');
    Route::post('/pembelajaran/store', [PembelajaranController::class, 'store'])->name('pembelajaran.store');
    Route::get('/pembelajaran/{pembelajaran}/edit', [PembelajaranController::class, 'edit'])->name('pembelajaran.edit');
    Route::put('/pembelajaran/{pembelajaran}/update', [PembelajaranController::class, 'update'])->name('pembelajaran.update');
    Route::delete('/pembelajaran/{pembelajaran}/destroy', [PembelajaranController::class, 'destroy'])->name('pembelajaran.destroy');
    Route::get('/pembelajaran/export/excel', [PembelajaranController::class, 'export_excel'])->name('pembelajaran.export');
    Route::put('/pembelajaran/{id}/toggle-aktif', [PembelajaranController::class, 'toggleAktif'])->name('pembelajaran.toggleAktif');

});

// Guru
Route::middleware(['auth', 'role:Guru'])->prefix('guru')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit_guru'])->name('profile-guru.edit');
    Route::put('/profile', [ProfileController::class, 'update_guru'])->name('profile-guru.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    //materi guru
    Route::get('/materi', [MateriController::class, 'index'])->name('materi.index');
    Route::post('/materi/store', [MateriController::class, 'store'])->name('materi.store');
    Route::put('/materi/{materi}/update', [MateriController::class, 'update'])->name('materi.update');
    Route::delete('/materi/{materi}/destroy', [MateriController::class, 'destroy'])->name('materi.destroy');
    Route::get('/materi/{materi}/download', [MateriController::class, 'download']);
    Route::get('/submit-materi/{mapel}/{kelas}/{tahunAjaran}/{semester}', [MateriController::class, 'show'])
        ->name('submit-materi.show');
    Route::get('/materi/{pertemuan_id}', [MateriController::class, 'getMateriByPertemuan']);


    //Kuis guru
    Route::get('/kuis', [KuisController::class, 'index'])->name('kuis.index');
    Route::post('/kuis/store', [KuisController::class, 'store'])->name('kuis.store');
    Route::put('/kuis/{kuis}/update', [KuisController::class, 'update'])->name('kuis.update');
    Route::delete('/kuis/{kuis}/destroy', [KuisController::class, 'destroy'])->name('kuis.destroy');
    Route::get('/submit-kuis/{mapel}/{kelas}/{tahunAjaran}/{semester}', [KuisController::class, 'show'])
        ->name('submit-kuis.show');
    Route::get('/kuis/{pertemuan_id}', [KuisController::class, 'getKuisByPertemuan']);


    //Soal guru
    Route::get('/soal', [SoalKuisController::class, 'index'])->name('soal.index');
    Route::get('/soal/create/{kuis_id}', [SoalKuisController::class, 'create'])->name('soal.create');
    Route::post('/soal/store/{kuis_id}', [SoalKuisController::class, 'store'])->name('soal.store');
    Route::get('/soal/{id}', [SoalKuisController::class, 'show'])->name('soal.show');
    Route::get('/soal/{soal}/edit', [SoalKuisController::class, 'edit'])->name('soal.edit');
    Route::put('/soal/{soal}', [SoalKuisController::class, 'update'])->name('soal.update');
    Route::delete('/soal/{soalKuis}', [SoalKuisController::class, 'destroy'])->name('soal.destroy');


    //Tugas guru
    Route::get('/tugas', [TugasController::class, 'index'])->name('tugas.index');
    Route::post('/tugas/store', [TugasController::class, 'store'])->name('tugas.store');
    Route::put('/tugas/{tugas}/update', [TugasController::class, 'update'])->name('tugas.update');
    Route::delete('/tugas/{tugas}/destroy', [TugasController::class, 'destroy'])->name('tugas.destroy');
    Route::get('/submit-tugas/{mapel}/{kelas}/{tahunAjaran}/{semester}', [TugasController::class, 'show'])
        ->name('submit-tugas.show');
    Route::get('/tugas/{pertemuan_id}', [TugasController::class, 'getTugasByPertemuan']);


    // Pertemuan Materi guru
    Route::get('/pertemuan-materi', [PertemuanMateriController::class, 'index'])->name('pertemuan-materi.index');
    Route::post('/pertemuan-materi/store/{pembelajaran_id}', [PertemuanMateriController::class, 'store'])->name('pertemuan-materi.store');
    Route::delete('/pertemuan-materi/{id}', [PertemuanMateriController::class, 'destroy'])->name('pertemuan-materi.destroy');
    Route::put('/pertemuan-materi/{id}', [PertemuanMateriController::class, 'update'])->name('pertemuan-materi.update');


    // Pertemuan Tugas guru
    Route::get('/pertemuan-tugas', [PertemuanTugasController::class, 'index'])->name('pertemuan-tugas.index');
    Route::post('/pertemuan-tugas/store/{pembelajaran_id}', [PertemuanTugasController::class, 'store'])->name('pertemuan-tugas.store');
    Route::delete('/pertemuan-tugas/{id}', [PertemuanTugasController::class, 'destroy'])->name('pertemuan-tugas.destroy');
    Route::put('/pertemuan-tugas/{id}', [PertemuanTugasController::class, 'update'])->name('pertemuan-tugas.update');
    Route::get('/submit-tugas/{mapel}/{kelas}/{tahunAjaran}/{semester}/list-tugas', [PertemuanTugasController::class, 'listTugas'])->name('list-pertemuan-tugas.index');
    Route::put('/submit-tugas/{id}/update-skor', [PertemuanTugasController::class, 'updateSkor'])->name('submit-tugas.updateSkor');
    Route::get('/tugas/export/excel', [PertemuanTugasController::class, 'export_excel'])->name('tugas.export');



    // Pertemuan Kuis guru
    Route::get('/pertemuan-kuis', [PertemuanKuisController::class, 'index'])->name('pertemuan-kuis.index');
    Route::post('/pertemuan-kuis/store/{pembelajaran_id}', [PertemuanKuisController::class, 'store'])->name('pertemuan-kuis.store');
    Route::delete('/pertemuan-kuis/{id}', [PertemuanKuisController::class, 'destroy'])->name('pertemuan-kuis.destroy');
    Route::put('/pertemuan-kuis/{id}', [PertemuanKuisController::class, 'update'])->name('pertemuan-kuis.update');
    Route::get('/submit-kuis/{mapel}/{kelas}/{tahunAjaran}/{semester}/list-kuis', [PertemuanKuisController::class, 'listKuis'])->name('list-pertemuan-kuis.index');
    Route::get('/pertemuan-kuis/{mapel}/{kelas}/{tahunAjaran}/{semester}/hasil-kuis/{kuis}/{siswa}', [PertemuanKuisController::class, 'show'])->name('hasil-kuis.show');
    Route::post('/pertemuan-kuis/hasil-kuis/{kuis}/{siswa}', [PertemuanKuisController::class, 'updateEssay'])->name('hasil-kuis.updateEssay');
    Route::get('/nilai/export/excel', [PertemuanKuisController::class, 'export_excel'])->name('nilai.export');


    // Approval/Tolak oleh guru
    Route::post('/enrollment/{id}/approve', [EnrollController::class, 'approve'])->name('enrollment.approve');
    Route::post('/enrollment/{id}/reject', [EnrollController::class, 'reject'])->name('enrollment.reject');
    Route::delete('/enrollment/{id}', [EnrollController::class, 'destroy'])->name('enrollment.destroy');
    Route::post('/enrollment/batch-update', [EnrollController::class, 'batchUpdate'])->name('enrollment.batchUpdate');
    Route::post('/enrollment/batch-delete', [EnrollController::class, 'batchDelete'])->name('enrollment.batchDelete');

    // List Siswa
    Route::get('/siswa-kelas/{mapel}/{kelas}/{tahunAjaran}/{semester}', [SiswaAdminController::class, 'show'])
        ->name('siswa-kelas.show');

    // Absensi
    Route::get('/absensi/{mapel}/{kelas}/{tahunAjaran}/{semester}', [AbsensiController::class, 'show'])
        ->name('absensi.show');
    Route::post('/absensi/store/{pembelajaran_id}', [AbsensiController::class, 'store'])->name('absensi.store');
    Route::put('/absensi/update/{id}', [AbsensiController::class, 'update'])->name('absensi.update');
    Route::delete('/absensi/{id}', [AbsensiController::class, 'destroy'])->name('absensi.destroy');
    Route::put('/absensi/{id}/toggle-aktif', [AbsensiController::class, 'toggleAktif'])->name('absensi.toggleAktif');


    // DetailAbsensi
    Route::get('/absensi/{mapel}/{kelas}/{tahunAjaran}/{semester}/detail-absensi', [DetailAbsensiController::class, 'index'])->name('detail-absensi.index');
    Route::post('/absensi/detail/store-or-update', [DetailAbsensiController::class, 'storeOrUpdate'])
        ->name('detail-absensi.storeOrUpdate');
    Route::get('/detail-absensi/export/excel', [DetailAbsensiController::class, 'export_excel'])->name('absensi.export');

    // Forum
    Route::get('/forum-diskusi/{mapel}/{kelas}/{tahunAjaran}/{semester}', [ForumController::class, 'forumDiskusiIndex'])->name('forum-diskusi-guru.index');
    Route::get('/forum-diskusi/{mapel}/{kelas}/{tahunAjaran}/{semester}/{forum}/view', [ForumController::class, 'forumDiskusiView'])->name('forum-diskusi-guru.view');
    Route::post('/forum/create', [ForumController::class, 'create'])->name('guru.forum.store');
    Route::put('/forum/{id}', [ForumController::class, 'update'])->name('guru.forum.update');
    Route::delete('/forum/{id}', [ForumController::class, 'destroy'])->name('guru.forum.destroy');
    // Komentar
    Route::post('/forum-diskusi/{mapel}/{kelas}/{tahunAjaran}/{semester}/{forum}/view', [ForumController::class, 'postKomentar'])->name('komentar.store');


});

// Wali Kelas
Route::middleware(['auth', 'role:Wali Kelas'])->prefix('walikelas')->group(function () {
    Route::get('/daftar-siswa/{kelas}/{tahunAjaran}', [HalamanWalasController::class, 'daftarSiswa'])->name('daftar-siswa.index');
    Route::get('/daftar-mapel/{kelas}/{tahunAjaran}', [HalamanWalasController::class, 'daftarMapel'])->name('daftar-mapel.index');
    
    Route::get('/export-nilai/{kelas}/{tahunAjaran}', [HalamanWalasController::class, 'export'])->name('export-nilai.index');
    Route::get('/export-tugas/{kelas}/{tahunAjaran}/excel', [HalamanWalasController::class, 'export_tugas'])
    ->name('export-tugas-kelas.export');
    Route::get('/export-kuis/{kelas}/{tahunAjaran}/excel', [HalamanWalasController::class, 'export_kuis'])
    ->name('export-kuis-kelas.export');
    Route::get('/export-absensi/{kelas}/{tahunAjaran}/excel', [HalamanWalasController::class, 'export_absensi'])
    ->name('export-absensi-kelas.export');

    
});

// Siswa
Route::middleware(PreventBackHistory::class, RedirectIfNotSiswa::class)->prefix('siswa')->group(function () {
    Route::get('/dashboard-siswa', [DashboardSiswaController::class, 'index'])->name('dashboard-siswa.index');

    Route::get('/profile-siswa', [ProfileController::class, 'edit_siswa'])->name('profile-siswa.edit');
    Route::put('/profile-siswa', [ProfileController::class, 'update_siswa'])->name('profile-siswa.update');

    // kelas siswa
    Route::get('/kelas/{id}', [KelasSiswaController::class, 'show'])->name('kelas.show');

    // enroll siswa
    Route::post('/enroll', [EnrollController::class, 'store'])->name('enroll.store');

    // mata pelajaran
    Route::get('/mata-pelajaran', [MataPelajaranController::class, 'index'])->name('mata-pelajaran.index');
    Route::get('/mata-pelajaran/{mapel}/{kelas}/{tahunAjaran}/{semester}', [MataPelajaranController::class, 'show'])->name('mata-pelajaran.show');
    Route::post('/absensi/{id}/lakukan', [MataPelajaranController::class, 'lakukanAbsensi'])->name('absensi.lakukan');
    Route::get('/absensi/{pertemuan_id}', [MataPelajaranController::class, 'getAbsensiByPertemuan']);


    //submit tugas
    Route::post('/submit-tugas-siswa', [SubmitTugasController::class, 'store'])->name('submit-tugas-siswa.store');
    Route::delete('/hapus-tugas-siswa/{id}', [SubmitTugasController::class, 'destroy'])->name('hapus-tugas-siswa.destroy');

    // tugas list
    Route::get('/list-tugas', [TugasSiswaController::class, 'index'])->name('list-tugas.index');

    // kuis siswa
    Route::get('/mata-pelajaran/{mapel}/{kelas}/{tahunAjaran}/{semester}/kuis/{judulKuis}/action', [KuisSiswaController::class, 'action'])->name('kuis-siswa.action');
    Route::post('/kuis/cek-token', [SiswaKuisSessionController::class, 'cekToken'])->name('kuis-siswa.cek-token');
    Route::post('/kuis/kumpulkan', [KuisSiswaController::class, 'kumpulkan'])->name('kuis.kumpulkan');
    Route::get('/list-kuis', [KuisSiswaController::class, 'index'])->name('list-kuis.index');

    // Forum
    Route::get('/forum', [ForumController::class, 'index'])->name('forum.index');
    Route::post('/forum/create', [ForumController::class, 'create'])->name('siswa.forum.store');
    Route::get('/forum/{forum}/view', [ForumController::class, 'view'])->name('forum.view');

    // Forum
    Route::get('/forum-diskusi/{mapel}/{kelas}/{tahunAjaran}/{semester}', [ForumController::class, 'forumDiskusiIndex'])->name('forum-diskusi.index');
    Route::get('/forum-diskusi/{mapel}/{kelas}/{tahunAjaran}/{semester}/{forum}/view', [ForumController::class, 'forumDiskusiView'])->name('forum-diskusi.view');
    Route::put('/forum/{id}', [ForumController::class, 'update'])->name('siswa.forum.update');
    Route::delete('/forum/{id}', [ForumController::class, 'destroy'])->name('siswa.forum.destroy');
    // Komentar
    Route::post('/forum-diskusi/{mapel}/{kelas}/{tahunAjaran}/{semester}/{forum}/view', [ForumController::class, 'postKomentar'])->name('komentar.store');

    // Sertifikat
    Route::get('/sertifikat', [SertifikatController::class,'process'])->name('setifikat-siswa.index');
});

require __DIR__ . '/auth.php';
