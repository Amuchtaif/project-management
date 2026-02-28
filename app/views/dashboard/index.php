<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
    <!-- Stats Cards -->
    <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm hover:shadow-md transition">
        <div class="flex items-center justify-between mb-4">
            <div class="bg-purple-100 p-3 rounded-2xl">
                <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2-2z"></path></svg>
            </div>
        </div>
        <p class="text-gray-500 text-sm font-medium mb-1"><?= ($data['user_role'] ?? 'admin') === 'admin' ? 'Total Proyek' : 'Proyek Saya'; ?></p>
        <h3 class="text-3xl font-bold text-gray-900"><?= $data['stats']['projects']; ?></h3>
    </div>

    <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm hover:shadow-md transition">
        <div class="flex items-center justify-between mb-4">
            <div class="bg-blue-100 p-3 rounded-2xl">
                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
            </div>
        </div>
        <p class="text-gray-500 text-sm font-medium mb-1"><?= ($data['user_role'] ?? 'admin') === 'admin' ? 'Tugas Aktif' : 'Tugas Saya'; ?></p>
        <h3 class="text-3xl font-bold text-gray-900"><?= $data['stats']['tasks']; ?></h3>
    </div>

    <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm hover:shadow-md transition">
        <div class="flex items-center justify-between mb-4">
            <div class="bg-indigo-100 p-3 rounded-2xl">
                <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
        </div>
        <p class="text-gray-500 text-sm font-medium mb-1">Tugas Selesai</p>
        <h3 class="text-3xl font-bold text-gray-900"><?= number_format($data['stats']['tasks'] * 0.8, 0); ?></h3>
    </div>

    <?php if(($data['user_role'] ?? 'admin') === 'admin') : ?>
    <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm hover:shadow-md transition">
        <div class="flex items-center justify-between mb-4">
            <div class="bg-gray-100 p-3 rounded-2xl">
                <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
            </div>
        </div>
        <p class="text-gray-500 text-sm font-medium mb-1">Anggota Tim</p>
        <h3 class="text-3xl font-bold text-gray-900"><?= $data['stats']['members']; ?></h3>
    </div>
    <?php else : ?>
    <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm hover:shadow-md transition">
        <div class="flex items-center justify-between mb-4">
            <div class="bg-emerald-100 p-3 rounded-2xl">
                <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
            </div>
        </div>
        <p class="text-gray-500 text-sm font-medium mb-1">Role Anda</p>
        <h3 class="text-xl font-bold text-gray-900 capitalize"><?= $_SESSION['user']['role']; ?></h3>
    </div>
    <?php endif; ?>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <!-- Left Column: Project Progress -->
    <div class="lg:col-span-2 space-y-8">
        <!-- Left Column: Project Progress -->
        <div class="bg-white p-8 rounded-3xl border border-gray-100 shadow-sm">
            <div class="flex justify-between items-center mb-8">
                <h2 class="text-xl font-bold text-gray-900"><?= ($data['user_role'] ?? 'admin') === 'admin' ? 'Progres Proyek' : 'Proyek Saya'; ?></h2>
                <a href="<?= BASEURL; ?>/project" class="text-indigo-600 text-sm font-bold hover:underline">Lihat Semua</a>
            </div>
            
            <div class="space-y-8">
                <?php if(empty($data['projects_progress'])) : ?>
                    <p class="text-gray-400 text-sm italic"><?= ($data['user_role'] ?? 'admin') === 'admin' ? 'Belum ada proyek aktif.' : 'Anda belum ditugaskan ke proyek manapun.'; ?></p>
                <?php endif; ?>
                <?php foreach(array_slice($data['projects_progress'], 0, 3) as $p) : 
                    $progress = (int)round($p['avg_progress']);
                    
                    // Dynamic Colors
                    $pColor = 'bg-red-500';
                    $borderCol = 'border-red-500';
                    $textCol = 'text-red-500';
                    
                    if ($progress >= 71) {
                        $pColor = 'bg-green-500';
                        $borderCol = 'border-green-500';
                        $textCol = 'text-green-500';
                    } elseif ($progress >= 41) {
                        $pColor = 'bg-yellow-400';
                        $borderCol = 'border-yellow-400';
                        $textCol = 'text-yellow-400';
                    }
                ?>
                <div class="group/proj">
                    <div class="flex justify-between items-center mb-4">
                        <div class="flex items-center gap-3">
                           <div class="w-2 h-2 rounded-full <?= $pColor ?>"></div>
                           <span class="font-bold text-gray-800 transition-colors group-hover/proj:text-indigo-600"><?= $p['name']; ?></span>
                        </div>
                        <span class="<?= $textCol ?> text-sm font-black"><?= $progress; ?>%</span>
                    </div>
                    
                    <div class="relative w-full bg-gray-50 h-2 rounded-full mb-4">
                        <div class="h-full rounded-full <?= $pColor ?> transition-all duration-700" style="width: <?= $progress; ?>%"></div>
                        <!-- Slider Look decoration -->
                        <div class="absolute top-1/2 -translate-y-1/2 w-3.5 h-3.5 bg-white border-2 <?= $borderCol ?> rounded-full shadow-sm shadow-black/10 transition-all duration-700 pointer-events-none" style="left: calc(<?= $progress; ?>% - 7px)"></div>
                    </div>

                    <div class="flex justify-between items-center">
                        <span class="text-xs text-gray-400">Project Lead: <span class="font-bold text-gray-600"><?= $p['leader_name'] ?? 'Belum ada'; ?></span></span>
                        <div class="flex -space-x-2">
                            <img src="https://ui-avatars.com/api/?name=<?= urlencode($p['leader_name'] ?? 'User'); ?>&background=random" class="w-6 h-6 rounded-full border-2 border-white shadow-sm" title="<?= $p['leader_name'] ?? 'User'; ?>">
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Task Distribution Chart -->
        <div class="bg-white p-8 rounded-3xl border border-gray-100 shadow-sm">
            <h2 class="text-xl font-bold text-gray-900 mb-8">Distribusi Tugas</h2>
            <div class="flex flex-col md:flex-row items-center justify-between gap-8">
                <?php
                    $dist = ['todo' => 0, 'in_progress' => 0, 'done' => 0];
                    $total = 0;
                    foreach($data['task_distribution'] as $d) {
                        $dist[$d['status']] = $d['count'];
                        $total += $d['count'];
                    }
                    
                    $pArr = [
                        'todo' => $total > 0 ? round(($dist['todo'] / $total) * 100) : 0,
                        'in_progress' => $total > 0 ? round(($dist['in_progress'] / $total) * 100) : 0,
                        'done' => $total > 0 ? round(($dist['done'] / $total) * 100) : 0
                    ];

                    // For SVG donut: dasharray values
                    $offset1 = 100 - $pArr['done'];
                    $offset2 = $offset1 - $pArr['in_progress'];
                ?>
                <div class="relative w-40 h-40">
                    <svg class="w-full h-full" viewBox="0 0 36 36">
                        <!-- Background Circle (Todo - Grey) -->
                        <path class="text-gray-100" stroke-width="3" stroke="currentColor" fill="none" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" />
                        
                        <!-- Done Circle (Green) -->
                        <path class="text-green-500" stroke-width="3" stroke-dasharray="<?= $pArr['done']; ?>, 100" stroke-dashoffset="0" stroke-linecap="round" stroke="currentColor" fill="none" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" />
                        
                        <!-- In Progress Circle (Indigo) -->
                        <path class="text-indigo-600" stroke-width="3" stroke-dasharray="<?= $pArr['in_progress']; ?>, 100" stroke-dashoffset="-<?= $pArr['done']; ?>" stroke-linecap="round" stroke="currentColor" fill="none" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" />
                    </svg>
                    <div class="absolute inset-0 flex flex-col items-center justify-center">
                        <span class="text-2xl font-bold text-gray-900"><?= $total; ?></span>
                        <span class="text-[10px] text-gray-400 uppercase font-bold">Total</span>
                    </div>
                </div>
                <div class="space-y-4 w-full md:w-auto">
                    <div class="flex items-center gap-3">
                        <div class="w-3 h-3 bg-green-500 rounded-full"></div>
                        <span class="text-sm text-gray-600 font-medium">Selesai (<?= $pArr['done']; ?>%)</span>
                    </div>
                    <div class="flex items-center gap-3">
                        <div class="w-3 h-3 bg-indigo-600 rounded-full"></div>
                        <span class="text-sm text-gray-600 font-medium">Dalam Proses (<?= $pArr['in_progress']; ?>%)</span>
                    </div>
                    <div class="flex items-center gap-3">
                        <div class="w-3 h-3 bg-gray-200 rounded-full"></div>
                        <span class="text-sm text-gray-600 font-medium">Rencana (<?= $pArr['todo']; ?>%)</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Right Column: Recent Activity -->
    <div class="bg-white p-8 rounded-3xl border border-gray-100 shadow-sm flex flex-col">
        <div class="flex justify-between items-center mb-8">
            <h2 class="text-xl font-bold text-gray-900">Aktivitas Terbaru</h2>
            <a href="<?= BASEURL; ?>/activity" class="text-indigo-600 text-sm font-bold hover:underline">Lihat Semua</a>
        </div>
        <div class="space-y-8 flex-grow">
            <?php if(empty($data['activities'])) : ?>
                <p class="text-gray-400 text-sm italic">Belum ada aktivitas baru.</p>
            <?php endif; ?>
            <?php foreach(array_slice($data['activities'], 0, 5) as $activity) : ?>
            <div class="flex gap-4">
                <img src="https://ui-avatars.com/api/?name=<?= urlencode($activity['user_name']); ?>&background=random" class="w-10 h-10 rounded-xl flex-shrink-0" alt="Avatar">
                <div class="flex-grow min-w-0">
                   <p class="text-sm text-gray-800 leading-snug break-words">
                       <span class="font-bold"><?= ($activity['user_id'] == $_SESSION['user']['id'] ? 'Anda' : $activity['user_name']); ?></span> 
                       <?= $activity['action']; ?> 
                       <span class="text-indigo-600 font-medium italic"><?= $activity['target']; ?></span>
                   </p>
                   <span class="text-xs text-gray-400"><?= date('H:i', strtotime($activity['created_at'])); ?></span>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>
