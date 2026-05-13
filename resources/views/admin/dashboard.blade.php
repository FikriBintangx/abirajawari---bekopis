<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Abi Raja Wari | Control Center</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&family=Outfit:wght@400;600;700&display=swap" rel="stylesheet">
    
    <style>
        body { font-family: 'Inter', sans-serif; }
        .font-display { font-family: 'Outfit', sans-serif; }
        .excel-table th, .excel-table td { border: 1px solid #000000; }
        .shadow-premium { box-shadow: 8px 8px 0px 0px #000000; }
        .btn-black { 
            background: #000000; 
            color: #ffffff; 
            transition: all 0.2s ease;
        }
        .btn-black:hover { 
            transform: translate(-2px, -2px);
            box-shadow: 4px 4px 0px 0px rgba(0,0,0,0.2);
        }
        .btn-black:active {
            transform: translate(0, 0);
        }
        [x-cloak] { display: none !important; }
        
        /* Smooth transitions */
        .fade-in { animation: fadeIn 0.5s ease-out; }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body class="bg-white text-black min-h-screen flex flex-col" x-data="excelApp()" x-init="initData({{ $sheets }})" x-cloak>
    
    <!-- Top Header -->
    <header class="border-b-4 border-black px-8 py-6 flex justify-between items-center bg-white sticky top-0 z-50">
        <div class="flex items-center gap-6">
            <div class="w-12 h-12 bg-black text-white flex items-center justify-center font-bold text-2xl border-2 border-black rotate-3">A</div>
            <div>
                <h1 class="font-display font-bold tracking-tighter text-3xl uppercase leading-none">Abi Raja Wari</h1>
                <p class="text-[10px] font-bold tracking-[0.3em] uppercase opacity-40 mt-1">Multi-Sheet Management System</p>
            </div>
        </div>
        <div class="flex items-center gap-8 text-xs font-bold tracking-widest uppercase">
            <div class="flex items-center gap-2">
                <span class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></span>
                <span>System Online</span>
            </div>
            <button @click="saveCurrentSheet()" class="btn-black px-6 py-3 border-2 border-black flex items-center gap-3">
                <template x-if="!saving">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
                </template>
                <template x-if="saving">
                    <svg class="animate-spin" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M21 12a9 9 0 1 1-6.219-8.56"/></svg>
                </template>
                <span x-text="saving ? 'Menyimpan...' : 'Simpan Sheet'"></span>
            </button>
        </div>
    </header>

    <div class="flex flex-1">
        <!-- Sidebar Navigation -->
        <aside class="w-72 border-r-4 border-black bg-white flex flex-col p-6 gap-8">
            <div>
                <h2 class="text-[10px] font-bold tracking-[0.2em] uppercase opacity-40 mb-4">Navigasi Sheet</h2>
                <div class="flex flex-col gap-2">
                    <template x-for="(sheet, index) in sheets" :key="sheet.id">
                        <button 
                            @click="activeSheetIndex = index"
                            :class="activeSheetIndex === index ? 'bg-black text-white' : 'hover:bg-gray-100'"
                            class="w-full text-left px-4 py-3 border-2 border-black font-bold text-sm transition-all flex justify-between items-center group"
                        >
                            <span x-text="sheet.name"></span>
                            <span x-show="activeSheetIndex === index" class="w-1.5 h-1.5 bg-white rounded-full"></span>
                        </button>
                    </template>
                </div>
            </div>

            <div class="mt-auto">
                <button @click="createNewSheet()" class="w-full py-4 border-2 border-black border-dashed font-bold text-xs uppercase hover:bg-black hover:text-white transition-all flex items-center justify-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                    Sheet Baru
                </button>
            </div>
        </aside>

        <!-- Main Workspace -->
        <main class="flex-1 flex flex-col bg-[#fdfdfd]">
            <!-- Toolbar -->
            <div class="px-8 py-6 flex justify-between items-center border-b-2 border-black">
                <div class="flex gap-3">
                    <button @click="addRow()" class="px-6 py-2 border-2 border-black font-bold text-xs uppercase hover:bg-black hover:text-white transition-all flex items-center gap-2">
                        + Tambah Baris
                    </button>
                    <button @click="addColumn()" class="px-6 py-2 border-2 border-black font-bold text-xs uppercase hover:bg-black hover:text-white transition-all flex items-center gap-2">
                        + Tambah Kolom
                    </button>
                </div>
                <div class="relative">
                    <input type="text" x-model="search" placeholder="Cari data..." class="pl-10 pr-4 py-2 border-2 border-black text-xs font-bold outline-none focus:bg-black focus:text-white transition-all w-64">
                    <svg class="absolute left-3 top-1/2 -translate-y-1/2 opacity-40" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
                </div>
            </div>

            <!-- Table Container -->
            <div class="flex-1 overflow-auto p-8 fade-in">
                <div class="bg-white border-4 border-black shadow-premium">
                    <table class="w-full text-left border-collapse min-w-max">
                        <thead>
                            <tr class="bg-black text-white">
                                <th class="px-4 py-4 w-16 text-center border-r border-white/20 text-[10px] font-bold">IDX</th>
                                <template x-for="(header, hIndex) in currentSheet().data.headers" :key="hIndex">
                                    <th class="px-6 py-4 text-[11px] uppercase tracking-widest font-black border-r border-white/20 relative group">
                                        <div class="flex justify-between items-center">
                                            <span x-text="header"></span>
                                            <button @click="removeColumn(hIndex)" class="opacity-0 group-hover:opacity-100 text-red-500 hover:scale-125 transition-all">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                                            </button>
                                        </div>
                                    </th>
                                </template>
                                <th class="px-6 py-4 w-20 text-center font-black">AKSI</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-black">
                            <template x-for="(row, rIndex) in filteredRows()" :key="rIndex">
                                <tr class="hover:bg-black/[0.02] group transition-all">
                                    <td class="px-4 py-4 text-center bg-black/5 font-black text-[10px]" x-text="rIndex + 1"></td>
                                    <template x-for="(header, hIndex) in currentSheet().data.headers" :key="hIndex">
                                        <td class="px-0 py-0 border-r border-black/10">
                                            <input 
                                                type="text" 
                                                x-model="row[header]" 
                                                @change="hasChanges = true"
                                                class="w-full h-full px-6 py-4 text-sm font-medium bg-transparent outline-none focus:bg-black focus:text-white transition-all"
                                            >
                                        </td>
                                    </template>
                                    <td class="px-4 py-4 text-center">
                                        <button @click="deleteRow(rIndex)" class="text-black/20 hover:text-red-600 transition-all opacity-0 group-hover:opacity-100 scale-125">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/></svg>
                                        </button>
                                    </td>
                                </tr>
                            </template>
                            
                            <!-- Empty State -->
                            <template x-if="filteredRows().length === 0">
                                <tr>
                                    <td :colspan="currentSheet().data.headers.length + 2" class="px-6 py-32 text-center">
                                        <div class="flex flex-col items-center gap-4 opacity-20">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round"><path d="M14.5 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7.5L14.5 2z"/><polyline points="14 2 14 8 20 8"/><line x1="8" y1="13" x2="16" y2="13"/><line x1="8" y1="17" x2="14" y2="17"/><line x1="8" y1="9" x2="10" y2="9"/></svg>
                                            <p class="font-display font-bold text-xl uppercase tracking-widest italic">Belum ada data di sheet ini</p>
                                        </div>
                                    </td>
                                </tr>
                            </template>
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>

    <!-- Toast Notification -->
    <div 
        x-show="toast.show" 
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 translate-y-10"
        x-transition:enter-end="opacity-100 translate-y-0"
        class="fixed bottom-8 right-8 z-[100]"
    >
        <div class="bg-black text-white px-8 py-4 border-2 border-black shadow-premium flex items-center gap-4">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" class="text-green-400"><polyline points="20 6 9 17 4 12"/></svg>
            <span class="font-bold text-sm uppercase tracking-wider" x-text="toast.message"></span>
        </div>
    </div>

    <script>
        function excelApp() {
            return {
                sheets: [],
                activeSheetIndex: 0,
                search: '',
                saving: false,
                hasChanges: false,
                toast: { show: false, message: '' },

                initData(data) {
                    this.sheets = data;
                },

                currentSheet() {
                    return this.sheets[this.activeSheetIndex] || { data: { headers: [], rows: [] } };
                },

                filteredRows() {
                    if (!this.currentSheet().data) return [];
                    if (!this.search) return this.currentSheet().data.rows;
                    return this.currentSheet().data.rows.filter(row => {
                        return Object.values(row).some(val => 
                            String(val).toLowerCase().includes(this.search.toLowerCase())
                        );
                    });
                },

                addRow() {
                    let newRow = {};
                    this.currentSheet().data.headers.forEach(h => {
                        newRow[h] = '';
                    });
                    this.currentSheet().data.rows.push(newRow);
                    this.hasChanges = true;
                },

                deleteRow(index) {
                    if(confirm('Hapus baris data ini?')) {
                        this.currentSheet().data.rows.splice(index, 1);
                        this.hasChanges = true;
                    }
                },

                addColumn() {
                    let colName = prompt("Masukkan Nama Kolom Baru:");
                    if (colName) {
                        if (this.currentSheet().data.headers.includes(colName)) {
                            return alert("Nama kolom sudah ada!");
                        }
                        this.currentSheet().data.headers.push(colName);
                        this.currentSheet().data.rows.forEach(row => {
                            row[colName] = '';
                        });
                        this.hasChanges = true;
                    }
                },

                removeColumn(hIndex) {
                    if(confirm('Hapus kolom ini beserta semua datanya?')) {
                        let colName = this.currentSheet().data.headers[hIndex];
                        this.currentSheet().data.headers.splice(hIndex, 1);
                        this.currentSheet().data.rows.forEach(row => {
                            delete row[colName];
                        });
                        this.hasChanges = true;
                    }
                },

                async createNewSheet() {
                    let name = prompt("Nama Sheet Baru:");
                    if (!name) return;

                    const response = await fetch('/admin/sheets', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({ name: name, headers: ['Nama', 'Detail'] })
                    });
                    const result = await response.json();
                    if (result.success) {
                        this.sheets.push(result.sheet);
                        this.activeSheetIndex = this.sheets.length - 1;
                        this.showToast('Sheet baru dibuat!');
                    }
                },

                async saveCurrentSheet() {
                    this.saving = true;
                    const sheet = this.currentSheet();
                    try {
                        const response = await fetch(`/admin/sheets/${sheet.id}`, {
                            method: 'PUT',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({ data: sheet.data })
                        });
                        const result = await response.json();
                        if (result.success) {
                            this.showToast('Data berhasil disimpan');
                            this.hasChanges = false;
                        }
                    } catch (e) {
                        alert('Gagal menyimpan data');
                    } finally {
                        this.saving = false;
                    }
                },

                showToast(msg) {
                    this.toast.message = msg;
                    this.toast.show = true;
                    setTimeout(() => { this.toast.show = false; }, 3000);
                }
            }
        }
    </script>
</body>
</html>
