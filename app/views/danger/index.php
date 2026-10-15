<div class="w-full space-y-6 pb-12">
    <!-- Action button teleport target -->
    <div id="danger-header-action" class="hidden">
        <a href="<?= BASEURL; ?>/danger/backup" class="px-3.5 py-2 rounded-xl text-xs font-medium bg-emerald-600 hover:bg-emerald-500 text-white transition-colors flex items-center gap-2 shadow-xs">
            <i class="fa-solid fa-cloud-arrow-down text-xs"></i>
            <span>Backup Database (.sql)</span>
        </a>
    </div>

    <!-- Top Notice Banner -->
    <div class="bg-rose-50/70 dark:bg-rose-950/20 border border-rose-200/90 dark:border-rose-900/50 rounded-2xl p-5 text-slate-800 dark:text-slate-200 shadow-xs flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
        <div class="flex items-start gap-3.5">
            <div class="w-10 h-10 rounded-xl bg-rose-100 dark:bg-rose-900/40 text-rose-600 dark:text-rose-400 flex items-center justify-center shrink-0">
                <i class="fa-solid fa-shield-halved text-lg"></i>
            </div>
            <div>
                <div class="flex items-center gap-2 mb-0.5">
                    <h2 class="text-sm sm:text-base font-semibold text-slate-900 dark:text-slate-100">Area Pemeliharaan & Kontrol Sistem</h2>
                    <span class="px-2 py-0.5 rounded text-[10px] font-medium bg-rose-100 text-rose-700 dark:bg-rose-900/50 dark:text-rose-300">Superadmin (ID: 1)</span>
                </div>
                <p class="text-xs text-slate-600 dark:text-slate-400 leading-relaxed max-w-3xl">
                    Pencadangan, pemulihan database, serta pembersihan data operasional. Setiap tindakan reset data bersifat permanen dan tidak dapat dibatalkan.
                </p>
            </div>
        </div>
        <div class="shrink-0 flex items-center gap-2 text-xs text-slate-500 dark:text-slate-400 self-end md:self-center bg-white/60 dark:bg-slate-900/60 px-3 py-1.5 rounded-xl border border-rose-200/50 dark:border-rose-900/40">
            <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
            <span>Akses Terverifikasi</span>
        </div>
    </div>

    <!-- Backup & Restore Panels (2 Columns) -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Panel 1: Backup Database -->
        <div class="bg-white dark:bg-slate-900 rounded-2xl p-5 sm:p-6 border border-slate-200/80 dark:border-slate-800 shadow-xs flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between gap-3 mb-3">
                    <div class="w-9 h-9 rounded-xl bg-emerald-50 dark:bg-emerald-950/50 text-emerald-600 dark:text-emerald-400 flex items-center justify-center shrink-0">
                        <i class="fa-solid fa-cloud-arrow-down text-sm"></i>
                    </div>
                    <span class="px-2 py-0.5 rounded-md text-[11px] font-medium bg-emerald-50 text-emerald-700 dark:bg-emerald-950/40 dark:text-emerald-400 border border-emerald-200/60 dark:border-emerald-800/60">
                        Pencadangan
                    </span>
                </div>
                <h3 class="text-sm sm:text-base font-semibold text-slate-900 dark:text-slate-100">Cadangan Database (Full Backup)</h3>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-1 leading-relaxed">
                    Unduh salinan berkas lengkap database (<strong class="text-slate-700 dark:text-slate-300 font-semibold">.sql</strong>) yang mencakup struktur tabel dan seluruh rekaman data untuk arsip keamanan.
                </p>

                <div class="grid grid-cols-2 sm:grid-cols-4 gap-2.5 mt-4 pt-4 border-t border-slate-100 dark:border-slate-800">
                    <div class="p-2.5 rounded-xl bg-slate-50 dark:bg-slate-800/40 border border-slate-100 dark:border-slate-800">
                        <p class="text-[10px] font-medium text-slate-400 dark:text-slate-500">Database</p>
                        <p class="text-xs font-semibold text-slate-800 dark:text-slate-200 mt-0.5 font-mono truncate"><?= htmlspecialchars($data['backup_stats']['db_name'] ?? 'project_mgmt'); ?></p>
                    </div>
                    <div class="p-2.5 rounded-xl bg-slate-50 dark:bg-slate-800/40 border border-slate-100 dark:border-slate-800">
                        <p class="text-[10px] font-medium text-slate-400 dark:text-slate-500">Total Tabel</p>
                        <p class="text-xs font-semibold text-emerald-600 dark:text-emerald-400 mt-0.5"><?= number_format($data['backup_stats']['tables'] ?? 0); ?> Tabel</p>
                    </div>
                    <div class="p-2.5 rounded-xl bg-slate-50 dark:bg-slate-800/40 border border-slate-100 dark:border-slate-800">
                        <p class="text-[10px] font-medium text-slate-400 dark:text-slate-500">Total Baris</p>
                        <p class="text-xs font-semibold text-indigo-600 dark:text-indigo-400 mt-0.5"><?= number_format($data['backup_stats']['rows'] ?? 0); ?> Baris</p>
                    </div>
                    <div class="p-2.5 rounded-xl bg-slate-50 dark:bg-slate-800/40 border border-slate-100 dark:border-slate-800">
                        <p class="text-[10px] font-medium text-slate-400 dark:text-slate-500">Ukuran</p>
                        <p class="text-xs font-semibold text-slate-800 dark:text-slate-200 mt-0.5 font-mono">~<?= number_format($data['backup_stats']['size_kb'] ?? 0, 1); ?> KB</p>
                    </div>
                </div>
            </div>

            <div class="mt-6">
                <a href="<?= BASEURL; ?>/danger/backup" class="w-full py-2.5 px-4 rounded-xl font-medium text-xs bg-emerald-600 hover:bg-emerald-500 text-white transition-colors flex items-center justify-center gap-2 shadow-xs">
                    <i class="fa-solid fa-download text-xs"></i>
                    <span>Unduh Cadangan Database (.sql)</span>
                </a>
            </div>
        </div>

        <!-- Panel 2: Restore Database -->
        <div class="bg-white dark:bg-slate-900 rounded-2xl p-5 sm:p-6 border border-slate-200/80 dark:border-slate-800 shadow-xs flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between gap-3 mb-3">
                    <div class="w-9 h-9 rounded-xl bg-indigo-50 dark:bg-indigo-950/50 text-indigo-600 dark:text-indigo-400 flex items-center justify-center shrink-0">
                        <i class="fa-solid fa-cloud-arrow-up text-sm"></i>
                    </div>
                    <span class="px-2 py-0.5 rounded-md text-[11px] font-medium bg-indigo-50 text-indigo-700 dark:bg-indigo-950/40 dark:text-indigo-400 border border-indigo-200/60 dark:border-indigo-800/60">
                        Pemulihan
                    </span>
                </div>
                <h3 class="text-sm sm:text-base font-semibold text-slate-900 dark:text-slate-100">Pulihkan Database (Restore .sql)</h3>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-1 leading-relaxed">
                    Unggah berkas <strong class="text-slate-700 dark:text-slate-300 font-semibold">.sql</strong> cadangan untuk memulihkan seluruh struktur dan data database ke versi sebelumnya.
                </p>

                <div class="mt-4 pt-4 border-t border-slate-100 dark:border-slate-800">
                    <form id="restoreFormActual" action="<?= BASEURL; ?>/danger/restore" method="POST" enctype="multipart/form-data">
                        <input type="file" name="sql_file" id="restoreFileInput" accept=".sql" class="hidden">
                        <input type="hidden" name="confirm_restore" id="hiddenRestoreConfirmText" value="">
                    </form>
                    <div id="restoreDropzone" onclick="document.getElementById('restoreFileInput').click()" class="border-2 border-dashed border-slate-200 dark:border-slate-700 hover:border-indigo-500 dark:hover:border-indigo-500 rounded-xl p-4 text-center cursor-pointer bg-slate-50/50 dark:bg-slate-800/30 transition-colors group">
                        <div id="dropzoneEmpty">
                            <i class="fa-solid fa-file-code text-slate-400 group-hover:text-indigo-500 text-lg mb-1 transition-colors"></i>
                            <p class="text-xs font-medium text-slate-700 dark:text-slate-300">Pilih berkas cadangan (.sql)</p>
                            <p class="text-[11px] text-slate-400 mt-0.5">Klik untuk memilih berkas cadangan dari komputer</p>
                        </div>
                        <div id="dropzoneSelected" class="hidden">
                            <div class="flex items-center justify-center gap-2 text-indigo-600 dark:text-indigo-400 font-medium text-xs">
                                <i class="fa-solid fa-circle-check text-xs text-emerald-500"></i>
                                <span id="selectedFileName" class="truncate max-w-[240px]">file.sql</span>
                            </div>
                            <p id="selectedFileSize" class="text-[10px] text-slate-400 mt-0.5 font-mono">0 KB</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-6">
                <button type="button" id="openRestoreModalBtn" disabled class="w-full py-2.5 px-4 rounded-xl font-medium text-xs bg-slate-100 dark:bg-slate-800 text-slate-400 dark:text-slate-500 cursor-not-allowed transition-colors flex items-center justify-center gap-2">
                    <i class="fa-solid fa-rotate-left text-xs"></i>
                    <span>Mulai Pemulihan Database</span>
                </button>
            </div>
        </div>
    </div>

    <!-- Reset Selection Area -->
    <div class="w-full bg-white dark:bg-slate-900 rounded-2xl p-5 sm:p-6 border border-slate-200/80 dark:border-slate-800 shadow-xs">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-5 pb-5 border-b border-slate-100 dark:border-slate-800">
            <div>
                <h3 class="text-sm sm:text-base font-semibold text-slate-900 dark:text-slate-100">Opsi Reset Data Sistem</h3>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Pilih kategori data di bawah ini untuk memulai proses pembersihan data secara selektif.</p>
            </div>
            <div class="flex items-center gap-2">
                <button type="button" id="selectAllBtn" class="px-3 py-1.5 rounded-lg text-xs font-medium bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 transition-colors flex items-center gap-1.5">
                    <i class="fa-solid fa-check-double text-[10px]"></i>
                    <span>Pilih Semua</span>
                </button>
                <button type="button" id="clearAllBtn" class="px-3 py-1.5 rounded-lg text-xs font-medium text-slate-500 hover:text-slate-700 dark:text-slate-400 dark:hover:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors flex items-center gap-1.5">
                    <i class="fa-solid fa-rotate-left text-[10px]"></i>
                    <span>Bersihkan</span>
                </button>
            </div>
        </div>

        <form id="dangerResetForm" action="<?= BASEURL; ?>/danger/reset" method="POST">
            <!-- Grid Option Cards -->
            <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-3.5">
                
                <!-- Option 1: Tasks -->
                <label class="reset-card group relative flex flex-col justify-between p-4 rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-800/30 hover:border-slate-300 dark:hover:border-slate-700 cursor-pointer transition-all">
                    <input type="checkbox" name="reset_options[]" value="tasks" class="reset-checkbox sr-only">
                    <div>
                        <div class="flex items-center justify-between gap-3 mb-2.5">
                            <div class="check-indicator w-5 h-5 rounded-md border border-slate-300 dark:border-slate-600 group-hover:border-rose-500 flex items-center justify-center shrink-0 transition-colors">
                                <i class="fa-solid fa-check text-[10px] text-white opacity-0 transition-opacity"></i>
                            </div>
                            <span class="px-2 py-0.5 rounded text-[10px] font-medium bg-indigo-50 text-indigo-700 dark:bg-indigo-950/40 dark:text-indigo-400 font-mono">
                                <?= number_format($data['counts']['tasks']); ?> tugas
                            </span>
                        </div>
                        <h4 class="text-xs font-semibold text-slate-900 dark:text-slate-100 mb-0.5">Papan Tugas (Tasks)</h4>
                        <p class="text-[11px] text-slate-500 dark:text-slate-400 leading-relaxed">
                            Menghapus seluruh tugas dan kartu kanban pada semua proyek.
                        </p>
                    </div>
                </label>

                <!-- Option 2: Projects -->
                <label class="reset-card group relative flex flex-col justify-between p-4 rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-800/30 hover:border-slate-300 dark:hover:border-slate-700 cursor-pointer transition-all">
                    <input type="checkbox" name="reset_options[]" value="projects" class="reset-checkbox sr-only">
                    <div>
                        <div class="flex items-center justify-between gap-3 mb-2.5">
                            <div class="check-indicator w-5 h-5 rounded-md border border-slate-300 dark:border-slate-600 group-hover:border-rose-500 flex items-center justify-center shrink-0 transition-colors">
                                <i class="fa-solid fa-check text-[10px] text-white opacity-0 transition-opacity"></i>
                            </div>
                            <span class="px-2 py-0.5 rounded text-[10px] font-medium bg-amber-50 text-amber-700 dark:bg-amber-950/40 dark:text-amber-400 font-mono">
                                <?= number_format($data['counts']['projects']); ?> proyek
                            </span>
                        </div>
                        <h4 class="text-xs font-semibold text-slate-900 dark:text-slate-100 mb-0.5">Manajemen Proyek (Projects)</h4>
                        <p class="text-[11px] text-slate-500 dark:text-slate-400 leading-relaxed">
                            Menghapus seluruh proyek, alokasi tim proyek, serta tugas di dalamnya.
                        </p>
                    </div>
                </label>

                <!-- Option 3: Team Members -->
                <label class="reset-card group relative flex flex-col justify-between p-4 rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-800/30 hover:border-slate-300 dark:hover:border-slate-700 cursor-pointer transition-all">
                    <input type="checkbox" name="reset_options[]" value="team" class="reset-checkbox sr-only">
                    <div>
                        <div class="flex items-center justify-between gap-3 mb-2.5">
                            <div class="check-indicator w-5 h-5 rounded-md border border-slate-300 dark:border-slate-600 group-hover:border-rose-500 flex items-center justify-center shrink-0 transition-colors">
                                <i class="fa-solid fa-check text-[10px] text-white opacity-0 transition-opacity"></i>
                            </div>
                            <span class="px-2 py-0.5 rounded text-[10px] font-medium bg-emerald-50 text-emerald-700 dark:bg-emerald-950/40 dark:text-emerald-400 font-mono">
                                <?= number_format($data['counts']['team']); ?> anggota
                            </span>
                        </div>
                        <h4 class="text-xs font-semibold text-slate-900 dark:text-slate-100 mb-0.5">Anggota Tim (Users)</h4>
                        <p class="text-[11px] text-slate-500 dark:text-slate-400 leading-relaxed">
                            Menghapus semua akun anggota tim. Akun Superadmin (ID: 1) tetap aman.
                        </p>
                    </div>
                </label>

                <!-- Option 4: Activities -->
                <label class="reset-card group relative flex flex-col justify-between p-4 rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-800/30 hover:border-slate-300 dark:hover:border-slate-700 cursor-pointer transition-all">
                    <input type="checkbox" name="reset_options[]" value="activities" class="reset-checkbox sr-only">
                    <div>
                        <div class="flex items-center justify-between gap-3 mb-2.5">
                            <div class="check-indicator w-5 h-5 rounded-md border border-slate-300 dark:border-slate-600 group-hover:border-rose-500 flex items-center justify-center shrink-0 transition-colors">
                                <i class="fa-solid fa-check text-[10px] text-white opacity-0 transition-opacity"></i>
                            </div>
                            <span class="px-2 py-0.5 rounded text-[10px] font-medium bg-purple-50 text-purple-700 dark:bg-purple-950/40 dark:text-purple-400 font-mono">
                                <?= number_format($data['counts']['activities']); ?> riwayat
                            </span>
                        </div>
                        <h4 class="text-xs font-semibold text-slate-900 dark:text-slate-100 mb-0.5">Log Aktivitas (Audit Log)</h4>
                        <p class="text-[11px] text-slate-500 dark:text-slate-400 leading-relaxed">
                            Menghapus seluruh riwayat aktivitas sistem, log autentikasi, dan audit trail.
                        </p>
                    </div>
                </label>

            </div>

            <!-- Action Trigger Footer -->
            <div class="mt-6 pt-5 border-t border-slate-100 dark:border-slate-800 flex flex-col sm:flex-row items-center justify-between gap-4">
                <div class="flex items-center gap-2">
                    <span id="selectedCountBadge" class="text-xs font-medium text-slate-600 dark:text-slate-300 px-2.5 py-1 rounded-lg bg-slate-100 dark:bg-slate-800">
                        0 dari 4 kategori dipilih
                    </span>
                    <span class="text-xs text-slate-400 hidden sm:inline">&bull; Klik tombol untuk melanjutkan verifikasi</span>
                </div>
                <button type="button" id="openConfirmModalBtn" disabled class="w-full sm:w-auto px-5 py-2.5 rounded-xl font-medium text-xs bg-slate-100 dark:bg-slate-800 text-slate-400 dark:text-slate-500 cursor-not-allowed transition-colors flex items-center justify-center gap-2">
                    <i class="fa-solid fa-triangle-exclamation text-xs"></i>
                    <span>Lanjutkan Reset Data</span>
                </button>
            </div>

            <!-- Hidden Challenge Confirmation Text Input -->
            <input type="hidden" name="confirm_text" id="hiddenConfirmText" value="">
        </form>
    </div>

    <!-- Security & Protection Information Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
        <div class="bg-white dark:bg-slate-900 p-5 rounded-2xl border border-slate-200/80 dark:border-slate-800 shadow-xs">
            <div class="w-8 h-8 rounded-lg bg-emerald-50 dark:bg-emerald-950/50 text-emerald-600 dark:text-emerald-400 flex items-center justify-center mb-3">
                <i class="fa-solid fa-lock text-xs"></i>
            </div>
            <h4 class="text-xs font-semibold text-slate-900 dark:text-slate-100 mb-1">Proteksi Akun Root</h4>
            <p class="text-xs text-slate-500 dark:text-slate-400 leading-relaxed">
                Akun Administrator Utama (ID: 1) diisolasi secara permanen dan tidak akan pernah terhapus oleh proses reset data apapun.
            </p>
        </div>

        <div class="bg-white dark:bg-slate-900 p-5 rounded-2xl border border-slate-200/80 dark:border-slate-800 shadow-xs">
            <div class="w-8 h-8 rounded-lg bg-amber-50 dark:bg-amber-950/50 text-amber-600 dark:text-amber-400 flex items-center justify-center mb-3">
                <i class="fa-solid fa-database text-xs"></i>
            </div>
            <h4 class="text-xs font-semibold text-slate-900 dark:text-slate-100 mb-1">Integritas Relasional</h4>
            <p class="text-xs text-slate-500 dark:text-slate-400 leading-relaxed">
                Pembersihan tabel menggunakan manajemen Foreign Key Checks yang aman agar database tetap konsisten tanpa menyisakan data yatim.
            </p>
        </div>

        <div class="bg-white dark:bg-slate-900 p-5 rounded-2xl border border-slate-200/80 dark:border-slate-800 shadow-xs">
            <div class="w-8 h-8 rounded-lg bg-rose-50 dark:bg-rose-950/50 text-rose-600 dark:text-rose-400 flex items-center justify-center mb-3">
                <i class="fa-solid fa-clipboard-check text-xs"></i>
            </div>
            <h4 class="text-xs font-semibold text-slate-900 dark:text-slate-100 mb-1">Audit Trail Otomatis</h4>
            <p class="text-xs text-slate-500 dark:text-slate-400 leading-relaxed">
                Setiap aktivitas unduhan backup dan eksekusi reset dicatat ke riwayat aktivitas sistem untuk akuntabilitas jejak audit.
            </p>
        </div>
    </div>
</div>

<!-- DANGER CONFIRMATION MODAL -->
<div id="dangerConfirmModal" class="fixed inset-0 z-[120] hidden overflow-y-auto" aria-labelledby="danger-modal-title" role="dialog" aria-modal="true">
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
        <!-- Backdrop -->
        <div class="fixed inset-0 bg-slate-950/60 backdrop-blur-xs modal-overlay opacity-0 transition-opacity duration-200" onclick="closeDangerModal()"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        
        <!-- Modal Card -->
        <div class="inline-block align-bottom bg-white dark:bg-slate-900 rounded-2xl text-left overflow-hidden shadow-xl transform modal-content sm:my-8 sm:align-middle sm:max-w-lg sm:w-full transition-all border border-slate-200/80 dark:border-slate-800">
            <div class="p-6">
                <!-- Header -->
                <div class="flex items-start gap-3.5 mb-5">
                    <div class="w-10 h-10 rounded-xl bg-rose-100 dark:bg-rose-950/60 text-rose-600 dark:text-rose-400 flex items-center justify-center shrink-0">
                        <i class="fa-solid fa-triangle-exclamation text-lg"></i>
                    </div>
                    <div>
                        <h3 class="text-base font-semibold text-slate-900 dark:text-slate-100" id="danger-modal-title">Konfirmasi Reset Database</h3>
                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Tindakan ini akan menghapus data yang Anda pilih secara permanen.</p>
                    </div>
                </div>

                <!-- Warning Notice -->
                <div class="p-3.5 rounded-xl bg-rose-50/80 dark:bg-rose-950/30 border border-rose-200/80 dark:border-rose-900/40 text-rose-800 dark:text-rose-300 text-xs leading-relaxed mb-4">
                    <div class="flex items-start gap-2">
                        <i class="fa-solid fa-circle-exclamation text-rose-600 dark:text-rose-400 text-xs mt-0.5 shrink-0"></i>
                        <span>Pastikan Anda telah mengunduh cadangan database sebelum melanjutkan pembersihan ini.</span>
                    </div>
                </div>

                <!-- Selected Items List -->
                <div class="mb-4">
                    <label class="block text-xs font-medium text-slate-700 dark:text-slate-300 mb-2">Kategori yang Akan Dihapus:</label>
                    <div id="modalSelectedList" class="space-y-1.5 max-h-36 overflow-y-auto pr-1">
                        <!-- Populated by JavaScript -->
                    </div>
                </div>

                <!-- Confirmation Text Input Challenge -->
                <div class="mb-6">
                    <label for="confirmationInput" class="block text-xs font-medium text-slate-700 dark:text-slate-300 mb-1.5">
                        Ketik <strong class="text-rose-600 dark:text-rose-400 font-mono select-all font-semibold">RESET DATA</strong> untuk mengonfirmasi:
                    </label>
                    <input type="text" 
                           id="confirmationInput" 
                           autocomplete="off" 
                           class="w-full bg-white dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-xl px-3.5 py-2.5 text-xs sm:text-sm font-mono text-slate-900 dark:text-slate-100 focus:border-rose-500 focus:ring-1 focus:ring-rose-500 outline-none uppercase transition-colors" 
                           placeholder="RESET DATA">
                    <p id="inputErrorMsg" class="text-[11px] text-rose-600 dark:text-rose-400 mt-1.5 hidden">Teks tidak cocok. Harap ketik persis "RESET DATA".</p>
                </div>

                <!-- Action Buttons -->
                <div class="flex flex-col-reverse sm:flex-row gap-2.5 pt-3 border-t border-slate-100 dark:border-slate-800">
                    <button type="button" onclick="closeDangerModal()" class="w-full py-2.5 px-4 rounded-xl text-xs font-medium bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 transition-colors text-center">
                        Batal
                    </button>
                    <button type="button" id="executeResetBtn" disabled class="w-full py-2.5 px-4 rounded-xl text-xs font-medium bg-slate-100 dark:bg-slate-800 text-slate-400 dark:text-slate-500 cursor-not-allowed transition-colors flex items-center justify-center gap-2 text-center">
                        <i class="fa-solid fa-trash-can text-xs"></i>
                        <span>Eksekusi Reset Data</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- RESTORE CONFIRMATION MODAL -->
<div id="restoreConfirmModal" class="fixed inset-0 z-[120] hidden overflow-y-auto" aria-labelledby="restore-modal-title" role="dialog" aria-modal="true">
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
        <!-- Backdrop -->
        <div class="fixed inset-0 bg-slate-950/60 backdrop-blur-xs modal-overlay opacity-0 transition-opacity duration-200" onclick="closeRestoreModal()"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        
        <!-- Modal Card -->
        <div class="inline-block align-bottom bg-white dark:bg-slate-900 rounded-2xl text-left overflow-hidden shadow-xl transform modal-content sm:my-8 sm:align-middle sm:max-w-lg sm:w-full transition-all border border-slate-200/80 dark:border-slate-800">
            <div class="p-6">
                <!-- Header -->
                <div class="flex items-start gap-3.5 mb-5">
                    <div class="w-10 h-10 rounded-xl bg-indigo-100 dark:bg-indigo-950/60 text-indigo-600 dark:text-indigo-400 flex items-center justify-center shrink-0">
                        <i class="fa-solid fa-cloud-arrow-up text-lg"></i>
                    </div>
                    <div>
                        <h3 class="text-base font-semibold text-slate-900 dark:text-slate-100" id="restore-modal-title">Pulihkan Database</h3>
                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Mengeksekusi skrip cadangan untuk menimpa struktur dan rekaman data.</p>
                    </div>
                </div>

                <!-- Warning Notice -->
                <div class="p-3.5 rounded-xl bg-amber-50/80 dark:bg-amber-950/30 border border-amber-200/80 dark:border-amber-900/40 text-amber-800 dark:text-amber-300 text-xs leading-relaxed mb-4">
                    <div class="flex items-start gap-2">
                        <i class="fa-solid fa-triangle-exclamation text-amber-600 dark:text-amber-400 text-xs mt-0.5 shrink-0"></i>
                        <span>PERHATIAN: Pemulihan akan menimpa seluruh tabel saat ini. Pastikan Anda memilih berkas backup yang valid.</span>
                    </div>
                </div>

                <!-- Selected File Info -->
                <div class="mb-4 p-3 rounded-xl bg-slate-50 dark:bg-slate-800/40 border border-slate-200/70 dark:border-slate-700/60">
                    <p class="text-[11px] font-medium text-slate-400 dark:text-slate-500 mb-0.5">Berkas yang Dipilih:</p>
                    <div class="flex items-center justify-between gap-3">
                        <span id="modalRestoreFileName" class="text-xs font-mono font-semibold text-slate-800 dark:text-slate-200 truncate">file.sql</span>
                        <span id="modalRestoreFileSize" class="text-xs font-mono text-indigo-600 dark:text-indigo-400 shrink-0">0 KB</span>
                    </div>
                </div>

                <!-- Confirmation Text Input Challenge -->
                <div class="mb-6">
                    <label for="restoreConfirmationInput" class="block text-xs font-medium text-slate-700 dark:text-slate-300 mb-1.5">
                        Ketik <strong class="text-indigo-600 dark:text-indigo-400 font-mono select-all font-semibold">RESTORE DATABASE</strong> untuk konfirmasi:
                    </label>
                    <input type="text" 
                           id="restoreConfirmationInput" 
                           autocomplete="off" 
                           class="w-full bg-white dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-xl px-3.5 py-2.5 text-xs sm:text-sm font-mono text-slate-900 dark:text-slate-100 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none uppercase transition-colors" 
                           placeholder="RESTORE DATABASE">
                    <p id="restoreInputErrorMsg" class="text-[11px] text-rose-600 dark:text-rose-400 mt-1.5 hidden">Teks tidak cocok. Harap ketik persis "RESTORE DATABASE".</p>
                </div>

                <!-- Action Buttons -->
                <div class="flex flex-col-reverse sm:flex-row gap-2.5 pt-3 border-t border-slate-100 dark:border-slate-800">
                    <button type="button" onclick="closeRestoreModal()" class="w-full py-2.5 px-4 rounded-xl text-xs font-medium bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 transition-colors text-center">
                        Batal
                    </button>
                    <button type="button" id="executeRestoreBtn" disabled class="w-full py-2.5 px-4 rounded-xl text-xs font-medium bg-slate-100 dark:bg-slate-800 text-slate-400 dark:text-slate-500 cursor-not-allowed transition-colors flex items-center justify-center gap-2 text-center">
                        <i class="fa-solid fa-rotate-left text-xs"></i>
                        <span>Eksekusi Pemulihan</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Teleport backup button to header action area
        const headerActionArea = document.getElementById('header-action-area');
        const dangerHeaderAction = document.getElementById('danger-header-action');
        if (headerActionArea && dangerHeaderAction) {
            const btn = dangerHeaderAction.querySelector('a');
            if (btn) headerActionArea.appendChild(btn);
        }

        // ================== RESTORE FILE HANDLING ==================
        const restoreFileInput = document.getElementById('restoreFileInput');
        const dropzoneEmpty = document.getElementById('dropzoneEmpty');
        const dropzoneSelected = document.getElementById('dropzoneSelected');
        const selectedFileName = document.getElementById('selectedFileName');
        const selectedFileSize = document.getElementById('selectedFileSize');
        const openRestoreModalBtn = document.getElementById('openRestoreModalBtn');
        const restoreModal = document.getElementById('restoreConfirmModal');
        const modalRestoreFileName = document.getElementById('modalRestoreFileName');
        const modalRestoreFileSize = document.getElementById('modalRestoreFileSize');
        const restoreConfirmInput = document.getElementById('restoreConfirmationInput');
        const restoreInputError = document.getElementById('restoreInputErrorMsg');
        const executeRestoreBtn = document.getElementById('executeRestoreBtn');
        const hiddenRestoreConfirm = document.getElementById('hiddenRestoreConfirmText');
        const restoreForm = document.getElementById('restoreFormActual');

        if (restoreFileInput) {
            restoreFileInput.addEventListener('change', function() {
                if (this.files && this.files[0]) {
                    const file = this.files[0];
                    if (!file.name.endsWith('.sql')) {
                        alert('Harap pilih berkas dengan format .sql');
                        this.value = '';
                        return;
                    }
                    const sizeKb = (file.size / 1024).toFixed(1);
                    selectedFileName.innerText = file.name;
                    selectedFileSize.innerText = sizeKb > 1024 ? (sizeKb / 1024).toFixed(2) + ' MB' : sizeKb + ' KB';
                    dropzoneEmpty.classList.add('hidden');
                    dropzoneSelected.classList.remove('hidden');

                    openRestoreModalBtn.disabled = false;
                    openRestoreModalBtn.className = "w-full py-2.5 px-4 rounded-xl font-medium text-xs bg-indigo-600 hover:bg-indigo-500 text-white cursor-pointer transition-colors shadow-xs flex items-center justify-center gap-2";
                } else {
                    dropzoneEmpty.classList.remove('hidden');
                    dropzoneSelected.classList.add('hidden');
                    openRestoreModalBtn.disabled = true;
                    openRestoreModalBtn.className = "w-full py-2.5 px-4 rounded-xl font-medium text-xs bg-slate-100 dark:bg-slate-800 text-slate-400 dark:text-slate-500 cursor-not-allowed transition-colors flex items-center justify-center gap-2";
                }
            });
        }

        if (openRestoreModalBtn) {
            openRestoreModalBtn.addEventListener('click', function() {
                if (!restoreFileInput.files || !restoreFileInput.files[0]) return;
                const file = restoreFileInput.files[0];
                modalRestoreFileName.innerText = file.name;
                const sizeKb = (file.size / 1024).toFixed(1);
                modalRestoreFileSize.innerText = sizeKb > 1024 ? (sizeKb / 1024).toFixed(2) + ' MB' : sizeKb + ' KB';

                restoreConfirmInput.value = '';
                restoreInputError.classList.add('hidden');
                validateRestoreInput();

                restoreModal.classList.remove('hidden');
                setTimeout(() => {
                    restoreModal.classList.add('modal-active');
                    restoreModal.querySelector('.modal-overlay').classList.remove('opacity-0');
                    restoreModal.querySelector('.modal-content').classList.remove('opacity-0', 'scale-90');
                    restoreConfirmInput.focus();
                }, 10);
            });
        }

        function validateRestoreInput() {
            const val = restoreConfirmInput.value.trim().toUpperCase();
            if (val === 'RESTORE DATABASE') {
                executeRestoreBtn.disabled = false;
                executeRestoreBtn.className = "w-full py-2.5 px-4 rounded-xl font-medium text-xs bg-indigo-600 hover:bg-indigo-500 text-white cursor-pointer transition-colors shadow-xs flex items-center justify-center gap-2 text-center";
                restoreInputError.classList.add('hidden');
            } else {
                executeRestoreBtn.disabled = true;
                executeRestoreBtn.className = "w-full py-2.5 px-4 rounded-xl font-medium text-xs bg-slate-100 dark:bg-slate-800 text-slate-400 dark:text-slate-500 cursor-not-allowed transition-colors flex items-center justify-center gap-2 text-center";
                if (val.length >= 8 && val !== 'RESTORE DATABASE') {
                    restoreInputError.classList.remove('hidden');
                } else {
                    restoreInputError.classList.add('hidden');
                }
            }
        }

        if (restoreConfirmInput) {
            restoreConfirmInput.addEventListener('input', validateRestoreInput);
        }

        if (executeRestoreBtn) {
            executeRestoreBtn.addEventListener('click', function() {
                if (restoreConfirmInput.value.trim().toUpperCase() === 'RESTORE DATABASE') {
                    hiddenRestoreConfirm.value = 'RESTORE DATABASE';
                    executeRestoreBtn.disabled = true;
                    executeRestoreBtn.innerHTML = '<i class="fa-solid fa-circle-notch fa-spin text-xs"></i> Memulihkan Database...';
                    restoreForm.submit();
                }
            });
        }

        // ================== RESET DATA SECTION ==================
        const checkboxes = document.querySelectorAll('.reset-checkbox');
        const selectAllBtn = document.getElementById('selectAllBtn');
        const clearAllBtn = document.getElementById('clearAllBtn');
        const openModalBtn = document.getElementById('openConfirmModalBtn');
        const countBadge = document.getElementById('selectedCountBadge');
        const modal = document.getElementById('dangerConfirmModal');
        const modalList = document.getElementById('modalSelectedList');
        const confirmInput = document.getElementById('confirmationInput');
        const inputError = document.getElementById('inputErrorMsg');
        const executeBtn = document.getElementById('executeResetBtn');
        const hiddenConfirmText = document.getElementById('hiddenConfirmText');
        const resetForm = document.getElementById('dangerResetForm');

        function updateUI() {
            let selectedCount = 0;
            checkboxes.forEach((cb) => {
                const card = cb.closest('.reset-card');
                const indicator = card.querySelector('.check-indicator');
                const checkIcon = indicator.querySelector('i');

                if (cb.checked) {
                    selectedCount++;
                    card.classList.add('border-rose-500', 'bg-rose-50/20', 'dark:border-rose-500', 'dark:bg-rose-950/20');
                    card.classList.remove('border-slate-200', 'dark:border-slate-800');
                    indicator.classList.add('bg-rose-600', 'border-rose-600');
                    indicator.classList.remove('border-slate-300', 'dark:border-slate-600');
                    checkIcon.classList.remove('opacity-0');
                } else {
                    card.classList.remove('border-rose-500', 'bg-rose-50/20', 'dark:border-rose-500', 'dark:bg-rose-950/20');
                    card.classList.add('border-slate-200', 'dark:border-slate-800');
                    indicator.classList.remove('bg-rose-600', 'border-rose-600');
                    indicator.classList.add('border-slate-300', 'dark:border-slate-600');
                    checkIcon.classList.add('opacity-0');
                }
            });

            countBadge.innerText = `${selectedCount} dari ${checkboxes.length} kategori dipilih`;

            if (selectedCount > 0) {
                openModalBtn.disabled = false;
                openModalBtn.className = "w-full sm:w-auto px-5 py-2.5 rounded-xl font-medium text-xs bg-rose-600 hover:bg-rose-500 text-white cursor-pointer transition-colors shadow-xs flex items-center justify-center gap-2";
                openModalBtn.innerHTML = `<i class="fa-solid fa-triangle-exclamation text-xs"></i> Lanjutkan Reset (${selectedCount} Kategori)`;
            } else {
                openModalBtn.disabled = true;
                openModalBtn.className = "w-full sm:w-auto px-5 py-2.5 rounded-xl font-medium text-xs bg-slate-100 dark:bg-slate-800 text-slate-400 dark:text-slate-500 cursor-not-allowed transition-colors flex items-center justify-center gap-2";
                openModalBtn.innerHTML = `<i class="fa-solid fa-triangle-exclamation text-xs"></i> Lanjutkan Reset Data`;
            }
        }

        checkboxes.forEach(cb => {
            cb.addEventListener('change', updateUI);
        });

        if (selectAllBtn) {
            selectAllBtn.addEventListener('click', () => {
                checkboxes.forEach(cb => cb.checked = true);
                updateUI();
            });
        }

        if (clearAllBtn) {
            clearAllBtn.addEventListener('click', () => {
                checkboxes.forEach(cb => cb.checked = false);
                updateUI();
            });
        }

        if (openModalBtn) {
            openModalBtn.addEventListener('click', () => {
                modalList.innerHTML = '';
                checkboxes.forEach(cb => {
                    if (cb.checked) {
                        const card = cb.closest('.reset-card');
                        const label = card.querySelector('h4').innerText;
                        const countEl = card.querySelector('.font-mono');
                        const countText = countEl ? countEl.innerText : '';

                        const row = document.createElement('div');
                        row.className = "flex items-center justify-between p-2.5 rounded-lg bg-slate-50 dark:bg-slate-800 text-xs border border-slate-100 dark:border-slate-700/80";
                        row.innerHTML = `
                            <div class="flex items-center gap-2 text-slate-800 dark:text-slate-200 font-medium">
                                <i class="fa-solid fa-trash-can text-rose-500 text-[10px]"></i>
                                <span>${label}</span>
                            </div>
                            <span class="text-rose-600 dark:text-rose-400 font-mono text-[11px] font-medium">${countText}</span>
                        `;
                        modalList.appendChild(row);
                    }
                });

                confirmInput.value = '';
                inputError.classList.add('hidden');
                validateChallengeInput();

                modal.classList.remove('hidden');
                setTimeout(() => {
                    modal.classList.add('modal-active');
                    modal.querySelector('.modal-overlay').classList.remove('opacity-0');
                    modal.querySelector('.modal-content').classList.remove('opacity-0', 'scale-90');
                    confirmInput.focus();
                }, 10);
            });
        }

        function validateChallengeInput() {
            const val = confirmInput.value.trim().toUpperCase();
            if (val === 'RESET DATA') {
                executeBtn.disabled = false;
                executeBtn.className = "w-full py-2.5 px-4 rounded-xl font-medium text-xs bg-rose-600 hover:bg-rose-500 text-white cursor-pointer transition-colors shadow-xs flex items-center justify-center gap-2 text-center";
                inputError.classList.add('hidden');
            } else {
                executeBtn.disabled = true;
                executeBtn.className = "w-full py-2.5 px-4 rounded-xl font-medium text-xs bg-slate-100 dark:bg-slate-800 text-slate-400 dark:text-slate-500 cursor-not-allowed transition-colors flex items-center justify-center gap-2 text-center";
                if (val.length >= 5 && val !== 'RESET DATA') {
                    inputError.classList.remove('hidden');
                } else {
                    inputError.classList.add('hidden');
                }
            }
        }

        if (confirmInput) {
            confirmInput.addEventListener('input', validateChallengeInput);
        }

        if (executeBtn) {
            executeBtn.addEventListener('click', function() {
                if (confirmInput.value.trim().toUpperCase() === 'RESET DATA') {
                    hiddenConfirmText.value = 'RESET DATA';
                    executeBtn.disabled = true;
                    executeBtn.innerHTML = '<i class="fa-solid fa-circle-notch fa-spin text-xs"></i> Memproses Reset...';
                    resetForm.submit();
                }
            });
        }

        updateUI();
    });

    function closeDangerModal() {
        const modal = document.getElementById('dangerConfirmModal');
        if (!modal) return;
        modal.classList.remove('modal-active');
        modal.querySelector('.modal-overlay').classList.add('opacity-0');
        modal.querySelector('.modal-content').classList.add('opacity-0', 'scale-90');
        setTimeout(() => {
            modal.classList.add('hidden');
        }, 200);
    }

    function closeRestoreModal() {
        const modal = document.getElementById('restoreConfirmModal');
        if (!modal) return;
        modal.classList.remove('modal-active');
        modal.querySelector('.modal-overlay').classList.add('opacity-0');
        modal.querySelector('.modal-content').classList.add('opacity-0', 'scale-90');
        setTimeout(() => {
            modal.classList.add('hidden');
        }, 200);
    }
</script>
