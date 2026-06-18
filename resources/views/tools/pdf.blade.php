{{-- resources/views/tools/pdf.blade.php --}}
@extends('layouts.app')
@section('title', 'Free Online PDF Tools — Merge, Split & Compress')
@section('meta_description', 'Free browser-based PDF tools. Merge, split, compress, rotate, and watermark your PDF documents without uploading them to any server.')

@section('head')
<link rel="canonical" href="{{ route('tools.pdf') }}">
<script type="application/ld+json">
{
    "@@context": "https://schema.org",
    "@@type": "BreadcrumbList",
    "itemListElement": [
        { "@@type": "ListItem", "position": 1, "name": "Home", "item": "{{ route('home') }}" },
        { "@@type": "ListItem", "position": 2, "name": "Office Tools", "item": "{{ route('tools.index') }}" },
        { "@@type": "ListItem", "position": 3, "name": "PDF Tools", "item": "{{ route('tools.pdf') }}" }
    ]
}
</script>
@endsection

@section('content')
<script src="https://unpkg.com/pdf-lib@1.17.1/dist/pdf-lib.min.js"></script>

<style>
/* ── Shared Tool Page Layout ── */
.suite-hero { padding: 2.5rem 0 1.5rem; }
.suite-badge { display:inline-flex; align-items:center; gap:.4rem; font-size:.72rem; font-weight:700; letter-spacing:.08em; text-transform:uppercase; padding:.35rem .85rem; border-radius:100px; margin-bottom:1rem; }
.suite-title { font-family:var(--font-display); font-size:clamp(1.8rem,4vw,2.6rem); font-weight:800; letter-spacing:-.04em; color:var(--ink); margin-bottom:.4rem; }
.tool-tab-bar { display:flex; flex-wrap:wrap; gap:.4rem; margin-bottom:1.5rem; }
.ttab { padding:.42rem .9rem; border-radius:var(--radius-sm); border:1px solid var(--card-border); background:var(--surface-2); font-size:.78rem; font-weight:600; color:var(--muted); cursor:pointer; transition:all .15s; }
.ttab:hover { border-color:#6366f1; color:#6366f1; }
.ttab.active { background:rgba(99,102,241,.12); color:#6366f1; border-color:rgba(99,102,241,.3); }
.tool-panel { background:var(--card-bg); border:1px solid var(--card-border); box-shadow:var(--card-shadow); border-radius:var(--radius-lg); overflow:hidden; }
.tool-panel-header { padding:1.25rem 1.5rem; border-bottom:1px solid var(--card-border); background:var(--surface-2); }
.tool-panel-title { font-family:var(--font-display); font-size:1rem; font-weight:700; color:var(--ink); margin:0 0 .2rem; display:flex; align-items:center; gap:.5rem; }
.tool-panel-desc { font-size:.78rem; color:var(--muted); margin:0; }
.tool-body { padding:1.5rem; }
.dropzone { border:2px dashed rgba(99,102,241,.25); border-radius:var(--radius); background:rgba(99,102,241,.02); min-height:150px; display:flex; flex-direction:column; justify-content:center; align-items:center; cursor:pointer; transition:all .15s; text-align:center; padding:2rem; }
.dropzone:hover,.dropzone.dragover { border-color:#6366f1; background:rgba(99,102,241,.06); }
.dropzone-icon { font-size:2rem; color:#6366f1; margin-bottom:.6rem; opacity:.8; }
.dropzone-title { font-weight:600; font-size:.88rem; color:var(--ink); margin-bottom:.2rem; }
.dropzone-sub   { font-size:.75rem; color:var(--muted); margin:0; }
.file-row { display:flex; align-items:center; justify-content:space-between; gap:.75rem; padding:.65rem 1rem; border:1px solid var(--card-border); border-radius:var(--radius-sm); background:var(--surface-2); font-size:.82rem; }
.file-index { width:22px; height:22px; border-radius:50%; background:#6366f1; color:#fff; font-size:.68rem; font-weight:700; display:flex; align-items:center; justify-content:center; flex-shrink:0; }
.btn-tool { display:inline-flex; align-items:center; gap:.4rem; font-size:.82rem; font-weight:700; padding:.58rem 1.2rem; border-radius:var(--radius-sm); border:none; cursor:pointer; transition:opacity .15s, transform .15s; }
.btn-tool:hover:not(:disabled) { opacity:.88; transform:translateY(-1px); }
.btn-tool:disabled { opacity:.5; cursor:not-allowed; transform:none; }
.btn-indigo { background:#6366f1; color:#fff; box-shadow:0 3px 10px rgba(99,102,241,.3); }
.btn-outline { background:transparent; border:1.5px solid var(--card-border); color:var(--muted); }
.btn-outline:hover:not(:disabled) { border-color:#6366f1; color:#6366f1; background:rgba(99,102,241,.05); transform:none; opacity:1; }
.stat-row { display:flex; justify-content:space-between; align-items:center; padding:.7rem 1rem; border:1px solid var(--card-border); border-radius:var(--radius-sm); background:var(--surface-2); font-size:.82rem; }
</style>

<div class="container-fluid px-lg-5 py-2"
     x-data="{ activeTool: 'pdf-merge' }">

    <div class="row g-4">
        {{-- Sidebar --}}
        <div class="col-lg-3 reveal reveal-left">
            @include('tools._sidebar', ['active' => 'pdf'])

            {{-- In-suite tool list --}}
            <div class="mt-3" style="background:var(--card-bg); border:1px solid var(--card-border); border-radius:var(--radius-lg); padding:1rem; box-shadow:var(--card-shadow);">
                <p class="mb-2" style="font-size:.65rem; font-weight:700; letter-spacing:.1em; text-transform:uppercase; color:var(--muted); padding:.25rem .5rem;">PDF Tools</p>
                @php
                $tools = [
                    ['id'=>'pdf-merge',    'icon'=>'bi-files',             'label'=>'Merge PDFs'],
                    ['id'=>'pdf-split',    'icon'=>'bi-scissors',          'label'=>'Split PDF'],
                    ['id'=>'pdf-compress', 'icon'=>'bi-file-zip',          'label'=>'Compress PDF'],
                    ['id'=>'pdf-rotate',   'icon'=>'bi-arrow-clockwise',   'label'=>'Rotate Pages'],
                    ['id'=>'img-to-pdf',   'icon'=>'bi-file-earmark-image','label'=>'Images to PDF'],
                    ['id'=>'pdf-to-img',   'icon'=>'bi-images',            'label'=>'PDF to Images'],
                    ['id'=>'pdf-watermark','icon'=>'bi-droplet-fill',      'label'=>'Add Watermark'],
                    ['id'=>'pdf-extract',  'icon'=>'bi-file-earmark-break','label'=>'Extract Pages'],
                ];
                @endphp
                @foreach($tools as $t)
                <button @click="activeTool = '{{ $t['id'] }}'"
                        :class="activeTool === '{{ $t['id'] }}' ? 'active-tool' : ''"
                        class="d-flex align-items-center gap-2 w-100 px-3 py-2 rounded mb-1 text-start border-0 tool-side-btn"
                        style="background:none; font-size:.8rem; font-weight:600; color:var(--muted); cursor:pointer; transition:all .15s;"
                        x-bind:style="activeTool === '{{ $t['id'] }}' ? 'background:rgba(99,102,241,.1); color:#6366f1;' : ''">
                    <i class="bi {{ $t['icon'] }}"></i> {{ $t['label'] }}
                </button>
                @endforeach
            </div>
        </div>

        {{-- Main workspace --}}
        <div class="col-lg-9">

            {{-- 1. Merge PDFs --}}
            <div x-show="activeTool === 'pdf-merge'" class="tool-panel"
                 x-data="{
                     files:[], isDragging:false,
                     addFiles(f){for(let x of f)if(x.type==='application/pdf')this.files.push({file:x,name:x.name,size:(x.size/1024/1024).toFixed(2)});},
                     removeFile(i){this.files.splice(i,1);},
                     moveUp(i){if(i>0){let t=this.files[i];this.files[i]=this.files[i-1];this.files[i-1]=t;}},
                     moveDown(i){if(i<this.files.length-1){let t=this.files[i];this.files[i]=this.files[i+1];this.files[i+1]=t;}},
                     async merge(){
                         if(this.files.length<2){alert('Add at least 2 PDFs.');return;}
                         const m=await PDFLib.PDFDocument.create();
                         for(let item of this.files){const b=await item.file.arrayBuffer();const p=await PDFLib.PDFDocument.load(b);const cp=await m.copyPages(p,p.getPageIndices());cp.forEach(pg=>m.addPage(pg));}
                         const b=await m.save();const blob=new Blob([b],{type:'application/pdf'});const a=document.createElement('a');a.href=URL.createObjectURL(blob);a.download='merged.pdf';a.click();
                     }
                 }">
                <div class="tool-panel-header">
                    <h3 class="tool-panel-title"><i class="bi bi-files" style="color:#6366f1;"></i> PDF Merger</h3>
                    <p class="tool-panel-desc">Combine multiple PDFs into one. Drag to reorder pages before merging.</p>
                </div>
                <div class="tool-body">
                    <div class="dropzone mb-4" :class="isDragging?'dragover':''" @dragover.prevent="isDragging=true" @dragleave.prevent="isDragging=false" @drop.prevent="isDragging=false;addFiles($event.dataTransfer.files)" @click="$refs.pdfMergeInput.click()">
                        <input type="file" x-ref="pdfMergeInput" class="d-none" multiple accept="application/pdf" @change="addFiles($event.target.files)">
                        <div class="dropzone-icon"><i class="bi bi-file-earmark-pdf-fill"></i></div>
                        <p class="dropzone-title">Drop PDF files here</p>
                        <p class="dropzone-sub">or click to browse — PDF only</p>
                    </div>
                    <template x-if="files.length > 0">
                        <div class="d-flex flex-column gap-2 mb-4">
                            <template x-for="(item, i) in files" :key="i">
                                <div class="file-row">
                                    <div class="d-flex align-items-center gap-2 flex-grow-1 overflow-hidden">
                                        <span class="file-index" x-text="i+1"></span>
                                        <i class="bi bi-file-pdf" style="color:#ef4444; font-size:1.1rem; flex-shrink:0;"></i>
                                        <span class="text-truncate fw-medium" x-text="item.name"></span>
                                        <span style="color:var(--muted); font-size:.72rem; flex-shrink:0;" x-text="item.size+' MB'"></span>
                                    </div>
                                    <div class="d-flex gap-1 flex-shrink-0">
                                        <button @click="moveUp(i)" class="btn-tool btn-outline py-1 px-2" :disabled="i===0"><i class="bi bi-arrow-up" style="font-size:.7rem;"></i></button>
                                        <button @click="moveDown(i)" class="btn-tool btn-outline py-1 px-2" :disabled="i===files.length-1"><i class="bi bi-arrow-down" style="font-size:.7rem;"></i></button>
                                        <button @click="removeFile(i)" class="btn-tool btn-outline py-1 px-2" style="color:#ef4444;"><i class="bi bi-trash" style="font-size:.7rem;"></i></button>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </template>
                    <div class="d-flex justify-content-end">
                        <button class="btn-tool btn-indigo" @click="merge" :disabled="files.length < 2">
                            <i class="bi bi-file-earmark-zip"></i> Merge & Download
                        </button>
                    </div>
                </div>
            </div>

            {{-- 2. Split PDF --}}
            <div x-show="activeTool === 'pdf-split'" class="tool-panel"
                 x-data="{
                     file:null, pageCount:0, ranges:'1-3, 5, 7-9', isDragging:false,
                     async loadPDF(f){
                         this.file=f;
                         const b=await f.arrayBuffer();
                         const p=await PDFLib.PDFDocument.load(b);
                         this.pageCount=p.getPageCount();
                     },
                     async split(){
                         if(!this.file)return;
                         const b=await this.file.arrayBuffer();
                         const src=await PDFLib.PDFDocument.load(b);
                         const parts=this.ranges.split(',').map(s=>s.trim()).filter(Boolean);
                         for(let part of parts){
                             const doc=await PDFLib.PDFDocument.create();
                             let indices=[];
                             if(part.includes('-')){const[s,e]=part.split('-').map(n=>parseInt(n)-1);for(let i=s;i<=Math.min(e,src.getPageCount()-1);i++)indices.push(i);}
                             else{const n=parseInt(part)-1;if(n>=0&&n<src.getPageCount())indices.push(n);}
                             const copied=await doc.copyPages(src,indices);
                             copied.forEach(pg=>doc.addPage(pg));
                             const out=await doc.save();
                             const blob=new Blob([out],{type:'application/pdf'});
                             const a=document.createElement('a');a.href=URL.createObjectURL(blob);a.download='split_pages_'+part.replace('-','_')+'.pdf';a.click();
                         }
                     }
                 }">
                <div class="tool-panel-header">
                    <h3 class="tool-panel-title"><i class="bi bi-scissors" style="color:#6366f1;"></i> PDF Splitter</h3>
                    <p class="tool-panel-desc">Extract specific pages or ranges from a PDF into separate files.</p>
                </div>
                <div class="tool-body">
                    <div class="dropzone mb-4" x-show="!file" :class="isDragging?'dragover':''" @dragover.prevent="isDragging=true" @dragleave.prevent="isDragging=false" @drop.prevent="isDragging=false;loadPDF($event.dataTransfer.files[0])" @click="$refs.pdfSplitInput.click()">
                        <input type="file" x-ref="pdfSplitInput" class="d-none" accept="application/pdf" @change="loadPDF($event.target.files[0])">
                        <div class="dropzone-icon"><i class="bi bi-file-earmark-pdf-fill"></i></div>
                        <p class="dropzone-title">Drop a PDF file here</p>
                        <p class="dropzone-sub">or click to browse</p>
                    </div>
                    <template x-if="file">
                        <div>
                            <div class="stat-row mb-4">
                                <span style="color:var(--muted);">Loaded file</span>
                                <span class="fw-bold" style="color:var(--ink);" x-text="file.name"></span>
                                <span style="color:#6366f1; font-weight:700;" x-text="pageCount + ' pages'"></span>
                            </div>
                            <label class="small fw-bold mb-1 d-block" style="color:var(--muted); font-size:.72rem; text-transform:uppercase; letter-spacing:.08em;">
                                Page Ranges (comma-separated)
                            </label>
                            <input type="text" class="form-control mb-1" x-model="ranges"
                                   placeholder="e.g. 1-3, 5, 7-9"
                                   style="background:var(--surface-2); border-color:var(--card-border); color:var(--ink); font-size:.85rem;">
                            <p class="mb-4" style="font-size:.72rem; color:var(--muted);">Each range/page becomes a separate PDF download.</p>
                            <div class="d-flex justify-content-between">
                                <button class="btn-tool btn-outline" @click="file=null;pageCount=0;"><i class="bi bi-arrow-left"></i> New file</button>
                                <button class="btn-tool btn-indigo" @click="split()"><i class="bi bi-scissors"></i> Split & Download</button>
                            </div>
                        </div>
                    </template>
                </div>
            </div>

            {{-- 3. Compress PDF --}}
            <div x-show="activeTool === 'pdf-compress'" class="tool-panel"
                 x-data="{
                     file:null, isDragging:false, origSize:0, newSize:0, done:false, outputUrl:'',
                     async loadPDF(f){
                         this.file=f; this.origSize=(f.size/1024).toFixed(1); this.done=false;
                     },
                     async compress(){
                         if(!this.file)return;
                         const b=await this.file.arrayBuffer();
                         const src=await PDFLib.PDFDocument.load(b,{ignoreEncryption:true});
                         const out=await src.save({useObjectStreams:true});
                         const blob=new Blob([out],{type:'application/pdf'});
                         this.newSize=(blob.size/1024).toFixed(1);
                         if(this.outputUrl)URL.revokeObjectURL(this.outputUrl);
                         this.outputUrl=URL.createObjectURL(blob);
                         this.done=true;
                     },
                     download(){const a=document.createElement('a');a.href=this.outputUrl;a.download='compressed.pdf';a.click();}
                 }">
                <div class="tool-panel-header">
                    <h3 class="tool-panel-title"><i class="bi bi-file-zip" style="color:#6366f1;"></i> PDF Compressor</h3>
                    <p class="tool-panel-desc">Reduce PDF file size using object stream compression (client-side).</p>
                </div>
                <div class="tool-body">
                    <div class="dropzone mb-4" x-show="!file" :class="isDragging?'dragover':''" @dragover.prevent="isDragging=true" @dragleave.prevent="isDragging=false" @drop.prevent="isDragging=false;loadPDF($event.dataTransfer.files[0])" @click="$refs.pdfCompInput.click()">
                        <input type="file" x-ref="pdfCompInput" class="d-none" accept="application/pdf" @change="loadPDF($event.target.files[0])">
                        <div class="dropzone-icon"><i class="bi bi-file-earmark-pdf-fill"></i></div>
                        <p class="dropzone-title">Drop a PDF file here</p>
                        <p class="dropzone-sub">or click to browse</p>
                    </div>
                    <template x-if="file">
                        <div>
                            <div class="row g-3 mb-4">
                                <div class="col-6">
                                    <div class="stat-row"><span style="color:var(--muted);">Original size</span><span style="font-weight:700; color:var(--ink);" x-text="origSize+' KB'"></span></div>
                                </div>
                                <div class="col-6" x-show="done">
                                    <div class="stat-row"><span style="color:var(--muted);">Compressed</span><span style="font-weight:700; color:#16a34a;" x-text="newSize+' KB'"></span></div>
                                </div>
                            </div>
                            <div class="d-flex justify-content-between">
                                <button class="btn-tool btn-outline" @click="file=null;done=false;"><i class="bi bi-arrow-left"></i> New file</button>
                                <div class="d-flex gap-2">
                                    <button class="btn-tool btn-indigo" @click="compress()" x-show="!done"><i class="bi bi-file-zip"></i> Compress</button>
                                    <button class="btn-tool btn-indigo" @click="download()" x-show="done"><i class="bi bi-download"></i> Download</button>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>
            </div>

            {{-- 4. Rotate PDF Pages --}}
            <div x-show="activeTool === 'pdf-rotate'" class="tool-panel"
                 x-data="{
                     file:null, isDragging:false, pageCount:0, rotateAll:90, outputUrl:'',
                     async loadPDF(f){this.file=f;const b=await f.arrayBuffer();const p=await PDFLib.PDFDocument.load(b);this.pageCount=p.getPageCount();},
                     async rotate(){
                         if(!this.file)return;
                         const b=await this.file.arrayBuffer();
                         const doc=await PDFLib.PDFDocument.load(b);
                         const pages=doc.getPages();
                         pages.forEach(p=>{const cur=p.getRotation().angle;p.setRotation(PDFLib.degrees((cur+parseInt(this.rotateAll))%360));});
                         const out=await doc.save();
                         const blob=new Blob([out],{type:'application/pdf'});
                         const a=document.createElement('a');a.href=URL.createObjectURL(blob);a.download='rotated.pdf';a.click();
                     }
                 }">
                <div class="tool-panel-header">
                    <h3 class="tool-panel-title"><i class="bi bi-arrow-clockwise" style="color:#6366f1;"></i> Rotate PDF Pages</h3>
                    <p class="tool-panel-desc">Rotate all pages in a PDF by 90°, 180°, or 270°.</p>
                </div>
                <div class="tool-body">
                    <div class="dropzone mb-4" x-show="!file" :class="isDragging?'dragover':''" @dragover.prevent="isDragging=true" @dragleave.prevent="isDragging=false" @drop.prevent="isDragging=false;loadPDF($event.dataTransfer.files[0])" @click="$refs.pdfRotInput.click()">
                        <input type="file" x-ref="pdfRotInput" class="d-none" accept="application/pdf" @change="loadPDF($event.target.files[0])">
                        <div class="dropzone-icon"><i class="bi bi-file-earmark-pdf-fill"></i></div>
                        <p class="dropzone-title">Drop a PDF file here</p><p class="dropzone-sub">or click to browse</p>
                    </div>
                    <template x-if="file">
                        <div>
                            <div class="stat-row mb-4">
                                <span style="color:var(--muted);">File</span>
                                <span class="fw-bold" style="color:var(--ink);" x-text="file.name"></span>
                                <span style="color:#6366f1; font-weight:700;" x-text="pageCount+' pages'"></span>
                            </div>
                            <label class="small fw-bold mb-2 d-block" style="color:var(--muted); font-size:.72rem; text-transform:uppercase;">Rotation</label>
                            <div class="d-flex gap-2 mb-4">
                                <button @click="rotateAll=90"  :class="rotateAll==90  ? 'btn-indigo' : 'btn-outline'" class="btn-tool"><i class="bi bi-arrow-clockwise"></i> 90°</button>
                                <button @click="rotateAll=180" :class="rotateAll==180 ? 'btn-indigo' : 'btn-outline'" class="btn-tool"><i class="bi bi-arrow-repeat"></i> 180°</button>
                                <button @click="rotateAll=270" :class="rotateAll==270 ? 'btn-indigo' : 'btn-outline'" class="btn-tool"><i class="bi bi-arrow-counterclockwise"></i> 270°</button>
                            </div>
                            <div class="d-flex justify-content-between">
                                <button class="btn-tool btn-outline" @click="file=null;"><i class="bi bi-arrow-left"></i> New file</button>
                                <button class="btn-tool btn-indigo" @click="rotate()"><i class="bi bi-arrow-clockwise"></i> Rotate & Download</button>
                            </div>
                        </div>
                    </template>
                </div>
            </div>

            {{-- 5. Images to PDF --}}
            <div x-show="activeTool === 'img-to-pdf'" class="tool-panel"
                 x-data="{
                     images:[], isDragging:false,
                     async loadJsPDFScript() {
                         if (!window.jspdf) {
                             const script = document.createElement('script');
                             script.src = 'https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js';
                             document.head.appendChild(script);
                             await new Promise(resolve => script.onload = resolve);
                         }
                     },
                     addImages(f){for(let x of f)if(x.type.startsWith('image/')){const r=new FileReader();r.onload=(e)=>{this.images.push({file:x,name:x.name,dataUrl:e.target.result})};r.readAsDataURL(x);}},
                     removeImage(i){this.images.splice(i,1);},
                     async generatePDF(){
                         if(!this.images.length)return;
                         await this.loadJsPDFScript(); // Ensure jspdf is loaded
                         const { jsPDF } = window.jspdf;
                         const doc = new jsPDF();
                         for(let i=0;i<this.images.length;i++){if(i>0)doc.addPage();const img=this.images[i];const o=new Image();o.src=img.dataUrl;await new Promise(r=>o.onload=r);const pw=doc.internal.pageSize.getWidth();const ph=doc.internal.pageSize.getHeight();const ratio=Math.min((pw-20)/o.width,(ph-20)/o.height);const fw=o.width*ratio;const fh=o.height*ratio;doc.addImage(img.dataUrl,'JPEG',(pw-fw)/2,(ph-fh)/2,fw,fh);}
                         doc.save('images.pdf');
                     }
                 }">
                <div class="tool-panel-header">
                    <h3 class="tool-panel-title"><i class="bi bi-file-earmark-image" style="color:#6366f1;"></i> Images to PDF</h3>
                    <p class="tool-panel-desc">Convert JPEG, PNG, or WebP images into a compiled PDF file.</p>
                </div>
                <div class="tool-body">
                    <div class="dropzone mb-4" :class="isDragging?'dragover':''" @dragover.prevent="isDragging=true" @dragleave.prevent="isDragging=false" @drop.prevent="isDragging=false;addImages($event.dataTransfer.files)" @click="$refs.imgToPdfInput.click()">
                        <input type="file" x-ref="imgToPdfInput" class="d-none" multiple accept="image/*" @change="addImages($event.target.files)">
                        <div class="dropzone-icon"><i class="bi bi-images"></i></div>
                        <p class="dropzone-title">Drop your images here</p>
                        <p class="dropzone-sub">JPEG · PNG · WebP supported</p>
                    </div>
                    <template x-if="images.length > 0">
                        <div class="mb-4">
                            <div class="row row-cols-2 row-cols-md-4 g-3">
                                <template x-for="(img, i) in images" :key="i">
                                    <div class="col">
                                        <div class="border rounded overflow-hidden" style="border-color:var(--card-border) !important;">
                                            <img :src="img.dataUrl" class="w-100 object-fit-cover" style="height:100px;">
                                            <div style="padding:.4rem;">
                                                <p class="mb-1 text-truncate" style="font-size:.7rem; color:var(--muted);" x-text="img.name"></p>
                                                <button @click="removeImage(i)" class="btn-tool btn-outline w-100 py-1" style="font-size:.7rem; color:#ef4444; border-color:rgba(239,68,68,.3);"><i class="bi bi-trash"></i> Remove</button>
                                            </div>
                                        </div>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </template>
                    <div class="d-flex justify-content-end">
                        <button class="btn-tool btn-indigo" @click="generatePDF" :disabled="images.length === 0">
                            <i class="bi bi-filetype-pdf"></i> Generate PDF
                        </button>
                    </div>
                </div>
            </div>

            {{-- 6. PDF to Images --}}
            <div x-show="activeTool === 'pdf-to-img'" class="tool-panel"
                 x-data="{
                     file:null, isDragging:false, pageCount:0, previews:[], loading:false,
                     scale: 1.5,
                     async loadPdfJsScript() {
                         if (!window.pdfjsLib) { // Check if pdf.js is already loaded
                             const script = document.createElement('script');
                             script.src = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.min.js';
                             document.head.appendChild(script);
                             await new Promise(resolve => script.onload = resolve);
                             // Initialize PDF.js worker
                             if (!pdfjsLib.GlobalWorkerOptions.workerSrc) {
                                 pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.worker.min.js';
                             }
                         }
                     },
                     async loadPDF(f){
                         this.file=f; this.previews=[]; this.loading=true;
                         try {
                             await this.loadPdfJsScript(); // Ensure pdf.js is loaded
                             const arrayBuffer = await f.arrayBuffer();
                             const pdf = await pdfjsLib.getDocument({data: arrayBuffer}).promise;
                             this.pageCount = pdf.numPages;

                             for (let i = 1; i <= pdf.numPages; i++) {
                                 const page = await pdf.getPage(i);
                                 const viewport = page.getViewport({scale: this.scale});
                                 const canvas = document.createElement('canvas');
                                 const context = canvas.getContext('2d');
                                 canvas.height = viewport.height;
                                 canvas.width = viewport.width;

                                 await page.render({canvasContext: context, viewport: viewport}).promise;
                                 this.previews.push({
                                     url: canvas.toDataURL('image/png'),
                                     name: f.name.replace('.pdf', '') + '_page_' + i + '.png'
                                 });
                             }
                         } catch (err) {
                             alert('Error rendering PDF: ' + err.message);
                         } finally {
                             this.loading = false;
                         }
                     },
                     download(url, name) { const a=document.createElement('a'); a.href=url; a.download=name; a.click(); }
                 }">
                <div class="tool-panel-header">
                    <h3 class="tool-panel-title"><i class="bi bi-images" style="color:#6366f1;"></i> PDF to Images</h3>
                    <p class="tool-panel-desc">Export each PDF page as a PNG image. Requires PDF.js (include via CDN in layout for full rendering).</p>
                </div>
                <div class="tool-body">
                    <div class="dropzone mb-4" x-show="!file" :class="isDragging?'dragover':''" @dragover.prevent="isDragging=true" @dragleave.prevent="isDragging=false" @drop.prevent="isDragging=false;loadPDF($event.dataTransfer.files[0])" @click="$refs.pdfToImgInput.click()">
                        <input type="file" x-ref="pdfToImgInput" class="d-none" accept="application/pdf" @change="loadPDF($event.target.files[0])">
                        <div class="dropzone-icon"><i class="bi bi-file-earmark-pdf-fill"></i></div>
                        <p class="dropzone-title">Drop a PDF file here</p>
                        <p class="dropzone-sub">Each page will be exported as PNG</p>
                    </div>
                    <template x-if="file">
                        <div>
                            <div class="stat-row mb-4">
                                <span style="color:var(--muted);">File</span>
                                <span class="fw-bold text-truncate" x-text="file.name"></span>
                                <span style="color:#6366f1; font-weight:700;" x-text="pageCount+' pages'"></span>
                            </div>
                            
                            <div class="row row-cols-2 row-cols-md-3 g-3 mb-4" x-show="previews.length > 0">
                                <template x-for="(img, i) in previews" :key="i">
                                    <div class="col">
                                        <div class="border rounded overflow-hidden bg-light">
                                            <img :src="img.url" class="w-100 h-auto" style="max-height: 200px; object-fit: contain;">
                                            <div class="p-2 border-top bg-white d-flex justify-content-between align-items-center">
                                                <span class="small text-muted" x-text="'Page ' + (i+1)"></span>
                                                <button @click="download(img.url, img.name)" class="btn btn-sm btn-link p-0 text-primary"><i class="bi bi-download"></i></button>
                                            </div>
                                        </div>
                                    </div>
                                </template>
                            </div>

                            <div class="d-flex justify-content-between">
                                <button class="btn-tool btn-outline" @click="file=null;pageCount=0;previews=[];"><i class="bi bi-arrow-left"></i> New file</button>
                                <div class="small text-muted" x-show="loading"><span class="spinner-border spinner-border-sm me-2"></span>Rendering pages...</div>
                            </div>
                        </div>
                    </template>
                </div>
            </div>

            {{-- 7. PDF Watermark --}}
            <div x-show="activeTool === 'pdf-watermark'" class="tool-panel"
                 x-data="{
                     file:null, isDragging:false, text:'CONFIDENTIAL', opacity:30, fontSize:48, color:'#cccccc',
                     async loadPDF(f){this.file=f;},
                     async addWatermark(){
                         if(!this.file)return;
                         const b=await this.file.arrayBuffer();
                         const doc=await PDFLib.PDFDocument.load(b);
                         const pages=doc.getPages();
                         const font=await doc.embedFont(PDFLib.StandardFonts.HelveticaBold);
                         const hex=this.color.replace('#','');
                         const r=parseInt(hex.substring(0,2),16)/255;
                         const g=parseInt(hex.substring(2,4),16)/255;
                         const bl=parseInt(hex.substring(4,6),16)/255;
                         pages.forEach(page=>{
                             const{width,height}=page.getSize();
                             page.drawText(this.text,{x:width/2-this.text.length*this.fontSize*0.28,y:height/2,size:this.fontSize,font,color:PDFLib.rgb(r,g,bl),opacity:this.opacity/100,rotate:PDFLib.degrees(-35)});
                         });
                         const out=await doc.save();
                         const blob=new Blob([out],{type:'application/pdf'});
                         const a=document.createElement('a');a.href=URL.createObjectURL(blob);a.download='watermarked.pdf';a.click();
                     }
                 }">
                <div class="tool-panel-header">
                    <h3 class="tool-panel-title"><i class="bi bi-droplet-fill" style="color:#6366f1;"></i> Add Watermark</h3>
                    <p class="tool-panel-desc">Stamp a diagonal text watermark on every page of a PDF.</p>
                </div>
                <div class="tool-body">
                    <div class="dropzone mb-4" x-show="!file" :class="isDragging?'dragover':''" @dragover.prevent="isDragging=true" @dragleave.prevent="isDragging=false" @drop.prevent="isDragging=false;loadPDF($event.dataTransfer.files[0])" @click="$refs.pdfWaterInput.click()">
                        <input type="file" x-ref="pdfWaterInput" class="d-none" accept="application/pdf" @change="loadPDF($event.target.files[0])">
                        <div class="dropzone-icon"><i class="bi bi-file-earmark-pdf-fill"></i></div>
                        <p class="dropzone-title">Drop a PDF file here</p><p class="dropzone-sub">or click to browse</p>
                    </div>
                    <template x-if="file">
                        <div>
                            <div class="row g-3 p-3 border rounded mb-4" style="background:var(--surface-2); border-color:var(--card-border) !important;">
                                <div class="col-md-6">
                                    <label class="small fw-bold mb-1 d-block" style="color:var(--muted); font-size:.72rem; text-transform:uppercase;">Watermark Text</label>
                                    <input type="text" class="form-control" x-model="text" style="background:var(--card-bg); border-color:var(--card-border); color:var(--ink);">
                                </div>
                                <div class="col-md-3">
                                    <label class="small fw-bold mb-1 d-block" style="color:var(--muted); font-size:.72rem; text-transform:uppercase;">Color</label>
                                    <input type="color" class="form-control form-control-color w-100" x-model="color">
                                </div>
                                <div class="col-md-3">
                                    <label class="small fw-bold mb-1 d-block" style="color:var(--muted); font-size:.72rem; text-transform:uppercase;">Font Size</label>
                                    <input type="number" class="form-control" x-model.number="fontSize" min="12" max="120" style="background:var(--card-bg); border-color:var(--card-border); color:var(--ink);">
                                </div>
                                <div class="col-12">
                                    <label class="small fw-bold mb-1 d-block" style="color:var(--muted); font-size:.72rem; text-transform:uppercase;">Opacity: <span x-text="opacity+'%'"></span></label>
                                    <input type="range" class="form-range" min="5" max="100" x-model="opacity">
                                </div>
                            </div>
                            <div class="d-flex justify-content-between">
                                <button class="btn-tool btn-outline" @click="file=null;"><i class="bi bi-arrow-left"></i> New file</button>
                                <button class="btn-tool btn-indigo" @click="addWatermark()"><i class="bi bi-droplet-fill"></i> Add & Download</button>
                            </div>
                        </div>
                    </template>
                </div>
            </div>

            {{-- 8. Extract Pages --}}
            <div x-show="activeTool === 'pdf-extract'" class="tool-panel"
                 x-data="{
                     file:null, isDragging:false, pageCount:0, pages:'2,4,6',
                     async loadPDF(f){this.file=f;const b=await f.arrayBuffer();const p=await PDFLib.PDFDocument.load(b);this.pageCount=p.getPageCount();},
                     async extract(){
                         if(!this.file)return;
                         const b=await this.file.arrayBuffer();
                         const src=await PDFLib.PDFDocument.load(b);
                         const doc=await PDFLib.PDFDocument.create();
                         const nums=this.pages.split(',').map(s=>parseInt(s.trim())-1).filter(n=>n>=0&&n<src.getPageCount());
                         const copied=await doc.copyPages(src,nums);
                         copied.forEach(p=>doc.addPage(p));
                         const out=await doc.save();
                         const blob=new Blob([out],{type:'application/pdf'});
                         const a=document.createElement('a');a.href=URL.createObjectURL(blob);a.download='extracted.pdf';a.click();
                     }
                 }">
                <div class="tool-panel-header">
                    <h3 class="tool-panel-title"><i class="bi bi-file-earmark-break" style="color:#6366f1;"></i> Extract Pages</h3>
                    <p class="tool-panel-desc">Pick specific page numbers from a PDF and save them as a new file.</p>
                </div>
                <div class="tool-body">
                    <div class="dropzone mb-4" x-show="!file" :class="isDragging?'dragover':''" @dragover.prevent="isDragging=true" @dragleave.prevent="isDragging=false" @drop.prevent="isDragging=false;loadPDF($event.dataTransfer.files[0])" @click="$refs.pdfExtInput.click()">
                        <input type="file" x-ref="pdfExtInput" class="d-none" accept="application/pdf" @change="loadPDF($event.target.files[0])">
                        <div class="dropzone-icon"><i class="bi bi-file-earmark-pdf-fill"></i></div>
                        <p class="dropzone-title">Drop a PDF file here</p><p class="dropzone-sub">or click to browse</p>
                    </div>
                    <template x-if="file">
                        <div>
                            <div class="stat-row mb-4">
                                <span style="color:var(--muted);">File</span>
                                <span class="fw-bold text-truncate" x-text="file.name"></span>
                                <span style="color:#6366f1; font-weight:700;" x-text="pageCount+' pages'"></span>
                            </div>
                            <label class="small fw-bold mb-1 d-block" style="color:var(--muted); font-size:.72rem; text-transform:uppercase;">Pages to Extract (comma-separated)</label>
                            <input type="text" class="form-control mb-4" x-model="pages" placeholder="e.g. 1, 3, 5" style="background:var(--surface-2); border-color:var(--card-border); color:var(--ink); font-size:.85rem;">
                            <div class="d-flex justify-content-between">
                                <button class="btn-tool btn-outline" @click="file=null;pageCount=0;"><i class="bi bi-arrow-left"></i> New file</button>
                                <button class="btn-tool btn-indigo" @click="extract()"><i class="bi bi-file-earmark-break"></i> Extract & Download</button>
                            </div>
                        </div>
                    </template>
                </div>
            </div>

        </div>{{-- /col-lg-9 --}}
    </div>{{-- /row --}}
</div>
@endsection