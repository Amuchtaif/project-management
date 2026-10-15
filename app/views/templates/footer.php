    </main>
</div> <!-- End Main Content Area -->

<!-- REUSABLE DELETE MODAL (Design updated to match) -->
<div id="deleteModal" class="fixed inset-0 z-[100] hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-900 bg-opacity-40 backdrop-blur-sm modal-overlay opacity-0" aria-hidden="true" onclick="toggleDeleteModal()"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div class="inline-block align-bottom bg-white dark:bg-slate-800 rounded-3xl text-left overflow-hidden shadow-2xl transform modal-content sm:my-8 sm:align-middle sm:max-w-lg sm:w-full transition-colors">
            <div class="bg-white dark:bg-slate-800 p-8">
                <div class="flex items-center gap-4 mb-6">
                    <div class="flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-2xl bg-red-50 dark:bg-red-900/20">
                        <i class="fa-solid fa-triangle-exclamation text-2xl text-red-600 dark:text-red-400"></i>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-gray-900 dark:text-gray-100" id="modal-title">Konfirmasi Hapus</h3>
                        <p class="text-gray-500 dark:text-gray-400 text-sm" id="deleteModalText">Apakah Anda yakin ingin menghapus data ini?</p>
                    </div>
                </div>
                <div class="flex flex-col sm:flex-row-reverse gap-3">
                    <a id="confirmDeleteBtn" href="#" class="w-full inline-flex justify-center rounded-xl px-6 py-3 bg-red-600 text-white font-bold hover:bg-red-700 transition shadow-lg shadow-red-100 dark:shadow-none">Hapus Data</a>
                    <button type="button" onclick="toggleDeleteModal()" class="w-full inline-flex justify-center rounded-xl px-6 py-3 bg-gray-100 dark:bg-slate-700 text-gray-700 dark:text-gray-300 font-bold hover:bg-gray-200 dark:hover:bg-slate-600 transition">Batal</button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- GLOBAL COMMAND PALETTE MODAL (Ctrl + K) -->
<div id="commandPaletteModal" class="fixed inset-0 z-[200] hidden overflow-y-auto" aria-modal="true" role="dialog">
    <div class="fixed inset-0 bg-slate-950/70 backdrop-blur-md transition-opacity duration-200 opacity-0" id="commandPaletteBackdrop" onclick="closeCommandPalette()"></div>
    <div class="min-h-screen px-4 text-center flex items-start justify-center pt-16 sm:pt-24 pb-8">
        <div class="inline-block w-full max-w-2xl text-left bg-white dark:bg-slate-900 rounded-3xl shadow-2xl border border-gray-100 dark:border-slate-800 overflow-hidden transform transition-all duration-200 scale-95 opacity-0 relative z-10" id="commandPaletteCard">
            <!-- Search Input Box -->
            <div class="p-4 sm:p-5 border-b border-gray-100 dark:border-slate-800 flex items-center gap-3">
                <i class="fa-solid fa-magnifying-glass text-indigo-600 dark:text-indigo-400 text-lg flex-shrink-0"></i>
                <input type="text" id="commandPaletteInput" autocomplete="off" placeholder="Cari project, tugas, anggota, atau ketik navigasi..." class="w-full bg-transparent text-sm sm:text-base font-bold text-gray-900 dark:text-gray-100 placeholder-gray-400 dark:placeholder-gray-500 outline-none">
                <button type="button" onclick="closeCommandPalette()" class="px-2 py-1 text-[11px] font-black uppercase text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 rounded-lg bg-gray-100 dark:bg-slate-800 transition">ESC</button>
            </div>
            
            <!-- Results Area -->
            <div id="commandPaletteContent" class="max-h-96 overflow-y-auto p-3 sm:p-4 space-y-1">
                <!-- Injected via JavaScript -->
            </div>

            <!-- Footer Shortcuts Hint -->
            <div class="p-3 bg-gray-50 dark:bg-slate-800/50 border-t border-gray-100 dark:border-slate-800/80 flex items-center justify-between text-[11px] text-gray-400 dark:text-gray-500 font-bold px-5">
                <div class="flex items-center gap-3">
                    <span><kbd class="px-1.5 py-0.5 rounded bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 font-mono text-[10px]">↑</kbd> <kbd class="px-1.5 py-0.5 rounded bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 font-mono text-[10px]">↓</kbd> Navigasi</span>
                    <span><kbd class="px-1.5 py-0.5 rounded bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 font-mono text-[10px]">↵</kbd> Buka</span>
                </div>
                <span>Assunnah Quick Command</span>
            </div>
        </div>
    </div>
</div>

<script>
    // ================= COMMAND PALETTE (CTRL + K) =================
    const cmdModal = document.getElementById('commandPaletteModal');
    const cmdBackdrop = document.getElementById('commandPaletteBackdrop');
    const cmdCard = document.getElementById('commandPaletteCard');
    const cmdInput = document.getElementById('commandPaletteInput');
    const cmdContent = document.getElementById('commandPaletteContent');
    let cmdDebounceTimer = null;
    let selectedCmdIndex = -1;

    function openCommandPalette() {
        if (!cmdModal) return;
        cmdModal.classList.remove('hidden');
        setTimeout(() => {
            cmdBackdrop.classList.remove('opacity-0');
            cmdCard.classList.remove('scale-95', 'opacity-0');
            cmdInput.focus();
            cmdInput.select();
        }, 10);
        document.body.classList.add('overflow-hidden');
        renderDefaultCommandPalette();
    }

    function closeCommandPalette() {
        if (!cmdModal) return;
        cmdBackdrop.classList.add('opacity-0');
        cmdCard.classList.add('scale-95', 'opacity-0');
        setTimeout(() => {
            cmdModal.classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
        }, 200);
    }

    // Global Shortcut Ctrl + K / Cmd + K
    document.addEventListener('keydown', function(e) {
        if ((e.ctrlKey || e.metaKey) && e.key.toLowerCase() === 'k') {
            e.preventDefault();
            if (cmdModal && !cmdModal.classList.contains('hidden')) {
                closeCommandPalette();
            } else {
                openCommandPalette();
            }
        } else if (e.key === 'Escape') {
            if (cmdModal && !cmdModal.classList.contains('hidden')) {
                closeCommandPalette();
            }
        }
    });

    function renderDefaultCommandPalette() {
        cmdContent.innerHTML = `
            <div class="px-3 py-2 text-[10px] font-black uppercase tracking-wider text-gray-400 dark:text-gray-500">Navigasi Cepat</div>
            <a href="<?= BASEURL; ?>/dashboard" class="cmd-item flex items-center justify-between p-3 rounded-2xl hover:bg-gray-50 dark:hover:bg-slate-800 text-gray-700 dark:text-gray-200 transition group">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-xl bg-blue-50 dark:bg-blue-950/60 text-blue-600 flex items-center justify-center flex-shrink-0">
                        <i class="fa-solid fa-house text-xs"></i>
                    </div>
                    <div>
                        <p class="text-xs font-bold text-gray-900 dark:text-gray-100">Dashboard Utama</p>
                        <p class="text-[11px] text-gray-400">Ringkasan & Metrik Sistem</p>
                    </div>
                </div>
                <i class="fa-solid fa-chevron-right text-xs text-gray-300 dark:text-gray-600 group-hover:text-indigo-500 transition"></i>
            </a>
            <a href="<?= BASEURL; ?>/project" class="cmd-item flex items-center justify-between p-3 rounded-2xl hover:bg-gray-50 dark:hover:bg-slate-800 text-gray-700 dark:text-gray-200 transition group">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-xl bg-amber-50 dark:bg-amber-950/60 text-amber-600 flex items-center justify-center flex-shrink-0">
                        <i class="fa-solid fa-folder-open text-xs"></i>
                    </div>
                    <div>
                        <p class="text-xs font-bold text-gray-900 dark:text-gray-100">Direktori Project</p>
                        <p class="text-[11px] text-gray-400">Kelola seluruh project yayasan</p>
                    </div>
                </div>
                <i class="fa-solid fa-chevron-right text-xs text-gray-300 dark:text-gray-600 group-hover:text-indigo-500 transition"></i>
            </a>
            <a href="<?= BASEURL; ?>/task" class="cmd-item flex items-center justify-between p-3 rounded-2xl hover:bg-gray-50 dark:hover:bg-slate-800 text-gray-700 dark:text-gray-200 transition group">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-xl bg-indigo-50 dark:bg-indigo-950/60 text-indigo-600 flex items-center justify-center flex-shrink-0">
                        <i class="fa-solid fa-table-columns text-xs"></i>
                    </div>
                    <div>
                        <p class="text-xs font-bold text-gray-900 dark:text-gray-100">Papan Tugas (Kanban)</p>
                        <p class="text-[11px] text-gray-400">Todo, In Progress, Done</p>
                    </div>
                </div>
                <i class="fa-solid fa-chevron-right text-xs text-gray-300 dark:text-gray-600 group-hover:text-indigo-500 transition"></i>
            </a>
            <a href="<?= BASEURL; ?>/task/calendar" class="cmd-item flex items-center justify-between p-3 rounded-2xl hover:bg-gray-50 dark:hover:bg-slate-800 text-gray-700 dark:text-gray-200 transition group">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-xl bg-purple-50 dark:bg-purple-950/60 text-purple-600 flex items-center justify-center flex-shrink-0">
                        <i class="fa-solid fa-calendar-days text-xs"></i>
                    </div>
                    <div>
                        <p class="text-xs font-bold text-gray-900 dark:text-gray-100">Kalender Tugas</p>
                        <p class="text-[11px] text-gray-400">Jadwal tenggat waktu tim</p>
                    </div>
                </div>
                <i class="fa-solid fa-chevron-right text-xs text-gray-300 dark:text-gray-600 group-hover:text-indigo-500 transition"></i>
            </a>
        `;
    }

    if (cmdInput) {
        cmdInput.addEventListener('input', function() {
            const query = this.value.trim();
            if (cmdDebounceTimer) clearTimeout(cmdDebounceTimer);
            if (!query) {
                renderDefaultCommandPalette();
                return;
            }

            cmdDebounceTimer = setTimeout(() => {
                fetch('<?= BASEURL; ?>/dashboard/globalSearch?q=' + encodeURIComponent(query))
                    .then(res => res.json())
                    .then(data => {
                        if (!data.results || data.results.length === 0) {
                            cmdContent.innerHTML = `
                                <div class="py-10 text-center text-gray-400 dark:text-gray-500">
                                    <i class="fa-solid fa-magnifying-glass text-2xl mb-2"></i>
                                    <p class="text-xs font-bold">Tidak ada hasil ditemukan untuk "${query}"</p>
                                </div>`;
                            return;
                        }

                        let html = '';
                        let currentCat = '';
                        data.results.forEach((item, idx) => {
                            if (item.category !== currentCat) {
                                currentCat = item.category;
                                html += `<div class="px-3 pt-3 pb-1 text-[10px] font-black uppercase tracking-wider text-gray-400 dark:text-gray-500">${currentCat}</div>`;
                            }
                            html += `
                                <a href="${item.url}" class="cmd-item flex items-center justify-between p-3 rounded-2xl hover:bg-indigo-50/50 dark:hover:bg-slate-800 text-gray-700 dark:text-gray-200 transition group" data-index="${idx}">
                                    <div class="flex items-center gap-3 min-w-0">
                                        <div class="w-8 h-8 rounded-xl bg-gray-100 dark:bg-slate-800 flex items-center justify-center flex-shrink-0">
                                            <i class="${item.icon} text-sm"></i>
                                        </div>
                                        <div class="min-w-0">
                                            <p class="text-xs font-black text-gray-900 dark:text-gray-100 truncate">${item.title}</p>
                                            <p class="text-[11px] text-gray-400 dark:text-gray-500 truncate">${item.subtitle}</p>
                                        </div>
                                    </div>
                                    <i class="fa-solid fa-arrow-right text-xs text-gray-300 dark:text-gray-600 group-hover:text-indigo-600 transition flex-shrink-0"></i>
                                </a>`;
                        });
                        cmdContent.innerHTML = html;
                    })
                    .catch(() => {
                        cmdContent.innerHTML = `<div class="py-6 text-center text-xs text-rose-500 font-bold">Gagal memuat hasil pencarian.</div>`;
                    });
            }, 180);
        });
    }

    // Keyboard navigation in palette
    if (cmdInput) {
        cmdInput.addEventListener('keydown', function(e) {
            const items = cmdContent.querySelectorAll('.cmd-item');
            if (e.key === 'ArrowDown') {
                e.preventDefault();
                selectedCmdIndex = (selectedCmdIndex + 1) % items.length;
                updateCmdSelection(items);
            } else if (e.key === 'ArrowUp') {
                e.preventDefault();
                selectedCmdIndex = (selectedCmdIndex - 1 + items.length) % items.length;
                updateCmdSelection(items);
            } else if (e.key === 'Enter') {
                e.preventDefault();
                if (selectedCmdIndex >= 0 && items[selectedCmdIndex]) {
                    items[selectedCmdIndex].click();
                } else if (items.length > 0) {
                    items[0].click();
                }
            }
        });
    }

    function updateCmdSelection(items) {
        items.forEach((item, idx) => {
            if (idx === selectedCmdIndex) {
                item.classList.add('bg-indigo-50', 'dark:bg-slate-800', 'ring-1', 'ring-indigo-500');
                item.scrollIntoView({ block: 'nearest' });
            } else {
                item.classList.remove('bg-indigo-50', 'dark:bg-slate-800', 'ring-1', 'ring-indigo-500');
            }
        });
    }
    // SIDEBAR TOGGLE FOR MOBILE
    const sidebar = document.getElementById('mainSidebar');
    const overlay = document.getElementById('sidebarOverlay');

    function toggleSidebar() {
        sidebar.classList.toggle('-translate-x-full');
        overlay.classList.toggle('hidden');
        setTimeout(() => {
            overlay.classList.toggle('opacity-0');
        }, 10);
        
        if (!sidebar.classList.contains('-translate-x-full')) {
            document.body.classList.add('overflow-hidden');
        } else {
            document.body.classList.remove('overflow-hidden');
        }
    }

    // GLOBAL NOTIFICATION SYSTEM (Consistent with Flasher::flash)
    let notificationTimer = null;

    function hideNotification() {
        const notification = document.getElementById('notification-bar');
        if (notification) {
            notification.classList.remove('translate-y-0', 'opacity-100', 'scale-100');
            notification.classList.add('-translate-y-8', 'opacity-0', 'scale-95');
            setTimeout(() => {
                if (notification && notification.parentNode) {
                    notification.remove();
                }
            }, 300);
        }
    }

    function showGlobalNotification(pesan, aksi = '', tipe = 'success') {
        const existing = document.getElementById('notification-bar');
        if (existing) {
            existing.remove();
        }
        if (notificationTimer) {
            clearTimeout(notificationTimer);
        }

        const rawTipe = (tipe || 'success').toLowerCase();
        let bgClass = 'bg-emerald-600';
        let iconClass = 'fa-solid fa-check';
        let titleText = 'Berhasil';

        if (rawTipe === 'warning') {
            bgClass = 'bg-amber-600';
            iconClass = 'fa-solid fa-triangle-exclamation';
            titleText = 'Perhatian';
        } else if (rawTipe === 'info') {
            bgClass = 'bg-indigo-600';
            iconClass = 'fa-solid fa-info';
            titleText = 'Informasi';
        } else if (rawTipe === 'danger' || rawTipe === 'error' || rawTipe === 'gagal') {
            bgClass = 'bg-rose-600';
            iconClass = 'fa-solid fa-xmark';
            titleText = 'Gagal';
        }

        pesan = (pesan || '').trim();
        aksi = (aksi || '').trim();
        let bodyText = '';
        if (!aksi) {
            bodyText = pesan;
        } else if (pesan.toLowerCase() === 'berhasil' && ['dihapus', 'ditambahkan', 'diperbarui'].includes(aksi.toLowerCase())) {
            bodyText = 'Data berhasil ' + aksi;
        } else if (pesan.toLowerCase() === 'gagal' && ['dihapus', 'ditambahkan', 'diperbarui'].includes(aksi.toLowerCase())) {
            bodyText = 'Data gagal ' + aksi;
        } else if (aksi.toLowerCase().includes(pesan.toLowerCase())) {
            bodyText = aksi;
        } else {
            bodyText = pesan + ' ' + aksi;
        }
        bodyText = bodyText.trim();
        if (bodyText) {
            bodyText = bodyText.charAt(0).toUpperCase() + bodyText.slice(1);
            if (!['.', '!', '?'].includes(bodyText.slice(-1))) {
                bodyText += '.';
            }
        }

        function escapeHtml(text) {
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }

        const bar = document.createElement('div');
        bar.id = 'notification-bar';
        bar.className = 'fixed top-6 left-1/2 -translate-x-1/2 z-[9999] max-w-[92vw] sm:max-w-md w-full pointer-events-none transform transition-all duration-300 ease-out -translate-y-8 opacity-0 scale-95';
        bar.innerHTML = `
            <div class="${bgClass} text-white px-4 py-3 sm:px-4.5 sm:py-3.5 rounded-2xl shadow-xl shadow-black/20 border border-white/20 flex items-center justify-between gap-3 pointer-events-auto relative overflow-hidden" role="alert">
                <div class="flex items-center gap-3.5 min-w-0 flex-1">
                    <div class="w-9 h-9 rounded-xl bg-white/20 flex items-center justify-center flex-shrink-0 text-white shadow-inner">
                        <i class="${iconClass} text-sm"></i>
                    </div>
                    <div class="min-w-0 flex-1">
                        <p class="text-[11px] font-bold uppercase tracking-wider text-white/80 leading-none mb-1">${titleText}</p>
                        <p class="text-xs sm:text-sm font-semibold text-white leading-snug break-words">${escapeHtml(bodyText)}</p>
                    </div>
                </div>
                <button type="button" onclick="hideNotification()" class="p-1.5 text-white/70 hover:text-white hover:bg-white/20 rounded-xl transition flex-shrink-0" title="Tutup notifikasi">
                    <i class="fa-solid fa-xmark text-xs"></i>
                </button>
                <div class="absolute bottom-0 left-0 right-0 h-[3px] bg-white/20">
                    <div id="toast-progress-bar" class="h-full bg-white/60 transition-all duration-[3500ms] ease-linear w-full"></div>
                </div>
            </div>
        `;
        document.body.appendChild(bar);

        setTimeout(() => {
            bar.classList.remove('-translate-y-8', 'opacity-0', 'scale-95');
            bar.classList.add('translate-y-0', 'opacity-100', 'scale-100');
            const prog = document.getElementById('toast-progress-bar');
            if (prog) prog.style.width = '0%';
        }, 30);

        notificationTimer = setTimeout(() => {
            hideNotification();
        }, 3600);
    }

    window.showGlobalNotification = showGlobalNotification;
    window.showNotification = showGlobalNotification;
    window.showTeamToast = showGlobalNotification;

    // ================= NOTIFICATION DISMISS & MANAGEMENT =================
    function animateRemoveNotifCard(card) {
        if (!card) return;
        card.style.transition = 'all 0.25s cubic-bezier(0.4, 0, 0.2, 1)';
        card.style.opacity = '0';
        card.style.transform = 'translateX(10px)';
        card.style.maxHeight = (card.scrollHeight || 64) + 'px';
        
        requestAnimationFrame(() => {
            card.style.maxHeight = '0px';
            card.style.paddingTop = '0px';
            card.style.paddingBottom = '0px';
            card.style.marginTop = '0px';
            card.style.marginBottom = '0px';
            card.style.borderWidth = '0px';
            card.style.pointerEvents = 'none';
        });

        setTimeout(() => {
            if (card && card.parentNode) {
                card.remove();
            }
            checkEmptyNotifState();
        }, 260);
    }

    function updateNotifCounters() {
        const container = document.getElementById('notifListContainer');
        if (!container) return;
        const visibleItems = container.querySelectorAll('.notif-item:not([style*="opacity: 0"])');
        const remaining = visibleItems.length;
        const badgeCount = document.getElementById('notifBadgeCount');
        const redDot = document.getElementById('notifRedDot');
        const clearBtn = document.getElementById('btnClearAllNotif');

        if (remaining <= 0) {
            if (badgeCount) badgeCount.remove();
            if (redDot) redDot.remove();
            if (clearBtn) clearBtn.remove();
        } else {
            if (badgeCount) {
                badgeCount.textContent = remaining;
            }
        }
    }

    function checkEmptyNotifState() {
        const container = document.getElementById('notifListContainer');
        if (!container) return;
        const remainingItems = container.querySelectorAll('.notif-item');
        if (remainingItems.length === 0) {
            const redDot = document.getElementById('notifRedDot');
            if (redDot) redDot.remove();

            const badgeCount = document.getElementById('notifBadgeCount');
            if (badgeCount) badgeCount.remove();

            const clearBtn = document.getElementById('btnClearAllNotif');
            if (clearBtn) clearBtn.remove();

            container.innerHTML = `
                <div class="px-6 py-10 text-center" id="emptyNotifState">
                    <div class="w-10 h-10 rounded-full bg-slate-100 dark:bg-slate-800 text-slate-400 dark:text-slate-500 flex items-center justify-center mx-auto mb-2.5">
                        <i class="fa-regular fa-bell text-sm"></i>
                    </div>
                    <p class="text-xs font-medium text-slate-700 dark:text-slate-300">Tidak ada notifikasi baru</p>
                    <p class="text-[11px] text-slate-400 dark:text-slate-500 mt-0.5">Semua notifikasi telah dibaca</p>
                </div>
            `;
        }
    }

    function dismissSingleNotification(event, notifId, card) {
        if (event) {
            event.preventDefault();
            event.stopPropagation();
        }

        if (!card && event) {
            card = event.currentTarget.closest('.notif-item');
        }

        if (card) {
            animateRemoveNotifCard(card);
        }
        updateNotifCounters();

        if (notifId) {
            fetch('<?= BASEURL ?>/notification/dismiss/' + notifId, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                keepalive: true
            }).catch(err => {
                console.error('Dismiss notification failed:', err);
            });
        }
    }

    async function handleNotificationClick(event, notifId, link) {
        // If click was triggered on the dismiss close button, do nothing here
        if (event.target && event.target.closest('.btn-dismiss-single')) {
            return;
        }

        const card = event.currentTarget.closest('.notif-item') || event.currentTarget;

        // Support opening in new tab without navigating current window
        if (event.ctrlKey || event.metaKey || event.shiftKey || event.button === 1) {
            if (card) animateRemoveNotifCard(card);
            updateNotifCounters();
            if (notifId) {
                fetch('<?= BASEURL ?>/notification/dismiss/' + notifId, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    keepalive: true
                }).catch(() => {});
            }
            return;
        }

        event.preventDefault();

        // Dismiss visually from dropdown immediately
        if (card) {
            animateRemoveNotifCard(card);
        }
        updateNotifCounters();

        // Send API call to mark as read before navigating
        if (notifId) {
            try {
                const dismissPromise = fetch('<?= BASEURL ?>/notification/dismiss/' + notifId, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    keepalive: true
                });
                // Allow up to 200ms for db update, then proceed smoothly
                await Promise.race([
                    dismissPromise,
                    new Promise(resolve => setTimeout(resolve, 200))
                ]);
            } catch (err) {
                console.error('Failed to dismiss notification:', err);
            }
        }

        if (link && link !== '#' && !link.startsWith('javascript:')) {
            window.location.href = link;
        }
    }

    function clearAllNotifications(event) {
        if (event) {
            event.preventDefault();
            event.stopPropagation();
        }

        const btn = document.getElementById('btnClearAllNotif');
        if (btn) {
            btn.disabled = true;
            btn.classList.add('opacity-50', 'pointer-events-none');
            btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin text-[10px]"></i><span>Memproses...</span>';
        }

        fetch('<?= BASEURL ?>/notification/clearAll', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' }
        })
        .then(res => res.json())
        .then(res => {
            if (res.success) {
                const redDot = document.getElementById('notifRedDot');
                if (redDot) redDot.remove();

                const badgeCount = document.getElementById('notifBadgeCount');
                if (badgeCount) badgeCount.remove();

                if (btn) btn.remove();

                const container = document.getElementById('notifListContainer');
                if (container) {
                    container.innerHTML = `
                        <div class="px-6 py-10 text-center" id="emptyNotifState">
                            <div class="w-10 h-10 rounded-full bg-slate-100 dark:bg-slate-800 text-slate-400 dark:text-slate-500 flex items-center justify-center mx-auto mb-2.5">
                                <i class="fa-regular fa-bell text-sm"></i>
                            </div>
                            <p class="text-xs font-medium text-slate-700 dark:text-slate-300">Tidak ada notifikasi baru</p>
                            <p class="text-[11px] text-slate-400 dark:text-slate-500 mt-0.5">Semua notifikasi telah dibaca</p>
                        </div>
                    `;
                }
            } else {
                if (btn) {
                    btn.disabled = false;
                    btn.classList.remove('opacity-50', 'pointer-events-none');
                    btn.innerHTML = '<i class="fa-solid fa-check-double text-[11px]"></i><span>Tandai semua dibaca</span>';
                }
                if (typeof showGlobalNotification === 'function') {
                    showGlobalNotification(res.message || 'Gagal membersihkan notifikasi', '', 'danger');
                }
            }
        })
        .catch(err => {
            console.error(err);
            if (btn) {
                btn.disabled = false;
                btn.classList.remove('opacity-50', 'pointer-events-none');
                btn.innerHTML = '<i class="fa-solid fa-check-double text-[11px]"></i><span>Tandai semua dibaca</span>';
            }
            if (typeof showGlobalNotification === 'function') {
                showGlobalNotification('Terjadi kesalahan koneksi server', '', 'danger');
            }
        });
    }

    window.animateRemoveNotifCard = animateRemoveNotifCard;
    window.updateNotifCounters = updateNotifCounters;
    window.checkEmptyNotifState = checkEmptyNotifState;
    window.dismissSingleNotification = dismissSingleNotification;
    window.handleNotificationClick = handleNotificationClick;
    window.clearAllNotifications = clearAllNotifications;

    window.onload = () => {
        const notification = document.getElementById('notification-bar');
        if (notification) {
            setTimeout(hideNotification, 3500);
        }
    }

    // REUSABLE DROPDOWN TOGGLE
    function toggleDropdown(id) {
        // Find the specific menu for this dropdown
        const menu = document.querySelector(`#${id} .dropdown-menu`);
        
        // Close ALL other dropdowns first
        document.querySelectorAll('.dropdown-menu').forEach(m => {
            if (m !== menu) {
                m.classList.add('hidden');
            }
        });
        
        // Toggle current menu
        if (menu) {
            menu.classList.toggle('hidden');
        }
    }

    // SHARED FILTER ENGINE
    function applyFilter(key, value) {
        const url = new URL(window.location.href);
        if(value) {
            url.searchParams.set(key, value);
        } else {
            url.searchParams.delete(key);
        }
        window.location.href = url.toString();
    }

    // Close dropdowns when clicking outside
    document.addEventListener('click', function(e) {
        if (!e.target.closest('.custom-dropdown')) {
            document.querySelectorAll('.dropdown-menu').forEach(menu => {
                menu.classList.add('hidden');
            });
        }
    });

    // REUSABLE DELETE MODAL
    const deleteModal = document.getElementById('deleteModal');
    const confirmDeleteBtn = document.getElementById('confirmDeleteBtn');
    const deleteModalText = document.getElementById('deleteModalText');

    function showDeleteModal(url, dataName) {
        deleteModalText.innerText = `Apakah Anda yakin ingin menghapus "${dataName}"? Tindakan ini tidak dapat dibatalkan.`;
        confirmDeleteBtn.setAttribute('href', url);
        
        deleteModal.classList.remove('hidden');
        // Let the browser paint the hidden change first
        setTimeout(() => {
            deleteModal.classList.add('modal-active');
        }, 10);
        document.body.classList.add('overflow-hidden');
    }

    function toggleDeleteModal() {
        deleteModal.classList.remove('modal-active');
        // Wait for animation to finish before hiding
        setTimeout(() => {
            deleteModal.classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
        }, 300);
    }
</script>

</body>
</html>
