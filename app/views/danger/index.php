<div class="w-full space-y-8 pb-12">
    <!-- Action button to be teleported to header action area -->
    <div id="danger-header-action" class="hidden">
        <a href="<?= BASEURL; ?>/danger/backup" class="px-5 py-2.5 rounded-xl text-xs font-black bg-emerald-600 hover:bg-emerald-700 text-white shadow-lg shadow-emerald-600/20 hover:scale-105 active:scale-95 transition-all flex items-center gap-2">
            <i class="fa-solid fa-cloud-arrow-down text-sm"></i>
            <span>Backup Database (.sql)</span>
        </a>
    </div>

    <!-- Top Warning Banner (Full Width) -->
    <div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-rose-600 via-rose-500 to-amber-600 text-white p-6 sm:p-8 shadow-xl shadow-rose-950/15">
        <div class="absolute -right-8 -bottom-8 opacity-15 pointer-events-none">
            <i class="fa-solid fa-triangle-exclamation text-[180px]"></i>
        </div>
        <div class="relative z-10 flex flex-col md:flex-row items-start md:items-center justify-between gap-6">
            <div class="flex items-start gap-4">
                <div class="w-12 h-12 rounded-2xl bg-white/20 backdrop-blur-md flex items-center justify-center flex-shrink-0 shadow-inner">
                    <i class="fa-solid fa-shield-halved text-2xl text-white"></i>
                </div>
                <div>
                    <div class="flex items-center gap-2 mb-1">
                        <span class="px-2.5 py-0.5 rounded-full bg-white/20 text-white text-[10px] font-black tracking-widest uppercase backdrop-blur-sm border border-white/30">Hak Akses Superadmin</span>
                    </div>
                    <h2 class="text-xl sm:text-2xl font-black tracking-tight text-white">Area Kendali Darurat & Reset Sistem</h2>
                    <p class="text-rose-100 text-xs sm:text-sm font-medium mt-1 max-w-3xl leading-relaxed">
                        Fitur ini dirancang untuk pemeliharaan sistem, pencadangan database, dan pembersihan data secara selektif maupun menyeluruh. Setiap aksi reset bersifat <span class="font-black underline decoration-white">permanen</span>.
                    </p>
                </div>
            </div>
            <div class="flex-shrink-0 flex items-center gap-2 bg-black/20 backdrop-blur-md px-4 py-2.5 rounded-2xl border border-white/10">
                <span class="relative flex h-3 w-3">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-3 w-3 bg-emerald-500"></span>
                </span>
                <span class="text-xs font-bold text-white tracking-wide">Status: Siaga & Terproteksi</span>
            </div>
        </div>
    </div>

    <!-- DISASTER RECOVERY & PEMELIHARAAN DATABASE (Full Width Grid) -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Card 1: Backup Database -->
        <div class="bg-white dark:bg-slate-800 rounded-3xl p-6 sm:p-8 border border-gray-100 dark:border-slate-700 shadow-sm transition-colors flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between gap-4 mb-4">
                    <div class="w-12 h-12 rounded-2xl bg-emerald-50 dark:bg-emerald-950/60 text-emerald-600 dark:text-emerald-400 flex items-center justify-center flex-shrink-0 shadow-sm">
                        <i class="fa-solid fa-cloud-arrow-down text-2xl"></i>
                    </div>
                    <span class="px-2.5 py-1 rounded-full text-[10px] font-black uppercase tracking-wider bg-emerald-100 dark:bg-emerald-950/80 text-emerald-700 dark:text-emerald-300">
                        Disaster Recovery
                    </span>
                </div>
                <h3 class="text-lg font-black text-gray-900 dark:text-gray-100 tracking-tight">Cadangan Database (Full Backup)</h3>
                <p class="text-xs font-medium text-gray-500 dark:text-gray-400 mt-1 leading-relaxed">
                    Unduh salinan berkas lengkap database (<strong class="text-emerald-600 dark:text-emerald-400 font-bold">.sql</strong>) yang mencakup struktur tabel dan seluruh record data untuk perlindungan arsip.
                </p>

                <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 mt-4 pt-4 border-t border-gray-100 dark:border-slate-700">
                    <div class="p-3 rounded-xl bg-gray-50 dark:bg-slate-900/50 border border-gray-100 dark:border-slate-700/50">
                        <p class="text-[9px] font-black uppercase tracking-wider text-gray-400">Database</p>
                        <p class="text-xs font-black text-gray-900 dark:text-gray-100 mt-0.5 font-mono truncate"><?= htmlspecialchars($data['backup_stats']['db_name'] ?? 'project_mgmt'); ?></p>
                    </div>
                    <div class="p-3 rounded-xl bg-gray-50 dark:bg-slate-900/50 border border-gray-100 dark:border-slate-700/50">
                        <p class="text-[9px] font-black uppercase tracking-wider text-gray-400">Total Tabel</p>
                        <p class="text-xs font-black text-emerald-600 dark:text-emerald-400 mt-0.5"><?= number_format($data['backup_stats']['tables'] ?? 0); ?> Tabel</p>
                    </div>
                    <div class="p-3 rounded-xl bg-gray-50 dark:bg-slate-900/50 border border-gray-100 dark:border-slate-700/50">
                        <p class="text-[9px] font-black uppercase tracking-wider text-gray-400">Total Baris</p>
                        <p class="text-xs font-black text-indigo-600 dark:text-indigo-400 mt-0.5"><?= number_format($data['backup_stats']['rows'] ?? 0); ?> Baris</p>
                    </div>
                    <div class="p-3 rounded-xl bg-gray-50 dark:bg-slate-900/50 border border-gray-100 dark:border-slate-700/50">
                        <p class="text-[9px] font-black uppercase tracking-wider text-gray-400">Ukuran</p>
                        <p class="text-xs font-black text-amber-600 dark:text-amber-400 mt-0.5 font-mono">~<?= number_format($data['backup_stats']['size_kb'] ?? 0, 1); ?> KB</p>
                    </div>
                </div>
            </div>

            <div class="mt-6">
                <a href="<?= BASEURL; ?>/danger/backup" class="w-full py-3.5 px-5 rounded-2xl font-black text-xs bg-emerald-600 hover:bg-emerald-700 text-white shadow-lg shadow-emerald-600/25 hover:scale-[1.01] active:scale-[0.99] transition-all flex items-center justify-center gap-2">
                    <i class="fa-solid fa-download text-xs"></i>
                    <span>Unduh Cadangan Database (.sql)</span>
                </a>
            </div>
        </div>

        <!-- Card 2: Restore Database -->
        <div class="bg-white dark:bg-slate-800 rounded-3xl p-6 sm:p-8 border border-gray-100 dark:border-slate-700 shadow-sm transition-colors flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between gap-4 mb-4">
                    <div class="w-12 h-12 rounded-2xl bg-indigo-50 dark:bg-indigo-950/60 text-indigo-600 dark:text-indigo-400 flex items-center justify-center flex-shrink-0 shadow-sm">
                        <i class="fa-solid fa-cloud-arrow-up text-2xl"></i>
                    </div>
                    <span class="px-2.5 py-1 rounded-full text-[10px] font-black uppercase tracking-wider bg-indigo-100 dark:bg-indigo-950/80 text-indigo-700 dark:text-indigo-300">
                        Pemulihan Sistem
                    </span>
                </div>
                <h3 class="text-lg font-black text-gray-900 dark:text-gray-100 tracking-tight">Pulihkan Database (Restore .sql)</h3>
                <p class="text-xs font-medium text-gray-500 dark:text-gray-400 mt-1 leading-relaxed">
                    Unggah berkas <strong class="text-indigo-600 dark:text-indigo-400 font-bold">.sql</strong> cadangan untuk memulihkan seluruh struktur dan data database ke versi sebelumnya.
                </p>

                <!-- File Dropzone -->
                <div class="mt-4 pt-4 border-t border-gray-100 dark:border-slate-700">
                    <form id="restoreFormActual" action="<?= BASEURL; ?>/danger/restore" method="POST" enctype="multipart/form-data">
                        <input type="file" name="sql_file" id="restoreFileInput" accept=".sql" class="hidden">
                        <input type="hidden" name="confirm_restore" id="hiddenRestoreConfirmText" value="">
                    </form>
                    <div id="restoreDropzone" onclick="document.getElementById('restoreFileInput').click()" class="border-2 border-dashed border-gray-200 dark:border-slate-700 hover:border-indigo-500 dark:hover:border-indigo-500 rounded-2xl p-4 text-center cursor-pointer bg-gray-50 dark:bg-slate-900/40 hover:bg-indigo-50/20 transition-all group">
                        <div id="dropzoneEmpty">
                            <i class="fa-solid fa-file-code text-gray-400 group-hover:text-indigo-500 text-xl mb-1.5 transition-colors"></i>
                            <p class="text-xs font-bold text-gray-700 dark:text-gray-300">Pilih berkas cadangan (.sql)</p>
                            <p class="text-[10px] text-gray-400 mt-0.5">Klik untuk memilih berkas atau seret ke sini</p>
                        </div>
                        <div id="dropzoneSelected" class="hidden">
                            <div class="flex items-center justify-center gap-2 text-indigo-600 dark:text-indigo-400 font-bold text-xs">
                                <i class="fa-solid fa-circle-check text-sm text-emerald-500"></i>
                                <span id="selectedFileName" class="truncate max-w-[220px]">file.sql</span>
                            </div>
                            <p id="selectedFileSize" class="text-[10px] text-gray-400 mt-0.5 font-mono">0 KB</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-6">
                <button type="button" id="openRestoreModalBtn" disabled class="w-full py-3.5 px-5 rounded-2xl font-black text-xs bg-gray-200 dark:bg-slate-700 text-gray-400 dark:text-gray-500 cursor-not-allowed transition-all flex items-center justify-center gap-2">
                    <i class="fa-solid fa-rotate-left text-xs"></i>
                    <span>Mulai Pemulihan Database</span>
                </button>
            </div>
        </div>
    </div>

    <!-- RESET SELECTION AREA (Full Width Card) -->
    <div class="w-full bg-white dark:bg-slate-800 rounded-3xl p-6 sm:p-8 border border-gray-100 dark:border-slate-700 shadow-sm transition-colors">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6 pb-6 border-b border-gray-100 dark:border-slate-700">
            <div>
                <h3 class="text-lg sm:text-xl font-black text-gray-900 dark:text-gray-100 tracking-tight">Opsi Reset Data Sistem</h3>
                <p class="text-xs sm:text-sm font-medium text-gray-400 dark:text-gray-500 mt-0.5">Centang satu atau beberapa kategori data di bawah ini untuk memulai proses reset.</p>
            </div>
            <div class="flex items-center gap-2">
                <button type="button" id="selectAllBtn" class="px-4 py-2 rounded-xl text-xs font-bold bg-gray-100 hover:bg-gray-200 dark:bg-slate-700 dark:hover:bg-slate-600 text-gray-700 dark:text-gray-200 transition flex items-center gap-2">
                    <i class="fa-solid fa-check-double text-[11px]"></i> Pilih Semua
                </button>
                <button type="button" id="clearAllBtn" class="px-4 py-2 rounded-xl text-xs font-bold bg-transparent hover:bg-gray-50 dark:hover:bg-slate-700/50 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition flex items-center gap-2">
                    <i class="fa-solid fa-rotate-left text-[11px]"></i> Bersihkan
                </button>
            </div>
        </div>

        <form id="dangerResetForm" action="<?= BASEURL; ?>/danger/reset" method="POST">
            <!-- Grid Option Cards (Full Width: 4 columns on large screens) -->
            <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4 sm:gap-5">
                
                <!-- Option 1: Tasks -->
                <label class="reset-card group relative flex flex-col justify-between p-5 rounded-2xl border-2 border-gray-100 dark:border-slate-700 bg-white dark:bg-slate-800 hover:border-rose-300 dark:hover:border-rose-900/60 cursor-pointer transition-all duration-200">
                    <input type="checkbox" name="reset_options[]" value="tasks" class="reset-checkbox sr-only">
                    <div>
                        <div class="flex items-center justify-between gap-3 mb-3">
                            <div class="check-indicator w-6 h-6 rounded-lg border-2 border-gray-300 dark:border-slate-600 group-hover:border-rose-500 flex items-center justify-center flex-shrink-0 transition-colors">
                                <i class="fa-solid fa-check text-xs text-white opacity-0 transition-opacity"></i>
                            </div>
                            <span class="px-2.5 py-0.5 rounded-lg text-[11px] font-black bg-indigo-50 text-indigo-600 dark:bg-indigo-950/60 dark:text-indigo-400 font-mono">
                                <?= number_format($data['counts']['tasks']); ?> tugas
                            </span>
                        </div>
                        <h4 class="text-sm font-black text-gray-900 dark:text-gray-100 mb-1">Papan Tugas (Tasks)</h4>
                        <p class="text-xs text-gray-500 dark:text-gray-400 leading-relaxed font-medium">
                            Menghapus semua tugas dan kartu Kanban (Todo, In Progress, Done) dari seluruh proyek.
                        </p>
                    </div>
                </label>

                <!-- Option 2: Projects -->
                <label class="reset-card group relative flex flex-col justify-between p-5 rounded-2xl border-2 border-gray-100 dark:border-slate-700 bg-white dark:bg-slate-800 hover:border-rose-300 dark:hover:border-rose-900/60 cursor-pointer transition-all duration-200">
                    <input type="checkbox" name="reset_options[]" value="projects" class="reset-checkbox sr-only">
                    <div>
                        <div class="flex items-center justify-between gap-3 mb-3">
                            <div class="check-indicator w-6 h-6 rounded-lg border-2 border-gray-300 dark:border-slate-600 group-hover:border-rose-500 flex items-center justify-center flex-shrink-0 transition-colors">
                                <i class="fa-solid fa-check text-xs text-white opacity-0 transition-opacity"></i>
                            </div>
                            <span class="px-2.5 py-0.5 rounded-lg text-[11px] font-black bg-amber-50 text-amber-600 dark:bg-amber-950/60 dark:text-amber-400 font-mono">
                                <?= number_format($data['counts']['projects']); ?> proyek
                            </span>
                        </div>
                        <h4 class="text-sm font-black text-gray-900 dark:text-gray-100 mb-1">Manajemen Proyek (Projects)</h4>
                        <p class="text-xs text-gray-500 dark:text-gray-400 leading-relaxed font-medium">
                            Menghapus seluruh proyek, relasi tim proyek, serta tugas terkait di dalamnya.
                        </p>
                    </div>
                </label>

                <!-- Option 3: Team Members -->
                <label class="reset-card group relative flex flex-col justify-between p-5 rounded-2xl border-2 border-gray-100 dark:border-slate-700 bg-white dark:bg-slate-800 hover:border-rose-300 dark:hover:border-rose-900/60 cursor-pointer transition-all duration-200">
                    <input type="checkbox" name="reset_options[]" value="team" class="reset-checkbox sr-only">
                    <div>
                        <div class="flex items-center justify-between gap-3 mb-3">
                            <div class="check-indicator w-6 h-6 rounded-lg border-2 border-gray-300 dark:border-slate-600 group-hover:border-rose-500 flex items-center justify-center flex-shrink-0 transition-colors">
                                <i class="fa-solid fa-check text-xs text-white opacity-0 transition-opacity"></i>
                            </div>
                            <span class="px-2.5 py-0.5 rounded-lg text-[11px] font-black bg-emerald-50 text-emerald-600 dark:bg-emerald-950/60 dark:text-emerald-400 font-mono">
                                <?= number_format($data['counts']['team']); ?> anggota
                            </span>
                        </div>
                        <h4 class="text-sm font-black text-gray-900 dark:text-gray-100 mb-1">Anggota Tim (Users)</h4>
                        <p class="text-xs text-gray-500 dark:text-gray-400 leading-relaxed font-medium">
                            Menghapus semua akun anggota tim. <strong class="text-emerald-600 dark:text-emerald-400 font-bold">Admin Utama (ID: 1) tetap terlindungi</strong> dan aman.
                        </p>
                    </div>
                </label>

                <!-- Option 4: Activities -->
                <label class="reset-card group relative flex flex-col justify-between p-5 rounded-2xl border-2 border-gray-100 dark:border-slate-700 bg-white dark:bg-slate-800 hover:border-rose-300 dark:hover:border-rose-900/60 cursor-pointer transition-all duration-200">
                    <input type="checkbox" name="reset_options[]" value="activities" class="reset-checkbox sr-only">
                    <div>
                        <div class="flex items-center justify-between gap-3 mb-3">
                            <div class="check-indicator w-6 h-6 rounded-lg border-2 border-gray-300 dark:border-slate-600 group-hover:border-rose-500 flex items-center justify-center flex-shrink-0 transition-colors">
                                <i class="fa-solid fa-check text-xs text-white opacity-0 transition-opacity"></i>
                            </div>
                            <span class="px-2.5 py-0.5 rounded-lg text-[11px] font-black bg-purple-50 text-purple-600 dark:bg-purple-950/60 dark:text-purple-400 font-mono">
                                <?= number_format($data['counts']['activities']); ?> riwayat
                            </span>
                        </div>
                        <h4 class="text-sm font-black text-gray-900 dark:text-gray-100 mb-1">Log Aktivitas (Audit Log)</h4>
                        <p class="text-xs text-gray-500 dark:text-gray-400 leading-relaxed font-medium">
                            Menghapus riwayat aktivitas, audit trail pengguna, login/logout, dan perubahan status.
                        </p>
                    </div>
                </label>

            </div>

            <!-- Action Trigger Footer -->
            <div class="mt-8 pt-6 border-t border-gray-100 dark:border-slate-700 flex flex-col sm:flex-row items-center justify-between gap-4">
                <div class="flex items-center gap-3">
                    <span id="selectedCountBadge" class="text-xs font-black text-rose-600 dark:text-rose-400 px-3 py-1.5 rounded-xl bg-rose-50 dark:bg-rose-950/50 border border-rose-100 dark:border-rose-900/50">
                        0 dari 4 kategori dipilih
                    </span>
                    <span class="text-xs font-medium text-gray-400 dark:text-gray-500 hidden sm:inline">• Klik lanjutkan untuk verifikasi</span>
                </div>
                <button type="button" id="openConfirmModalBtn" disabled class="w-full sm:w-auto px-6 py-3.5 rounded-2xl font-bold text-sm bg-gray-200 dark:bg-slate-700 text-gray-400 dark:text-gray-500 cursor-not-allowed transition-all flex items-center justify-center gap-2">
                    <i class="fa-solid fa-triangle-exclamation text-xs"></i>
                    <span>Lanjutkan Reset Data</span>
                </button>
            </div>

            <!-- Hidden Confirmation Input inside Form to be populated by Modal -->
            <input type="hidden" name="confirm_text" id="hiddenConfirmText" value="">
        </form>
    </div>

    <!-- Security & Protection Information Cards (Full Width 3-Cols) -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white dark:bg-slate-800 p-6 rounded-3xl border border-gray-100 dark:border-slate-700 transition-colors">
            <div class="w-10 h-10 rounded-2xl bg-emerald-50 dark:bg-emerald-950/50 text-emerald-600 dark:text-emerald-400 flex items-center justify-center mb-4">
                <i class="fa-solid fa-lock text-base"></i>
            </div>
            <h4 class="text-sm font-black text-gray-900 dark:text-gray-100 mb-1">Proteksi Akun Root</h4>
            <p class="text-xs text-gray-500 dark:text-gray-400 leading-relaxed">
                Akun Administrator Utama (ID: 1) diisolasi dan tidak akan pernah terhapus oleh proses reset apapun.
            </p>
        </div>

        <div class="bg-white dark:bg-slate-800 p-6 rounded-3xl border border-gray-100 dark:border-slate-700 transition-colors">
            <div class="w-10 h-10 rounded-2xl bg-amber-50 dark:bg-amber-950/50 text-amber-600 dark:text-amber-400 flex items-center justify-center mb-4">
                <i class="fa-solid fa-database text-base"></i>
            </div>
            <h4 class="text-sm font-black text-gray-900 dark:text-gray-100 mb-1">Integritas Relasional</h4>
            <p class="text-xs text-gray-500 dark:text-gray-400 leading-relaxed">
                Pembersihan tabel menggunakan manajemen Foreign Key Checks aman sehingga tidak menyisakan data yatim (orphan data).
            </p>
        </div>

        <div class="bg-white dark:bg-slate-800 p-6 rounded-3xl border border-gray-100 dark:border-slate-700 transition-colors">
            <div class="w-10 h-10 rounded-2xl bg-rose-50 dark:bg-rose-950/50 text-rose-600 dark:text-rose-400 flex items-center justify-center mb-4">
                <i class="fa-solid fa-clipboard-check text-base"></i>
            </div>
            <h4 class="text-sm font-black text-gray-900 dark:text-gray-100 mb-1">Audit Trail Otomatis</h4>
            <p class="text-xs text-gray-500 dark:text-gray-400 leading-relaxed">
                Setiap eksekusi unduhan backup dan reset dicatat secara otomatis ke riwayat aktivitas sistem untuk akuntabilitas jejak audit.
            </p>
        </div>
    </div>
</div>

<!-- DANGER CONFIRMATION MODAL -->
<div id="dangerConfirmModal" class="fixed inset-0 z-[120] hidden overflow-y-auto" aria-labelledby="danger-modal-title" role="dialog" aria-modal="true">
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
        <!-- Backdrop -->
        <div class="fixed inset-0 bg-slate-950/70 backdrop-blur-md modal-overlay opacity-0 transition-opacity duration-300" onclick="closeDangerModal()"></div>
        
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        
        <!-- Modal Card -->
        <div class="inline-block align-bottom bg-white dark:bg-slate-900 rounded-3xl text-left overflow-hidden shadow-2xl transform modal-content sm:my-8 sm:align-middle sm:max-w-lg sm:w-full transition-all border border-rose-100 dark:border-rose-950/50">
            <div class="p-6 sm:p-8">
                <!-- Header Icon & Title -->
                <div class="flex items-center gap-4 mb-6">
                    <div class="w-14 h-14 rounded-2xl bg-rose-100 dark:bg-rose-950/60 text-rose-600 dark:text-rose-400 flex items-center justify-center flex-shrink-0 animate-pulse">
                        <i class="fa-solid fa-triangle-exclamation text-2xl"></i>
                    </div>
                    <div>
                        <div class="flex items-center gap-2">
                            <span class="px-2 py-0.5 rounded text-[10px] font-black uppercase tracking-wider bg-rose-600 text-white">Konfirmasi Kritis</span>
                        </div>
                        <h3 class="text-xl font-black text-gray-900 dark:text-gray-100 mt-1" id="danger-modal-title">Reset Permanen Database</h3>
                    </div>
                </div>

                <!-- Warning Notice -->
                <div class="p-4 rounded-2xl bg-rose-50 dark:bg-rose-950/40 border border-rose-200 dark:border-rose-900/50 text-rose-800 dark:text-rose-300 text-xs leading-relaxed font-bold mb-6">
                    <div class="flex items-start gap-2.5">
                        <i class="fa-solid fa-circle-exclamation text-rose-600 dark:text-rose-400 text-sm mt-0.5 flex-shrink-0"></i>
                        <span>PERINGATAN: Tindakan ini akan menghapus data yang Anda pilih secara permanen. Pastikan Anda telah mengunduh Backup Database sebelum melanjutkan.</span>
                    </div>
                </div>

                <!-- Selected Items List -->
                <div class="mb-6">
                    <label class="block text-xs font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest mb-2.5">Daftar Data yang Akan Dihapus:</label>
                    <div id="modalSelectedList" class="space-y-2 max-h-40 overflow-y-auto pr-1">
                        <!-- Populated by JavaScript -->
                    </div>
                </div>

                <!-- Confirmation Text Input Challenge -->
                <div class="mb-6">
                    <label for="confirmationInput" class="block text-xs font-black text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-2">
                        Ketik <span class="text-rose-600 dark:text-rose-400 select-all font-mono font-black">RESET DATA</span> untuk konfirmasi:
                    </label>
                    <input type="text" id="confirmationInput" autocomplete="off" class="w-full bg-gray-50 dark:bg-slate-800 border-2 border-gray-200 dark:border-slate-700 rounded-xl px-4 py-3 text-sm font-mono font-black text-gray-800 dark:text-gray-100 focus:border-rose-500 focus:ring-4 focus:ring-rose-500/10 outline-none uppercase transition" placeholder="Ketik: RESET DATA">
                    <p id="inputErrorMsg" class="text-[11px] font-bold text-rose-500 mt-1.5 hidden">Teks tidak cocok. Harap ketik persis "RESET DATA".</p>
                </div>

<!-- RESTORE CONFIRMATION MODAL -->
<div id="restoreConfirmModal" class="fixed inset-0 z-[120] hidden overflow-y-auto" aria-labelledby="restore-modal-title" role="dialog" aria-modal="true">
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
        <!-- Backdrop -->
        <div class="fixed inset-0 bg-slate-950/70 backdrop-blur-md modal-overlay opacity-0 transition-opacity duration-300" onclick="closeRestoreModal()"></div>
        
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        
        <!-- Modal Card -->
        <div class="inline-block align-bottom bg-white dark:bg-slate-900 rounded-3xl text-left overflow-hidden shadow-2xl transform modal-content sm:my-8 sm:align-middle sm:max-w-lg sm:w-full transition-all border border-indigo-100 dark:border-indigo-950/50">
            <div class="p-6 sm:p-8">
                <!-- Header Icon & Title -->
                <div class="flex items-center gap-4 mb-6">
                    <div class="w-14 h-14 rounded-2xl bg-indigo-100 dark:bg-indigo-950/60 text-indigo-600 dark:text-indigo-400 flex items-center justify-center flex-shrink-0">
                        <i class="fa-solid fa-cloud-arrow-up text-2xl"></i>
                    </div>
                    <div>
                        <div class="flex items-center gap-2">
                            <span class="px-2 py-0.5 rounded text-[10px] font-black uppercase tracking-wider bg-indigo-600 text-white">Pemulihan Data</span>
                        </div>
                        <h3 class="text-xl font-black text-gray-900 dark:text-gray-100 mt-1" id="restore-modal-title">Pulihkan Database dari Cadangan</h3>
                    </div>
                </div>

                <!-- Warning Notice -->
                <div class="p-4 rounded-2xl bg-amber-50 dark:bg-amber-950/40 border border-amber-200 dark:border-amber-900/50 text-amber-800 dark:text-amber-300 text-xs leading-relaxed font-bold mb-6">
                    <div class="flex items-start gap-2.5">
                        <i class="fa-solid fa-triangle-exclamation text-amber-600 dark:text-amber-400 text-sm mt-0.5 flex-shrink-0"></i>
                        <span>PERHATIAN: Pemulihan akan mengeksekusi berkas cadangan dan menimpa struktur serta data saat ini. Pastikan Anda memilih berkas yang tepat.</span>
                    </div>
                </div>

                <!-- Selected File Info -->
                <div class="mb-6 p-4 rounded-2xl bg-gray-50 dark:bg-slate-800 border border-gray-100 dark:border-slate-700">
                    <p class="text-[10px] font-black uppercase tracking-wider text-gray-400 dark:text-gray-500 mb-1">Berkas Cadangan yang Dipilih</p>
                    <div class="flex items-center justify-between gap-3">
                        <span id="modalRestoreFileName" class="text-xs font-black text-gray-800 dark:text-gray-200 font-mono truncate">file.sql</span>
                        <span id="modalRestoreFileSize" class="text-[11px] font-black text-indigo-600 dark:text-indigo-400 font-mono flex-shrink-0">0 KB</span>
                    </div>
                </div>

                <!-- Confirmation Text Input Challenge -->
                <div class="mb-6">
                    <label for="restoreConfirmationInput" class="block text-xs font-black text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-2">
                        Ketik <span class="text-indigo-600 dark:text-indigo-400 select-all font-mono font-black">RESTORE DATABASE</span> untuk konfirmasi:
                    </label>
                    <input type="text" id="restoreConfirmationInput" autocomplete="off" class="w-full bg-gray-50 dark:bg-slate-800 border-2 border-gray-200 dark:border-slate-700 rounded-xl px-4 py-3 text-sm font-mono font-black text-gray-800 dark:text-gray-100 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 outline-none uppercase transition" placeholder="Ketik: RESTORE DATABASE">
                    <p id="restoreInputErrorMsg" class="text-[11px] font-bold text-rose-500 mt-1.5 hidden">Teks tidak cocok. Harap ketik persis "RESTORE DATABASE".</p>
                </div>

                <!-- Action Buttons -->
                <div class="flex flex-col-reverse sm:flex-row gap-3">
                    <button type="button" onclick="closeRestoreModal()" class="w-full py-3.5 px-5 rounded-xl font-bold text-xs bg-gray-100 hover:bg-gray-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-gray-600 dark:text-gray-300 transition text-center">
                        Batal
                    </button>
                    <button type="button" id="executeRestoreBtn" disabled class="w-full py-3.5 px-5 rounded-xl font-bold text-xs bg-gray-300 dark:bg-slate-700 text-gray-400 dark:text-gray-500 cursor-not-allowed transition flex items-center justify-center gap-2 text-center shadow-lg shadow-indigo-900/10">
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
        const restoreDropzone = document.getElementById('restoreDropzone');
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
                    openRestoreModalBtn.className = "w-full py-3.5 px-5 rounded-2xl font-black text-xs bg-indigo-600 hover:bg-indigo-700 text-white cursor-pointer shadow-lg shadow-indigo-600/30 hover:scale-[1.01] active:scale-[0.99] transition-all flex items-center justify-center gap-2";
                } else {
                    dropzoneEmpty.classList.remove('hidden');
                    dropzoneSelected.classList.add('hidden');
                    openRestoreModalBtn.disabled = true;
                    openRestoreModalBtn.className = "w-full py-3.5 px-5 rounded-2xl font-black text-xs bg-gray-200 dark:bg-slate-700 text-gray-400 dark:text-gray-500 cursor-not-allowed transition-all flex items-center justify-center gap-2";
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
                executeRestoreBtn.className = "w-full py-3.5 px-5 rounded-xl font-bold text-xs bg-indigo-600 hover:bg-indigo-700 text-white cursor-pointer shadow-lg shadow-indigo-600/30 transition flex items-center justify-center gap-2 text-center";
                restoreInputError.classList.add('hidden');
            } else {
                executeRestoreBtn.disabled = true;
                executeRestoreBtn.className = "w-full py-3.5 px-5 rounded-xl font-bold text-xs bg-gray-200 dark:bg-slate-700 text-gray-400 dark:text-gray-500 cursor-not-allowed transition flex items-center justify-center gap-2 text-center";
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
        const cards = document.querySelectorAll('.reset-card');
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
                    card.classList.remove('border-gray-100', 'dark:border-slate-700');
                    indicator.classList.add('bg-rose-600', 'border-rose-600');
                    indicator.classList.remove('border-gray-300', 'dark:border-slate-600');
                    checkIcon.classList.remove('opacity-0');
                } else {
                    card.classList.remove('border-rose-500', 'bg-rose-50/20', 'dark:border-rose-500', 'dark:bg-rose-950/20');
                    card.classList.add('border-gray-100', 'dark:border-slate-700');
                    indicator.classList.remove('bg-rose-600', 'border-rose-600');
                    indicator.classList.add('border-gray-300', 'dark:border-slate-600');
                    checkIcon.classList.add('opacity-0');
                }
            });

            countBadge.innerText = `${selectedCount} dari ${checkboxes.length} kategori dipilih`;

            if (selectedCount > 0) {
                openModalBtn.disabled = false;
                openModalBtn.className = "w-full sm:w-auto px-6 py-3.5 rounded-2xl font-bold text-sm bg-rose-600 hover:bg-rose-700 text-white cursor-pointer shadow-lg shadow-rose-600/30 hover:scale-[1.02] active:scale-[0.98] transition-all flex items-center justify-center gap-2";
                openModalBtn.innerHTML = `<i class="fa-solid fa-triangle-exclamation text-xs"></i> Lanjutkan Reset (${selectedCount} Kategori)`;
            } else {
                openModalBtn.disabled = true;
                openModalBtn.className = "w-full sm:w-auto px-6 py-3.5 rounded-2xl font-bold text-sm bg-gray-200 dark:bg-slate-700 text-gray-400 dark:text-gray-500 cursor-not-allowed transition-all flex items-center justify-center gap-2";
                openModalBtn.innerHTML = `<i class="fa-solid fa-triangle-exclamation text-xs"></i> Lanjutkan Reset Data`;
            }
        }

        checkboxes.forEach(cb => {
            cb.addEventListener('change', updateUI);
        });

        selectAllBtn.addEventListener('click', () => {
            checkboxes.forEach(cb => cb.checked = true);
            updateUI();
        });

        clearAllBtn.addEventListener('click', () => {
            checkboxes.forEach(cb => cb.checked = false);
            updateUI();
        });

        openModalBtn.addEventListener('click', () => {
            modalList.innerHTML = '';
            checkboxes.forEach(cb => {
                if (cb.checked) {
                    const card = cb.closest('.reset-card');
                    const label = card.querySelector('h4').innerText;
                    const countEl = card.querySelector('.font-mono');
                    const countText = countEl ? countEl.innerText : '';

                    const row = document.createElement('div');
                    row.className = "flex items-center justify-between p-3 rounded-xl bg-gray-50 dark:bg-slate-800 text-xs font-bold border border-gray-100 dark:border-slate-700";
                    row.innerHTML = `
                        <div class="flex items-center gap-2 text-gray-800 dark:text-gray-200">
                            <i class="fa-solid fa-trash-can text-rose-500 text-[11px]"></i>
                            <span>${label}</span>
                        </div>
                        <span class="text-rose-600 dark:text-rose-400 font-mono">${countText}</span>
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

        function validateChallengeInput() {
            const val = confirmInput.value.trim().toUpperCase();
            if (val === 'RESET DATA') {
                executeBtn.disabled = false;
                executeBtn.className = "w-full py-3.5 px-5 rounded-xl font-bold text-xs bg-rose-600 hover:bg-rose-700 text-white cursor-pointer shadow-lg shadow-rose-600/30 transition flex items-center justify-center gap-2 text-center";
                inputError.classList.add('hidden');
            } else {
                executeBtn.disabled = true;
                executeBtn.className = "w-full py-3.5 px-5 rounded-xl font-bold text-xs bg-gray-200 dark:bg-slate-700 text-gray-400 dark:text-gray-500 cursor-not-allowed transition flex items-center justify-center gap-2 text-center";
                if (val.length >= 5 && val !== 'RESET DATA') {
                    inputError.classList.remove('hidden');
                } else {
                    inputError.classList.add('hidden');
                }
            }
        }

        confirmInput.addEventListener('input', validateChallengeInput);

        executeBtn.addEventListener('click', function() {
            if (confirmInput.value.trim().toUpperCase() === 'RESET DATA') {
                hiddenConfirmText.value = 'RESET DATA';
                executeBtn.disabled = true;
                executeBtn.innerHTML = '<i class="fa-solid fa-circle-notch fa-spin text-xs"></i> Memproses Reset...';
                resetForm.submit();
            }
        });

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
