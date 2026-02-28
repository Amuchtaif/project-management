<div class="w-full">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Sidebar Settings -->
        <div class="space-y-4">
            <div class="bg-white dark:bg-slate-800 p-6 rounded-3xl border border-gray-100 dark:border-slate-700 shadow-sm transition-colors">
                <div class="flex flex-col items-center text-center p-4">
                    <img src="https://ui-avatars.com/api/?name=<?= urlencode($_SESSION['user']['name']); ?>&size=128&background=E2E8F0&color=475569" class="w-32 h-32 rounded-full border-4 border-indigo-50 dark:border-slate-700 shadow-lg mb-4 transition-colors" alt="Avatar">
                    <h3 class="text-xl font-bold text-gray-900 dark:text-gray-100 transition-colors"><?= $_SESSION['user']['name']; ?></h3>
                    <p class="text-xs font-bold text-indigo-600 dark:text-indigo-400 bg-indigo-50 dark:bg-indigo-900/30 px-3 py-1 rounded-full mt-2 uppercase tracking-widest transition-colors"><?= $_SESSION['user']['role']; ?></p>
                </div>
                <div class="border-t border-gray-50 dark:border-slate-700 mt-6 pt-6 space-y-1 transition-colors">
                    <a href="#" class="flex items-center gap-3 px-4 py-3 bg-indigo-50 dark:bg-indigo-900/40 text-indigo-700 dark:text-indigo-300 rounded-2xl font-bold transition transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                        Umum
                    </a>
                    <a href="#" class="flex items-center gap-3 px-4 py-3 text-gray-400 dark:text-gray-500 hover:bg-gray-50 dark:hover:bg-slate-700/50 hover:text-gray-600 dark:hover:text-gray-300 rounded-2xl font-bold transition transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                        Notifikasi
                    </a>
                </div>
            </div>
        </div>

        <!-- Main Form -->
        <div class="lg:col-span-2">
            <div class="bg-white dark:bg-slate-800 rounded-3xl border border-gray-100 dark:border-slate-700 shadow-sm overflow-hidden transition-colors">
                <div class="p-8 border-b border-gray-50 dark:border-slate-700 transition-colors">
                    <h2 class="text-xl font-bold text-gray-900 dark:text-gray-100 transition-colors">Informasi Pribadi</h2>
                    <p class="text-gray-400 dark:text-gray-500 text-sm mt-1 transition-colors">Perbarui nama dan keamanan akun Anda di sini.</p>
                </div>
                
                <div class="px-8 pt-6">
                    <?php Flasher::flashInline(); ?>
                </div>
                
                <form action="<?= BASEURL; ?>/settings/update" method="POST" class="p-8 space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2 transition-colors">Nama Lengkap</label>
                            <input type="text" name="name" value="<?= $data['user']['name']; ?>" class="w-full px-4 py-3 rounded-xl bg-white dark:bg-slate-900 border border-gray-200 dark:border-slate-700 text-gray-700 dark:text-gray-100 focus:ring-2 focus:ring-indigo-500 outline-none transition transition-colors" required>
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2 transition-colors">Email (Tidak dapat diubah)</label>
                            <input type="email" value="<?= $data['user']['email']; ?>" class="w-full px-4 py-3 rounded-xl border border-gray-100 dark:border-slate-700 bg-gray-50 dark:bg-slate-900/50 text-gray-400 dark:text-gray-600 outline-none cursor-not-allowed transition-colors" disabled>
                        </div>
                    </div>

                    <div class="pt-6 border-t border-gray-50 dark:border-slate-700 transition-colors">
                        <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100 mb-6 transition-colors">Ubah Kata Sandi</h3>
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2 transition-colors">Kata Sandi Baru</label>
                                <input type="password" name="password" placeholder="Kosongkan jika tidak ingin mengubah" class="w-full px-4 py-3 rounded-xl bg-white dark:bg-slate-900 border border-gray-200 dark:border-slate-700 text-gray-700 dark:text-gray-100 focus:ring-2 focus:ring-indigo-500 outline-none transition transition-colors">
                                <p class="text-xs text-gray-400 dark:text-gray-500 mt-2 italic transition-colors">Gunakan minimal 8 karakter dengan kombinasi huruf dan angka.</p>
                            </div>
                        </div>
                    </div>

                    <div class="pt-10 flex justify-end gap-3">
                        <button type="submit" class="px-8 py-3 bg-indigo-600 text-white rounded-xl font-bold hover:bg-indigo-700 transition shadow-lg shadow-indigo-100 dark:shadow-none">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
