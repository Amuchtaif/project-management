<?php if(isset($_SESSION['user']) && $_SESSION['user']['role'] === 'admin') : ?>
<div id="project-action-content" class="hidden">
    <button class="w-full lg:w-auto bg-[#4f46e5] text-white px-6 py-3 rounded-xl font-bold flex items-center justify-center gap-2 hover:bg-indigo-700 transition shadow-lg shadow-indigo-100 btnTambahProyek">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
        Buat Proyek Baru
    </button>
</div>
<?php endif; ?>

<div class="bg-white rounded-[2rem] border border-gray-100 shadow-sm overflow-hidden p-6 scale-95 md:scale-100">
    <!-- Filters & Flash (Keep existing structure) -->
    <div class="flex flex-wrap items-center justify-between gap-4 mb-8">
        <div class="flex flex-wrap items-center gap-4">
            <div class="relative custom-dropdown" id="projectStatusDropdown">
                <button onclick="toggleDropdown('projectStatusDropdown')" class="bg-gray-50 border border-gray-100 rounded-xl px-4 py-2.5 text-sm font-bold text-gray-700 focus:ring-2 focus:ring-indigo-500 outline-none cursor-pointer tracking-tight flex items-center gap-2">
                    <?= isset($_GET['status']) ? $_GET['status'] : 'Semua Status' ?>
                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                </button>
                <div class="dropdown-menu absolute top-full left-0 mt-2 w-48 bg-white border border-gray-100 rounded-xl shadow-xl py-2 hidden z-50">
                    <button onclick="applyFilter('status', '')" class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">Semua Status</button>
                    <button onclick="applyFilter('status', 'Sesuai Jadwal')" class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">Sesuai Jadwal</button>
                    <button onclick="applyFilter('status', 'Terhambat')" class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">Terhambat</button>
                    <button onclick="applyFilter('status', 'Selesai')" class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">Selesai</button>
                </div>
            </div>

            <div class="relative">
                <input type="text" id="projectSearch" placeholder="Cari proyek..." value="<?= $_GET['search'] ?? '' ?>" 
                    class="bg-gray-50 border border-gray-100 rounded-xl px-4 py-2.5 text-sm font-bold text-gray-700 focus:ring-2 focus:ring-indigo-500 outline-none pr-10"
                    onkeypress="if(event.key === 'Enter') applyFilter('search', this.value)">
                <svg class="w-4 h-4 text-gray-400 absolute right-3 top-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            </div>
            
            <?php if(isset($_GET['status']) || isset($_GET['search'])) : ?>
                <a href="<?= BASEURL ?>/project" class="text-xs font-bold text-red-500 hover:underline transition">Hapus Filter</a>
            <?php endif; ?>
        </div>
        
        <div class="flex bg-gray-50 p-1.5 rounded-xl gap-1">
            <button onclick="applyFilter('status', 'Sesuai Jadwal')" class="px-3 md:px-5 py-1.5 rounded-lg text-[10px] md:text-sm font-bold transition <?= (isset($_GET['status']) && $_GET['status'] == 'Sesuai Jadwal') ? 'bg-white text-indigo-600 shadow-sm' : 'text-gray-500 hover:bg-white hover:shadow-sm' ?>">Sesuai Jadwal</button>
            <button onclick="applyFilter('status', 'Terhambat')" class="px-3 md:px-5 py-1.5 rounded-lg text-[10px] md:text-sm font-bold transition <?= (isset($_GET['status']) && $_GET['status'] == 'Terhambat') ? 'bg-white text-indigo-600 shadow-sm' : 'text-gray-500 hover:bg-white hover:shadow-sm' ?>">Terhambat</button>
            <button onclick="applyFilter('status', 'Selesai')" class="px-3 md:px-5 py-1.5 rounded-lg text-[10px] md:text-sm font-bold transition <?= (isset($_GET['status']) && $_GET['status'] == 'Selesai') ? 'bg-white text-indigo-600 shadow-sm' : 'text-gray-500 hover:bg-white hover:shadow-sm' ?>">Selesai</button>
        </div>
    </div>

    <!-- Table -->
    <div class="overflow-x-auto -mx-6 px-6">
        <table class="w-full text-left min-w-[800px]">
            <thead>
                <tr class="text-[11px] font-extrabold text-[#9ca3af] uppercase tracking-[0.2em] border-b border-gray-50">
                    <th class="pb-6 pl-2">Nama Proyek</th>
                    <th class="pb-6">Klien</th>
                    <th class="pb-6">Tenggat Waktu</th>
                    <th class="pb-6">Status</th>
                    <th class="pb-6">Progres</th>
                    <th class="pb-6">Tim</th>
                    <?php if(isset($_SESSION['user']) && $_SESSION['user']['role'] === 'admin') : ?>
                    <th class="pb-6 text-right pr-4">Aksi</th>
                    <?php endif; ?>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50/50">
                <?php if(empty($data['projects'])) : ?>
                    <tr><td colspan="6" class="py-20 text-center text-gray-400 font-medium">Belum ada proyek ditambahkan.</td></tr>
                <?php endif; ?>
                <?php foreach($data['projects'] as $p) : ?>
                <tr class="group hover:bg-gray-50/50 transition">
                    <td class="py-6 pl-2">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 <?= $p['status_color']; ?> rounded-2xl flex items-center justify-center bg-opacity-10 shadow-sm border border-gray-100/50">
                                <?php if($p['icon'] == 'rocket') : ?>
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                                <?php elseif($p['icon'] == 'bank') : ?>
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
                                <?php elseif($p['icon'] == 'leaf') : ?>
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path></svg>
                                <?php else : ?>
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"></path></svg>
                                <?php endif; ?>
                            </div>
                            <div>
                                <h4 class="font-extrabold text-[#111827] text-[15px] tracking-tight"><?= $p['name']; ?></h4>
                            </div>
                        </div>
                    </td>
                    <td class="py-6">
                        <span class="text-[#6b7280] font-bold text-[14px]"><?= $p['client']; ?></span>
                    </td>
                    <td class="py-6 font-bold text-[14px] text-[#4b5563]">
                        <?= date('d M Y', strtotime($p['due_date'])); ?>
                    </td>
                    <td class="py-6">
                        <span class="px-4 py-1.5 rounded-full text-[10px] font-black tracking-[0.1em] <?= $p['status_color']; ?> bg-opacity-20 flex w-fit items-center justify-center leading-none border border-current bg-white">
                            <?= strtoupper($p['status']); ?>
                        </span>
                    </td>
                    <td class="py-6">
                        <?php 
                            $progress = (int)round($p['avg_progress'] ?? 0);
                            $pColor = 'bg-red-500';
                            $textCol = 'text-red-500';
                            if ($progress >= 71) { $pColor = 'bg-green-500'; $textCol = 'text-green-500'; }
                            elseif ($progress >= 41) { $pColor = 'bg-yellow-400'; $textCol = 'text-yellow-400'; }
                        ?>
                        <div class="flex flex-col gap-1.5 w-28">
                            <div class="flex justify-between items-center text-[10px] font-black">
                                <span class="<?= $textCol ?> uppercase tracking-wider"><?= $progress; ?>%</span>
                            </div>
                            <div class="w-full bg-gray-100 h-1.5 rounded-full overflow-hidden">
                                <div class="<?= $pColor ?> h-full rounded-full transition-all duration-500" style="width: <?= $progress; ?>%"></div>
                            </div>
                        </div>
                    </td>
                    <td class="py-6">
                        <?php if(!empty($p['leader_name'])) : ?>
                        <div class="flex items-center gap-3">
                            <img src="https://ui-avatars.com/api/?name=<?= urlencode($p['leader_name']); ?>&background=random" class="w-8 h-8 rounded-full border-2 border-white shadow-sm">
                            <div class="flex flex-col">
                                <span class="text-[13px] font-black text-gray-900 leading-tight"><?= $p['leader_name']; ?></span>
                                <span class="text-[10px] font-bold text-indigo-500 uppercase tracking-wider">Project Lead</span>
                            </div>
                        </div>
                        <?php else : ?>
                        <span class="text-[11px] font-bold text-gray-400 italic">Belum ditentukan</span>
                        <?php endif; ?>
                    </td>
                    <?php if(isset($_SESSION['user']) && $_SESSION['user']['role'] === 'admin') : ?>
                    <td class="py-6 text-right pr-4">
                        <div class="flex items-center justify-end gap-5">
                            <button class="p-2 text-[#9ca3af] hover:text-indigo-600 transition rounded-lg hover:bg-gray-50 btnEditProyek" data-id="<?= $p['id']; ?>">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                            </button>
                            <button class="p-2 text-[#9ca3af] hover:text-red-500 transition rounded-lg hover:bg-gray-50" onclick="showDeleteModal('<?= BASEURL; ?>/project/delete/<?= $p['id']; ?>', '<?= addslashes($p['name']); ?>')">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                            </button>
                        </div>
                    </td>
                    <?php endif; ?>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <!-- Pagination Footer -->
    <div class="flex items-center justify-between mt-8">
        <p class="text-[13px] text-[#9ca3af] font-medium">Menampilkan <?= count($data['projects']); ?> proyek</p>
    </div>
</div>

<!-- Modal Form Proyek -->
<div id="projectModal" class="fixed inset-0 z-[100] hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-900 bg-opacity-40 backdrop-blur-sm modal-overlay opacity-0 transition-opacity" aria-hidden="true" onclick="toggleProjectModal()"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div class="inline-block align-bottom bg-white rounded-3xl text-left shadow-2xl transform modal-content sm:my-8 sm:align-middle sm:max-w-xl sm:w-full">
            <form action="<?= BASEURL; ?>/project/add" method="post" id="projectForm">
                <input type="hidden" name="id" id="project_id">
                <div class="p-8 md:p-10">
                    <div class="flex justify-between items-center mb-8">
                        <div>
                            <h3 class="text-2xl font-black text-gray-900 tracking-tight" id="modalTitle">Tambah Proyek</h3>
                            <p class="text-gray-400 text-sm font-bold mt-1">Lengkapi informasi detail proyek di bawah ini.</p>
                        </div>
                        <button type="button" onclick="toggleProjectModal()" class="text-gray-300 hover:text-gray-500 transition">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </button>
                    </div>

                    <div class="space-y-6">
                        <div>
                            <label for="name" class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2 ml-1">Nama Proyek</label>
                            <input type="text" name="name" id="name" required class="w-full bg-gray-50 border-2 border-gray-50 rounded-2xl px-5 py-4 text-sm font-bold text-gray-700 focus:bg-white focus:border-indigo-500 outline-none transition" placeholder="Contoh: Website Redesign 2024">
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="client" class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2 ml-1">Klien</label>
                                <input type="text" name="client" id="client" required class="w-full bg-gray-50 border-2 border-gray-50 rounded-2xl px-5 py-4 text-sm font-bold text-gray-700 focus:bg-white focus:border-indigo-500 outline-none transition" placeholder="Nama Perusahaan">
                            </div>
                            <div>
                                <label for="due_date" class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2 ml-1">Tenggat Waktu</label>
                                <input type="date" name="due_date" id="due_date" required class="w-full bg-gray-50 border-2 border-gray-50 rounded-2xl px-5 py-4 text-sm font-bold text-gray-700 focus:bg-white focus:border-indigo-500 outline-none transition">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="status" class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2 ml-1">Status Utama</label>
                                <div class="select-container" id="statusSelectContainer">
                                    <div class="select-trigger">
                                        <span class="select-trigger-text text-gray-400">Pilih Status...</span>
                                        <svg class="select-trigger-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                    </div>
                                    <div class="select-options">
                                        <div class="select-option" data-value="Sesuai Jadwal">Sesuai Jadwal</div>
                                        <div class="select-option" data-value="Terhambat">Terhambat</div>
                                        <div class="select-option" data-value="Selesai">Selesai</div>
                                    </div>
                                </div>
                                <input type="hidden" name="status" id="status" value="Sesuai Jadwal">
                            </div>
                            <div>
                                <label for="icon" class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2 ml-1">Ikon Representasi</label>
                                <div class="select-container" id="iconSelectContainer">
                                    <div class="select-trigger">
                                        <span class="select-trigger-text text-gray-400">Pilih Ikon...</span>
                                        <svg class="select-trigger-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                    </div>
                                    <div class="select-options">
                                        <div class="select-option" data-value="rocket">🚀 Roket</div>
                                        <div class="select-option" data-value="bank">🏦 Finansial</div>
                                        <div class="select-option" data-value="leaf">🌿 Lingkungan</div>
                                        <div class="select-option" data-value="code">💻 Teknologi</div>
                                    </div>
                                </div>
                                <input type="hidden" name="icon" id="icon" value="rocket">
                            </div>
                        </div>

                        <div>
                            <label for="leader_id" class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2 ml-1">Penanggung Jawab Proyek</label>
                            <div class="select-container" id="leaderSelectContainer">
                                <div class="select-trigger">
                                    <span class="select-trigger-text text-gray-400">Pilih Penanggung Jawab...</span>
                                    <svg class="select-trigger-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                </div>
                                <div class="select-options">
                                    <?php foreach($data['team'] as $m) : ?>
                                        <div class="select-option" data-value="<?= $m['id']; ?>"><?= $m['name']; ?></div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                            <input type="hidden" name="leader_id" id="leader_id">
                        </div>

                        <div>
                            <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2 ml-1">Warna Aksen</label>
                            <div class="flex gap-4">
                                <label class="cursor-pointer">
                                    <input type="radio" name="status_color" value="text-indigo-600" class="hidden peer" checked>
                                    <div class="w-10 h-10 rounded-xl bg-indigo-600 peer-checked:ring-4 ring-indigo-100 ring-offset-2 transition"></div>
                                </label>
                                <label class="cursor-pointer">
                                    <input type="radio" name="status_color" value="text-purple-600" class="hidden peer">
                                    <div class="w-10 h-10 rounded-xl bg-purple-600 peer-checked:ring-4 ring-purple-100 ring-offset-2 transition"></div>
                                </label>
                                <label class="cursor-pointer">
                                    <input type="radio" name="status_color" value="text-emerald-600" class="hidden peer">
                                    <div class="w-10 h-10 rounded-xl bg-emerald-600 peer-checked:ring-4 ring-emerald-100 ring-offset-2 transition"></div>
                                </label>
                                <label class="cursor-pointer">
                                    <input type="radio" name="status_color" value="text-amber-600" class="hidden peer">
                                    <div class="w-10 h-10 rounded-xl bg-amber-600 peer-checked:ring-4 ring-amber-100 ring-offset-2 transition"></div>
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="flex gap-4 mt-10">
                        <button type="button" onclick="toggleProjectModal()" class="flex-1 px-8 py-4 rounded-2xl bg-gray-100 text-gray-700 font-black text-sm uppercase tracking-widest hover:bg-gray-200 transition">Batal</button>
                        <button type="submit" class="flex-1 px-8 py-4 rounded-2xl bg-indigo-600 text-white font-black text-sm uppercase tracking-widest hover:bg-indigo-700 transition shadow-lg shadow-indigo-100">Simpan Proyek</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    const projectModal = document.getElementById('projectModal');
    const projectForm = document.getElementById('projectForm');
    const modalTitle = document.getElementById('modalTitle');

    function toggleProjectModal() {
        if (projectModal.classList.contains('hidden')) {
            projectModal.classList.remove('hidden');
            setTimeout(() => {
                projectModal.classList.add('modal-active');
                document.body.classList.add('overflow-hidden');
            }, 10);
        } else {
            projectModal.classList.remove('modal-active');
            setTimeout(() => {
                projectModal.classList.add('hidden');
                document.body.classList.remove('overflow-hidden');
                projectForm.reset();
                document.getElementById('project_id').value = '';
            }, 300);
        }
    }

    // Modal behavior handled via jQuery if available, or native JS
    document.addEventListener('DOMContentLoaded', function() {
        // Teleport button to header
        const actionArea = document.getElementById('header-action-area');
        const actionContent = document.getElementById('project-action-content');
        if (actionArea && actionContent) {
            const btn = actionContent.querySelector('button');
            actionArea.appendChild(btn);
        }

        // Initialize Custom Selects
        initCustomSelect('statusSelectContainer', 'status');
        initCustomSelect('iconSelectContainer', 'icon');
        initCustomSelect('leaderSelectContainer', 'leader_id');

        const btnsTambah = document.querySelectorAll('.btnTambahProyek');
        btnsTambah.forEach(btn => {
            btn.addEventListener('click', function() {
                modalTitle.innerText = 'Tambah Proyek Baru';
                projectForm.setAttribute('action', '<?= BASEURL; ?>/project/add');
                
                document.getElementById('status').value = 'Sesuai Jadwal';
                document.getElementById('icon').value = 'rocket';
                document.getElementById('leader_id').value = '';
                
                initCustomSelect('statusSelectContainer', 'status');
                initCustomSelect('iconSelectContainer', 'icon');
                initCustomSelect('leaderSelectContainer', 'leader_id');

                toggleProjectModal();
            });
        });

        const btnsEdit = document.querySelectorAll('.btnEditProyek');
        btnsEdit.forEach(btn => {
            btn.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                modalTitle.innerText = 'Ubah Detail Proyek';
                projectForm.setAttribute('action', '<?= BASEURL; ?>/project/edit');
                
                fetch('<?= BASEURL; ?>/project/getEdit/' + id)
                    .then(response => response.json())
                    .then(data => {
                        document.getElementById('project_id').value = data.id;
                        document.getElementById('name').value = data.name;
                        document.getElementById('client').value = data.client;
                        document.getElementById('due_date').value = data.due_date;
                        
                        document.getElementById('status').value = data.status;
                        document.getElementById('icon').value = data.icon;
                        document.getElementById('leader_id').value = data.leader_id || '';
                        
                        initCustomSelect('statusSelectContainer', 'status');
                        initCustomSelect('iconSelectContainer', 'icon');
                        initCustomSelect('leaderSelectContainer', 'leader_id');
                        
                        const radios = document.querySelectorAll('input[name="status_color"]');
                        radios.forEach(r => {
                            if (r.value === data.status_color) r.checked = true;
                        });

                        toggleProjectModal();
                    });
            });
        });
    });
</script>
