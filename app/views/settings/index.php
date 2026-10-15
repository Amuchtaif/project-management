<div class="w-full space-y-6 pb-12">
    <!-- Flash Notification Inline -->
    <?php Flasher::flashInline(); ?>

    <!-- User Identity Card -->
    <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200/80 dark:border-slate-800 p-5 sm:p-6 shadow-xs flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div class="flex items-center gap-4">
            <img src="https://ui-avatars.com/api/?name=<?= urlencode($_SESSION['user']['name']); ?>&size=128&background=E2E8F0&color=475569" 
                 class="w-16 h-16 rounded-full border-2 border-slate-100 dark:border-slate-800 object-cover shrink-0" 
                 alt="Avatar">
            <div>
                <div class="flex items-center gap-2 flex-wrap">
                    <h2 class="text-base sm:text-lg font-semibold text-slate-900 dark:text-slate-100"><?= htmlspecialchars($_SESSION['user']['name']); ?></h2>
                    <span class="px-2 py-0.5 rounded-md text-[11px] font-medium bg-slate-100 text-slate-700 dark:bg-slate-800 dark:text-slate-300 capitalize border border-slate-200/60 dark:border-slate-700/60">
                        <?= htmlspecialchars($_SESSION['user']['role']); ?>
                    </span>
                    <?php if (!empty($data['user']['department'])) : ?>
                        <span class="px-2 py-0.5 rounded-md text-[11px] font-medium bg-slate-100 text-slate-600 dark:bg-slate-800 dark:text-slate-400 border border-slate-200/60 dark:border-slate-700/60">
                            <?= htmlspecialchars($data['user']['department']); ?>
                        </span>
                    <?php endif; ?>
                </div>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-1 flex items-center gap-1.5">
                    <i class="fa-regular fa-envelope text-slate-400 text-[11px]"></i>
                    <span><?= htmlspecialchars($data['user']['email']); ?></span>
                </p>
            </div>
        </div>

        <div class="flex items-center gap-2 self-end sm:self-center">
            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-emerald-50 dark:bg-emerald-950/40 text-emerald-700 dark:text-emerald-400 border border-emerald-200/60 dark:border-emerald-800/60">
                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                <span>Akun Aktif</span>
            </span>
        </div>
    </div>

    <!-- Main Settings Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Left: Account Context / Info -->
        <div class="space-y-4">
            <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200/80 dark:border-slate-800 p-5 shadow-xs">
                <h3 class="text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400 mb-3">Ringkasan Akun</h3>
                <dl class="space-y-3 text-xs">
                    <div class="flex justify-between items-center py-1.5 border-b border-slate-100 dark:border-slate-800/80">
                        <dt class="text-slate-500 dark:text-slate-400">ID Pengguna</dt>
                        <dd class="font-mono font-medium text-slate-800 dark:text-slate-200">#<?= htmlspecialchars($data['user']['id']); ?></dd>
                    </div>
                    <div class="flex justify-between items-center py-1.5 border-b border-slate-100 dark:border-slate-800/80">
                        <dt class="text-slate-500 dark:text-slate-400">Otoritas Akses</dt>
                        <dd class="font-medium text-slate-800 dark:text-slate-200 capitalize"><?= htmlspecialchars($data['user']['role']); ?></dd>
                    </div>
                    <div class="flex justify-between items-center py-1.5 border-b border-slate-100 dark:border-slate-800/80">
                        <dt class="text-slate-500 dark:text-slate-400">Profesi / Jabatan</dt>
                        <dd class="font-medium text-slate-800 dark:text-slate-200"><?= htmlspecialchars($data['user']['role_label'] ?? $data['user']['role']); ?></dd>
                    </div>
                    <div class="flex justify-between items-center py-1.5">
                        <dt class="text-slate-500 dark:text-slate-400">Departemen</dt>
                        <dd class="font-medium text-slate-800 dark:text-slate-200"><?= htmlspecialchars($data['user']['department'] ?? '-'); ?></dd>
                    </div>
                </dl>
            </div>

            <div class="bg-slate-50 dark:bg-slate-900/40 rounded-2xl border border-slate-200/70 dark:border-slate-800 p-4 text-xs text-slate-600 dark:text-slate-400 leading-relaxed">
                <div class="flex items-start gap-2.5">
                    <i class="fa-solid fa-circle-info text-slate-400 mt-0.5 text-xs"></i>
                    <div>
                        <p class="font-medium text-slate-700 dark:text-slate-300">Catatan Keamanan</p>
                        <p class="mt-0.5 text-slate-500 dark:text-slate-400">Perubahan email atau departemen kerja harus diajukan melalui Administrator sistem.</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right: Settings Form -->
        <div class="lg:col-span-2">
            <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200/80 dark:border-slate-800 shadow-xs overflow-hidden">
                <form action="<?= BASEURL; ?>/settings/update" method="POST" class="divide-y divide-slate-100 dark:divide-slate-800">
                    <!-- Section 1: Profil -->
                    <div class="p-6 sm:p-7 space-y-4">
                        <div>
                            <h3 class="text-sm font-semibold text-slate-900 dark:text-slate-100">Informasi Pribadi</h3>
                            <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Nama yang ditampilkan pada tugas, aktivitas, dan laporan tim.</p>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-2">
                            <div>
                                <label for="name" class="block text-xs font-medium text-slate-700 dark:text-slate-300 mb-1.5">Nama Lengkap</label>
                                <input type="text" 
                                       id="name" 
                                       name="name" 
                                       value="<?= htmlspecialchars($data['user']['name']); ?>" 
                                       required
                                       class="w-full bg-white dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-xl px-3.5 py-2.5 text-xs sm:text-sm text-slate-900 dark:text-slate-100 placeholder-slate-400 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none transition-colors">
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-slate-700 dark:text-slate-300 mb-1.5 flex items-center justify-between">
                                    <span>Alamat Email</span>
                                    <span class="text-[10px] text-slate-400 flex items-center gap-1">
                                        <i class="fa-solid fa-lock text-[9px]"></i> Tetap
                                    </span>
                                </label>
                                <input type="email" 
                                       value="<?= htmlspecialchars($data['user']['email']); ?>" 
                                       disabled 
                                       class="w-full bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700/80 rounded-xl px-3.5 py-2.5 text-xs sm:text-sm text-slate-500 dark:text-slate-400 outline-none cursor-not-allowed">
                            </div>
                        </div>
                    </div>

                    <!-- Section 2: Keamanan -->
                    <div class="p-6 sm:p-7 space-y-4">
                        <div>
                            <h3 class="text-sm font-semibold text-slate-900 dark:text-slate-100">Keamanan Akun</h3>
                            <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Perbarui kata sandi untuk melindungi akses akun Anda.</p>
                        </div>

                        <div class="max-w-md pt-2">
                            <label for="password" class="block text-xs font-medium text-slate-700 dark:text-slate-300 mb-1.5">Kata Sandi Baru</label>
                            <div class="relative">
                                <input type="password" 
                                       id="password" 
                                       name="password" 
                                       placeholder="Kosongkan jika tidak ingin mengubah" 
                                       autocomplete="new-password"
                                       class="w-full bg-white dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-xl px-3.5 py-2.5 pr-10 text-xs sm:text-sm text-slate-900 dark:text-slate-100 placeholder-slate-400 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none transition-colors">
                                <button type="button" 
                                        onclick="toggleSettingsPassword()" 
                                        class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 dark:hover:text-slate-300 p-1"
                                        title="Tampilkan / Sembunyikan Kata Sandi">
                                    <i id="settingsPwdIcon" class="fa-regular fa-eye text-xs"></i>
                                </button>
                            </div>
                            <p class="text-[11px] text-slate-500 dark:text-slate-400 mt-1.5">Gunakan minimal 8 karakter dengan kombinasi huruf dan angka.</p>
                        </div>
                    </div>

                    <!-- Footer Action -->
                    <div class="p-4 sm:px-7 bg-slate-50/50 dark:bg-slate-900/50 flex items-center justify-end gap-3">
                        <button type="submit" class="px-5 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white font-medium text-xs transition-colors shadow-xs">
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    function toggleSettingsPassword() {
        const input = document.getElementById('password');
        const icon = document.getElementById('settingsPwdIcon');
        if (!input || !icon) return;
        if (input.type === 'password') {
            input.type = 'text';
            icon.className = 'fa-regular fa-eye-slash text-xs';
        } else {
            input.type = 'password';
            icon.className = 'fa-regular fa-eye text-xs';
        }
    }
</script>
