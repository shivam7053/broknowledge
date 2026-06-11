{{-- tools.blade.php --}}
@extends('layouts.app')

@section('title', 'Office & Productivity Tools')

@section('content')

<script src="https://unpkg.com/pdf-lib@1.17.1/dist/pdf-lib.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/PapaParse/5.4.1/papaparse.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/marked/marked.min.js"></script>

<style>
    /* ══════════════════════════════════════════════
       TOOLS PAGE
    ══════════════════════════════════════════════ */

    .tools-hero {
        padding: 3.5rem 0 2rem;
        text-align: center;
    }

    .tools-title {
        font-family: var(--font-display);
        font-size: clamp(2.2rem, 5vw, 3.4rem);
        font-weight: 800;
        letter-spacing: -.04em;
        line-height: 1.06;
        color: var(--ink);
        margin-bottom: .75rem;
    }

    .tools-title .tools-accent { color: #6366f1; }

    /* ── Sidebar ───────────────────────────────── */
    .tools-sidebar {
        background: var(--card-bg);
        border: 1px solid var(--card-border);
        box-shadow: var(--card-shadow);
        border-radius: var(--radius-lg);
        padding: 1.25rem;
        position: sticky;
        top: 90px;
        z-index: 10;
    }

    .sidebar-group-label {
        display: flex;
        align-items: center;
        gap: .5rem;
        font-size: .7rem;
        font-weight: 700;
        letter-spacing: .1em;
        text-transform: uppercase;
        padding: .5rem .5rem .35rem;
        margin-top: .5rem;
        margin-bottom: .25rem;
    }

    .tool-tab {
        display: flex;
        align-items: center;
        gap: .6rem;
        width: 100%;
        padding: .58rem .75rem;
        border-radius: var(--radius-sm);
        border: 1px solid transparent;
        background: none;
        font-size: .8rem;
        font-weight: 600;
        color: var(--muted);
        cursor: pointer;
        text-align: left;
        transition: background var(--transition-fast), color var(--transition-fast), border-color var(--transition-fast);
    }

    .tool-tab:hover { background: var(--surface-2); color: var(--ink); }

    .tool-tab.active {
        font-weight: 700;
        border-color: transparent;
    }

    .tool-tab.active.pdf-active   { background: rgba(99,102,241,.1);  color: #6366f1; border-color: rgba(99,102,241,.2); }
    .tool-tab.active.doc-active   { background: rgba(22,163,74,.1);   color: var(--brand); border-color: rgba(22,163,74,.2); }
    .tool-tab.active.data-active  { background: rgba(245,158,11,.1);  color: #d97706; border-color: rgba(245,158,11,.2); }
    .tool-tab.active.photo-active { background: rgba(239,68,68,.1);   color: #dc2626; border-color: rgba(239,68,68,.2); }
    .tool-tab.active.dev-active   { background: rgba(6,182,212,.1);   color: #0891b2; border-color: rgba(6,182,212,.2); }

    /* ── Tool panel ────────────────────────────── */
    .tool-panel {
        background: var(--card-bg);
        border: 1px solid var(--card-border);
        box-shadow: var(--card-shadow);
        border-radius: var(--radius-lg);
        overflow: hidden;
    }

    .tool-panel-header {
        padding: 1.25rem 1.5rem;
        border-bottom: 1px solid var(--card-border);
        background: var(--surface-2);
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 1rem;
    }

    .tool-panel-title {
        font-family: var(--font-display);
        font-size: 1.05rem;
        font-weight: 700;
        color: var(--ink);
        letter-spacing: -.01em;
        margin: 0;
        display: flex;
        align-items: center;
        gap: .5rem;
    }

    .tool-panel-desc {
        font-size: .78rem;
        color: var(--muted);
        margin: 0;
        line-height: 1.5;
    }

    .tool-body { padding: 1.5rem; }

    /* Dropzone */
    .dropzone {
        border: 2px dashed rgba(99,102,241,.25);
        border-radius: var(--radius);
        background: rgba(99,102,241,.02);
        min-height: 160px;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        cursor: pointer;
        transition: border-color var(--transition-fast), background var(--transition-fast);
        text-align: center;
        padding: 2rem;
    }

    .dropzone:hover, .dropzone.dragover {
        border-color: #6366f1;
        background: rgba(99,102,241,.05);
    }

    .dropzone-icon {
        font-size: 2.2rem;
        color: #6366f1;
        margin-bottom: .75rem;
        opacity: .8;
    }

    .dropzone-title { font-weight: 600; font-size: .88rem; color: var(--ink); margin-bottom: .25rem; }
    .dropzone-sub   { font-size: .75rem; color: var(--muted); }

    /* File queue */
    .file-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: .75rem;
        padding: .7rem 1rem;
        border: 1px solid var(--card-border);
        border-radius: var(--radius-sm);
        background: var(--surface-2);
        font-size: .82rem;
    }

    .file-index {
        width: 22px; height: 22px;
        border-radius: 50%;
        background: #6366f1;
        color: #fff;
        font-size: .68rem;
        font-weight: 700;
        display: flex; align-items: center; justify-content: center;
        flex-shrink: 0;
    }

    /* Action buttons */
    .btn-tool {
        display: inline-flex;
        align-items: center;
        gap: .4rem;
        font-size: .82rem;
        font-weight: 700;
        padding: .58rem 1.25rem;
        border-radius: var(--radius-sm);
        border: none;
        cursor: pointer;
        transition: opacity var(--transition-fast), transform var(--transition-spring);
    }

    .btn-tool:hover:not(:disabled) { opacity: .88; transform: translateY(-1px); }
    .btn-tool:disabled { opacity: .5; cursor: not-allowed; transform: none; }

    .btn-tool-indigo { background: #6366f1; color: #fff; box-shadow: 0 3px 10px rgba(99,102,241,.3); }
    .btn-tool-green  { background: var(--brand); color: #fff; box-shadow: 0 3px 10px rgba(22,163,74,.3); }
    .btn-tool-amber  { background: #f59e0b; color: #fff; box-shadow: 0 3px 10px rgba(245,158,11,.3); }
    .btn-tool-red    { background: #ef4444; color: #fff; box-shadow: 0 3px 10px rgba(239,68,68,.3); }
    .btn-tool-cyan   { background: #06b6d4; color: #fff; box-shadow: 0 3px 10px rgba(6,182,212,.3); }

    .btn-tool-outline {
        background: transparent;
        border: 1.5px solid var(--card-border);
        color: var(--muted);
    }
    .btn-tool-outline:hover:not(:disabled) { border-color: var(--brand); color: var(--brand); background: var(--brand-pale); transform: none; opacity: 1; }

    /* Live stat boxes */
    .stat-box {
        padding: .85rem 1rem;
        border-radius: var(--radius-sm);
        background: var(--surface-2);
        border: 1px solid var(--card-border);
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .stat-box-label { font-size: .75rem; color: var(--muted); }
    .stat-box-value { font-family: var(--font-display); font-weight: 700; font-size: 1.1rem; color: var(--brand); }

    /* Code / text areas */
    .mono-area {
        font-family: 'Fira Code', 'Cascadia Code', monospace;
        font-size: .82rem;
        line-height: 1.65;
        background: #0d1117 !important;
        color: #e6edf3 !important;
        border: 1px solid rgba(255,255,255,.08) !important;
        border-radius: var(--radius-sm) !important;
        resize: vertical;
    }

    .mono-area:focus {
        border-color: rgba(99,102,241,.5) !important;
        box-shadow: 0 0 0 3px rgba(99,102,241,.1) !important;
        outline: none;
    }

    .output-terminal {
        font-family: 'Fira Code', monospace;
        font-size: .8rem;
        background: #0d1117;
        color: #e6edf3;
        border: 1px solid rgba(255,255,255,.08);
        border-radius: var(--radius-sm);
        padding: 1rem;
        min-height: 120px;
        white-space: pre-wrap;
        overflow-y: auto;
    }

    /* Diff view */
    .diff-removed { background: rgba(239,68,68,.08); color: #ef4444; border-left: 3px solid #ef4444; }
    .diff-added   { background: rgba(22,163,74,.08);  color: var(--brand); border-left: 3px solid var(--brand); }

    /* Image preview */
    .img-preview-badge {
        position: absolute;
        top: .5rem;
        left: .5rem;
        font-size: .62rem;
        font-weight: 700;
        letter-spacing: .08em;
        text-transform: uppercase;
        background: rgba(0,0,0,.55);
        color: #fff;
        border-radius: 100px;
        padding: .22rem .6rem;
        backdrop-filter: blur(4px);
    }
</style>

<div class="container-fluid px-lg-5 py-2"
     x-data="{
         activeSuite: 'pdf',
         activeTool: 'pdf-merge',
         selectTool(suite, tool) { this.activeSuite = suite; this.activeTool = tool; }
     }">

    {{-- ── Hero ──────────────────────────────────────────── --}}
    <div class="tools-hero reveal">
        <div class="eyebrow mb-3 mx-auto" style="width:fit-content; background:rgba(99,102,241,.1); color:#6366f1; border-color:rgba(99,102,241,.2);">
            <i class="bi bi-tools"></i> Office Workspace
        </div>
        <h1 class="tools-title">
            Private tools,<br><span class="tools-accent">right in your browser.</span>
        </h1>
        <p class="small mt-2" style="color:var(--muted); max-width:420px; margin:0 auto; line-height:1.65;">
            Every tool runs entirely client-side. Your files never leave your device.
        </p>
    </div>

    <div class="row g-4">

        {{-- ── Sidebar ──────────────────────────────────── --}}
        <div class="col-lg-3 reveal reveal-left">
            <div class="tools-sidebar">

                <div class="sidebar-group-label" style="color:#6366f1;">
                    <i class="bi bi-file-earmark-pdf-fill"></i> PDF Suite
                </div>
                <button @click="selectTool('pdf','pdf-merge')"   :class="activeTool==='pdf-merge'   ? 'active pdf-active' : ''" class="tool-tab"><i class="bi bi-files"></i> Merge PDFs</button>
                <button @click="selectTool('pdf','img-to-pdf')"  :class="activeTool==='img-to-pdf'  ? 'active pdf-active' : ''" class="tool-tab"><i class="bi bi-file-earmark-image"></i> Images to PDF</button>

                <div class="sidebar-group-label" style="color:var(--brand);">
                    <i class="bi bi-file-earmark-word-fill"></i> Document Suite
                </div>
                <button @click="selectTool('doc','word-counter')" :class="activeTool==='word-counter' ? 'active doc-active' : ''" class="tool-tab"><i class="bi bi-type"></i> Word Counter</button>
                <button @click="selectTool('doc','markdown')"     :class="activeTool==='markdown'     ? 'active doc-active' : ''" class="tool-tab"><i class="bi bi-markdown"></i> Markdown Editor</button>
                <button @click="selectTool('doc','diff')"         :class="activeTool==='diff'         ? 'active doc-active' : ''" class="tool-tab"><i class="bi bi-arrow-left-right"></i> Text Diff</button>

                <div class="sidebar-group-label" style="color:#d97706;">
                    <i class="bi bi-file-earmark-spreadsheet-fill"></i> Data Suite
                </div>
                <button @click="selectTool('data','csv-viewer')"  :class="activeTool==='csv-viewer'  ? 'active data-active' : ''" class="tool-tab"><i class="bi bi-table"></i> CSV Viewer</button>
                <button @click="selectTool('data','json-to-csv')" :class="activeTool==='json-to-csv' ? 'active data-active' : ''" class="tool-tab"><i class="bi bi-filetype-csv"></i> JSON → CSV</button>

                <div class="sidebar-group-label" style="color:#dc2626;">
                    <i class="bi bi-image-fill"></i> Photo Suite
                </div>
                <button @click="selectTool('photo','compressor')" :class="activeTool==='compressor' ? 'active photo-active' : ''" class="tool-tab"><i class="bi bi-aspect-ratio"></i> Compress & Resize</button>
                <button @click="selectTool('photo','metadata')"   :class="activeTool==='metadata'   ? 'active photo-active' : ''" class="tool-tab"><i class="bi bi-info-circle"></i> EXIF & Info</button>

                <div class="sidebar-group-label" style="color:#0891b2;">
                    <i class="bi bi-code-slash"></i> Developer Tools
                </div>
                <button @click="selectTool('dev','base64-converter')"    :class="activeTool==='base64-converter'    ? 'active dev-active' : ''" class="tool-tab"><i class="bi bi-braces"></i> Base64</button>
                <button @click="selectTool('dev','url-encoder-decoder')" :class="activeTool==='url-encoder-decoder' ? 'active dev-active' : ''" class="tool-tab"><i class="bi bi-link-45deg"></i> URL Encode</button>

            </div>
        </div>

        {{-- ── Tool Workspace ───────────────────────────── --}}
        <div class="col-lg-9">

            {{-- PDF Merge --}}
            <div x-show="activeTool === 'pdf-merge'" class="tool-panel"
                 x-data="{
                     files: [], isDragging: false,
                     addFiles(f) { for(let x of f) if(x.type==='application/pdf') this.files.push({file:x,name:x.name,size:(x.size/1024/1024).toFixed(2)}); },
                     removeFile(i) { this.files.splice(i,1); },
                     moveUp(i) { if(i>0){let t=this.files[i];this.files[i]=this.files[i-1];this.files[i-1]=t;} },
                     moveDown(i) { if(i<this.files.length-1){let t=this.files[i];this.files[i]=this.files[i+1];this.files[i+1]=t;} },
                     async mergePDFs() {
                         if(this.files.length<2){alert('Add at least 2 PDFs.');return;}
                         const m=await PDFLib.PDFDocument.create();
                         for(let item of this.files){const b=await item.file.arrayBuffer();const p=await PDFLib.PDFDocument.load(b);const cp=await m.copyPages(p,p.getPageIndices());cp.forEach(pg=>m.addPage(pg));}
                         const b=await m.save();const blob=new Blob([b],{type:'application/pdf'});const a=document.createElement('a');a.href=URL.createObjectURL(blob);a.download='merged.pdf';a.click();
                     }
                 }">
                <div class="tool-panel-header">
                    <div>
                        <h3 class="tool-panel-title"><i class="bi bi-files" style="color:#6366f1;"></i> PDF Merger</h3>
                        <p class="tool-panel-desc">Combine multiple PDFs into one. Drag to reorder pages.</p>
                    </div>
                </div>
                <div class="tool-body">
                    <div class="dropzone mb-4"
                         :class="isDragging?'dragover':''"
                         @dragover.prevent="isDragging=true" @dragleave.prevent="isDragging=false"
                         @drop.prevent="isDragging=false;addFiles($event.dataTransfer.files)"
                         @click="$refs.pdfInput.click()">
                        <input type="file" x-ref="pdfInput" class="d-none" multiple accept="application/pdf" @change="addFiles($event.target.files)">
                        <div class="dropzone-icon"><i class="bi bi-file-earmark-pdf-fill"></i></div>
                        <p class="dropzone-title">Drop your PDF files here</p>
                        <p class="dropzone-sub">or click to browse — PDF only</p>
                    </div>

                    <template x-if="files.length > 0">
                        <div class="mb-4">
                            <div class="d-flex align-items-center justify-content-between mb-2">
                                <span class="small fw-bold" style="color:var(--muted-light); letter-spacing:.06em; text-transform:uppercase; font-size:.68rem;">Queue — <span x-text="files.length"></span> files</span>
                            </div>
                            <div class="d-flex flex-column gap-2">
                                <template x-for="(item, index) in files" :key="index">
                                    <div class="file-row">
                                        <div class="d-flex align-items-center gap-2 overflow-hidden flex-grow-1">
                                            <span class="file-index" x-text="index+1"></span>
                                            <i class="bi bi-file-pdf" style="color:#ef4444; font-size:1.1rem; flex-shrink:0;"></i>
                                            <span class="text-truncate fw-medium" style="font-size:.82rem;" x-text="item.name"></span>
                                            <span style="color:var(--muted-light); font-size:.72rem; flex-shrink:0;" x-text="item.size+' MB'"></span>
                                        </div>
                                        <div class="d-flex gap-1 flex-shrink-0">
                                            <button @click.stop="moveUp(index)" class="btn-tool btn-tool-outline py-1 px-2" :disabled="index===0"><i class="bi bi-arrow-up" style="font-size:.7rem;"></i></button>
                                            <button @click.stop="moveDown(index)" class="btn-tool btn-tool-outline py-1 px-2" :disabled="index===files.length-1"><i class="bi bi-arrow-down" style="font-size:.7rem;"></i></button>
                                            <button @click.stop="removeFile(index)" class="btn-tool btn-tool-outline py-1 px-2" style="color:#ef4444; border-color:rgba(239,68,68,.3);"><i class="bi bi-trash" style="font-size:.7rem;"></i></button>
                                        </div>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </template>

                    <div class="d-flex justify-content-end">
                        <button class="btn-tool btn-tool-indigo" @click="mergePDFs" :disabled="files.length < 2">
                            <i class="bi bi-file-earmark-zip"></i> Merge & Download
                        </button>
                    </div>
                </div>
            </div>

            {{-- Images to PDF --}}
            <div x-show="activeTool === 'img-to-pdf'" class="tool-panel"
                 x-data="{
                     images:[], isDragging:false,
                     addImages(f){for(let x of f)if(x.type.startsWith('image/')){const r=new FileReader();r.onload=(e)=>{this.images.push({file:x,name:x.name,dataUrl:e.target.result})};r.readAsDataURL(x);}},
                     removeImage(i){this.images.splice(i,1);},
                     async generatePDF(){
                         if(!this.images.length)return;
                         const{jsPDF}=window.jspdf;const doc=new jsPDF();
                         for(let i=0;i<this.images.length;i++){if(i>0)doc.addPage();const img=this.images[i];const o=new Image();o.src=img.dataUrl;await new Promise(r=>o.onload=r);const pw=doc.internal.pageSize.getWidth();const ph=doc.internal.pageSize.getHeight();const ratio=Math.min((pw-20)/o.width,(ph-20)/o.height);const fw=o.width*ratio;const fh=o.height*ratio;doc.addImage(img.dataUrl,'JPEG',(pw-fw)/2,(ph-fh)/2,fw,fh);}
                         doc.save('images.pdf');
                     }
                 }">
                <div class="tool-panel-header">
                    <div>
                        <h3 class="tool-panel-title"><i class="bi bi-file-earmark-image" style="color:#6366f1;"></i> Images to PDF</h3>
                        <p class="tool-panel-desc">Convert JPEG, PNG, or WebP images into a compiled PDF file.</p>
                    </div>
                </div>
                <div class="tool-body">
                    <div class="dropzone mb-4"
                         :class="isDragging?'dragover':''"
                         @dragover.prevent="isDragging=true" @dragleave.prevent="isDragging=false"
                         @drop.prevent="isDragging=false;addImages($event.dataTransfer.files)"
                         @click="$refs.imgInput.click()">
                        <input type="file" x-ref="imgInput" class="d-none" multiple accept="image/*" @change="addImages($event.target.files)">
                        <div class="dropzone-icon"><i class="bi bi-images"></i></div>
                        <p class="dropzone-title">Drop your images here</p>
                        <p class="dropzone-sub">JPEG · PNG · WebP supported</p>
                    </div>
                    <template x-if="images.length > 0">
                        <div class="mb-4">
                            <div class="row row-cols-2 row-cols-md-4 g-3">
                                <template x-for="(img, index) in images" :key="index">
                                    <div class="col">
                                        <div class="border rounded overflow-hidden position-relative" style="border-color:var(--card-border) !important;">
                                            <img :src="img.dataUrl" class="w-100 object-fit-cover" style="height:110px;">
                                            <div style="padding:.5rem;">
                                                <p class="mb-1 text-truncate" style="font-size:.72rem; color:var(--muted);" x-text="img.name"></p>
                                                <button @click.stop="removeImage(index)" class="btn-tool btn-tool-outline w-100 py-1" style="font-size:.72rem; color:#ef4444; border-color:rgba(239,68,68,.3);">
                                                    <i class="bi bi-trash"></i> Remove
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </template>
                    <div class="d-flex justify-content-end">
                        <button class="btn-tool btn-tool-indigo" @click="generatePDF" :disabled="images.length === 0">
                            <i class="bi bi-filetype-pdf"></i> Generate PDF
                        </button>
                    </div>
                </div>
            </div>

            {{-- Word Counter --}}
            <div x-show="activeTool === 'word-counter'" class="tool-panel"
                 x-data="{
                     text:'',
                     get counts(){
                         const c=this.text.trim();
                         return {
                             words: c===''?0:c.split(/\s+/).length,
                             characters: this.text.length,
                             charNoSpaces: this.text.replace(/\s/g,'').length,
                             sentences: c===''?0:c.split(/[.!?]+/).filter(Boolean).length,
                             paragraphs: c===''?0:c.split(/\n+/).filter(Boolean).length,
                             readTime: Math.max(1,Math.ceil((c===''?0:c.split(/\s+/).length)/200)),
                             speakTime: Math.max(1,Math.ceil((c===''?0:c.split(/\s+/).length)/130))
                         }
                     }
                 }">
                <div class="tool-panel-header">
                    <div>
                        <h3 class="tool-panel-title"><i class="bi bi-type" style="color:var(--brand);"></i> Word & Text Counter</h3>
                        <p class="tool-panel-desc">Real-time word count, reading time, and text statistics.</p>
                    </div>
                </div>
                <div class="tool-body">
                    <div class="row g-4">
                        <div class="col-md-8">
                            <textarea class="form-control" rows="12"
                                      placeholder="Paste or type your text here…"
                                      x-model="text"
                                      style="resize:vertical; font-size:.875rem; line-height:1.7; border-color:var(--card-border); background:var(--surface-2); color:var(--ink);"></textarea>
                        </div>
                        <div class="col-md-4">
                            <div class="d-flex flex-column gap-2">
                                <div class="stat-box">
                                    <span class="stat-box-label">Words</span>
                                    <span class="stat-box-value" style="color:var(--brand);" x-text="counts.words"></span>
                                </div>
                                <div class="stat-box">
                                    <span class="stat-box-label">Characters</span>
                                    <span class="stat-box-value" x-text="counts.characters"></span>
                                </div>
                                <div class="stat-box">
                                    <span class="stat-box-label">No Spaces</span>
                                    <span class="stat-box-value" x-text="counts.charNoSpaces"></span>
                                </div>
                                <div class="stat-box">
                                    <span class="stat-box-label">Sentences</span>
                                    <span class="stat-box-value" x-text="counts.sentences"></span>
                                </div>
                                <div class="stat-box">
                                    <span class="stat-box-label">Paragraphs</span>
                                    <span class="stat-box-value" x-text="counts.paragraphs"></span>
                                </div>
                                <div class="stat-box">
                                    <span class="stat-box-label">Read time</span>
                                    <span class="stat-box-value" x-text="counts.readTime+' min'"></span>
                                </div>
                                <div class="stat-box">
                                    <span class="stat-box-label">Speak time</span>
                                    <span class="stat-box-value" x-text="counts.speakTime+' min'"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Markdown Editor --}}
            <div x-show="activeTool === 'markdown'" class="tool-panel"
                 x-data="{
                     rawMarkdown: '# Live Markdown Preview\n\nEdit this text to see changes live!\n\n## Lists\n1. First item\n2. Second item\n\n## Formats\n**Bold Text** and *Italic Text*\n\n```js\nconsole.log(\"Hello, World!\");\n```\n\n> Run entirely in the browser. No uploads.',
                     get htmlContent() { return marked.parse(this.rawMarkdown); }
                 }">
                <div class="tool-panel-header">
                    <div>
                        <h3 class="tool-panel-title"><i class="bi bi-markdown" style="color:var(--brand);"></i> Markdown Live Editor</h3>
                        <p class="tool-panel-desc">Write Markdown on the left and preview the rendered HTML on the right.</p>
                    </div>
                </div>
                <div class="tool-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="small fw-bold mb-2 d-block" style="color:var(--muted-light); letter-spacing:.08em; text-transform:uppercase; font-size:.65rem;">Markdown</label>
                            <textarea class="mono-area form-control p-3" rows="14" x-model="rawMarkdown"></textarea>
                        </div>
                        <div class="col-md-6">
                            <label class="small fw-bold mb-2 d-block" style="color:var(--muted-light); letter-spacing:.08em; text-transform:uppercase; font-size:.65rem;">Preview</label>
                            <div class="form-control overflow-auto p-4"
                                 style="height:328px; background:var(--surface-2); border-color:var(--card-border); color:var(--ink); line-height:1.7;"
                                 x-html="htmlContent"></div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Text Diff --}}
            <div x-show="activeTool === 'diff'" class="tool-panel"
                 x-data="{
                     textA: 'Laravel is a web application framework.\nIt uses PHP for structure.\nDesigned by Taylor Otwell.',
                     textB: 'Laravel is an open-source web application framework.\nIt uses PHP for structure.\nDesigned & developed by Taylor Otwell.',
                     diffLines:[],
                     compare(){
                         const a=this.textA.split('\n'),b=this.textB.split('\n'),max=Math.max(a.length,b.length),d=[];
                         for(let i=0;i<max;i++){const la=a[i]||'',lb=b[i]||'';d.push({status:la===lb?'normal':'changed',a:la,b:lb});}
                         this.diffLines=d;
                     }
                 }" x-init="compare()">
                <div class="tool-panel-header">
                    <div>
                        <h3 class="tool-panel-title"><i class="bi bi-arrow-left-right" style="color:var(--brand);"></i> Text Diff Compare</h3>
                        <p class="tool-panel-desc">Highlight line-by-line differences between two text versions.</p>
                    </div>
                </div>
                <div class="tool-body">
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="small fw-bold mb-2 d-block" style="color:var(--muted-light); text-transform:uppercase; font-size:.65rem; letter-spacing:.08em;">Original (A)</label>
                            <textarea class="form-control" rows="6" x-model="textA" @input="compare()"
                                      style="font-size:.82rem; background:var(--surface-2); border-color:var(--card-border); color:var(--ink);"></textarea>
                        </div>
                        <div class="col-md-6">
                            <label class="small fw-bold mb-2 d-block" style="color:var(--muted-light); text-transform:uppercase; font-size:.65rem; letter-spacing:.08em;">Modified (B)</label>
                            <textarea class="form-control" rows="6" x-model="textB" @input="compare()"
                                      style="font-size:.82rem; background:var(--surface-2); border-color:var(--card-border); color:var(--ink);"></textarea>
                        </div>
                    </div>
                    <label class="small fw-bold mb-2 d-block" style="color:var(--muted-light); text-transform:uppercase; font-size:.65rem; letter-spacing:.08em;">Comparison</label>
                    <div class="row g-2" style="font-size:.8rem; font-family:monospace;">
                        <div class="col-6">
                            <div class="border rounded overflow-auto p-2" style="max-height:220px; background:var(--surface-2); border-color:var(--card-border) !important;">
                                <template x-for="(line, idx) in diffLines" :key="'a-'+idx">
                                    <div class="px-2 py-1 text-truncate rounded mb-1"
                                         :class="line.status==='changed' ? 'diff-removed' : ''"
                                         x-text="line.a || ' '"></div>
                                </template>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="border rounded overflow-auto p-2" style="max-height:220px; background:var(--surface-2); border-color:var(--card-border) !important;">
                                <template x-for="(line, idx) in diffLines" :key="'b-'+idx">
                                    <div class="px-2 py-1 text-truncate rounded mb-1"
                                         :class="line.status==='changed' ? 'diff-added' : ''"
                                         x-text="line.b || ' '"></div>
                                </template>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- CSV Viewer --}}
            <div x-show="activeTool === 'csv-viewer'" class="tool-panel"
                 x-data="{
                     headers:[],rows:[],search:'',isDragging:false,sortCol:null,sortDesc:false,
                     parseCSV(f){Papa.parse(f,{header:true,skipEmptyLines:true,complete:(r)=>{if(r.data.length>0){this.headers=Object.keys(r.data[0]);this.rows=r.data;}}});},
                     get filteredRows(){
                         let r=this.rows;
                         if(this.search.trim()){const s=this.search.toLowerCase();r=r.filter(row=>Object.values(row).some(v=>String(v).toLowerCase().includes(s)));}
                         if(this.sortCol!==null){r=[...r].sort((a,b)=>{const va=String(a[this.sortCol]),vb=String(b[this.sortCol]);return this.sortDesc?vb.localeCompare(va,undefined,{numeric:true}):va.localeCompare(vb,undefined,{numeric:true});});}
                         return r;
                     },
                     setSort(h){this.sortCol===h?this.sortDesc=!this.sortDesc:(this.sortCol=h,this.sortDesc=false);}
                 }">
                <div class="tool-panel-header">
                    <div>
                        <h3 class="tool-panel-title"><i class="bi bi-table" style="color:#d97706;"></i> CSV Table Viewer</h3>
                        <p class="tool-panel-desc">Load any CSV spreadsheet — search, sort, and explore data instantly.</p>
                    </div>
                </div>
                <div class="tool-body">
                    <div class="dropzone mb-4" x-show="rows.length===0"
                         :class="isDragging?'dragover':''"
                         @dragover.prevent="isDragging=true" @dragleave.prevent="isDragging=false"
                         @drop.prevent="isDragging=false;parseCSV($event.dataTransfer.files[0])"
                         @click="$refs.csvInput.click()">
                        <input type="file" x-ref="csvInput" class="d-none" accept=".csv" @change="parseCSV($event.target.files[0])">
                        <div class="dropzone-icon" style="color:#d97706;"><i class="bi bi-filetype-csv"></i></div>
                        <p class="dropzone-title">Drop your CSV file here</p>
                        <p class="dropzone-sub">or click to browse</p>
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
                                <button class="btn-tool btn-tool-outline ms-auto" @click="rows=[];headers=[];search='';sortCol=null;">
                                    <i class="bi bi-arrow-left"></i> New file
                                </button>
                            </div>
                            <div class="table-responsive border rounded" style="max-height:380px; border-color:var(--card-border) !important; background:var(--card-bg);">
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

            {{-- JSON to CSV --}}
            <div x-show="activeTool === 'json-to-csv'" class="tool-panel"
                 x-data="{
                     jsonText: '[{\"name\":\"Alice\",\"role\":\"Developer\",\"city\":\"Mumbai\"},{\"name\":\"Bob\",\"role\":\"Designer\",\"city\":\"Delhi\"}]',
                     downloadCSV(){
                         try{const d=JSON.parse(this.jsonText);if(!Array.isArray(d)){alert('Must be a JSON array.');return;}const csv=Papa.unparse(d);const blob=new Blob([csv],{type:'text/csv;charset=utf-8;'});const a=document.createElement('a');a.href=URL.createObjectURL(blob);a.download='converted.csv';a.click();}catch(e){alert('Invalid JSON: '+e.message);}
                     }
                 }">
                <div class="tool-panel-header">
                    <div>
                        <h3 class="tool-panel-title"><i class="bi bi-filetype-csv" style="color:#d97706;"></i> JSON → CSV Converter</h3>
                        <p class="tool-panel-desc">Paste a JSON array and download it as a spreadsheet-ready CSV.</p>
                    </div>
                </div>
                <div class="tool-body">
                    <label class="small fw-bold mb-2 d-block" style="color:var(--muted-light); text-transform:uppercase; font-size:.65rem; letter-spacing:.08em;">JSON Input (Array of Objects)</label>
                    <textarea class="mono-area form-control p-3 mb-4" rows="8" x-model="jsonText"></textarea>
                    <div class="d-flex justify-content-end">
                        <button class="btn-tool btn-tool-amber" @click="downloadCSV">
                            <i class="bi bi-download"></i> Convert & Download
                        </button>
                    </div>
                </div>
            </div>

            {{-- Image Compressor --}}
            <div x-show="activeTool === 'compressor'" class="tool-panel"
                 x-data="{
                     file:null,originalSize:0,compressedSize:0,originalUrl:'',outputUrl:'',
                     width:0,height:0,aspectRatio:1,quality:80,format:'image/jpeg',keepRatio:true,isDragging:false,
                     loadImage(f){this.file=f;this.originalSize=(f.size/1024).toFixed(1);this.originalUrl=URL.createObjectURL(f);const img=new Image();img.src=this.originalUrl;img.onload=()=>{this.width=img.width;this.height=img.height;this.aspectRatio=img.width/img.height;this.compress();};},
                     widthChanged(){if(this.keepRatio)this.height=Math.round(this.width/this.aspectRatio);this.compress();},
                     heightChanged(){if(this.keepRatio)this.width=Math.round(this.height*this.aspectRatio);this.compress();},
                     compress(){if(!this.file)return;const img=new Image();img.src=this.originalUrl;img.onload=()=>{const c=document.createElement('canvas');c.width=this.width;c.height=this.height;c.getContext('2d').drawImage(img,0,0,this.width,this.height);c.toBlob((b)=>{this.compressedSize=(b.size/1024).toFixed(1);if(this.outputUrl)URL.revokeObjectURL(this.outputUrl);this.outputUrl=URL.createObjectURL(b);},this.format,this.quality/100);};},
                     download(){if(!this.outputUrl)return;const a=document.createElement('a');a.href=this.outputUrl;a.download='optimized.'+this.format.split('/')[1];a.click();}
                 }">
                <div class="tool-panel-header">
                    <div>
                        <h3 class="tool-panel-title"><i class="bi bi-aspect-ratio" style="color:#dc2626;"></i> Image Compressor & Resizer</h3>
                        <p class="tool-panel-desc">Compress, resize, and convert images client-side. No uploads.</p>
                    </div>
                </div>
                <div class="tool-body">
                    <div class="dropzone mb-4" x-show="!file"
                         :class="isDragging?'dragover':''"
                         @dragover.prevent="isDragging=true" @dragleave.prevent="isDragging=false"
                         @drop.prevent="isDragging=false;loadImage($event.dataTransfer.files[0])"
                         @click="$refs.imgCompInput.click()"
                         style="border-color:rgba(239,68,68,.25); --dz-hover-color:rgba(239,68,68,.05);">
                        <input type="file" x-ref="imgCompInput" class="d-none" accept="image/*" @change="loadImage($event.target.files[0])">
                        <div class="dropzone-icon" style="color:#dc2626;"><i class="bi bi-file-earmark-image"></i></div>
                        <p class="dropzone-title">Drop your image here</p>
                        <p class="dropzone-sub">JPEG · PNG · WebP</p>
                    </div>
                    <template x-if="file">
                        <div>
                            <div class="row g-3 mb-4">
                                <div class="col-md-6">
                                    <div class="position-relative border rounded overflow-hidden" style="border-color:var(--card-border) !important;">
                                        <span class="img-preview-badge">Original · <span x-text="originalSize+' KB'"></span></span>
                                        <img :src="originalUrl" class="w-100 object-fit-cover" style="max-height:200px;">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="position-relative border rounded overflow-hidden" style="border-color:var(--card-border) !important;">
                                        <span class="img-preview-badge" style="background:rgba(22,163,74,.7);">Optimized · <span x-text="compressedSize+' KB'"></span></span>
                                        <img :src="outputUrl" class="w-100 object-fit-cover" style="max-height:200px;">
                                    </div>
                                </div>
                            </div>
                            <div class="row g-3 p-3 border rounded mb-4" style="background:var(--surface-2); border-color:var(--card-border) !important;">
                                <div class="col-md-3">
                                    <label class="small fw-bold mb-1" style="color:var(--muted); font-size:.72rem;">Width (px)</label>
                                    <input type="number" class="form-control form-control-sm" x-model.number="width" @input="widthChanged()"
                                           style="background:var(--card-bg); border-color:var(--card-border); color:var(--ink);">
                                </div>
                                <div class="col-md-3">
                                    <label class="small fw-bold mb-1" style="color:var(--muted); font-size:.72rem;">Height (px)</label>
                                    <input type="number" class="form-control form-control-sm" x-model.number="height" @input="heightChanged()"
                                           style="background:var(--card-bg); border-color:var(--card-border); color:var(--ink);">
                                </div>
                                <div class="col-md-6 d-flex align-items-end">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="keepRatio" x-model="keepRatio">
                                        <label class="form-check-label small" for="keepRatio" style="color:var(--muted); font-size:.78rem;">Keep aspect ratio</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label class="small fw-bold mb-1" style="color:var(--muted); font-size:.72rem;">Quality: <span x-text="quality+'%'"></span></label>
                                    <input type="range" class="form-range" min="10" max="100" x-model="quality" @input="compress()">
                                </div>
                                <div class="col-md-6">
                                    <label class="small fw-bold mb-1" style="color:var(--muted); font-size:.72rem;">Format</label>
                                    <select class="form-select form-select-sm" x-model="format" @change="compress()"
                                            style="background:var(--card-bg); border-color:var(--card-border); color:var(--ink);">
                                        <option value="image/jpeg">JPEG</option>
                                        <option value="image/png">PNG</option>
                                        <option value="image/webp">WebP</option>
                                    </select>
                                </div>
                            </div>
                            <div class="d-flex justify-content-between">
                                <button class="btn-tool btn-tool-outline" @click="file=null;originalUrl='';outputUrl='';">
                                    <i class="bi bi-arrow-left"></i> Change image
                                </button>
                                <button class="btn-tool btn-tool-red" @click="download()">
                                    <i class="bi bi-download"></i> Download Optimized
                                </button>
                            </div>
                        </div>
                    </template>
                </div>
            </div>

            {{-- EXIF Metadata --}}
            <div x-show="activeTool === 'metadata'" class="tool-panel"
                 x-data="{
                     file:null,metadata:[],originalUrl:'',isDragging:false,
                     readMetadata(f){this.file=f;this.originalUrl=URL.createObjectURL(f);const d=[{name:'File Name',val:f.name},{name:'File Size',val:(f.size/1024).toFixed(1)+' KB'},{name:'MIME Type',val:f.type},{name:'Last Modified',val:new Date(f.lastModified).toLocaleString()}];const img=new Image();img.src=this.originalUrl;img.onload=()=>{d.push({name:'Dimensions',val:img.width+' × '+img.height+' px'});d.push({name:'Aspect Ratio',val:(img.width/img.height).toFixed(3)});d.push({name:'Megapixels',val:((img.width*img.height)/1e6).toFixed(2)+' MP'});this.metadata=d;};}
                 }">
                <div class="tool-panel-header">
                    <div>
                        <h3 class="tool-panel-title"><i class="bi bi-info-circle" style="color:#dc2626;"></i> Image Info & Metadata</h3>
                        <p class="tool-panel-desc">Extract dimensions, file size, MIME type, and properties from any image.</p>
                    </div>
                </div>
                <div class="tool-body">
                    <div class="dropzone mb-4" x-show="!file"
                         :class="isDragging?'dragover':''"
                         @dragover.prevent="isDragging=true" @dragleave.prevent="isDragging=false"
                         @drop.prevent="isDragging=false;readMetadata($event.dataTransfer.files[0])"
                         @click="$refs.metaInput.click()"
                         style="border-color:rgba(239,68,68,.25);">
                        <input type="file" x-ref="metaInput" class="d-none" accept="image/*" @change="readMetadata($event.target.files[0])">
                        <div class="dropzone-icon" style="color:#dc2626;"><i class="bi bi-info-circle"></i></div>
                        <p class="dropzone-title">Drop an image to inspect</p>
                        <p class="dropzone-sub">No data is uploaded — fully local</p>
                    </div>
                    <template x-if="file">
                        <div>
                            <div class="row g-4 mb-4">
                                <div class="col-md-5">
                                    <div class="border rounded overflow-hidden" style="border-color:var(--card-border) !important;">
                                        <img :src="originalUrl" class="w-100 object-fit-contain" style="max-height:250px; background:var(--surface-2);">
                                    </div>
                                </div>
                                <div class="col-md-7">
                                    <label class="small fw-bold mb-3 d-block" style="color:var(--muted-light); text-transform:uppercase; font-size:.65rem; letter-spacing:.08em;">Attributes</label>
                                    <div class="d-flex flex-column gap-2">
                                        <template x-for="item in metadata" :key="item.name">
                                            <div class="stat-box">
                                                <span class="stat-box-label" x-text="item.name"></span>
                                                <span style="font-size:.82rem; font-weight:600; color:var(--ink);" x-text="item.val"></span>
                                            </div>
                                        </template>
                                    </div>
                                </div>
                            </div>
                            <button class="btn-tool btn-tool-outline" @click="file=null;originalUrl='';metadata=[];">
                                <i class="bi bi-arrow-left"></i> Analyze another
                            </button>
                        </div>
                    </template>
                </div>
            </div>

            {{-- Base64 --}}
            <div x-show="activeTool === 'base64-converter'" class="tool-panel"
                 x-data="{ inputText:'', outputText:'', encode(){this.outputText=btoa(this.inputText);}, decode(){try{this.outputText=atob(this.inputText);}catch(e){alert('Invalid Base64: '+e.message);}}, clear(){this.inputText='';this.outputText='';} }">
                <div class="tool-panel-header">
                    <div>
                        <h3 class="tool-panel-title"><i class="bi bi-braces" style="color:#0891b2;"></i> Base64 Encode / Decode</h3>
                        <p class="tool-panel-desc">Convert text to Base64 or decode Base64 back to plain text.</p>
                    </div>
                </div>
                <div class="tool-body">
                    <label class="small fw-bold mb-2 d-block" style="color:var(--muted-light); text-transform:uppercase; font-size:.65rem; letter-spacing:.08em;">Input</label>
                    <textarea class="mono-area form-control p-3 mb-3" rows="5" x-model="inputText" placeholder="Enter text to encode or a Base64 string to decode…"></textarea>
                    <div class="d-flex gap-2 mb-3">
                        <button class="btn-tool btn-tool-cyan" @click="encode()"><i class="bi bi-arrow-right-square"></i> Encode</button>
                        <button class="btn-tool" @click="decode()" style="background:rgba(6,182,212,.12); color:#0891b2; border:1px solid rgba(6,182,212,.25);"><i class="bi bi-arrow-left-square"></i> Decode</button>
                        <button class="btn-tool btn-tool-outline ms-auto" @click="clear()"><i class="bi bi-x-circle"></i> Clear</button>
                    </div>
                    <label class="small fw-bold mb-2 d-block" style="color:var(--muted-light); text-transform:uppercase; font-size:.65rem; letter-spacing:.08em;">Output</label>
                    <textarea class="mono-area form-control p-3" rows="5" x-model="outputText" readonly placeholder="Result appears here…"></textarea>
                </div>
            </div>

            {{-- URL Encoder --}}
            <div x-show="activeTool === 'url-encoder-decoder'" class="tool-panel"
                 x-data="{ inputText:'', outputText:'', encode(){this.outputText=encodeURIComponent(this.inputText);}, decode(){try{this.outputText=decodeURIComponent(this.inputText);}catch(e){alert('Invalid URL string: '+e.message);}}, clear(){this.inputText='';this.outputText='';} }">
                <div class="tool-panel-header">
                    <div>
                        <h3 class="tool-panel-title"><i class="bi bi-link-45deg" style="color:#0891b2;"></i> URL Encode / Decode</h3>
                        <p class="tool-panel-desc">Encode special characters for URLs or decode percent-encoded strings.</p>
                    </div>
                </div>
                <div class="tool-body">
                    <label class="small fw-bold mb-2 d-block" style="color:var(--muted-light); text-transform:uppercase; font-size:.65rem; letter-spacing:.08em;">Input</label>
                    <textarea class="mono-area form-control p-3 mb-3" rows="5" x-model="inputText" placeholder="Enter a URL or text to encode, or a percent-encoded string to decode…"></textarea>
                    <div class="d-flex gap-2 mb-3">
                        <button class="btn-tool btn-tool-cyan" @click="encode()"><i class="bi bi-arrow-right-square"></i> Encode</button>
                        <button class="btn-tool" @click="decode()" style="background:rgba(6,182,212,.12); color:#0891b2; border:1px solid rgba(6,182,212,.25);"><i class="bi bi-arrow-left-square"></i> Decode</button>
                        <button class="btn-tool btn-tool-outline ms-auto" @click="clear()"><i class="bi bi-x-circle"></i> Clear</button>
                    </div>
                    <label class="small fw-bold mb-2 d-block" style="color:var(--muted-light); text-transform:uppercase; font-size:.65rem; letter-spacing:.08em;">Output</label>
                    <textarea class="mono-area form-control p-3" rows="5" x-model="outputText" readonly placeholder="Result appears here…"></textarea>
                </div>
            </div>

        </div>
    </div>
</div>

@endsection