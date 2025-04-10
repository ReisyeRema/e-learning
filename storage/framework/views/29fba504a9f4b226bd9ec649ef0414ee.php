 <!-- Modal Kumpul Tugas -->
 <div class="modal fade" id="modalKumpul" tabindex="-1" aria-labelledby="modalKumpulLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalKumpulLabel" style="color: #000000">Kumpulkan Tugas</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form>
                    <div class="mb-3">
                        <p>Detail Tugas: <a id="tugasLink" href="#" target="_blank">Lihat Tugas</a></p>
                        <p><strong>Judul Tugas:</strong> <span id="tugasJudul"></span></p>

                    </div>

                    <div class="mb-3">
                        <label for="fileTugas" class="form-label">Upload Tugas</label>
                        <div class="upload-box">
                            <input type="file" id="cover" name="cover" class="file-input">
                            <div class="upload-area">
                                <i class="fas fa-cloud-upload-alt"></i>
                                <p>Drag your file(s) or <span class="browse-text">browse</span></p>
                                <small>Max 10 MB files are allowed</small>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="catatan" class="form-label">Catatan (Opsional)</label>
                        <textarea class="form-control" id="catatan" rows="3"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-success">Kumpulkan</button>
            </div>
        </div>
    </div>
</div><?php /**PATH D:\DATA MATKUL\SEMESTER 6\TA\PROJECT\e-learn-laravel\resources\views/pages/siswa/mataPelajaran/modal.blade.php ENDPATH**/ ?>