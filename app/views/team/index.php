<div id="team-action-content" class="hidden">
    <button class="w-full lg:w-auto bg-[#4f46e5] text-white px-6 py-2.5 rounded-xl font-bold flex items-center justify-center gap-2 hover:bg-indigo-700 transition shadow-lg shadow-indigo-100 dark:shadow-none btnTambahTim">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
        Tambah Anggota
    </button>
</div>

<!-- Header Controls -->
<div class="bg-white dark:bg-slate-800 rounded-[2rem] border border-gray-100 dark:border-slate-700 shadow-sm p-4 mb-6 transition-colors">
    <div class="flex flex-col md:flex-row gap-4 items-center justify-between">
        <div class="relative flex-grow max-w-2xl w-full">
            <input type="text" placeholder="Cari nama, email, atau departemen..." class="w-full bg-gray-50 dark:bg-slate-900 border border-transparent dark:border-slate-700 rounded-xl px-12 py-3 text-sm text-gray-700 dark:text-gray-300 focus:ring-2 focus:ring-indigo-500 focus:bg-white dark:focus:bg-slate-800 outline-none transition transition-colors">
            <svg class="w-5 h-5 text-gray-400 dark:text-gray-500 absolute left-4 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
        </div>
        <div class="flex gap-3 w-full md:w-auto">
            <button class="flex-1 md:flex-none flex items-center justify-center gap-2 bg-white dark:bg-slate-800 border border-gray-100 dark:border-slate-700 px-6 py-3 rounded-xl text-sm font-bold text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-slate-700 transition transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path></svg>
                Filter
            </button>
            <button class="flex-1 md:flex-none flex items-center justify-center gap-2 bg-white dark:bg-slate-800 border border-gray-100 dark:border-slate-700 px-6 py-3 rounded-xl text-sm font-bold text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-slate-700 transition transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
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
                    <th class="pb-6">Peran</th>
                    <th class="pb-6">Role Sistem</th>
                    <th class="pb-6">Departemen</th>
                    <th class="pb-6">Status</th>
                    <th class="pb-6">Terakhir Aktif</th>
                    </tr>
            </thead>
            <tbody class="divide-y divide-gray-50/50 dark:divide-slate-700/50">
                <?php if(empty($data['team'])) : ?>
                    <tr><td colspan="8" class="py-20 text-center text-gray-400 font-medium">Belum ada anggota tim.</td></tr>
                <?php endif; ?>
                <?php foreach($data['team'] as $member) : ?>
                <tr class="group hover:bg-gray-50/50 dark:hover:bg-slate-900/30 transition-colors">
                    <td class="py-6 pl-2">
                        <div class="flex items-center gap-4">
                            <img src="https://ui-avatars.com/api/?name=<?= urlencode($member['name']); ?>&background=random" class="w-10 h-10 rounded-full border-2 border-white dark:border-slate-800 shadow-sm transition-colors" alt="Avatar">
                            <span class="font-extrabold text-[#111827] dark:text-gray-100 text-[15px] tracking-tight transition-colors"><?= $member['name']; ?></span>
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
                        <span class="px-3 py-1.5 rounded-full text-[10px] font-black tracking-wider <?= $member['role'] === 'admin' ? 'bg-purple-50 dark:bg-purple-900/20 text-purple-600 dark:text-purple-400' : 'bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400'; ?> flex w-fit items-center justify-center leading-none border border-current bg-white dark:bg-slate-800 transition-colors">
                            <?= strtoupper($member['role']); ?>
                        </span>
                    </td>
                    <td class="py-6">
                        <span class="text-[#6b7280] dark:text-gray-300 font-bold text-[14px] transition-colors"><?= $member['department'] ?? '-'; ?></span>
                    </td>
                    <td class="py-6">
                        <div class="flex items-center gap-2 transition-colors">
                            <div class="w-2 h-2 rounded-full <?= $member['status'] == 'AKTIF' ? 'bg-green-500 shadow-[0_0_8px_rgba(34,197,94,0.4)]' : 'bg-gray-300 dark:bg-slate-700'; ?>"></div>
                            <span class="text-[11px] font-black tracking-widest <?= $member['status_color']; ?> italic transition-colors"><?= strtoupper($member['status'] ?? 'AKTIF'); ?></span>
                        </div>
                    </td>
                    <td class="py-6 transition-colors">
                        <span class="text-[#9ca3af] dark:text-gray-400 font-bold text-[14px] transition-colors"><?= $member['last_active'] ? date('d M H:i', strtotime($member['last_active'])) : '-'; ?></span>
                    </td>
                    <td class="py-6 text-right pr-2">
                        <div class="flex items-center justify-end gap-3 transition-colors">
                            <button class="p-2 text-[#9ca3af] dark:text-gray-500 hover:text-indigo-600 dark:hover:text-indigo-400 transition rounded-lg hover:bg-gray-50 dark:hover:bg-slate-900 btnEditTim" data-id="<?= $member['id']; ?>">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                            </button>
                            <?php if($member['id'] != $_SESSION['user']['id']) : ?>
                            <button class="p-2 text-[#9ca3af] dark:text-gray-500 hover:text-red-500 transition rounded-lg hover:bg-gray-50 dark:hover:bg-slate-900" onclick="showDeleteModal('<?= BASEURL; ?>/team/delete/<?= $member['id']; ?>', '<?= addslashes($member['name']); ?>')">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                            </button>
                            <?php endif; ?>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <!-- Table Footer -->
    <div class="flex items-center justify-between mt-8">
        <p class="text-[13px] text-[#9ca3af] dark:text-gray-500 font-medium transition-colors">Menampilkan <?= count($data['team']); ?> anggota</p>
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
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
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

                        <!-- Password Field (Baru) -->
                        <div id="passwordGroup">
                            <label for="password" class="block text-xs font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest mb-2 ml-1 transition-colors">Kata Sandi <span id="passwordHint" class="normal-case tracking-normal font-medium">(kosongkan untuk default: namadepan123)</span></label>
                            <div class="relative">
                                <input type="password" name="password" id="password" class="w-full bg-gray-50 dark:bg-slate-900 border-2 border-gray-50 dark:border-slate-700 rounded-2xl px-5 py-4 text-sm font-bold text-gray-700 dark:text-gray-100 focus:bg-white dark:focus:bg-slate-800 focus:border-indigo-500 outline-none transition pr-12 transition-colors" placeholder="Masukkan kata sandi">
                                <button type="button" onclick="togglePasswordVisibility()" class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 dark:text-gray-500 hover:text-gray-600 dark:hover:text-gray-300 transition">
                                    <svg id="pwdToggleIcon" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
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
                                        <svg class="select-trigger-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                    </div>
                                    <div class="select-options">
                                        <div class="select-option" data-value="AKTIF">Aktif</div>
                                        <div class="select-option" data-value="NONAKTIF">Nonaktif</div>
                                    </div>
                                </div>
                                <input type="hidden" name="status" id="status" value="AKTIF">
                            </div>
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

<script>
    const teamModal = document.getElementById('teamModal');
    const teamForm = document.getElementById('teamForm');
    const teamModalTitle = document.getElementById('teamModalTitle');

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

    function togglePasswordVisibility() {
        const pwdInput = document.getElementById('password');
        const icon = document.getElementById('pwdToggleIcon');
        if (pwdInput.type === 'password') {
            pwdInput.type = 'text';
            icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a10.057 10.057 0 012.183-3.183M9.878 9.878a3 3 0 014.243 4.243M9.878 9.878L3 3m6.878 6.878L14.121 14.121M14.121 14.121L21 21"></path>';
        } else {
            pwdInput.type = 'password';
            icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>';
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
        initCustomSelect('statusSelectContainer', 'status');

        const btnsTambah = document.querySelectorAll('.btnTambahTim');
        btnsTambah.forEach(btn => {
            btn.addEventListener('click', function() {
                teamModalTitle.innerText = 'Tambah Anggota Baru';
                teamForm.setAttribute('action', '<?= BASEURL; ?>/team/add');
                
                // Reset custom select
                document.getElementById('status').value = 'AKTIF';
                document.getElementById('password').value = '';
                document.getElementById('password').removeAttribute('placeholder');
                document.getElementById('password').setAttribute('placeholder', 'Masukkan kata sandi');
                document.getElementById('passwordHint').innerText = '(kosongkan untuk default: namadepan123)';
                initCustomSelect('statusSelectContainer', 'status');

                toggleTeamModal();
            });
        });

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
                        
                        // Set hidden input
                        document.getElementById('status').value = data.status || 'AKTIF';
                        // Sync visual
                        initCustomSelect('statusSelectContainer', 'status');
                        
                        document.getElementById('role_color').value = data.role_color || 'bg-blue-50 text-blue-600';
                        document.getElementById('status_color').value = data.status_color || 'text-green-500';
                        
                        // Password hint saat edit
                        document.getElementById('password').value = '';
                        document.getElementById('passwordHint').innerText = '(kosongkan jika tidak ingin mengubah)';

                        toggleTeamModal();
                    });
            });
        });
    });
</script>
