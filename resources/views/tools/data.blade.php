{{-- resources/views/tools/data.blade.php --}}
@extends('layouts.app')
@section('title', 'JSON & Data Processing Tools — Formatter, CSV & SQL')
@section('meta_description', 'Process data safely in your browser. JSON formatter, JSON to CSV converter, SQL prettifier, and CSV viewer. No data is sent to our servers.')

@section('head')
<link rel="canonical" href="{{ route('tools.data') }}">
<script type="application/ld+json">
{
    "@@context": "https://schema.org",
    "@@type": "BreadcrumbList",
    "itemListElement": [
        { "@@type": "ListItem", "position": 1, "name": "Home", "item": "{{ route('home') }}" },
        { "@@type": "ListItem", "position": 2, "name": "Office Tools", "item": "{{ route('tools.index') }}" },
        { "@@type": "ListItem", "position": 3, "name": "Data Tools", "item": "{{ route('tools.data') }}" }
    ]
}
</script>
@endsection

@section('content')

<style>
.tool-panel{background:var(--card-bg);border:1px solid var(--card-border);box-shadow:var(--card-shadow);border-radius:var(--radius-lg);overflow:hidden;}
.tool-panel-header{padding:1.25rem 1.5rem;border-bottom:1px solid var(--card-border);background:var(--surface-2);}
.tool-panel-title{font-family:var(--font-display);font-size:1rem;font-weight:700;color:var(--ink);margin:0 0 .2rem;display:flex;align-items:center;gap:.5rem;}
.tool-panel-desc{font-size:.78rem;color:var(--muted);margin:0;}
.tool-body{padding:1.5rem;}
.btn-tool{display:inline-flex;align-items:center;gap:.4rem;font-size:.82rem;font-weight:700;padding:.58rem 1.2rem;border-radius:var(--radius-sm);border:none;cursor:pointer;transition:opacity .15s,transform .15s;}
.btn-tool:hover:not(:disabled){opacity:.88;transform:translateY(-1px);}
.btn-tool:disabled{opacity:.5;cursor:not-allowed;transform:none;}
.btn-amber{background:#d97706;color:#fff;box-shadow:0 3px 10px rgba(217,119,6,.3);}
.btn-outline{background:transparent;border:1.5px solid var(--card-border);color:var(--muted);}
.btn-outline:hover:not(:disabled){border-color:#d97706;color:#d97706;background:rgba(217,119,6,.05);transform:none;opacity:1;}
.mono-area{font-family:'Fira Code',monospace;font-size:.82rem;line-height:1.65;background:#0d1117 !important;color:#e6edf3 !important;border:1px solid rgba(255,255,255,.08) !important;border-radius:var(--radius-sm) !important;resize:vertical;}
.dropzone{border:2px dashed rgba(217,119,6,.25);border-radius:var(--radius);background:rgba(217,119,6,.02);min-height:160px;display:flex;flex-direction:column;justify-content:center;align-items:center;cursor:pointer;transition:all .15s;text-align:center;padding:2rem;}
.dropzone:hover,.dropzone.dragover{border-color:#d97706;background:rgba(217,119,6,.05);}
.diff-removed{background:rgba(239,68,68,.08);color:#ef4444;border-left:3px solid #ef4444;}
.diff-added{background:rgba(22,163,74,.08);color:#16a34a;border-left:3px solid #16a34a;}
</style>

<div class="container-fluid px-lg-5 py-2"
     x-data="{ activeTool: 'csv-viewer' }">

    <div class="row g-4">
        {{-- Sidebar --}}
        <div class="col-lg-3 reveal reveal-left">
            @include('tools._sidebar', ['active' => 'data'])

            <div class="mt-3" style="background:var(--card-bg);border:1px solid var(--card-border);border-radius:var(--radius-lg);padding:1rem;box-shadow:var(--card-shadow);">
                <p class="mb-2" style="font-size:.65rem;font-weight:700;letter-spacing:.1em;text-transform:uppercase;color:var(--muted);padding:.25rem .5rem;">Data Tools</p>
                @php
                $tools = [
                    ['id'=>'csv-viewer',   'icon'=>'bi-table',              'label'=>'CSV Viewer'],
                    ['id'=>'json-to-csv',  'icon'=>'bi-filetype-csv',       'label'=>'JSON → CSV'],
                    ['id'=>'csv-to-json',  'icon'=>'bi-filetype-json',      'label'=>'CSV → JSON'],
                    ['id'=>'json-format',  'icon'=>'bi-braces-asterisk',    'label'=>'JSON Formatter'],
                    ['id'=>'json-diff',    'icon'=>'bi-file-diff',          'label'=>'JSON Diff'],
                    ['id'=>'sql-format',   'icon'=>'bi-database-fill-gear', 'label'=>'SQL Formatter'],
                    ['id'=>'fake-data',    'icon'=>'bi-magic',              'label'=>'Fake Data Gen'],
                ];
                @endphp
                @foreach($tools as $t)
                <button @click="activeTool = '{{ $t['id'] }}'"
                        class="d-flex align-items-center gap-2 w-100 px-3 py-2 rounded mb-1 text-start border-0"
                        style="background:none;font-size:.8rem;font-weight:600;color:var(--muted);cursor:pointer;transition:all .15s;"
                        x-bind:style="activeTool==='{{ $t['id'] }}'?'background:rgba(217,119,6,.1);color:#d97706;':''">
                    <i class="bi {{ $t['icon'] }}"></i> {{ $t['label'] }}
                </button>
                @endforeach
            </div>
        </div>

        <div class="col-lg-9">

            {{-- 1. CSV Viewer --}}
            <div x-show="activeTool === 'csv-viewer'" class="tool-panel"
                 x-data="{
                     headers:[],rows:[],search:'',isDragging:false,sortCol:null,sortDesc:false,
                     async loadPapaParse() {
                         if (window.Papa) return;
                         const script = document.createElement('script');
                         script.src = 'https://cdnjs.cloudflare.com/ajax/libs/PapaParse/5.4.1/papaparse.min.js';
                         document.head.appendChild(script);
                         await new Promise(r => script.onload = r);
                     },
                     async parseCSV(f){
                         await this.loadPapaParse();
                         Papa.parse(f,{header:true,skipEmptyLines:true,complete:(r)=>{if(r.data.length>0){this.headers=Object.keys(r.data[0]);this.rows=r.data;}}});
                     },
                     get filteredRows(){
                         let r=this.rows;
                         if(this.search.trim()){const s=this.search.toLowerCase();r=r.filter(row=>Object.values(row).some(v=>String(v).toLowerCase().includes(s)));}
                         if(this.sortCol!==null){r=[...r].sort((a,b)=>{const va=String(a[this.sortCol]),vb=String(b[this.sortCol]);return this.sortDesc?vb.localeCompare(va,undefined,{numeric:true}):va.localeCompare(vb,undefined,{numeric:true});});}
                         return r;
                     },
                     setSort(h){this.sortCol===h?this.sortDesc=!this.sortDesc:(this.sortCol=h,this.sortDesc=false);}
                 }">
                <div class="tool-panel-header">
                    <h3 class="tool-panel-title"><i class="bi bi-table" style="color:#d97706;"></i> CSV Table Viewer</h3>
                    <p class="tool-panel-desc">Load any CSV spreadsheet — search, sort, and explore data instantly.</p>
                </div>
                <div class="tool-body">
                    <div class="dropzone mb-4" x-show="rows.length===0"
                         :class="isDragging?'dragover':''"
                         @dragover.prevent="isDragging=true" @dragleave.prevent="isDragging=false"
                         @drop.prevent="isDragging=false;parseCSV($event.dataTransfer.files[0])"
                         @click="$refs.csvInput.click()">
                        <input type="file" x-ref="csvInput" class="d-none" accept=".csv" @change="parseCSV($event.target.files[0])">
                        <div style="font-size:2rem;color:#d97706;margin-bottom:.6rem;"><i class="bi bi-filetype-csv"></i></div>
                        <p style="font-weight:600;font-size:.88rem;color:var(--ink);margin-bottom:.2rem;">Drop your CSV file here</p>
                        <p style="font-size:.75rem;color:var(--muted);margin:0;">or click to browse</p>
                    </div>
                    <template x-if="rows.length > 0">
                        <div>
                            <div class="d-flex gap-3 align-items-center mb-3">
                                <div class="input-group" style="max-width:280px;">
                                    <span class="input-group-text" style="background:var(--surface-2); border-color:var(--card-border);"><i class="bi bi-search" style="color:var(--muted); font-size:.8rem;"></i></span>
                                    <input type="text" class="form-control" placeholder="Search…"
                                           style="background:var(--surface-2); border-color:var(--card-border); color:var(--ink); font-size:.82rem;"
                                           x-model="search">
                                </div>
                                <button class="btn-tool btn-outline ms-auto" @click="rows=[];headers=[];search='';sortCol=null;">
                                    <i class="bi bi-arrow-left"></i> New file
                                </button>
                            </div>
                            <div class="table-responsive border rounded" style="max-height:400px; border-color:var(--card-border) !important; background:var(--card-bg);">
                                <table class="table table-hover mb-0 align-middle" style="font-size:.8rem;">
                                    <thead class="sticky-top" style="background:var(--surface-2);">
                                        <tr>
                                            <template x-for="header in headers" :key="header">
                                                <th @click="setSort(header)" class="px-3 py-2 text-nowrap" style="cursor:pointer; font-size:.68rem; letter-spacing:.06em; text-transform:uppercase; color:var(--muted); border-bottom:1px solid var(--card-border);">
                                                    <span x-text="header"></span>
                                                    <i class="bi ms-1" :class="sortCol===header?(sortDesc?'bi-sort-down-alt':'bi-sort-up'):'bi-arrow-down-up'" style="opacity:.5;"></i>
                                                </th>
                                            </template>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <template x-for="(row,idx) in filteredRows" :key="idx">
                                            <tr>
                                                <template x-for="header in headers" :key="header">
                                                    <td class="px-3 py-2 text-truncate" style="max-width:180px; color:var(--ink);" x-text="row[header]"></td>
                                                </template>
                                            </tr>
                                        </template>
                                    </tbody>
                                </table>
                            </div>
                            <p class="small mt-2 text-end" style="color:var(--muted-light);">
                                <span x-text="filteredRows.length"></span> / <span x-text="rows.length"></span> rows
                            </p>
                        </div>
                    </template>
                </div>
            </div>

            {{-- 2. JSON to CSV --}}
            <div x-show="activeTool === 'json-to-csv'" class="tool-panel"
                 x-data='{
                     jsonText: "[{\n  \"name\": \"Alice\",\n  \"role\": \"Developer\",\n  \"city\": \"Mumbai\"\n}, {\n  \"name\": \"Bob\",\n  \"role\": \"Designer\",\n  \"city\": \"Delhi\"\n}]",
                     async loadPapaParse() {
                         if (window.Papa) return;
                         const script = document.createElement("script");
                         script.src = "https://cdnjs.cloudflare.com/ajax/libs/PapaParse/5.4.1/papaparse.min.js";
                         document.head.appendChild(script);
                         await new Promise(r => script.onload = r);
                     },
                     async downloadCSV(){
                         try{
                            await this.loadPapaParse();
                            const d=JSON.parse(this.jsonText);
                            if(!Array.isArray(d)){alert('Must be a JSON array.');return;}
                            const csv=Papa.unparse(d);
                            const blob=new Blob([csv],{type:'text/csv;charset=utf-8;'});
                            const a=document.createElement("a");a.href=URL.createObjectURL(blob);a.download="converted.csv";a.click();
                         }catch(e){alert("Invalid JSON Input. Please ensure it is a valid JSON array of objects.");}
                     }
                 }'>
                <div class="tool-panel-header">
                    <h3 class="tool-panel-title"><i class="bi bi-filetype-csv" style="color:#d97706;"></i> JSON → CSV Converter</h3>
                    <p class="tool-panel-desc">Paste a JSON array and download it as a spreadsheet-ready CSV.</p>
                </div>
                <div class="tool-body">
                    <label class="small fw-bold mb-2 d-block" style="color:var(--muted); text-transform:uppercase; font-size:.65rem; letter-spacing:.08em;">JSON Input (Array of Objects)</label>
                    <textarea class="mono-area form-control p-3 mb-4" rows="12" x-model="jsonText"></textarea>
                    <div class="d-flex justify-content-between">
                        <button class="btn-tool btn-outline" @click="jsonText = ''"><i class="bi bi-trash"></i> Clear</button>
                        <button class="btn-tool btn-amber" @click="downloadCSV">
                            <i class="bi bi-download"></i> Convert & Download
                        </button>
                    </div>
                </div>
            </div>

            {{-- 3. CSV to JSON --}}
            <div x-show="activeTool === 'csv-to-json'" class="tool-panel"
                 x-data="{
                     csvText: 'name,role,city\nAlice,Developer,Mumbai\nBob,Designer,Delhi',
                     jsonOutput: '',
                     async loadPapaParse() {
                         if (window.Papa) return;
                         const script = document.createElement('script');
                         script.src = 'https://cdnjs.cloudflare.com/ajax/libs/PapaParse/5.4.1/papaparse.min.js';
                         document.head.appendChild(script);
                         await new Promise(r => script.onload = r);
                     },
                     async convertCSVtoJSON(){
                         try{
                            await this.loadPapaParse();
                            Papa.parse(this.csvText, {
                                header: true,
                                skipEmptyLines: true,
                                complete: (results) => {
                                    this.jsonOutput = JSON.stringify(results.data, null, 2);
                                }
                            });
                         }catch(e){alert('Invalid CSV: '+e.message);}
                     },
                     downloadJSON(){
                         if(!this.jsonOutput) return;
                         const blob=new Blob([this.jsonOutput],{type:'application/json;charset=utf-8;'});
                         const a=document.createElement('a');a.href=URL.createObjectURL(blob);a.download='converted.json';a.click();
                     }
                 }" x-init="convertCSVtoJSON()">
                <div class="tool-panel-header">
                    <h3 class="tool-panel-title"><i class="bi bi-filetype-json" style="color:#d97706;"></i> CSV → JSON Converter</h3>
                    <p class="tool-panel-desc">Paste CSV data and convert it into a formatted JSON array.</p>
                </div>
                <div class="tool-body">
                    <label class="small fw-bold mb-2 d-block" style="color:var(--muted); text-transform:uppercase; font-size:.65rem; letter-spacing:.08em;">CSV Input</label>
                    <textarea class="mono-area form-control p-3 mb-4" rows="12" x-model="csvText" @input="convertCSVtoJSON()"></textarea>
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <label class="small fw-bold m-0 d-block" style="color:var(--muted); text-transform:uppercase; font-size:.65rem; letter-spacing:.08em;">JSON Output</label>
                        <button class="btn btn-link btn-sm p-0 text-decoration-none" style="color:#d97706; font-size:.75rem;" @click="navigator.clipboard.writeText(jsonOutput)" x-show="jsonOutput">Copy JSON</button>
                    </div>
                    <textarea class="mono-area form-control p-3 mb-4" rows="12" x-model="jsonOutput" readonly></textarea>
                    <div class="d-flex justify-content-end">
                        <button class="btn-tool btn-amber" @click="downloadJSON()" :disabled="!jsonOutput"><i class="bi bi-download"></i> Download JSON</button>
                    </div>
                </div>
            </div>

            {{-- 4. JSON Formatter --}}
            <div x-show="activeTool === 'json-format'" class="tool-panel"
                 x-data='{
                     input: "{\n  \"user\": {\n    \"id\": 1,\n    \"name\": \"John Doe\",\n    \"active\": true\n  }\n}",
                     output: '',
                     format(indent) {
                         try {
                             const obj = JSON.parse(this.input);
                             this.output = JSON.stringify(obj, null, indent);
                         } catch(e) { this.output = "Invalid JSON: " + e.message; }
                     },
                     minify() {
                         try {
                             this.output = JSON.stringify(JSON.parse(this.input));
                         } catch(e) { this.output = "Invalid JSON: " + e.message; }
                     }
                 }'>
                <div class="tool-panel-header">
                    <h3 class="tool-panel-title"><i class="bi bi-braces-asterisk" style="color:#d97706;"></i> JSON Formatter</h3>
                    <p class="tool-panel-desc">Prettify, validate, and minify JSON code with custom indentation.</p>
                </div>
                <div class="tool-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="small fw-bold mb-2 d-block text-uppercase" style="color:var(--muted); font-size:.65rem;">JSON Input</label>
                            <textarea class="mono-area form-control p-3" rows="15" x-model="input"></textarea>
                        </div>
                        <div class="col-md-6">
                            <label class="small fw-bold mb-2 d-block text-uppercase" style="color:var(--muted); font-size:.65rem;">JSON Output</label>
                            <textarea class="mono-area form-control p-3" rows="15" x-model="output" readonly></textarea>
                        </div>
                    </div>
                    <div class="mt-4 d-flex gap-2">
                        <button class="btn-tool btn-amber" @click="format(2)">Prettify (2 Sp)</button>
                        <button class="btn-tool btn-amber" @click="format(4)">Prettify (4 Sp)</button>
                        <button class="btn-tool btn-outline" @click="minify()">Minify JSON</button>
                        <button class="btn-tool btn-outline ms-auto" @click="navigator.clipboard.writeText(output)" :disabled="!output"><i class="bi bi-clipboard"></i> Copy Result</button>
                    </div>
                </div>
            </div>

            {{-- 5. JSON Diff --}}
            <div x-show="activeTool === 'json-diff'" class="tool-panel"
                 x-data='{
                     jsonA: "{\n  \"id\": 1,\n  \"name\": \"Alice\"\n}",
                     jsonB: "{\n  \"id\": 1,\n  \"name\": \"Bob\",\n  \"age\": 25\n}",
                     diffLines: [],
                     compare() {
                         try {
                             const a = JSON.stringify(JSON.parse(this.jsonA), null, 2).split('\n');
                             const b = JSON.stringify(JSON.parse(this.jsonB), null, 2).split('\n');
                             const max = Math.max(a.length, b.length);
                             const d = [];
                             for(let i=0; i<max; i++) {
                                 const la = a[i] || '';
                                 const lb = b[i] || '';
                                 d.push({ status: la === lb ? "normal" : "changed", a: la, b: lb });
                             }
                             this.diffLines = d;
                         } catch(e) { alert("Invalid JSON in input: " + e.message); }
                     }
                 }' x-init="compare()">
                <div class="tool-panel-header">
                    <h3 class="tool-panel-title"><i class="bi bi-file-diff" style="color:#d97706;"></i> JSON Diff Viewer</h3>
                    <p class="tool-panel-desc">Compare two JSON objects side-by-side to highlight differences.</p>
                </div>
                <div class="tool-body">
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="small fw-bold mb-1 text-uppercase" style="color:var(--muted); font-size:.65rem;">JSON A</label>
                            <textarea class="mono-area form-control p-2" rows="8" x-model="jsonA" @input="compare()"></textarea>
                        </div>
                        <div class="col-md-6">
                            <label class="small fw-bold mb-1 text-uppercase" style="color:var(--muted); font-size:.65rem;">JSON B</label>
                            <textarea class="mono-area form-control p-2" rows="8" x-model="jsonB" @input="compare()"></textarea>
                        </div>
                    </div>
                    <div class="row g-2" style="font-size:.78rem; font-family:var(--font-mono);">
                        <div class="col-6">
                            <div class="border rounded overflow-auto p-2" style="max-height:280px; background:#0d1117;">
                                <template x-for="(line, idx) in diffLines" :key="'a-'+idx">
                                    <div class="px-2 py-0 rounded mb-1 text-nowrap" :class="line.status==='changed'?'diff-removed':''" style="min-height:1.2rem;" x-text="line.a || ' '"></div>
                                </template>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="border rounded overflow-auto p-2" style="max-height:280px; background:#0d1117;">
                                <template x-for="(line, idx) in diffLines" :key="'b-'+idx">
                                    <div class="px-2 py-0 rounded mb-1 text-nowrap" :class="line.status==='changed'?'diff-added':''" style="min-height:1.2rem;" x-text="line.b || ' '"></div>
                                </template>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- 6. SQL Formatter --}}
            <div x-show="activeTool === 'sql-format'" class="tool-panel"
                 x-data="{
                     sql: 'SELECT u.id, u.name, p.title FROM users u JOIN posts p ON u.id = p.user_id WHERE p.status = \'published\' ORDER BY p.created_at DESC',
                     formatted: '',
                     async loadSqlFormatter() {
                         if (window.sqlFormatter) return;
                         const script = document.createElement('script');
                         script.src = 'https://unpkg.com/sql-formatter@4.0.2/dist/sql-formatter.min.js';
                         document.head.appendChild(script);
                         await new Promise(r => script.onload = r);
                     },
                     async format() {
                         try {
                             await this.loadSqlFormatter();
                             this.formatted = window.sqlFormatter.format(this.sql, {
                                 language: 'sql',
                                 indent: '  ',
                                 uppercase: true
                             });
                         } catch(e) { this.formatted = '-- Error: ' + e.message; }
                     }
                 }" x-init="format()">
                <div class="tool-panel-header">
                    <h3 class="tool-panel-title"><i class="bi bi-database-fill-gear" style="color:#d97706;"></i> SQL Formatter</h3>
                    <p class="tool-panel-desc">Beautify messy SQL queries into readable, well-indented statements.</p>
                </div>
                <div class="tool-body">
                    <label class="small fw-bold mb-2 d-block text-uppercase" style="color:var(--muted); font-size:.65rem;">Raw SQL Query</label>
                    <textarea class="mono-area form-control p-3 mb-4" rows="8" x-model="sql" @input="format()"></textarea>
                    <label class="small fw-bold mb-2 d-block text-uppercase" style="color:var(--muted); font-size:.65rem;">Formatted Result</label>
                    <textarea class="mono-area form-control p-3" rows="12" x-model="formatted" readonly></textarea>
                    <div class="mt-3 d-flex justify-content-end">
                        <button class="btn-tool btn-outline" @click="navigator.clipboard.writeText(formatted)"><i class="bi bi-clipboard"></i> Copy Result</button>
                    </div>
                </div>
            </div>

            {{-- 7. Fake Data Generator --}}
            <div x-show="activeTool === 'fake-data'" class="tool-panel"
                 x-data="{
                     type: 'users', count: 5, result: '',
                     generate() {
                         const data = [];
                         for(let i=1; i<=this.count; i++) {
                             if(this.type === 'users') {
                                 const names = ['James', 'Mary', 'Robert', 'Patricia', 'John', 'Jennifer'];
                                 data.push({ id: i, name: names[i % names.length], email: (names[i % names.length]).toLowerCase() + i + '@example.com', role: i % 2 === 0 ? 'Admin' : 'Editor', active: true });
                             } else if(this.type === 'products') {
                                 const prods = ['Laptop', 'Mouse', 'Keyboard', 'Monitor', 'Headset'];
                                 data.push({ id: 100 + i, product: prods[i % prods.length], sku: 'SKU-' + (1000 + i), price: parseFloat((Math.random() * 500 + 10).toFixed(2)), stock: Math.floor(Math.random() * 50) });
                             } else {
                                 data.push({ id: i, city: ['London', 'New York', 'Tokyo', 'Paris'][i % 4], zip: 10000 + i, coordinates: { lat: (Math.random() * 180 - 90).toFixed(4), lng: (Math.random() * 360 - 180).toFixed(4) } });
                             }
                         }
                         this.result = JSON.stringify(data, null, 2);
                     }
                 }" x-init="generate()">
                <div class="tool-panel-header">
                    <h3 class="tool-panel-title"><i class="bi bi-magic" style="color:#d97706;"></i> Fake Data Generator</h3>
                    <p class="tool-panel-desc">Quickly generate mock JSON data arrays for testing and prototyping.</p>
                </div>
                <div class="tool-body">
                    <div class="row g-3 mb-4">
                        <div class="col-md-5">
                            <label class="small fw-bold mb-1 text-uppercase" style="color:var(--muted); font-size:.65rem;">Data Template</label>
                            <select class="form-select" x-model="type" @change="generate()" style="background:var(--surface-2); border-color:var(--card-border); color:var(--ink);">
                                <option value="users">User Profiles</option>
                                <option value="products">Product Catalog</option>
                                <option value="locations">Locations / Logistics</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="small fw-bold mb-1 text-uppercase" style="color:var(--muted); font-size:.65rem;">Count</label>
                            <input type="number" class="form-control" x-model.number="count" min="1" max="50" @input="generate()" style="background:var(--surface-2); border-color:var(--card-border); color:var(--ink);">
                        </div>
                        <div class="col-md-4 d-flex align-items-end">
                            <button class="btn-tool btn-amber w-100" @click="generate()"><i class="bi bi-arrow-repeat"></i> Regenerate</button>
                        </div>
                    </div>
                    <textarea class="mono-area form-control p-3" rows="12" x-model="result" readonly></textarea>
                    <div class="mt-3 d-flex justify-content-end">
                        <button class="btn-tool btn-outline" @click="navigator.clipboard.writeText(result)"><i class="bi bi-clipboard"></i> Copy JSON</button>
                    </div>
                </div>
            </div>

        </div>{{-- /col-lg-9 --}}
    </div>{{-- /row --}}
</div>
@endsection