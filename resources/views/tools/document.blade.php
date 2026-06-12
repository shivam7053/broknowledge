{{-- resources/views/tools/document.blade.php --}}
@extends('layouts.app')
@section('title', 'Document Tools')

@section('content')
<script src="https://cdn.jsdelivr.net/npm/marked/marked.min.js"></script>

<style>
.tool-panel{background:var(--card-bg);border:1px solid var(--card-border);box-shadow:var(--card-shadow);border-radius:var(--radius-lg);overflow:hidden;}
.tool-panel-header{padding:1.25rem 1.5rem;border-bottom:1px solid var(--card-border);background:var(--surface-2);}
.tool-panel-title{font-family:var(--font-display);font-size:1rem;font-weight:700;color:var(--ink);margin:0 0 .2rem;display:flex;align-items:center;gap:.5rem;}
.tool-panel-desc{font-size:.78rem;color:var(--muted);margin:0;}
.tool-body{padding:1.5rem;}
.btn-tool{display:inline-flex;align-items:center;gap:.4rem;font-size:.82rem;font-weight:700;padding:.58rem 1.2rem;border-radius:var(--radius-sm);border:none;cursor:pointer;transition:opacity .15s,transform .15s;}
.btn-tool:hover:not(:disabled){opacity:.88;transform:translateY(-1px);}
.btn-tool:disabled{opacity:.5;cursor:not-allowed;transform:none;}
.btn-green{background:#16a34a;color:#fff;box-shadow:0 3px 10px rgba(22,163,74,.3);}
.btn-outline{background:transparent;border:1.5px solid var(--card-border);color:var(--muted);}
.btn-outline:hover:not(:disabled){border-color:#16a34a;color:#16a34a;background:rgba(22,163,74,.05);transform:none;opacity:1;}
.mono-area{font-family:'Fira Code',monospace;font-size:.82rem;line-height:1.65;background:#0d1117 !important;color:#e6edf3 !important;border:1px solid rgba(255,255,255,.08) !important;border-radius:var(--radius-sm) !important;resize:vertical;}
.stat-box{padding:.8rem 1rem;border-radius:var(--radius-sm);background:var(--surface-2);border:1px solid var(--card-border);display:flex;justify-content:space-between;align-items:center;}
.diff-removed{background:rgba(239,68,68,.08);color:#ef4444;border-left:3px solid #ef4444;}
.diff-added{background:rgba(22,163,74,.08);color:#16a34a;border-left:3px solid #16a34a;}
</style>

<div class="container-fluid px-lg-5 py-2"
     x-data="{ activeTool: 'word-counter' }">

    <div class="row g-4">
        {{-- Sidebar --}}
        <div class="col-lg-3 reveal reveal-left">
            @include('tools._sidebar', ['active' => 'document'])

            <div class="mt-3" style="background:var(--card-bg);border:1px solid var(--card-border);border-radius:var(--radius-lg);padding:1rem;box-shadow:var(--card-shadow);">
                <p class="mb-2" style="font-size:.65rem;font-weight:700;letter-spacing:.1em;text-transform:uppercase;color:var(--muted);padding:.25rem .5rem;">Document Tools</p>
                @php
                $tools = [
                    ['id'=>'word-counter',   'icon'=>'bi-type',              'label'=>'Word Counter'],
                    ['id'=>'markdown',       'icon'=>'bi-markdown',          'label'=>'Markdown Editor'],
                    ['id'=>'diff',           'icon'=>'bi-arrow-left-right',  'label'=>'Text Diff'],
                    ['id'=>'case-converter', 'icon'=>'bi-fonts',             'label'=>'Case Converter'],
                    ['id'=>'lorem-ipsum',    'icon'=>'bi-paragraph',         'label'=>'Lorem Ipsum'],
                    ['id'=>'find-replace',   'icon'=>'bi-search-heart',      'label'=>'Find & Replace'],
                    ['id'=>'text-sort',      'icon'=>'bi-sort-alpha-down',   'label'=>'Text Sorter'],
                    ['id'=>'dedup',          'icon'=>'bi-layers',            'label'=>'Remove Duplicates'],
                    ['id'=>'text-to-pdf',    'icon'=>'bi-filetype-pdf',      'label'=>'Text to PDF'],
                    ['id'=>'line-numbering', 'icon'=>'bi-list-ol',           'label'=>'Line Numbering'],
                    ['id'=>'pdf-to-word',    'icon'=>'bi-filetype-doc',      'label'=>'PDF to Word (Pro)'],
                ];
                @endphp
                @foreach($tools as $t)
                <button @click="activeTool = '{{ $t['id'] }}'"
                        class="d-flex align-items-center gap-2 w-100 px-3 py-2 rounded mb-1 text-start border-0"
                        style="background:none;font-size:.8rem;font-weight:600;color:var(--muted);cursor:pointer;transition:all .15s;"
                        x-bind:style="activeTool==='{{ $t['id'] }}'?'background:rgba(22,163,74,.1);color:#16a34a;':''">
                    <i class="bi {{ $t['icon'] }}"></i> {{ $t['label'] }}
                </button>
                @endforeach
            </div>
        </div>

        <div class="col-lg-9">

            {{-- 1. Word Counter --}}
            <div x-show="activeTool === 'word-counter'" class="tool-panel"
                 x-data="{
                     text:'',
                     get counts(){
                         const c=this.text.trim();
                         const words=c===''?0:c.split(/\s+/).length;
                         return{
                             words,characters:this.text.length,
                             charNoSpaces:this.text.replace(/\s/g,'').length,
                             sentences:c===''?0:c.split(/[.!?]+/).filter(Boolean).length,
                             paragraphs:c===''?0:c.split(/\n+/).filter(Boolean).length,
                             readTime:Math.max(1,Math.ceil(words/200)),
                             speakTime:Math.max(1,Math.ceil(words/130)),
                             avgWordLen:words===0?0:(c.replace(/\s/g,'').length/words).toFixed(1)
                         }
                     }
                 }">
                <div class="tool-panel-header">
                    <h3 class="tool-panel-title"><i class="bi bi-type" style="color:#16a34a;"></i> Word & Text Counter</h3>
                    <p class="tool-panel-desc">Real-time word count, reading time, and text statistics.</p>
                </div>
                <div class="tool-body">
                    <div class="row g-4">
                        <div class="col-md-8">
                            <textarea class="form-control" rows="13" placeholder="Paste or type your text here…" x-model="text"
                                      style="resize:vertical;font-size:.875rem;line-height:1.7;border-color:var(--card-border);background:var(--surface-2);color:var(--ink);"></textarea>
                            <div class="d-flex gap-2 mt-2">
                                <button class="btn-tool btn-outline py-1" @click="text=''"><i class="bi bi-x-circle"></i> Clear</button>
                                <button class="btn-tool btn-outline py-1" @click="navigator.clipboard.writeText(text)"><i class="bi bi-clipboard"></i> Copy</button>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="d-flex flex-column gap-2">
                                @foreach([['Words','words','#16a34a'],['Characters','characters',null],['No Spaces','charNoSpaces',null],['Sentences','sentences',null],['Paragraphs','paragraphs',null],['Read Time','readTime',' min'],['Speak Time','speakTime',' min'],['Avg Word Len','avgWordLen',' chars']] as [$lbl,$key,$sfx])
                                <div class="stat-box">
                                    <span style="font-size:.75rem;color:var(--muted);">{{ $lbl }}</span>
                                    <span style="font-family:var(--font-display);font-weight:700;font-size:1.05rem;color:{{ $key==='words'?'#16a34a':'var(--ink)' }};"
                                          x-text="counts.{{ $key }}{{ $sfx?'+\''.addslashes($sfx).'\'' : '' }}"></span>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- 2. Markdown Editor --}}
            <div x-show="activeTool === 'markdown'" class="tool-panel"
                 x-data="{
                     raw:'# Live Markdown Preview\n\nEdit this text to see changes **live**!\n\n## Lists\n1. First item\n2. Second item\n3. Third item\n\n## Code\n```js\nconsole.log(\"Hello, World!\");\n```\n\n> Everything runs in the browser. No uploads.',
                     async loadMarkedScript() {
                         if (!window.marked) { // Check if marked.js is already loaded
                             const script = document.createElement('script');
                             script.src = 'https://cdn.jsdelivr.net/npm/marked/marked.min.js';
                             document.head.appendChild(script);
                             await new Promise(resolve => script.onload = resolve);
                         }
                     },
                     get html(){return marked.parse(this.raw);}
                 }">
                <div class="tool-panel-header">
                    <h3 class="tool-panel-title"><i class="bi bi-markdown" style="color:#16a34a;"></i> Markdown Live Editor</h3>
                    <p class="tool-panel-desc">Write Markdown on the left, preview rendered HTML on the right.</p>
                </div>
                <div class="tool-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="small fw-bold mb-2 d-block" style="color:var(--muted);font-size:.65rem;letter-spacing:.08em;text-transform:uppercase;">Markdown</label>
                            <textarea class="mono-area form-control p-3" rows="16" x-model="raw" x-init="loadMarkedScript()"></textarea>
                        </div>
                        <div class="col-md-6">
                            <label class="small fw-bold mb-2 d-block" style="color:var(--muted);font-size:.65rem;letter-spacing:.08em;text-transform:uppercase;">Preview</label>
                            <div class="form-control overflow-auto p-4" style="height:374px;background:var(--surface-2);border-color:var(--card-border);color:var(--ink);line-height:1.7;" x-html="html"></div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- 3. Text Diff --}}
            <div x-show="activeTool === 'diff'" class="tool-panel"
                 x-data="{
                     textA:'Laravel is a web application framework.\nIt uses PHP for structure.\nDesigned by Taylor Otwell.',
                     textB:'Laravel is an open-source web application framework.\nIt uses PHP for structure.\nDesigned & developed by Taylor Otwell.',
                     diffLines:[],
                     compare(){const a=this.textA.split('\n'),b=this.textB.split('\n'),max=Math.max(a.length,b.length),d=[];for(let i=0;i<max;i++){const la=a[i]||'',lb=b[i]||'';d.push({status:la===lb?'normal':'changed',a:la,b:lb});}this.diffLines=d;}
                 }" x-init="compare()">
                <div class="tool-panel-header">
                    <h3 class="tool-panel-title"><i class="bi bi-arrow-left-right" style="color:#16a34a;"></i> Text Diff Viewer</h3>
                    <p class="tool-panel-desc">Highlight line-by-line differences between two text versions.</p>
                </div>
                <div class="tool-body">
                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="small fw-bold mb-1 d-block" style="color:var(--muted);font-size:.65rem;text-transform:uppercase;">Original (A)</label>
                            <textarea class="form-control" rows="7" x-model="textA" @input="compare()" style="font-size:.82rem;background:var(--surface-2);border-color:var(--card-border);color:var(--ink);"></textarea>
                        </div>
                        <div class="col-md-6">
                            <label class="small fw-bold mb-1 d-block" style="color:var(--muted);font-size:.65rem;text-transform:uppercase;">Modified (B)</label>
                            <textarea class="form-control" rows="7" x-model="textB" @input="compare()" style="font-size:.82rem;background:var(--surface-2);border-color:var(--card-border);color:var(--ink);"></textarea>
                        </div>
                    </div>
                    <div class="row g-2" style="font-size:.8rem;font-family:monospace;">
                        <div class="col-6">
                            <div class="border rounded overflow-auto p-2" style="max-height:200px;background:var(--surface-2);border-color:var(--card-border) !important;">
                                <template x-for="(line,idx) in diffLines" :key="'a-'+idx">
                                    <div class="px-2 py-1 rounded mb-1" :class="line.status==='changed'?'diff-removed':''" x-text="line.a||' '"></div>
                                </template>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="border rounded overflow-auto p-2" style="max-height:200px;background:var(--surface-2);border-color:var(--card-border) !important;">
                                <template x-for="(line,idx) in diffLines" :key="'b-'+idx">
                                    <div class="px-2 py-1 rounded mb-1" :class="line.status==='changed'?'diff-added':''" x-text="line.b||' '"></div>
                                </template>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- 4. Case Converter --}}
            <div x-show="activeTool === 'case-converter'" class="tool-panel"
                 x-data="{
                     text:'Hello World from Laravel Tools',
                     get upper(){return this.text.toUpperCase();},
                     get lower(){return this.text.toLowerCase();},
                     get title(){return this.text.replace(/\w\S*/g,t=>t.charAt(0).toUpperCase()+t.substring(1).toLowerCase());},
                     get sentence(){return this.text.charAt(0).toUpperCase()+this.text.slice(1).toLowerCase();},
                     get camel(){return this.text.replace(/(?:^\w|[A-Z]|\b\w)/g,(w,i)=>i===0?w.toLowerCase():w.toUpperCase()).replace(/\s+/g,'');},
                     get snake(){return this.text.toLowerCase().replace(/\s+/g,'_');},
                     get kebab(){return this.text.toLowerCase().replace(/\s+/g,'-');},
                     get pascal(){return this.text.replace(/(?:^\w|[A-Z]|\b\w)/g,w=>w.toUpperCase()).replace(/\s+/g,'');},
                     copy(v){navigator.clipboard.writeText(v);}
                 }">
                <div class="tool-panel-header">
                    <h3 class="tool-panel-title"><i class="bi bi-fonts" style="color:#16a34a;"></i> Case Converter</h3>
                    <p class="tool-panel-desc">Convert text to UPPER, lower, Title, camelCase, snake_case, kebab-case, PascalCase.</p>
                </div>
                <div class="tool-body">
                    <label class="small fw-bold mb-1 d-block" style="color:var(--muted);font-size:.72rem;text-transform:uppercase;">Input Text</label>
                    <textarea class="form-control mb-4" rows="3" x-model="text" style="background:var(--surface-2);border-color:var(--card-border);color:var(--ink);font-size:.875rem;"></textarea>
                    <div class="d-flex flex-column gap-2">
                        <template x-for="[label, val] in [['UPPERCASE',upper],['lowercase',lower],['Title Case',title],['Sentence case',sentence],['camelCase',camel],['snake_case',snake],['kebab-case',kebab],['PascalCase',pascal]]" :key="label">
                            <div class="d-flex align-items-center justify-content-between gap-3 p-3 rounded border" style="background:var(--surface-2);border-color:var(--card-border) !important;">
                                <div class="flex-grow-1 overflow-hidden">
                                    <p class="mb-1" style="font-size:.65rem;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:var(--muted);" x-text="label"></p>
                                    <p class="mb-0 text-truncate fw-medium" style="font-size:.85rem;color:var(--ink);" x-text="val"></p>
                                </div>
                                <button class="btn-tool btn-outline py-1 px-2 flex-shrink-0" @click="copy(val)"><i class="bi bi-clipboard" style="font-size:.75rem;"></i></button>
                            </div>
                        </template>
                    </div>
                </div>
            </div>

            {{-- 5. Lorem Ipsum Generator --}}
            <div x-show="activeTool === 'lorem-ipsum'" class="tool-panel"
                 x-data="{
                     count:3, type:'paragraphs', output:'',
                     lorem:'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.',
                     words:'Lorem ipsum dolor sit amet consectetur adipiscing elit sed do eiusmod tempor incididunt ut labore et dolore magna aliqua ut enim ad minim veniam quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat duis aute irure dolor reprehenderit voluptate velit esse cillum dolore eu fugiat nulla pariatur excepteur sint occaecat cupidatat non proident sunt culpa qui officia deserunt mollit anim id est laborum'.split(' '),
                     generate(){
                         const n=parseInt(this.count)||3;
                         if(this.type==='paragraphs'){this.output=Array(n).fill(this.lorem).join('\n\n');}
                         else if(this.type==='sentences'){
                             const sentences=this.lorem.split('. ');
                             const out=[];for(let i=0;i<n;i++)out.push(sentences[i%sentences.length]);
                             this.output=out.join('. ')+'.';
                         } else {
                             this.output=this.words.slice(0,n).join(' ')+'.';
                         }
                     }
                 }" x-init="generate()">
                <div class="tool-panel-header">
                    <h3 class="tool-panel-title"><i class="bi bi-paragraph" style="color:#16a34a;"></i> Lorem Ipsum Generator</h3>
                    <p class="tool-panel-desc">Generate placeholder text for mockups and designs.</p>
                </div>
                <div class="tool-body">
                    <div class="row g-3 mb-4">
                        <div class="col-md-4">
                            <label class="small fw-bold mb-1 d-block" style="color:var(--muted);font-size:.72rem;text-transform:uppercase;">Type</label>
                            <select class="form-select" x-model="type" @change="generate()" style="background:var(--surface-2);border-color:var(--card-border);color:var(--ink);">
                                <option value="paragraphs">Paragraphs</option>
                                <option value="sentences">Sentences</option>
                                <option value="words">Words</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="small fw-bold mb-1 d-block" style="color:var(--muted);font-size:.72rem;text-transform:uppercase;">Count</label>
                            <input type="number" class="form-control" x-model="count" @input="generate()" min="1" max="20" style="background:var(--surface-2);border-color:var(--card-border);color:var(--ink);">
                        </div>
                        <div class="col-md-4 d-flex align-items-end">
                            <button class="btn-tool btn-green w-100" @click="generate()"><i class="bi bi-arrow-repeat"></i> Regenerate</button>
                        </div>
                    </div>
                    <textarea class="form-control mb-3" rows="8" x-model="output" readonly style="background:var(--surface-2);border-color:var(--card-border);color:var(--ink);font-size:.85rem;line-height:1.7;"></textarea>
                    <button class="btn-tool btn-outline" @click="navigator.clipboard.writeText(output)"><i class="bi bi-clipboard"></i> Copy to Clipboard</button>
                </div>
            </div>

            {{-- 6. Find & Replace --}}
            <div x-show="activeTool === 'find-replace'" class="tool-panel"
                 x-data="{
                     text:'The quick brown fox jumps over the lazy dog.\nThe dog barked at the fox.',
                     find:'fox', replace:'cat', useRegex:false, caseSensitive:false, count:0,
                     get result(){
                         if(!this.find)return{text:this.text,count:0};
                         let flags='g'+(this.caseSensitive?'':'i');
                         let pattern=this.useRegex?new RegExp(this.find,flags):new RegExp(this.find.replace(/[.*+?^${}()|[\]\\]/g,'\\$&'),flags);
                         let matches=this.text.match(pattern);
                         this.count=matches?matches.length:0;
                         return{text:this.text.replace(pattern,this.replace),count:this.count};
                     }
                 }">
                <div class="tool-panel-header">
                    <h3 class="tool-panel-title"><i class="bi bi-search-heart" style="color:#16a34a;"></i> Find & Replace</h3>
                    <p class="tool-panel-desc">Search and replace text with optional regex and case sensitivity.</p>
                </div>
                <div class="tool-body">
                    <div class="row g-3 mb-3">
                        <div class="col-md-5">
                            <label class="small fw-bold mb-1 d-block" style="color:var(--muted);font-size:.72rem;text-transform:uppercase;">Find</label>
                            <input type="text" class="form-control" x-model="find" style="background:var(--surface-2);border-color:var(--card-border);color:var(--ink);">
                        </div>
                        <div class="col-md-5">
                            <label class="small fw-bold mb-1 d-block" style="color:var(--muted);font-size:.72rem;text-transform:uppercase;">Replace With</label>
                            <input type="text" class="form-control" x-model="replace" style="background:var(--surface-2);border-color:var(--card-border);color:var(--ink);">
                        </div>
                        <div class="col-md-2 d-flex align-items-end gap-2 flex-wrap">
                            <div class="form-check form-switch mb-0">
                                <input class="form-check-input" type="checkbox" x-model="useRegex" id="useRegex">
                                <label class="form-check-label small" for="useRegex" style="color:var(--muted);font-size:.72rem;">Regex</label>
                            </div>
                        </div>
                    </div>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="small fw-bold mb-1 d-block" style="color:var(--muted);font-size:.72rem;text-transform:uppercase;">Original</label>
                            <textarea class="form-control" rows="8" x-model="text" style="font-size:.82rem;background:var(--surface-2);border-color:var(--card-border);color:var(--ink);"></textarea>
                        </div>
                        <div class="col-md-6">
                            <label class="small fw-bold mb-1 d-block" style="color:var(--muted);font-size:.72rem;text-transform:uppercase;">Result <span class="ms-2" style="color:#16a34a;" x-text="'('+result.count+' replacements)'"></span></label>
                            <textarea class="form-control" rows="8" :value="result.text" readonly style="font-size:.82rem;background:var(--surface-2);border-color:var(--card-border);color:var(--ink);"></textarea>
                        </div>
                    </div>
                    <div class="mt-3 d-flex gap-2">
                        <button class="btn-tool btn-outline" @click="text=result.text"><i class="bi bi-check2"></i> Apply to Input</button>
                        <button class="btn-tool btn-outline" @click="navigator.clipboard.writeText(result.text)"><i class="bi bi-clipboard"></i> Copy Result</button>
                    </div>
                </div>
            </div>

            {{-- 7. Text Sorter --}}
            <div x-show="activeTool === 'text-sort'" class="tool-panel"
                 x-data="{
                     input:'Banana\nApple\nCherry\nDate\nElderfig\nApricot',
                     order:'asc', byLength:false, trim:true,
                     get sorted(){
                         let lines=this.input.split('\n');
                         if(this.trim)lines=lines.map(l=>l.trim()).filter(Boolean);
                         lines.sort((a,b)=>{
                             const cmp=this.byLength?a.length-b.length:a.localeCompare(b,undefined,{numeric:true,sensitivity:'base'});
                             return this.order==='asc'?cmp:-cmp;
                         });
                         return lines.join('\n');
                     }
                 }">
                <div class="tool-panel-header">
                    <h3 class="tool-panel-title"><i class="bi bi-sort-alpha-down" style="color:#16a34a;"></i> Text Line Sorter</h3>
                    <p class="tool-panel-desc">Sort lines alphabetically, reverse, or by length. One line per item.</p>
                </div>
                <div class="tool-body">
                    <div class="d-flex flex-wrap gap-3 mb-4">
                        <div>
                            <label class="small fw-bold mb-1 d-block" style="color:var(--muted);font-size:.72rem;text-transform:uppercase;">Order</label>
                            <div class="d-flex gap-1">
                                <button @click="order='asc'"  :class="order==='asc'?'btn-green':'btn-outline'" class="btn-tool py-1 px-3">A → Z</button>
                                <button @click="order='desc'" :class="order==='desc'?'btn-green':'btn-outline'" class="btn-tool py-1 px-3">Z → A</button>
                            </div>
                        </div>
                        <div>
                            <label class="small fw-bold mb-1 d-block" style="color:var(--muted);font-size:.72rem;text-transform:uppercase;">Sort By</label>
                            <div class="d-flex gap-1">
                                <button @click="byLength=false" :class="!byLength?'btn-green':'btn-outline'" class="btn-tool py-1 px-3">Alphabet</button>
                                <button @click="byLength=true"  :class="byLength?'btn-green':'btn-outline'"  class="btn-tool py-1 px-3">Length</button>
                            </div>
                        </div>
                    </div>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="small fw-bold mb-1 d-block" style="color:var(--muted);font-size:.72rem;text-transform:uppercase;">Input (one item per line)</label>
                            <textarea class="form-control" rows="10" x-model="input" style="font-size:.85rem;background:var(--surface-2);border-color:var(--card-border);color:var(--ink);"></textarea>
                        </div>
                        <div class="col-md-6">
                            <label class="small fw-bold mb-1 d-block" style="color:var(--muted);font-size:.72rem;text-transform:uppercase;">Sorted Output</label>
                            <textarea class="form-control" rows="10" :value="sorted" readonly style="font-size:.85rem;background:var(--surface-2);border-color:var(--card-border);color:var(--ink);"></textarea>
                        </div>
                    </div>
                    <div class="mt-3">
                        <button class="btn-tool btn-outline" @click="navigator.clipboard.writeText(sorted)"><i class="bi bi-clipboard"></i> Copy Result</button>
                    </div>
                </div>
            </div>

            {{-- 8. Remove Duplicates --}}
            <div x-show="activeTool === 'dedup'" class="tool-panel"
                 x-data="{
                     input:'apple\nbanana\napple\ncherry\nbanana\ndate',
                     caseSensitive:false, trim:true,
                     get deduped(){
                         let lines=this.input.split('\n');
                         if(this.trim)lines=lines.map(l=>l.trim());
                         const seen=new Set();
                         return lines.filter(l=>{
                             const key=this.caseSensitive?l:l.toLowerCase();
                             if(seen.has(key))return false;
                             seen.add(key);return true;
                         }).join('\n');
                     },
                     get removedCount(){
                         const orig=this.input.split('\n').length;
                         const dedup=this.deduped.split('\n').filter(Boolean).length;
                         return orig-dedup;
                     }
                 }">
                <div class="tool-panel-header">
                    <h3 class="tool-panel-title"><i class="bi bi-layers" style="color:#16a34a;"></i> Remove Duplicate Lines</h3>
                    <p class="tool-panel-desc">Strip repeated lines from any text. Preserves original order.</p>
                </div>
                <div class="tool-body">
                    <div class="d-flex gap-4 mb-4">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" x-model="trim" id="dedupTrim">
                            <label class="form-check-label small" for="dedupTrim" style="color:var(--muted);">Trim whitespace</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" x-model="caseSensitive" id="dedupCase">
                            <label class="form-check-label small" for="dedupCase" style="color:var(--muted);">Case sensitive</label>
                        </div>
                    </div>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="small fw-bold mb-1 d-block" style="color:var(--muted);font-size:.72rem;text-transform:uppercase;">Input</label>
                            <textarea class="form-control" rows="10" x-model="input" style="font-size:.85rem;background:var(--surface-2);border-color:var(--card-border);color:var(--ink);"></textarea>
                        </div>
                        <div class="col-md-6">
                            <label class="small fw-bold mb-1 d-block" style="color:var(--muted);font-size:.72rem;text-transform:uppercase;">
                                Deduplicated <span style="color:#16a34a;" x-text="'(−'+removedCount+' dupes)'"></span>
                            </label>
                            <textarea class="form-control" rows="10" :value="deduped" readonly style="font-size:.85rem;background:var(--surface-2);border-color:var(--card-border);color:var(--ink);"></textarea>
                        </div>
                    </div>
                    <div class="mt-3">
                        <button class="btn-tool btn-outline" @click="navigator.clipboard.writeText(deduped)"><i class="bi bi-clipboard"></i> Copy Result</button>
                    </div>
                </div>
            </div>

            {{-- 9. Text to PDF --}}
            <div x-show="activeTool === 'text-to-pdf'" class="tool-panel"
                 x-data="{
                     text: 'Type or paste your text here to convert it into a PDF document.',
                     fileName: 'document.pdf',
                     async convertTextToPdf() {
                         const { jsPDF } = window.jspdf;
                         const doc = new jsPDF();
                         const lines = doc.splitTextToSize(this.text, 180); // 180mm width
                         doc.text(lines, 10, 10); // 10mm margin
                         doc.save(this.fileName);
                     }
                 }">
                <div class="tool-panel-header">
                    <h3 class="tool-panel-title"><i class="bi bi-filetype-pdf" style="color:#16a34a;"></i> Text to PDF Converter</h3>
                    <p class="tool-panel-desc">Convert plain text into a downloadable PDF document.</p>
                </div>
                <div class="tool-body">
                    <label class="small fw-bold mb-2 d-block" style="color:var(--muted); text-transform:uppercase; font-size:.65rem; letter-spacing:.08em;">Text Input</label>
                    <textarea class="form-control mb-3" rows="12" x-model="text"
                              style="resize:vertical;font-size:.875rem;line-height:1.7;border-color:var(--card-border);background:var(--surface-2);color:var(--ink);"></textarea>

                    <label class="small fw-bold mb-2 d-block" style="color:var(--muted); text-transform:uppercase; font-size:.65rem; letter-spacing:.08em;">File Name</label>
                    <input type="text" class="form-control mb-4" x-model="fileName" style="background:var(--surface-2);border-color:var(--card-border);color:var(--ink);">

                    <div class="d-flex justify-content-end">
                        <button class="btn-tool btn-green" @click="convertTextToPdf()"><i class="bi bi-filetype-pdf"></i> Generate & Download PDF</button>
                    </div>
                </div>
            </div>

            {{-- 10. Line Numbering --}}
            <div x-show="activeTool === 'line-numbering'" class="tool-panel"
                 x-data="{
                     text: 'Line 1\nLine 2\nLine 3\nAnother line here.',
                     get numberedText() {
                         return this.text.split('\n').map((line, index) => `${index + 1}. ${line}`).join('\n');
                     }
                 }">
                <div class="tool-panel-header">
                    <h3 class="tool-panel-title"><i class="bi bi-list-ol" style="color:#16a34a;"></i> Line Numbering</h3>
                    <p class="tool-panel-desc">Add line numbers to each line of your text.</p>
                </div>
                <div class="tool-body">
                    <label class="small fw-bold mb-2 d-block" style="color:var(--muted); text-transform:uppercase; font-size:.65rem; letter-spacing:.08em;">Text Input</label>
                    <textarea class="form-control mb-4" rows="10" x-model="text"
                              style="resize:vertical;font-size:.875rem;line-height:1.7;border-color:var(--card-border);background:var(--surface-2);color:var(--ink);"></textarea>

                    <label class="small fw-bold mb-2 d-block" style="color:var(--muted); text-transform:uppercase; font-size:.65rem; letter-spacing:.08em;">Numbered Output</label>
                    <textarea class="mono-area form-control p-3 mb-4" rows="10" :value="numberedText" readonly></textarea>

                    <div class="d-flex justify-content-end">
                        <button class="btn-tool btn-outline" @click="navigator.clipboard.writeText(numberedText)"><i class="bi bi-clipboard"></i> Copy Numbered Text</button>
                    </div>
                </div>
            </div>

            {{-- 11. PDF to Word (Placeholder) --}}
            <div x-show="activeTool === 'pdf-to-word'" class="tool-panel"
                 x-data="{
                     file: null,
                     isDragging: false,
                     loadImage(f) {
                         if (!f || f.type !== 'application/pdf') {
                             alert('Please upload a PDF file.');
                             return;
                         }
                         this.file = f;
                     }
                 }">
                <div class="tool-panel-header">
                    <h3 class="tool-panel-title"><i class="bi bi-filetype-doc" style="color:#16a34a;"></i> PDF to Word Converter (Pro)</h3>
                    <p class="tool-panel-desc">Convert PDF documents to editable Word (.docx) format. (Advanced feature, often requires server-side processing).</p>
                </div>
                <div class="tool-body">
                    <div class="dropzone mb-4" x-show="!file" :class="isDragging?'dragover':''" @dragover.prevent="isDragging=true" @dragleave.prevent="isDragging=false" @drop.prevent="isDragging=false;loadImage($event.dataTransfer.files[0])" @click="$refs.pdfToWordInput.click()">
                        <input type="file" x-ref="pdfToWordInput" class="d-none" accept="application/pdf" @change="loadImage($event.target.files[0])">
                        <div style="font-size:2rem;color:#16a34a;margin-bottom:.6rem;"><i class="bi bi-file-earmark-word"></i></div>
                        <p style="font-weight:600;font-size:.88rem;color:var(--ink);margin-bottom:.2rem;">Drop your PDF file here</p>
                        <p style="font-size:.75rem;color:var(--muted);margin:0;">or click to browse</p>
                    </div>
                    <template x-if="file">
                        <div class="stat-row mb-4">
                            <span style="color:var(--muted);">File loaded:</span>
                            <span class="fw-bold" style="color:var(--ink);" x-text="file.name"></span>
                            <button class="btn-tool btn-outline py-1 px-2" @click="file=null"><i class="bi bi-x-circle"></i> Remove</button>
                        </div>
                    </template>
                    <p class="alert alert-info small mb-0">
                        <strong>Note:</strong> Converting PDF to Word accurately is a complex task that typically requires advanced server-side processing or specialized libraries. This feature is currently a placeholder.
                    </p>
                </div>
            </div>

        </div>{{-- /col-lg-9 --}}
    </div>{{-- /row --}}
</div>
@endsection