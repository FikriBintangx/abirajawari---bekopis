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
        :root {
            --bg-main: #ffffff;
            --bg-secondary: #f9f9f9;
            --text-main: #000000;
            --accent-color: #000000;
            --accent-text: #ffffff;
            --border-color: #000000;
            --row-hover: rgba(0, 0, 0, 0.03);
            --shadow-color: #000000;
        }

        body { 
            font-family: 'Inter', sans-serif; 
            background-color: var(--bg-main);
            color: var(--text-main);
            transition: background-color 0.3s ease, color 0.3s ease;
        }
        
        .font-display { font-family: 'Outfit', sans-serif; }
        
        .excel-table th, .excel-table td { 
            border: 1px solid var(--border-color); 
        }
        
        .shadow-premium { 
            box-shadow: 8px 8px 0px 0px var(--shadow-color); 
        }
        
        .btn-black { 
            background: var(--accent-color); 
            color: var(--accent-text); 
            transition: all 0.2s ease;
        }
        
        .btn-black:hover { 
            transform: translate(-2px, -2px);
            box-shadow: 4px 4px 0px 0px rgba(0,0,0,0.2);
        }
        
        .btn-black:active {
            transform: translate(0, 0);
        }

        .hide-scrollbar::-webkit-scrollbar { display: none; }
        .hide-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }

        [x-cloak] { display: none !important; }
        
        .fade-in { animation: fadeIn 0.5s ease-out; }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* Input color custom styling */
        input[type="color"] {
            -webkit-appearance: none;
            border: 2px solid var(--border-color);
            width: 40px;
            height: 40px;
            cursor: pointer;
            background: none;
            padding: 0;
        }
        input[type="color"]::-webkit-color-swatch-wrapper { padding: 0; }
        input[type="color"]::-webkit-color-swatch { border: none; }
    </style>
</head>
<body class="min-h-screen flex flex-col overflow-hidden" x-data="excelApp()" x-init="initData({{ $sheets }})" x-cloak>
    
    <!-- Top Header -->
    <header class="border-b-4 border-black px-8 py-4 flex justify-between items-center bg-white sticky top-0 z-50 shrink-0" style="border-color: var(--border-color); background-color: var(--bg-main);">
        <div class="flex items-center gap-6">
            <div class="w-10 h-10 bg-black text-white flex items-center justify-center font-bold text-xl border-2 border-black rotate-3" style="background-color: var(--accent-color); color: var(--accent-text); border-color: var(--border-color);">A</div>
            <div>
                <h1 class="font-display font-bold tracking-tighter text-2xl uppercase leading-none" style="color: var(--text-main);">Abi Raja Wari</h1>
                <p class="text-[9px] font-bold tracking-[0.3em] uppercase opacity-40 mt-1">Control Center</p>
            </div>
        </div>
        <div class="flex items-center gap-4 text-xs font-bold tracking-widest uppercase">
            <!-- Settings Toggle -->
            <button @click="showSettings = true" class="p-3 border-2 border-black hover:bg-black hover:text-white transition-all" style="border-color: var(--border-color);">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12.22 2h-.44a2 2 0 0 0-2 2v.18a2 2 0 0 1-1 1.73l-.43.25a2 2 0 0 1-2 0l-.15-.08a2 2 0 0 0-2.73.73l-.22.38a2 2 0 0 0 .73 2.73l.15.1a2 2 0 0 1 1 1.72v.51a2 2 0 0 1-1 1.74l-.15.09a2 2 0 0 0-.73 2.73l.22.38a2 2 0 0 0 2.73.73l.15-.08a2 2 0 0 1 2 0l.43.25a2 2 0 0 1 1 1.73V20a2 2 0 0 0 2 2h.44a2 2 0 0 0 2-2v-.18a2 2 0 0 1 1-1.73l.43-.25a2 2 0 0 1 2 0l.15.08a2 2 0 0 0 2.73-.73l.22-.39a2 2 0 0 0-.73-2.73l-.15-.08a2 2 0 0 1-1-1.74v-.5a2 2 0 0 1 1-1.74l.15-.09a2 2 0 0 0 .73-2.73l-.22-.38a2 2 0 0 0-2.73-.73l-.15.08a2 2 0 0 1-2 0l-.43-.25a2 2 0 0 1-1-1.73V4a2 2 0 0 0-2-2z"/><circle cx="12" cy="12" r="3"/></svg>
            </button>

            <button @click="saveCurrentSheet()" class="btn-black px-5 py-2.5 border-2 border-black flex items-center gap-3 text-[10px]" style="background-color: var(--accent-color); color: var(--accent-text); border-color: var(--border-color);">
                <template x-if="!saving">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
                </template>
                <template x-if="saving">
                    <svg class="animate-spin" xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M21 12a9 9 0 1 1-6.219-8.56"/></svg>
                </template>
                <span x-text="saving ? 'Menyimpan...' : 'Simpan Data'"></span>
            </button>
        </div>
    </header>

    <!-- Toolbar & Main Area -->
    <main class="flex-1 flex flex-col overflow-hidden">
        <!-- Toolbar -->
        <div class="px-8 py-4 flex justify-between items-center border-b-2 border-black shrink-0" style="border-color: var(--border-color);">
            <div class="flex gap-2">
                <button @click="addRow()" class="px-5 py-2 border-2 border-black font-bold text-[10px] uppercase hover:bg-black hover:text-white transition-all flex items-center gap-2" style="border-color: var(--border-color);">
                    + Baris
                </button>
                <button @click="addColumn()" class="px-5 py-2 border-2 border-black font-bold text-[10px] uppercase hover:bg-black hover:text-white transition-all flex items-center gap-2" style="border-color: var(--border-color);">
                    + Kolom
                </button>
                <div class="w-[2px] bg-black/10 mx-2"></div>
                <button @click="createNewSheet()" class="px-5 py-2 border-2 border-black border-dashed font-bold text-[10px] uppercase hover:bg-black hover:text-white transition-all flex items-center gap-2" style="border-color: var(--border-color);">
                    + Sheet Baru
                </button>
            </div>
            <div class="relative">
                <input type="text" x-model="search" placeholder="Cari data..." class="pl-9 pr-4 py-2 border-2 border-black text-[10px] font-bold outline-none focus:bg-black focus:text-white transition-all w-56" style="border-color: var(--border-color);">
                <svg class="absolute left-3 top-1/2 -translate-y-1/2 opacity-40" xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
            </div>
        </div>

        <!-- Table Container -->
        <div class="flex-1 overflow-auto p-6 fade-in" style="background-color: var(--bg-secondary);">
            <div class="bg-white border-4 border-black shadow-premium" style="border-color: var(--border-color);">
                <table class="w-full text-left border-collapse min-w-max">
                    <thead class="sticky top-0 z-10">
                        <tr class="bg-black text-white" style="background-color: var(--accent-color); color: var(--accent-text);">
                            <th class="px-4 py-3 w-14 text-center border-r border-white/20 text-[9px] font-black">#</th>
                            <template x-for="(header, hIndex) in currentSheet().data.headers" :key="hIndex">
                                <th class="px-5 py-3 text-[10px] uppercase tracking-widest font-black border-r border-white/20 relative group">
                                    <div class="flex justify-between items-center">
                                        <span x-text="header"></span>
                                        <button @click="removeColumn(hIndex)" class="opacity-0 group-hover:opacity-100 text-red-500 hover:scale-125 transition-all">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                                        </button>
                                    </div>
                                </th>
                            </template>
                            <th class="px-4 py-3 w-16 text-center font-black text-[9px]">AKSI</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-black" style="border-color: var(--border-color);">
                        <template x-for="(row, rIndex) in filteredRows()" :key="rIndex">
                            <tr class="hover:bg-black/[0.02] group transition-all" :style="`&:hover { background-color: var(--row-hover); }`">
                                <td class="px-3 py-3 text-center bg-black/5 font-black text-[9px]" style="background-color: rgba(0,0,0,0.05); color: var(--text-main);" x-text="rIndex + 1"></td>
                                <template x-for="(header, hIndex) in currentSheet().data.headers" :key="hIndex">
                                    <td class="px-0 py-0 border-r" style="border-color: var(--border-color);">
                                        <input 
                                            type="text" 
                                            x-model="row[header]" 
                                            @change="hasChanges = true"
                                            class="w-full h-full px-5 py-3 text-xs font-medium bg-transparent outline-none focus:bg-black focus:text-white transition-all"
                                            style="color: var(--text-main);"
                                        >
                                    </td>
                                </template>
                                <td class="px-3 py-3 text-center">
                                    <button @click="deleteRow(rIndex)" class="text-black/20 hover:text-red-600 transition-all opacity-0 group-hover:opacity-100 scale-110">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/></svg>
                                    </button>
                                </td>
                            </tr>
                        </template>
                        
                        <!-- Empty State -->
                        <template x-if="filteredRows().length === 0">
                            <tr>
                                <td :colspan="currentSheet().data.headers.length + 2" class="px-6 py-24 text-center">
                                    <div class="flex flex-col items-center gap-4 opacity-10">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M14.5 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7.5L14.5 2z"/><polyline points="14 2 14 8 20 8"/><line x1="8" y1="13" x2="16" y2="13"/><line x1="8" y1="17" x2="14" y2="17"/><line x1="8" y1="9" x2="10" y2="9"/></svg>
                                        <p class="font-display font-bold text-lg uppercase tracking-widest italic">Data Kosong</p>
                                    </div>
                                </td>
                            </tr>
                        </template>
                    </tbody>
                </table>
            </div>
        </div>
    </main>

    <!-- Bottom Sheet Tabs (FOOTER) -->
    <footer class="border-t-4 border-black bg-white flex items-center shrink-0 h-14" style="border-color: var(--border-color); background-color: var(--bg-main);">
        <div class="flex flex-1 overflow-x-auto hide-scrollbar scroll-smooth h-full">
            <template x-for="(sheet, index) in sheets" :key="sheet.id">
                <div class="flex h-full shrink-0 group">
                    <button 
                        @click="activeSheetIndex = index"
                        :class="activeSheetIndex === index ? 'bg-black text-white px-8' : 'hover:bg-black/5 px-6'"
                        class="h-full border-r-2 border-black flex items-center gap-3 transition-all cursor-pointer relative"
                        style="border-color: var(--border-color); border-right-width: 2px;"
                        :style="activeSheetIndex === index ? 'background-color: var(--accent-color); color: var(--accent-text);' : 'color: var(--text-main);'"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" :class="activeSheetIndex === index ? 'opacity-100' : 'opacity-40'"><path d="M14.5 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7.5L14.5 2z"/><polyline points="14 2 14 8 20 8"/></svg>
                        <span class="text-[10px] font-black uppercase tracking-widest whitespace-nowrap" x-text="sheet.name"></span>
                        
                        <div @click.stop="deleteSheet(sheet.id, index)" class="ml-2 p-1 hover:bg-red-500 rounded text-red-500 hover:text-white transition-all" x-show="sheets.length > 1">
                            <svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                        </div>
                    </button>
                </div>
            </template>
        </div>
        <div class="px-6 border-l-2 border-black h-full flex items-center bg-black/5" style="border-color: var(--border-color);">
            <p class="text-[9px] font-black uppercase tracking-tighter opacity-30 whitespace-nowrap">ABIRAJAVARI &copy; 2026</p>
        </div>
    </footer>

    <!-- Settings Modal -->
    <div 
        x-show="showSettings" 
        class="fixed inset-0 z-[200] flex items-center justify-center p-6 bg-black/40 backdrop-blur-sm"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95"
    >
        <div class="bg-white border-4 border-black w-full max-w-md shadow-premium p-8 relative" @click.away="showSettings = false">
            <button @click="showSettings = false" class="absolute top-4 right-4 p-2 hover:bg-black hover:text-white transition-all border-2 border-black">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
            </button>
            
            <h2 class="font-display font-black text-2xl uppercase tracking-tighter mb-8 italic underline decoration-4 underline-offset-4">Theme Settings</h2>
            
            <div class="grid grid-cols-2 gap-6">
                <div class="flex flex-col gap-2">
                    <label class="text-[10px] font-black uppercase tracking-widest opacity-60">Background Utama</label>
                    <input type="color" x-model="theme.bgMain" @input="updateTheme()">
                </div>
                <div class="flex flex-col gap-2">
                    <label class="text-[10px] font-black uppercase tracking-widest opacity-60">Background Table</label>
                    <input type="color" x-model="theme.bgSecondary" @input="updateTheme()">
                </div>
                <div class="flex flex-col gap-2">
                    <label class="text-[10px] font-black uppercase tracking-widest opacity-60">Warna Teks</label>
                    <input type="color" x-model="theme.textMain" @input="updateTheme()">
                </div>
                <div class="flex flex-col gap-2">
                    <label class="text-[10px] font-black uppercase tracking-widest opacity-60">Warna Aksen (Header)</label>
                    <input type="color" x-model="theme.accentColor" @input="updateTheme()">
                </div>
                <div class="flex flex-col gap-2">
                    <label class="text-[10px] font-black uppercase tracking-widest opacity-60">Warna Teks Aksen</label>
                    <input type="color" x-model="theme.accentText" @input="updateTheme()">
                </div>
                <div class="flex flex-col gap-2">
                    <label class="text-[10px] font-black uppercase tracking-widest opacity-60">Warna Garis (Border)</label>
                    <input type="color" x-model="theme.borderColor" @input="updateTheme()">
                </div>
            </div>

            <div class="mt-10 pt-6 border-t-2 border-black flex gap-3">
                <button @click="resetTheme()" class="flex-1 py-3 border-2 border-black font-bold text-xs uppercase hover:bg-red-500 hover:text-white transition-all">
                    Reset Default
                </button>
                <button @click="showSettings = false" class="flex-1 py-3 bg-black text-white font-bold text-xs uppercase hover:bg-gray-800 transition-all">
                    Tutup
                </button>
            </div>
        </div>
    </div>

    <!-- Toast Notification -->
    <div 
        x-show="toast.show" 
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 translate-y-10"
        x-transition:enter-end="opacity-100 translate-y-0"
        class="fixed bottom-20 right-8 z-[100]"
    >
        <div class="bg-black text-white px-6 py-3 border-2 border-black shadow-premium flex items-center gap-4" style="background-color: var(--accent-color); color: var(--accent-text); border-color: var(--border-color);">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" class="text-green-400"><polyline points="20 6 9 17 4 12"/></svg>
            <span class="font-bold text-[10px] uppercase tracking-wider" x-text="toast.message"></span>
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
                showSettings: false,
                
                // Theme settings
                theme: {
                    bgMain: '#ffffff',
                    bgSecondary: '#f9f9f9',
                    textMain: '#000000',
                    accentColor: '#000000',
                    accentText: '#ffffff',
                    borderColor: '#000000',
                    shadowColor: '#000000'
                },

                initData(data) {
                    this.sheets = data;
                    
                    // Load saved theme from localStorage
                    const savedTheme = localStorage.getItem('abiraja_theme');
                    if (savedTheme) {
                        this.theme = JSON.parse(savedTheme);
                        this.updateTheme();
                    }
                },

                updateTheme() {
                    const root = document.documentElement;
                    root.style.setProperty('--bg-main', this.theme.bgMain);
                    root.style.setProperty('--bg-secondary', this.theme.bgSecondary);
                    root.style.setProperty('--text-main', this.theme.textMain);
                    root.style.setProperty('--accent-color', this.theme.accentColor);
                    root.style.setProperty('--accent-text', this.theme.accentText);
                    root.style.setProperty('--border-color', this.theme.borderColor);
                    root.style.setProperty('--shadow-color', this.theme.borderColor);
                    
                    // Save to localStorage
                    localStorage.setItem('abiraja_theme', JSON.stringify(this.theme));
                },

                resetTheme() {
                    this.theme = {
                        bgMain: '#ffffff',
                        bgSecondary: '#f9f9f9',
                        textMain: '#000000',
                        accentColor: '#000000',
                        accentText: '#ffffff',
                        borderColor: '#000000',
                        shadowColor: '#000000'
                    };
                    this.updateTheme();
                    this.showToast('Tema direset ke default');
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

                async deleteSheet(id, index) {
                    if(!confirm(`Hapus sheet "${this.sheets[index].name}" permanen?`)) return;

                    const response = await fetch(`/admin/sheets/${id}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        }
                    });
                    const result = await response.json();
                    if (result.success) {
                        this.sheets.splice(index, 1);
                        if (this.activeSheetIndex >= this.sheets.length) {
                            this.activeSheetIndex = Math.max(0, this.sheets.length - 1);
                        }
                        this.showToast('Sheet dihapus');
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
