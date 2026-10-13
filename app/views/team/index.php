<div id="team-action-content" class="hidden">
    <button class="w-full lg:w-auto bg-[#4f46e5] text-white px-6 py-2.5 rounded-xl font-bold flex items-center justify-center gap-2 hover:bg-indigo-700 transition shadow-lg shadow-indigo-100 dark:shadow-none btnTambahTim">
        <i class="fa-solid fa-plus text-sm"></i>
        Tambah Anggota
    </button>
</div>

<!-- Header Controls -->
<div class="bg-white dark:bg-slate-800 rounded-[2rem] border border-gray-100 dark:border-slate-700 shadow-sm p-4 mb-6 transition-colors">
    <div class="flex flex-col md:flex-row gap-4 items-center justify-between">
        <div class="relative flex-grow max-w-2xl w-full">
            <input type="text" id="teamSearch" placeholder="Cari nama, email, atau departemen..." class="w-full bg-gray-50 dark:bg-slate-900 border border-transparent dark:border-slate-700 rounded-xl px-12 py-3 text-sm text-gray-700 dark:text-gray-300 focus:ring-2 focus:ring-indigo-500 focus:bg-white dark:focus:bg-slate-800 outline-none transition transition-colors">
            <i class="fa-solid fa-magnifying-glass text-gray-400 dark:text-gray-500 absolute left-4 top-1/2 -translate-y-1/2 text-sm pointer-events-none"></i>
        </div>
        <div class="flex gap-3 w-full md:w-auto">
            <button class="flex-1 md:flex-none flex items-center justify-center gap-2 bg-white dark:bg-slate-800 border border-gray-100 dark:border-slate-700 px-6 py-3 rounded-xl text-sm font-bold text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-slate-700 transition transition-colors">
                <i class="fa-solid fa-filter text-xs"></i>
                Filter
            </button>
            <button class="flex-1 md:flex-none flex items-center justify-center gap-2 bg-white dark:bg-slate-800 border border-gray-100 dark:border-slate-700 px-6 py-3 rounded-xl text-sm font-bold text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-slate-700 transition transition-colors">
                <i class="fa-solid fa-download text-xs"></i>
                Ekspor
            </button>
        </div>
    </div>
</div>

<!-- Team Table -->
<div class="bg-white dark:bg-slate-800 rounded-[2rem] border border-gray-100 dark:border-slate-700 shadow-sm overflow-hidden p-6 scale-95 md:scale-100 transition-colors">
    <div class="overflow-x-auto -mx-6 px-6">
        <table class="w-full text-left min-w-[900px]">
            <thead>
                <tr class="text-[11px] font-extrabold text-[#9ca3af] dark:text-gray-500 uppercase tracking-[0.2em] border-b border-gray-50 dark:border-slate-700 transition-colors">
                    <th class="pb-6 pl-2">Profil Pengguna</th>
                    <th class="pb-6">Email</th>
                    <th class="pb-6">Jabatan / Profesi</th>
                    <th class="pb-6">Hak Akses Sistem</th>
                    <th class="pb-6">Departemen</th>
                    <th class="pb-6">Status</th>
                    <th class="pb-6">Terakhir Aktif</th>
                    <th class="pb-6 text-right pr-2">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50/50 dark:divide-slate-700/50" id="teamTableBody">
                <?php if(empty($data['team'])) : ?>
                    <tr id="emptyTeamRow"><td colspan="8" class="py-20 text-center text-gray-400 font-medium">Belum ada anggota tim.</td></tr>
                <?php endif; ?>
                <?php foreach($data['team'] as $member) : ?>
                <tr class="team-row group hover:bg-gray-50/50 dark:hover:bg-slate-900/30 transition-colors"
                    data-name="<?= strtolower(htmlspecialchars($member['name'], ENT_QUOTES)); ?>"
                    data-email="<?= strtolower(htmlspecialchars($member['email'], ENT_QUOTES)); ?>"
                    data-dept="<?= strtolower(htmlspecialchars($member['department'] ?? '', ENT_QUOTES)); ?>"
                    data-role="<?= strtolower(htmlspecialchars($member['role_label'] ?? $member['role'], ENT_QUOTES)); ?>">
                    <td class="py-6 pl-2">
                        <div class="flex items-center gap-4">
                            <img src="https://ui-avatars.com/api/?name=<?= urlencode($member['name']); ?>&background=random" class="w-10 h-10 rounded-full border-2 border-white dark:border-slate-800 shadow-sm transition-colors" alt="Avatar">
                            <div>
                                <span class="font-extrabold text-[#111827] dark:text-gray-100 text-[15px] tracking-tight block transition-colors"><?= $member['name']; ?></span>
                                <?php if($member['id'] == $_SESSION['user']['id']) : ?>
                                    <span class="text-[10px] font-bold text-indigo-600 dark:text-indigo-400 bg-indigo-50 dark:bg-indigo-900/30 px-2 py-0.5 rounded-md inline-block mt-0.5">Akun Anda</span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </td>
                    <td class="py-6">
                        <span class="text-[#9ca3af] dark:text-gray-400 font-bold text-[14px] transition-colors"><?= $member['email']; ?></span>
                    </td>
                    <td class="py-6">
                        <span class="px-3 py-1.5 rounded-full text-[10px] font-black tracking-wider <?= $member['role_color']; ?> flex w-fit items-center justify-center leading-none border border-current bg-white dark:bg-slate-800 transition-colors">
                            <?= strtoupper($member['role_label'] ?? $member['role']); ?>
                        </span>
                    </td>
                    <td class="py-6">
                        <?php if($member['role'] === 'admin') : ?>
                            <div class="flex flex-col gap-1">
                                <span class="px-3 py-1.5 rounded-full text-[10px] font-black tracking-wider bg-purple-50 dark:bg-purple-900/30 text-purple-700 dark:text-purple-300 flex w-fit items-center gap-1.5 border border-purple-200 dark:border-purple-800 transition-colors shadow-sm">
                                    <i class="fa-solid fa-shield-halved text-[11px] text-purple-600 dark:text-purple-400"></i>
                                    AKSES PENUH (ADMIN)
                                </span>
                                <span class="text-[11px] text-gray-400 dark:text-gray-500 font-semibold pl-1">Boleh melihat full data</span>
                            </div>
                        <?php else : ?>
                            <div class="flex flex-col gap-1">
                                <span class="px-3 py-1.5 rounded-full text-[10px] font-black tracking-wider bg-slate-100 dark:bg-slate-700/60 text-slate-700 dark:text-slate-300 flex w-fit items-center gap-1.5 border border-slate-200 dark:border-slate-600 transition-colors">
                                    <i class="fa-solid fa-user-lock text-[11px] text-slate-500 dark:text-slate-400"></i>
                                    AKSES TERBATAS (MEMBER)
                                </span>
                                <span class="text-[11px] text-gray-400 dark:text-gray-500 font-semibold pl-1">Hanya tugas & proyek sendiri</span>
                            </div>
                        <?php endif; ?>
                    </td>
                    <td class="py-6">
                        <span class="text-[#6b7280] dark:text-gray-300 font-bold text-[14px] transition-colors"><?= $member['department'] ?? '-'; ?></span>
                    </td>
                    <td class="py-6">
                        <div class="flex items-center gap-2 transition-colors">
                            <div class="w-2 h-2 rounded-full <?= $member['status'] == 'AKTIF' ? 'bg-green-500 shadow-[0_0_8px_rgba(34,197,94,0.4)]' : 'bg-red-500 shadow-[0_0_8px_rgba(239,68,68,0.4)]'; ?>"></div>
                            <span class="text-[11px] font-black tracking-widest <?= $member['status'] == 'AKTIF' ? 'text-green-500' : 'text-red-500'; ?> italic transition-colors"><?= strtoupper($member['status'] ?? 'AKTIF'); ?></span>
                        </div>
                    </td>
                    <td class="py-6 transition-colors">
                        <span class="text-[#9ca3af] dark:text-gray-400 font-bold text-[14px] transition-colors"><?= $member['last_active'] ? date('d M H:i', strtotime($member['last_active'])) : '-'; ?></span>
                    </td>
                    <td class="py-6 text-right pr-2">
                        <div class="flex items-center justify-end gap-2 transition-colors">
                            <button class="p-2 text-[#9ca3af] dark:text-gray-500 hover:text-amber-600 dark:hover:text-amber-400 transition rounded-lg hover:bg-amber-50 dark:hover:bg-slate-900 btnResetPassword" title="Reset Kata Sandi" data-id="<?= $member['id']; ?>" data-name="<?= htmlspecialchars($member['name'], ENT_QUOTES); ?>" data-email="<?= htmlspecialchars($member['email'], ENT_QUOTES); ?>">
                                <i class="fa-solid fa-key text-sm"></i>
                            </button>
                            <button class="p-2 text-[#9ca3af] dark:text-gray-500 hover:text-indigo-600 dark:hover:text-indigo-400 transition rounded-lg hover:bg-gray-50 dark:hover:bg-slate-900 btnEditTim" title="Ubah Detail" data-id="<?= $member['id']; ?>">
                                <i class="fa-solid fa-pen-to-square text-sm"></i>
                            </button>
                            <?php if($member['id'] != $_SESSION['user']['id']) : ?>
                            <button class="p-2 text-[#9ca3af] dark:text-gray-500 hover:text-red-500 transition rounded-lg hover:bg-gray-50 dark:hover:bg-slate-900" title="Hapus Anggota" onclick="showDeleteModal('<?= BASEURL; ?>/team/delete/<?= $member['id']; ?>', '<?= addslashes($member['name']); ?>')">
                                <i class="fa-solid fa-trash-can text-sm"></i>
                            </button>
                            <?php endif; ?>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <!-- Table Pagination Footer -->
    <div class="flex flex-col sm:flex-row items-center justify-between gap-4 mt-8 pt-6 border-t border-gray-100 dark:border-slate-700/80 transition-colors" id="teamPaginationWrapper">
        <div class="flex flex-wrap items-center gap-3 text-[13px] text-gray-500 dark:text-gray-400 font-medium">
            <span id="teamPaginationInfo">Menampilkan <?= count($data['team']); ?> anggota</span>
            <div class="flex items-center gap-2 pl-3 border-l border-gray-200 dark:border-slate-700">
                <span class="text-xs text-gray-400 dark:text-gray-500 font-bold">Baris per halaman:</span>
                <select id="teamPageSize" class="bg-gray-50 dark:bg-slate-900 border border-gray-200 dark:border-slate-700 rounded-xl px-2.5 py-1 text-xs font-bold text-gray-700 dark:text-gray-300 outline-none focus:ring-2 focus:ring-indigo-500 cursor-pointer">
                    <option value="5">5</option>
                    <option value="10" selected>10</option>
                    <option value="25">25</option>
                    <option value="all">Semua</option>
                </select>
            </div>
        </div>

        <div class="flex items-center gap-1.5" id="teamPaginationControls">
            <!-- Dynamically populated buttons -->
        </div>
    </div>
</div>

<!-- Modal Form Tim -->
<div id="teamModal" class="fixed inset-0 z-[100] hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-900 bg-opacity-40 backdrop-blur-sm modal-overlay opacity-0 transition-opacity" aria-hidden="true" onclick="toggleTeamModal()"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div class="inline-block align-bottom bg-white dark:bg-slate-800 rounded-3xl text-left shadow-2xl transform modal-content sm:my-8 sm:align-middle sm:max-w-xl sm:w-full transition-colors">
            <form action="<?= BASEURL; ?>/team/add" method="post" id="teamForm">
                <input type="hidden" name="id" id="member_id">
                <div class="p-8 md:p-10">
                    <div class="flex justify-between items-center mb-8 transition-colors">
                        <div>
                            <h3 class="text-2xl font-black text-gray-900 dark:text-gray-100 tracking-tight transition-colors" id="teamModalTitle">Tambah Anggota</h3>
                            <p class="text-gray-400 dark:text-gray-500 text-sm font-bold mt-1 transition-colors">Lengkapi informasi biodata anggota tim baru.</p>
                        </div>
                        <button type="button" onclick="toggleTeamModal()" class="text-gray-300 dark:text-gray-600 hover:text-gray-500 dark:hover:text-gray-400 transition">
                            <i class="fa-solid fa-xmark text-2xl"></i>
                        </button>
                    </div>

                    <div class="space-y-6">
                        <div>
                            <label for="name" class="block text-xs font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest mb-2 ml-1 transition-colors">Nama Lengkap</label>
                            <input type="text" name="name" id="name" required class="w-full bg-gray-50 dark:bg-slate-900 border-2 border-gray-50 dark:border-slate-700 rounded-2xl px-5 py-4 text-sm font-bold text-gray-700 dark:text-gray-100 focus:bg-white dark:focus:bg-slate-800 focus:border-indigo-500 outline-none transition transition-colors" placeholder="Masukkan nama lengkap">
                        </div>

                        <div>
                            <label for="email" class="block text-xs font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest mb-2 ml-1 transition-colors">Alamat Email</label>
                            <input type="email" name="email" id="email" required class="w-full bg-gray-50 dark:bg-slate-900 border-2 border-gray-50 dark:border-slate-700 rounded-2xl px-5 py-4 text-sm font-bold text-gray-700 dark:text-gray-100 focus:bg-white dark:focus:bg-slate-800 focus:border-indigo-500 outline-none transition transition-colors" placeholder="email@contoh.com">
                        </div>

                        <!-- Password Field -->
                        <div id="passwordGroup">
                            <label for="password" class="block text-xs font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest mb-2 ml-1 transition-colors">Kata Sandi <span id="passwordHint" class="normal-case tracking-normal font-medium"></span></label>
                            <div class="relative">
                                <input type="password" name="password" id="password" value="password123" class="w-full bg-gray-50 dark:bg-slate-900 border-2 border-gray-50 dark:border-slate-700 rounded-2xl px-5 py-4 text-sm font-bold text-gray-700 dark:text-gray-100 focus:bg-white dark:focus:bg-slate-800 focus:border-indigo-500 outline-none transition pr-12 transition-colors" placeholder="Masukkan kata sandi">
                                <button type="button" onclick="togglePasswordVisibility()" class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 dark:text-gray-500 hover:text-gray-600 dark:hover:text-gray-300 transition">
                                    <i id="pwdToggleIcon" class="fa-solid fa-eye text-base"></i>
                                </button>
                            </div>
                            <div id="defaultPasswordBtnContainer" class="flex items-center gap-2 mt-2">
                                <button type="button" onclick="document.getElementById('password').value='password123'" class="px-2.5 py-1 rounded-lg bg-indigo-50 dark:bg-indigo-950/40 text-indigo-600 dark:text-indigo-400 border border-indigo-200 dark:border-indigo-800 text-[11px] font-bold hover:bg-indigo-100 dark:hover:bg-indigo-900/40 transition flex items-center gap-1.5 shadow-xs">
                                    <i class="fa-solid fa-key text-[10px]"></i>
                                    <span>Gunakan Default: <strong class="font-mono">password123</strong></span>
                                </button>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="role" class="block text-xs font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest mb-2 ml-1 transition-colors">Peran / Jabatan</label>
                                <input type="text" name="role" id="role" required class="w-full bg-gray-50 dark:bg-slate-900 border-2 border-gray-50 dark:border-slate-700 rounded-2xl px-5 py-4 text-sm font-bold text-gray-700 dark:text-gray-100 focus:bg-white dark:focus:bg-slate-800 focus:border-indigo-500 outline-none transition transition-colors" placeholder="Contoh: UI Designer">
                            </div>
                            <div>
                                <label for="department" class="block text-xs font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest mb-2 ml-1 transition-colors">Departemen</label>
                                <input type="text" name="department" id="department" required class="w-full bg-gray-50 dark:bg-slate-900 border-2 border-gray-50 dark:border-slate-700 rounded-2xl px-5 py-4 text-sm font-bold text-gray-700 dark:text-gray-100 focus:bg-white dark:focus:bg-slate-800 focus:border-indigo-500 outline-none transition transition-colors" placeholder="Contoh: Creative">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="status" class="block text-xs font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest mb-2 ml-1 transition-colors">Status Keanggotaan</label>
                                <div class="select-container" id="statusSelectContainer">
                                    <div class="select-trigger">
                                        <span class="select-trigger-text text-gray-400 dark:text-gray-500">Pilih Status...</span>
                                        <i class="fa-solid fa-chevron-down select-trigger-icon flex items-center justify-center text-xs"></i>
                                    </div>
                                    <div class="select-options">
                                        <div class="select-option" data-value="AKTIF">Aktif</div>
                                        <div class="select-option" data-value="NONAKTIF">Nonaktif</div>
                                    </div>
                                </div>
                                <input type="hidden" name="status" id="status" value="AKTIF">
                            </div>
                        </div>

                        <!-- Hak Akses Sistem (Level Otoritas) -->
                        <div class="pt-2">
                            <label class="block text-xs font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest mb-2.5 ml-1 transition-colors">
                                Hak Akses Sistem <span class="text-indigo-600 dark:text-indigo-400 font-bold lowercase">(apakah boleh melihat full layaknya admin)</span>
                            </label>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3" id="systemRoleSelector">
                                <label class="relative flex flex-col p-4 rounded-2xl border-2 border-indigo-500 bg-indigo-50/40 dark:bg-indigo-900/20 cursor-pointer transition-all role-card" id="role-card-member">
                                    <div class="flex items-center justify-between mb-2">
                                        <div class="flex items-center gap-2.5">
                                            <span class="w-8 h-8 rounded-xl bg-blue-100 dark:bg-blue-900/40 text-blue-600 dark:text-blue-400 flex items-center justify-center text-xs font-bold">
                                                <i class="fa-solid fa-user-lock"></i>
                                            </span>
                                            <span class="font-black text-sm text-gray-900 dark:text-gray-100">Member</span>
                                        </div>
                                        <input type="radio" name="system_role" id="role_radio_member" value="member" checked class="w-4 h-4 text-indigo-600 focus:ring-indigo-500 accent-indigo-600 cursor-pointer">
                                    </div>
                                    <p class="text-[12px] text-gray-500 dark:text-gray-400 font-medium leading-relaxed">
                                        <strong>Akses Terbatas:</strong> Hanya dapat melihat tugas & proyek yang ditugaskan kepada dirinya.
                                    </p>
                                </label>

                                <label class="relative flex flex-col p-4 rounded-2xl border-2 border-gray-200 dark:border-slate-700 hover:border-indigo-300 dark:hover:border-slate-600 cursor-pointer transition-all role-card" id="role-card-admin">
                                    <div class="flex items-center justify-between mb-2">
                                        <div class="flex items-center gap-2.5">
                                            <span class="w-8 h-8 rounded-xl bg-purple-100 dark:bg-purple-900/40 text-purple-600 dark:text-purple-400 flex items-center justify-center text-xs font-bold">
                                                <i class="fa-solid fa-shield-halved"></i>
                                            </span>
                                            <span class="font-black text-sm text-gray-900 dark:text-gray-100">Admin</span>
                                        </div>
                                        <input type="radio" name="system_role" id="role_radio_admin" value="admin" class="w-4 h-4 text-indigo-600 focus:ring-indigo-500 accent-indigo-600 cursor-pointer">
                                    </div>
                                    <p class="text-[12px] text-gray-500 dark:text-gray-400 font-medium leading-relaxed">
                                        <strong>Akses Penuh:</strong> Boleh melihat full semua proyek, papan tugas, tim & log aktivitas layaknya admin.
                                    </p>
                                </label>
                            </div>
                            <p id="selfRoleNotice" class="hidden text-xs text-amber-600 dark:text-amber-400 font-bold mt-2 ml-1">
                                <i class="fa-solid fa-triangle-exclamation mr-1"></i> Anda tidak dapat mengubah hak akses akun Anda sendiri demi keamanan.
                            </p>
                        </div>

                        <!-- Color Presets (Hidden logic) -->
                        <input type="hidden" name="role_color" id="role_color" value="bg-blue-50 text-blue-600">
                        <input type="hidden" name="status_color" id="status_color" value="text-green-500">
                    </div>

                    <div class="flex gap-4 mt-10">
                        <button type="button" onclick="toggleTeamModal()" class="flex-1 px-8 py-4 rounded-2xl bg-gray-100 dark:bg-slate-700 text-gray-700 dark:text-gray-300 font-black text-sm uppercase tracking-widest hover:bg-gray-200 dark:hover:bg-slate-600 transition transition-colors">Batal</button>
                        <button type="submit" class="flex-1 px-8 py-4 rounded-2xl bg-indigo-600 text-white font-black text-sm uppercase tracking-widest hover:bg-indigo-700 transition shadow-lg shadow-indigo-100 dark:shadow-none">Simpan Anggota</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Reset Password -->
<div id="resetPasswordModal" class="fixed inset-0 z-[100] hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-900 bg-opacity-40 backdrop-blur-sm modal-overlay opacity-0 transition-opacity" aria-hidden="true" onclick="toggleResetPasswordModal()"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div class="inline-block align-bottom bg-white dark:bg-slate-800 rounded-3xl text-left shadow-2xl transform modal-content sm:my-8 sm:align-middle sm:max-w-md sm:w-full transition-colors">
            <form action="<?= BASEURL; ?>/team/resetPassword" method="post" id="resetPasswordForm">
                <input type="hidden" name="id" id="reset_member_id">
                <div class="p-8">
                    <div class="flex justify-between items-center mb-6">
                        <div class="flex items-center gap-3">
                            <div class="w-12 h-12 rounded-2xl bg-amber-50 dark:bg-amber-900/30 text-amber-600 dark:text-amber-400 flex items-center justify-center text-xl shadow-sm">
                                <i class="fa-solid fa-key"></i>
                            </div>
                            <div>
                                <h3 class="text-xl font-black text-gray-900 dark:text-gray-100 tracking-tight">Reset Kata Sandi</h3>
                                <p class="text-gray-400 dark:text-gray-500 text-xs font-bold mt-0.5">Atur kata sandi baru untuk pengguna</p>
                            </div>
                        </div>
                        <button type="button" onclick="toggleResetPasswordModal()" class="text-gray-300 dark:text-gray-600 hover:text-gray-500 dark:hover:text-gray-400 transition">
                            <i class="fa-solid fa-xmark text-2xl"></i>
                        </button>
                    </div>

                    <!-- Member info preview -->
                    <div class="bg-gray-50 dark:bg-slate-900/80 rounded-2xl p-4 border border-gray-100 dark:border-slate-700/60 mb-6 flex items-center gap-3.5">
                        <img id="reset_member_avatar" src="" class="w-11 h-11 rounded-full border border-white dark:border-slate-700 shadow-sm" alt="Avatar">
                        <div class="flex flex-col min-w-0">
                            <span id="reset_member_name" class="font-extrabold text-gray-900 dark:text-gray-100 text-sm truncate"></span>
                            <span id="reset_member_email" class="text-xs text-gray-400 dark:text-gray-500 font-semibold truncate"></span>
                        </div>
                    </div>

                    <div class="space-y-4">
                        <div>
                            <label for="reset_new_password" class="block text-xs font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest mb-2 ml-1">
                                Kata Sandi Baru
                            </label>
                            <div class="relative">
                                <input type="password" name="password" id="reset_new_password" required minlength="4"
                                    class="w-full bg-gray-50 dark:bg-slate-900 border-2 border-gray-200 dark:border-slate-700 rounded-2xl px-5 py-3.5 text-sm font-bold text-gray-700 dark:text-gray-100 focus:bg-white dark:focus:bg-slate-800 focus:border-amber-500 outline-none transition pr-12"
                                    placeholder="Masukkan kata sandi baru">
                                <button type="button" onclick="toggleResetPasswordVisibility()" class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 dark:text-gray-500 hover:text-gray-600 dark:hover:text-gray-300 transition">
                                    <i id="resetPwdToggleIcon" class="fa-solid fa-eye text-base"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Quick presets -->
                        <div class="flex flex-wrap items-center gap-2 pt-1">
                            <button type="button" id="btnUseDefaultPassword123" class="px-3.5 py-2 rounded-xl bg-amber-50 dark:bg-amber-900/30 text-amber-700 dark:text-amber-400 border border-amber-200 dark:border-amber-800 text-xs font-bold hover:bg-amber-100 dark:hover:bg-amber-900/50 transition flex items-center gap-1.5 shadow-xs">
                                <i class="fa-solid fa-key text-[10px]"></i>
                                <span>Gunakan Default:</span>
                                <span class="font-black px-1.5 py-0.5 rounded-md bg-amber-200/70 dark:bg-amber-800/60 text-amber-900 dark:text-amber-100 font-mono text-[11px]">password123</span>
                            </button>
                            <button type="button" id="btnGenerateRandomPassword" class="px-3 py-2 rounded-xl bg-gray-100 dark:bg-slate-700 text-gray-700 dark:text-gray-300 border border-gray-200 dark:border-slate-600 text-xs font-bold hover:bg-gray-200 dark:hover:bg-slate-600 transition flex items-center gap-1.5">
                                <i class="fa-solid fa-shuffle text-[10px]"></i>
                                Acak Aman
                            </button>
                        </div>
                    </div>

                    <div class="flex gap-3 mt-8">
                        <button type="button" onclick="toggleResetPasswordModal()" class="flex-1 px-6 py-3.5 rounded-2xl bg-gray-100 dark:bg-slate-700 text-gray-700 dark:text-gray-300 font-black text-xs uppercase tracking-widest hover:bg-gray-200 dark:hover:bg-slate-600 transition">Batal</button>
                        <button type="submit" class="flex-1 px-6 py-3.5 rounded-2xl bg-amber-500 hover:bg-amber-600 text-white font-black text-xs uppercase tracking-widest transition shadow-lg shadow-amber-500/20">Simpan Sandi</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    const teamModal = document.getElementById('teamModal');
    const teamForm = document.getElementById('teamForm');
    const teamModalTitle = document.getElementById('teamModalTitle');

    const resetPasswordModal = document.getElementById('resetPasswordModal');
    const resetPasswordForm = document.getElementById('resetPasswordForm');
    const currentLoggedUserId = '<?= $_SESSION['user']['id']; ?>';

    function toggleTeamModal() {
        if (teamModal.classList.contains('hidden')) {
            teamModal.classList.remove('hidden');
            setTimeout(() => {
                teamModal.classList.add('modal-active');
                document.body.classList.add('overflow-hidden');
            }, 10);
        } else {
            teamModal.classList.remove('modal-active');
            setTimeout(() => {
                teamModal.classList.add('hidden');
                document.body.classList.remove('overflow-hidden');
                teamForm.reset();
                document.getElementById('member_id').value = '';
            }, 300);
        }
    }

    function toggleResetPasswordModal() {
        if (resetPasswordModal.classList.contains('hidden')) {
            resetPasswordModal.classList.remove('hidden');
            setTimeout(() => {
                resetPasswordModal.classList.add('modal-active');
                document.body.classList.add('overflow-hidden');
            }, 10);
        } else {
            resetPasswordModal.classList.remove('modal-active');
            setTimeout(() => {
                resetPasswordModal.classList.add('hidden');
                document.body.classList.remove('overflow-hidden');
                resetPasswordForm.reset();
                document.getElementById('reset_member_id').value = '';
            }, 300);
        }
    }

    function togglePasswordVisibility() {
        const pwdInput = document.getElementById('password');
        const icon = document.getElementById('pwdToggleIcon');
        if (pwdInput.type === 'password') {
            pwdInput.type = 'text';
            icon.className = 'fa-solid fa-eye-slash text-base';
        } else {
            pwdInput.type = 'password';
            icon.className = 'fa-solid fa-eye text-base';
        }
    }

    function toggleResetPasswordVisibility() {
        const pwdInput = document.getElementById('reset_new_password');
        const icon = document.getElementById('resetPwdToggleIcon');
        if (pwdInput.type === 'password') {
            pwdInput.type = 'text';
            icon.className = 'fa-solid fa-eye-slash text-base';
        } else {
            pwdInput.type = 'password';
            icon.className = 'fa-solid fa-eye text-base';
        }
    }

    function selectSystemRole(role) {
        const cardMember = document.getElementById('role-card-member');
        const cardAdmin = document.getElementById('role-card-admin');
        const radioMember = document.getElementById('role_radio_member');
        const radioAdmin = document.getElementById('role_radio_admin');

        if (role === 'admin') {
            radioAdmin.checked = true;
            cardAdmin.classList.add('border-indigo-500', 'bg-indigo-50/40', 'dark:bg-indigo-900/20');
            cardAdmin.classList.remove('border-gray-200', 'dark:border-slate-700');

            cardMember.classList.remove('border-indigo-500', 'bg-indigo-50/40', 'dark:bg-indigo-900/20');
            cardMember.classList.add('border-gray-200', 'dark:border-slate-700');
        } else {
            radioMember.checked = true;
            cardMember.classList.add('border-indigo-500', 'bg-indigo-50/40', 'dark:bg-indigo-900/20');
            cardMember.classList.remove('border-gray-200', 'dark:border-slate-700');

            cardAdmin.classList.remove('border-indigo-500', 'bg-indigo-50/40', 'dark:bg-indigo-900/20');
            cardAdmin.classList.add('border-gray-200', 'dark:border-slate-700');
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        // Teleport button to header
        const actionArea = document.getElementById('header-action-area');
        const actionContent = document.getElementById('team-action-content');
        if (actionArea && actionContent) {
            const btn = actionContent.querySelector('button');
            actionArea.appendChild(btn);
        }

        // Initialize Custom Select
        initCustomSelect('statusSelectContainer', 'status', (val) => {
            document.getElementById('status_color').value = val === 'AKTIF' ? 'text-green-500' : 'text-red-500';
        });

        // Click listeners on role cards
        document.getElementById('role-card-member').addEventListener('click', function() {
            if (!document.getElementById('role_radio_member').disabled) {
                selectSystemRole('member');
            }
        });
        document.getElementById('role-card-admin').addEventListener('click', function() {
            if (!document.getElementById('role_radio_admin').disabled) {
                selectSystemRole('admin');
            }
        });

        // Add Member Button
        const btnsTambah = document.querySelectorAll('.btnTambahTim');
        btnsTambah.forEach(btn => {
            btn.addEventListener('click', function() {
                teamModalTitle.innerText = 'Tambah Anggota Baru';
                teamForm.setAttribute('action', '<?= BASEURL; ?>/team/add');
                
                // Reset fields
                document.getElementById('status').value = 'AKTIF';
                document.getElementById('password').value = 'password123';
                document.getElementById('password').removeAttribute('placeholder');
                document.getElementById('password').setAttribute('placeholder', 'Masukkan kata sandi');
                document.getElementById('passwordHint').innerText = '';
                const defPwdBtn = document.getElementById('defaultPasswordBtnContainer');
                if (defPwdBtn) defPwdBtn.classList.remove('hidden');
                
                // Enable system role options
                document.getElementById('role_radio_member').disabled = false;
                document.getElementById('role_radio_admin').disabled = false;
                document.getElementById('selfRoleNotice').classList.add('hidden');
                selectSystemRole('member');

                initCustomSelect('statusSelectContainer', 'status', (val) => {
                    document.getElementById('status_color').value = val === 'AKTIF' ? 'text-green-500' : 'text-red-500';
                });

                toggleTeamModal();
            });
        });

        // Edit Member Button
        const btnsEdit = document.querySelectorAll('.btnEditTim');
        btnsEdit.forEach(btn => {
            btn.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                teamModalTitle.innerText = 'Ubah Detail Anggota';
                teamForm.setAttribute('action', '<?= BASEURL; ?>/team/edit');
                
                fetch('<?= BASEURL; ?>/team/getEdit/' + id)
                    .then(response => response.json())
                    .then(data => {
                        document.getElementById('member_id').value = data.id;
                        document.getElementById('name').value = data.name;
                        document.getElementById('email').value = data.email;
                        document.getElementById('role').value = data.role_label || data.role;
                        document.getElementById('department').value = data.department || '';
                        
                        // Set status
                        document.getElementById('status').value = data.status || 'AKTIF';
                        initCustomSelect('statusSelectContainer', 'status', (val) => {
                            document.getElementById('status_color').value = val === 'AKTIF' ? 'text-green-500' : 'text-red-500';
                        });
                        
                        document.getElementById('role_color').value = data.role_color || 'bg-blue-50 text-blue-600';
                        document.getElementById('status_color').value = data.status_color || 'text-green-500';
                        
                        // Password hint saat edit
                        document.getElementById('password').value = '';
                        document.getElementById('passwordHint').innerText = '(kosongkan jika tidak ingin mengubah)';
                        const defPwdBtn = document.getElementById('defaultPasswordBtnContainer');
                        if (defPwdBtn) defPwdBtn.classList.add('hidden');

                        // Set system role (admin vs member)
                        selectSystemRole(data.role || 'member');

                        // If editing current logged in user, protect from self demotion
                        const isSelf = (String(data.id) === String(currentLoggedUserId));
                        document.getElementById('role_radio_member').disabled = isSelf;
                        document.getElementById('role_radio_admin').disabled = isSelf;
                        if (isSelf) {
                            document.getElementById('selfRoleNotice').classList.remove('hidden');
                        } else {
                            document.getElementById('selfRoleNotice').classList.add('hidden');
                        }

                        toggleTeamModal();
                    });
            });
        });

        // Reset Password Buttons
        let currentResetMemberName = '';
        const btnsReset = document.querySelectorAll('.btnResetPassword');
        btnsReset.forEach(btn => {
            btn.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                const name = this.getAttribute('data-name');
                const email = this.getAttribute('data-email');
                currentResetMemberName = name;

                document.getElementById('reset_member_id').value = id;
                document.getElementById('reset_member_name').innerText = name;
                document.getElementById('reset_member_email').innerText = email;
                document.getElementById('reset_member_avatar').src = 'https://ui-avatars.com/api/?name=' + encodeURIComponent(name) + '&background=random';
                
                // Clear input
                const pwdInput = document.getElementById('reset_new_password');
                pwdInput.value = '';
                pwdInput.type = 'password';
                document.getElementById('resetPwdToggleIcon').className = 'fa-solid fa-eye text-base';

                toggleResetPasswordModal();
            });
        });

        // Preset Button: Default Password (password123)
        const btnDefaultPwd = document.getElementById('btnUseDefaultPassword123');
        if (btnDefaultPwd) {
            btnDefaultPwd.addEventListener('click', function() {
                const pwdInput = document.getElementById('reset_new_password');
                pwdInput.value = 'password123';
                pwdInput.type = 'text';
                document.getElementById('resetPwdToggleIcon').className = 'fa-solid fa-eye-slash text-base';
                pwdInput.focus();
                if (typeof showTeamToast === 'function') {
                    showTeamToast('Kata sandi default "password123" diterapkan');
                }
            });
        }

        // Preset Button: Random Password
        document.getElementById('btnGenerateRandomPassword').addEventListener('click', function() {
            const chars = 'ABCDEFGHJKLMNPQRSTUVWXYZabcdefghjkmnpqrstuvwxyz23456789!@#$%';
            let randomPwd = '';
            for (let i = 0; i < 10; i++) {
                randomPwd += chars.charAt(Math.floor(Math.random() * chars.length));
            }
            const pwdInput = document.getElementById('reset_new_password');
            pwdInput.value = randomPwd;
            pwdInput.type = 'text';
            document.getElementById('resetPwdToggleIcon').className = 'fa-solid fa-eye-slash text-base';
        });

        // Initialize Team Data Table Pagination
        initTeamPagination();
    });

    function initTeamPagination() {
        const rows = Array.from(document.querySelectorAll('.team-row'));
        const pageSizeSelect = document.getElementById('teamPageSize');
        const infoEl = document.getElementById('teamPaginationInfo');
        const controlsEl = document.getElementById('teamPaginationControls');
        const searchInput = document.getElementById('teamSearch');
        const emptyRow = document.getElementById('emptyTeamRow');
        
        let currentPage = 1;
        let pageSize = parseInt(pageSizeSelect.value) || 10;
        let filteredRows = [...rows];

        function applyFilter() {
            const query = (searchInput ? searchInput.value.trim().toLowerCase() : '');
            filteredRows = rows.filter(row => {
                if (!query) return true;
                const name = row.getAttribute('data-name') || '';
                const email = row.getAttribute('data-email') || '';
                const dept = row.getAttribute('data-dept') || '';
                const role = row.getAttribute('data-role') || '';
                return name.includes(query) || email.includes(query) || dept.includes(query) || role.includes(query);
            });
            currentPage = 1;
            render();
        }

        function render() {
            const total = filteredRows.length;
            const effectivePageSize = (pageSizeSelect.value === 'all') ? (total || 1) : pageSize;
            const totalPages = Math.max(1, Math.ceil(total / effectivePageSize));

            if (currentPage > totalPages) currentPage = totalPages;
            if (currentPage < 1) currentPage = 1;

            const startIndex = (currentPage - 1) * effectivePageSize;
            const endIndex = (pageSizeSelect.value === 'all') ? total : Math.min(startIndex + effectivePageSize, total);

            // Hide all rows first
            rows.forEach(r => r.classList.add('hidden'));

            // Show filtered rows in current page
            for (let i = startIndex; i < endIndex; i++) {
                if (filteredRows[i]) {
                    filteredRows[i].classList.remove('hidden');
                }
            }

            // Empty search state
            let noMatchRow = document.getElementById('noMatchTeamRow');
            if (total === 0 && rows.length > 0) {
                if (!noMatchRow) {
                    noMatchRow = document.createElement('tr');
                    noMatchRow.id = 'noMatchTeamRow';
                    noMatchRow.innerHTML = '<td colspan="8" class="py-16 text-center text-gray-400 font-medium">Tidak ada anggota yang cocok dengan kata kunci pencarian.</td>';
                    document.getElementById('teamTableBody').appendChild(noMatchRow);
                }
                noMatchRow.classList.remove('hidden');
            } else if (noMatchRow) {
                noMatchRow.classList.add('hidden');
            }

            // Update info text
            if (total === 0) {
                infoEl.innerText = 'Menampilkan 0 anggota';
            } else if (pageSizeSelect.value === 'all' || total <= effectivePageSize) {
                infoEl.innerText = `Menampilkan ${total} anggota`;
            } else {
                infoEl.innerText = `Menampilkan ${startIndex + 1} - ${endIndex} dari ${total} anggota`;
            }

            // Render pagination buttons
            controlsEl.innerHTML = '';
            if (totalPages <= 1 && total <= effectivePageSize) {
                return;
            }

            // Prev Button
            const prevBtn = document.createElement('button');
            prevBtn.type = 'button';
            prevBtn.className = `px-3 py-1.5 rounded-xl border text-xs font-bold transition flex items-center gap-1.5 ${currentPage === 1 ? 'border-gray-100 dark:border-slate-800 text-gray-300 dark:text-gray-600 cursor-not-allowed opacity-60' : 'border-gray-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-slate-700 shadow-2xs'}`;
            prevBtn.disabled = (currentPage === 1);
            prevBtn.innerHTML = '<i class="fa-solid fa-chevron-left text-[10px]"></i> <span class="hidden sm:inline">Sebelumnya</span>';
            prevBtn.onclick = () => { if (currentPage > 1) { currentPage--; render(); } };
            controlsEl.appendChild(prevBtn);

            // Page numbers
            for (let p = 1; p <= totalPages; p++) {
                if (totalPages > 6 && Math.abs(p - currentPage) > 2 && p !== 1 && p !== totalPages) {
                    if (p === 2 || p === totalPages - 1) {
                        const dots = document.createElement('span');
                        dots.className = 'px-1 text-xs text-gray-400 font-bold';
                        dots.innerText = '...';
                        controlsEl.appendChild(dots);
                    }
                    continue;
                }

                const pageBtn = document.createElement('button');
                pageBtn.type = 'button';
                pageBtn.className = `min-w-[32px] h-8 px-2 rounded-xl text-xs font-black transition ${p === currentPage ? 'bg-indigo-600 text-white shadow-sm shadow-indigo-200 dark:shadow-none' : 'border border-gray-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-slate-700'}`;
                pageBtn.innerText = p;
                pageBtn.onclick = () => { currentPage = p; render(); };
                controlsEl.appendChild(pageBtn);
            }

            // Next Button
            const nextBtn = document.createElement('button');
            nextBtn.type = 'button';
            nextBtn.className = `px-3 py-1.5 rounded-xl border text-xs font-bold transition flex items-center gap-1.5 ${currentPage === totalPages ? 'border-gray-100 dark:border-slate-800 text-gray-300 dark:text-gray-600 cursor-not-allowed opacity-60' : 'border-gray-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-slate-700 shadow-2xs'}`;
            nextBtn.disabled = (currentPage === totalPages);
            nextBtn.innerHTML = '<span class="hidden sm:inline">Selanjutnya</span> <i class="fa-solid fa-chevron-right text-[10px]"></i>';
            nextBtn.onclick = () => { if (currentPage < totalPages) { currentPage++; render(); } };
            controlsEl.appendChild(nextBtn);
        }

        pageSizeSelect.addEventListener('change', function() {
            pageSize = (this.value === 'all') ? rows.length : parseInt(this.value);
            currentPage = 1;
            render();
        });

        if (searchInput) {
            searchInput.addEventListener('input', applyFilter);
        }

        applyFilter();
    }
</script>

