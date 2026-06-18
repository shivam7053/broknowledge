{{-- resources/views/tools/image.blade.php --}}
@extends('layouts.app')
@section('title', 'Free Online Image Tools — Compress, Resize & Convert')
@section('meta_description', 'Optimize and edit images client-side. Compress JPEGs, convert to WebP, resize, crop, and add watermarks instantly without uploading files.')

@section('head')
<link rel="canonical" href="{{ route('tools.image') }}">
<script type="application/ld+json">
{
    "@@context": "https://schema.org",
    "@@type": "BreadcrumbList",
    "itemListElement": [
        { "@@type": "ListItem", "position": 1, "name": "Home", "item": "{{ route('home') }}" },
        { "@@type": "ListItem", "position": 2, "name": "Office Tools", "item": "{{ route('tools.index') }}" },
        { "@@type": "ListItem", "position": 3, "name": "Image Tools", "item": "{{ route('tools.image') }}" }
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
.btn-red{background:#dc2626;color:#fff;box-shadow:0 3px 10px rgba(220,38,38,.3);}
.btn-outline{background:transparent;border:1.5px solid var(--card-border);color:var(--muted);}
.btn-outline:hover:not(:disabled){border-color:#dc2626;color:#dc2626;background:rgba(239,68,68,.05);transform:none;opacity:1;}
.dropzone{border:2px dashed rgba(239,68,68,.25);border-radius:var(--radius);background:rgba(239,68,68,.02);min-height:140px;display:flex;flex-direction:column;justify-content:center;align-items:center;cursor:pointer;transition:all .15s;text-align:center;padding:2rem;}
.dropzone:hover,.dropzone.dragover{border-color:#dc2626;background:rgba(239,68,68,.05);}
.img-badge{position:absolute;top:.5rem;left:.5rem;font-size:.62rem;font-weight:700;letter-spacing:.08em;text-transform:uppercase;background:rgba(0,0,0,.55);color:#fff;border-radius:100px;padding:.22rem .6rem;backdrop-filter:blur(4px);}
.stat-row{display:flex;justify-content:space-between;align-items:center;padding:.7rem 1rem;border:1px solid var(--card-border);border-radius:var(--radius-sm);background:var(--surface-2);font-size:.82rem;}
</style>

<div class="container-fluid px-lg-5 py-2"
     x-data="{ activeTool: 'compressor' }">

    <div class="row g-4">
        {{-- Sidebar --}}
        <div class="col-lg-3 reveal reveal-left">
            @include('tools._sidebar', ['active' => 'image'])

            <div class="mt-3" style="background:var(--card-bg);border:1px solid var(--card-border);border-radius:var(--radius-lg);padding:1rem;box-shadow:var(--card-shadow);">
                <p class="mb-2" style="font-size:.65rem;font-weight:700;letter-spacing:.1em;text-transform:uppercase;color:var(--muted);padding:.25rem .5rem;">Image Tools</p>
                @php
                $tools = [
                    ['id'=>'compressor',  'icon'=>'bi-aspect-ratio',     'label'=>'Compress & Resize'],
                    ['id'=>'converter',   'icon'=>'bi-arrow-left-right', 'label'=>'Format Converter'],
                    ['id'=>'cropper',     'icon'=>'bi-crop',             'label'=>'Crop Image'],
                    ['id'=>'grayscale',   'icon'=>'bi-circle-half',      'label'=>'Grayscale / Filter'],
                    ['id'=>'watermark',   'icon'=>'bi-droplet',          'label'=>'Text Watermark'],
                    ['id'=>'metadata',    'icon'=>'bi-info-circle',      'label'=>'EXIF & Info'],
                    ['id'=>'fliprotate',  'icon'=>'bi-arrow-clockwise',  'label'=>'Flip & Rotate'],
                ];
                @endphp
                @foreach($tools as $t)
                <button @click="activeTool = '{{ $t['id'] }}'"
                        class="d-flex align-items-center gap-2 w-100 px-3 py-2 rounded mb-1 text-start border-0"
                        style="background:none;font-size:.8rem;font-weight:600;color:var(--muted);cursor:pointer;transition:all .15s;"
                        x-bind:style="activeTool==='{{ $t['id'] }}'?'background:rgba(239,68,68,.1);color:#dc2626;':''">
                    <i class="bi {{ $t['icon'] }}"></i> {{ $t['label'] }}
                </button>
                @endforeach
            </div>
        </div>

        <div class="col-lg-9">

            {{-- 1. Compress & Resize --}}
            <div x-show="activeTool === 'compressor'" class="tool-panel"
                 x-data="{
                     file:null,originalSize:0,compressedSize:0,originalUrl:'',outputUrl:'',
                     width:0,height:0,aspectRatio:1,quality:80,format:'image/jpeg',keepRatio:true,isDragging:false,processing:false,loadedImage:null,
                     loadImage(f){
                         if(!f) return;
                         if(this.originalUrl) URL.revokeObjectURL(this.originalUrl);
                         this.file=f;this.originalSize=(f.size/1024).toFixed(1);
                         this.originalUrl=URL.createObjectURL(f);
                         this.loadedImage=new Image();
                         this.loadedImage.src=this.originalUrl;
                         this.loadedImage.onload=()=>{
                             this.width=this.loadedImage.width;this.height=this.loadedImage.height;this.aspectRatio=this.loadedImage.width/this.loadedImage.height;this.compress();
                         };
                     },
                     widthChanged(){if(this.keepRatio)this.height=Math.round(this.width/this.aspectRatio);this.compress();},
                     heightChanged(){if(this.keepRatio)this.width=Math.round(this.height*this.aspectRatio);this.compress();},
                     compress(){
                         if(!this.file || !this.loadedImage || !this.loadedImage.complete) return; this.processing=true;
                         const c=document.createElement('canvas');c.width=this.width;c.height=this.height;c.getContext('2d').drawImage(this.loadedImage,0,0,this.width,this.height);c.toBlob((b)=>{this.compressedSize=(b.size/1024).toFixed(1);if(this.outputUrl)URL.revokeObjectURL(this.outputUrl);this.outputUrl=URL.createObjectURL(b);this.processing=false;},this.format,this.quality/100);
                     },
                     download(){const a=document.createElement('a');a.href=this.outputUrl;a.download='optimized.'+this.format.split('/')[1];a.click();}
                 }">
                <div class="tool-panel-header">
                    <h3 class="tool-panel-title"><i class="bi bi-aspect-ratio" style="color:#dc2626;"></i> Compress & Resize</h3>
                    <p class="tool-panel-desc">Compress, resize, and convert images client-side. No uploads.</p>
                </div>
                <div class="tool-body">
                    <div class="dropzone mb-4" x-show="!file" :class="isDragging?'dragover':''" @dragover.prevent="isDragging=true" @dragleave.prevent="isDragging=false" @drop.prevent="isDragging=false;loadImage($event.dataTransfer.files[0])" @click="$refs.imgCompInput.click()">
                        <input type="file" x-ref="imgCompInput" class="d-none" accept="image/*" @change="loadImage($event.target.files[0])">
                        <div style="font-size:2rem;color:#dc2626;margin-bottom:.6rem;"><i class="bi bi-file-earmark-image"></i></div>
                        <p style="font-weight:600;font-size:.88rem;color:var(--ink);margin-bottom:.2rem;">Drop your image here</p>
                        <p style="font-size:.75rem;color:var(--muted);margin:0;">JPEG · PNG · WebP</p>
                    </div>
                    <template x-if="file">
                        <div>
                            <div class="row g-3 mb-4">
                                <div class="col-md-6">
                                    <div class="position-relative border rounded overflow-hidden" style="border-color:var(--card-border) !important;">
                                        <span class="img-badge">Original · <span x-text="originalSize+' KB'"></span></span>
                                        <img :src="originalUrl" class="w-100 object-fit-cover" style="max-height:200px;">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="position-relative border rounded overflow-hidden" style="border-color:var(--card-border) !important;">
                                        <span class="img-badge" style="background:rgba(22,163,74,.7);">Optimized · <span x-text="compressedSize+' KB'"></span></span>
                                        <img :src="outputUrl" class="w-100 object-fit-cover" style="max-height:200px;">
                                    </div>
                                </div>
                            </div>
                            <div class="row g-3 p-3 border rounded mb-4" style="background:var(--surface-2);border-color:var(--card-border) !important;">
                                <div class="col-md-3">
                                    <label class="small fw-bold mb-1" style="color:var(--muted);font-size:.72rem;">Width (px)</label>
                                    <input type="number" class="form-control form-control-sm" x-model.number="width" @input="widthChanged()" style="background:var(--card-bg);border-color:var(--card-border);color:var(--ink);">
                                </div>
                                <div class="col-md-3">
                                    <label class="small fw-bold mb-1" style="color:var(--muted);font-size:.72rem;">Height (px)</label>
                                    <input type="number" class="form-control form-control-sm" x-model.number="height" @input="heightChanged()" style="background:var(--card-bg);border-color:var(--card-border);color:var(--ink);">
                                </div>
                                <div class="col-md-6 d-flex align-items-end">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" x-model="keepRatio" id="keepRatio">
                                        <label class="form-check-label small" for="keepRatio" style="color:var(--muted);font-size:.78rem;">Keep aspect ratio</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label class="small fw-bold mb-1" style="color:var(--muted);font-size:.72rem;">Quality: <span x-text="quality+'%'"></span></label>
                                    <input type="range" class="form-range" min="10" max="100" x-model="quality" @input="compress()">
                                </div>
                                <div class="col-md-6">
                                    <label class="small fw-bold mb-1" style="color:var(--muted);font-size:.72rem;">Format</label>
                                    <select class="form-select form-select-sm" x-model="format" @change="compress()" style="background:var(--card-bg);border-color:var(--card-border);color:var(--ink);">
                                        <option value="image/jpeg">JPEG</option>
                                        <option value="image/png">PNG</option>
                                        <option value="image/webp">WebP</option>
                                    </select>
                                </div>
                            </div>
                            <div class="d-flex justify-content-between">
                                <button class="btn-tool btn-outline" @click="file=null;originalUrl='';outputUrl='';"><i class="bi bi-arrow-left"></i> Change image</button>
                                <button class="btn-tool btn-red" @click="download()"><i class="bi bi-download"></i> Download Optimized</button>
                            </div>
                        </div>
                    </template>
                </div>
            </div>

            {{-- 2. Format Converter --}}
            <div x-show="activeTool === 'converter'" class="tool-panel"
                 x-data="{
                     file:null, originalUrl:'', outputUrl:'', isDragging:false,
                     targetFormat:'image/png', quality:92, processing:false, loadedImage:null,
                     loadImage(f){
                         if(!f) return;
                         if(this.originalUrl) URL.revokeObjectURL(this.originalUrl);
                         this.file=f;this.originalUrl=URL.createObjectURL(f);
                         this.loadedImage=new Image();
                         this.loadedImage.src=this.originalUrl;
                         this.loadedImage.onload=()=>{ /* No initial conversion needed here, user clicks convert */ };
                     },
                     convert(){
                         if(!this.file || !this.loadedImage || !this.loadedImage.complete) return;
                         const c=document.createElement('canvas');c.width=this.loadedImage.width;c.height=this.loadedImage.height;
                         c.getContext('2d').drawImage(this.loadedImage,0,0);
                             c.toBlob((b)=>{if(this.outputUrl)URL.revokeObjectURL(this.outputUrl);this.outputUrl=URL.createObjectURL(b);},this.targetFormat,this.quality/100);
                     },
                     download(){const ext=this.targetFormat.split('/')[1];const a=document.createElement('a');a.href=this.outputUrl;a.download='converted.'+ext;a.click();}
                 }">
                <div class="tool-panel-header">
                    <h3 class="tool-panel-title"><i class="bi bi-arrow-left-right" style="color:#dc2626;"></i> Image Format Converter</h3>
                    <p class="tool-panel-desc">Convert between JPEG, PNG, and WebP formats instantly.</p>
                </div>
                <div class="tool-body">
                    <div class="dropzone mb-4" x-show="!file" :class="isDragging?'dragover':''" @dragover.prevent="isDragging=true" @dragleave.prevent="isDragging=false" @drop.prevent="isDragging=false;loadImage($event.dataTransfer.files[0])" @click="$refs.imgConvInput.click()">
                        <input type="file" x-ref="imgConvInput" class="d-none" accept="image/*" @change="loadImage($event.target.files[0])">
                        <div style="font-size:2rem;color:#dc2626;margin-bottom:.6rem;"><i class="bi bi-images"></i></div>
                        <p style="font-weight:600;font-size:.88rem;color:var(--ink);margin-bottom:.2rem;">Drop image to convert</p>
                        <p style="font-size:.75rem;color:var(--muted);margin:0;">JPEG · PNG · WebP · GIF</p>
                    </div>
                    <template x-if="file">
                        <div>
                            <div class="row g-3 p-3 border rounded mb-4" style="background:var(--surface-2);border-color:var(--card-border) !important;">
                                <div class="col-md-6">
                                    <label class="small fw-bold mb-1 d-block" style="color:var(--muted);font-size:.72rem;text-transform:uppercase;">Convert To</label>
                                    <select class="form-select" x-model="targetFormat" style="background:var(--card-bg);border-color:var(--card-border);color:var(--ink);">
                                        <option value="image/jpeg">JPEG</option>
                                        <option value="image/png">PNG</option>
                                        <option value="image/webp">WebP</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="small fw-bold mb-1 d-block" style="color:var(--muted);font-size:.72rem;text-transform:uppercase;">Quality: <span x-text="quality+'%'"></span></label>
                                    <input type="range" class="form-range mt-2" min="10" max="100" x-model="quality">
                                </div>
                            </div>
                            <template x-if="outputUrl">
                                <div class="mb-4">
                                    <img :src="outputUrl" class="rounded border w-100 object-fit-contain" style="max-height:220px;background:var(--surface-2);border-color:var(--card-border) !important;">
                                </div>
                            </template>
                            <div class="d-flex justify-content-between">
                                <button class="btn-tool btn-outline" @click="file=null;outputUrl='';"><i class="bi bi-arrow-left"></i> New image</button>
                                <div class="d-flex gap-2">
                                    <button class="btn-tool btn-outline" @click="convert()"><i class="bi bi-arrow-repeat"></i> Convert</button>
                                    <button class="btn-tool btn-red" @click="download()" :disabled="!outputUrl"><i class="bi bi-download"></i> Download</button>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>
            </div>

            {{-- 3. Crop Image --}}
            <div x-show="activeTool === 'cropper'" class="tool-panel"
                 x-data="{
                     file:null,originalUrl:'',outputUrl:'',isDragging:false,
                     x:0,y:0,w:200,h:200,imgW:0,imgH:0,loadedImage:null,
                     loadImage(f){
                        if(!f) return;
                        if(this.originalUrl) URL.revokeObjectURL(this.originalUrl);
                        this.file=f;this.originalUrl=URL.createObjectURL(f);
                        this.loadedImage=new Image();
                        this.loadedImage.src=this.originalUrl;
                        this.loadedImage.onload=()=>{
                            this.imgW=this.loadedImage.width;
                            this.imgH=this.loadedImage.height;
                            this.w=Math.round(this.imgW/2);this.h=Math.round(this.imgH/2);
                        };
                     },
                     crop(){
                         if(!this.file)return;
                         if(!this.loadedImage || !this.loadedImage.complete) return; // Ensure image is loaded

                         const c=document.createElement('canvas');c.width=this.w;c.height=this.h;
                         c.getContext('2d').drawImage(this.loadedImage,this.x,this.y,this.w,this.h,0,0,this.w,this.h);
                         if(this.outputUrl) URL.revokeObjectURL(this.outputUrl);
                         c.toBlob(blob => { this.outputUrl = URL.createObjectURL(blob); }, 'image/png');
                     },
                     download(){const a=document.createElement('a');a.href=this.outputUrl;a.download='cropped.png';a.click();}
                 }">
                <div class="tool-panel-header">
                    <h3 class="tool-panel-title"><i class="bi bi-crop" style="color:#dc2626;"></i> Crop Image</h3>
                    <p class="tool-panel-desc">Set X, Y, width, height to crop a precise region from any image.</p>
                </div>
                <div class="tool-body">
                    <div class="dropzone mb-4" x-show="!file" :class="isDragging?'dragover':''" @dragover.prevent="isDragging=true" @dragleave.prevent="isDragging=false" @drop.prevent="isDragging=false;loadImage($event.dataTransfer.files[0])" @click="$refs.imgCropInput.click()">
                        <input type="file" x-ref="imgCropInput" class="d-none" accept="image/*" @change="loadImage($event.target.files[0])">
                        <div style="font-size:2rem;color:#dc2626;margin-bottom:.6rem;"><i class="bi bi-crop"></i></div>
                        <p style="font-weight:600;font-size:.88rem;color:var(--ink);margin-bottom:.2rem;">Drop image to crop</p>
                        <p style="font-size:.75rem;color:var(--muted);margin:0;">JPEG · PNG · WebP</p>
                    </div>
                    <template x-if="file">
                        <div>
                            <div class="stat-row mb-4">
                                <span style="color:var(--muted);">Original size</span>
                                <span style="font-weight:700;color:var(--ink);" x-text="imgW+'×'+imgH+' px'"></span>
                            </div>
                            <div class="row g-3 mb-4">
                                <div class="col-6 col-md-3"><label class="small mb-1 d-block" style="color:var(--muted);font-size:.72rem;text-transform:uppercase;">X (px)</label><input type="number" class="form-control form-control-sm" x-model.number="x" style="background:var(--surface-2);border-color:var(--card-border);color:var(--ink);"></div>
                                <div class="col-6 col-md-3"><label class="small mb-1 d-block" style="color:var(--muted);font-size:.72rem;text-transform:uppercase;">Y (px)</label><input type="number" class="form-control form-control-sm" x-model.number="y" style="background:var(--surface-2);border-color:var(--card-border);color:var(--ink);"></div>
                                <div class="col-6 col-md-3"><label class="small mb-1 d-block" style="color:var(--muted);font-size:.72rem;text-transform:uppercase;">Width</label><input type="number" class="form-control form-control-sm" x-model.number="w" style="background:var(--surface-2);border-color:var(--card-border);color:var(--ink);"></div>
                                <div class="col-6 col-md-3"><label class="small mb-1 d-block" style="color:var(--muted);font-size:.72rem;text-transform:uppercase;">Height</label><input type="number" class="form-control form-control-sm" x-model.number="h" style="background:var(--surface-2);border-color:var(--card-border);color:var(--ink);"></div>
                            </div>
                            <template x-if="outputUrl">
                                <div class="mb-4 border rounded overflow-hidden" style="border-color:var(--card-border) !important;">
                                    <img :src="outputUrl" class="w-100 object-fit-contain" style="max-height:220px;background:var(--surface-2);">
                                </div>
                            </template>
                            <div class="d-flex justify-content-between">
                                <button class="btn-tool btn-outline" @click="file=null;outputUrl='';"><i class="bi bi-arrow-left"></i> New image</button>
                                <div class="d-flex gap-2">
                                    <button class="btn-tool btn-outline" @click="crop()"><i class="bi bi-crop"></i> Preview Crop</button>
                                    <button class="btn-tool btn-red" @click="download()" :disabled="!outputUrl"><i class="bi bi-download"></i> Download</button>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>
            </div>

            {{-- 4. Grayscale / Filters --}}
            <div x-show="activeTool === 'grayscale'" class="tool-panel"
                 x-data="{
                     file:null,originalUrl:'',outputUrl:'',isDragging:false,
                     filter:'grayscale',brightness:100,contrast:100, loadedImage:null,
                     loadImage(f){
                         if(!f) return;
                         if(this.originalUrl) URL.revokeObjectURL(this.originalUrl);
                         this.file=f;this.originalUrl=URL.createObjectURL(f);
                         this.loadedImage=new Image();this.loadedImage.src=this.originalUrl;this.loadedImage.onload=()=>this.apply();
                     },
                     apply(){
                         if(!this.file || !this.loadedImage || !this.loadedImage.complete) return;
                         const c=document.createElement('canvas');c.width=this.loadedImage.width;c.height=this.loadedImage.height;
                             const ctx=c.getContext('2d');
                             let f='brightness('+this.brightness+'%) contrast('+this.contrast+'%)';
                             if(this.filter==='grayscale')f+=' grayscale(100%)';
                             else if(this.filter==='sepia')f+=' sepia(100%)';
                             else if(this.filter==='invert')f+=' invert(100%)';
                             else if(this.filter==='saturate')f+=' saturate(200%)';
                             else if(this.filter==='blur')f+=' blur(2px)';
                             ctx.filter=f; // Apply filter before drawing
                             ctx.drawImage(this.loadedImage,0,0);
                             this.outputUrl=c.toDataURL('image/jpeg',0.92);
                         };
                     },
                     download(){const a=document.createElement('a');a.href=this.outputUrl;a.download='filtered.jpg';a.click();}
                 }">
                <div class="tool-panel-header">
                    <h3 class="tool-panel-title"><i class="bi bi-circle-half" style="color:#dc2626;"></i> Grayscale & Filters</h3>
                    <p class="tool-panel-desc">Apply grayscale, sepia, invert, saturate, or blur filters to any image.</p>
                </div>
                <div class="tool-body">
                    <div class="dropzone mb-4" x-show="!file" :class="isDragging?'dragover':''" @dragover.prevent="isDragging=true" @dragleave.prevent="isDragging=false" @drop.prevent="isDragging=false;loadImage($event.dataTransfer.files[0])" @click="$refs.imgFilterInput.click()">
                        <input type="file" x-ref="imgFilterInput" class="d-none" accept="image/*" @change="loadImage($event.target.files[0])">
                        <div style="font-size:2rem;color:#dc2626;margin-bottom:.6rem;"><i class="bi bi-funnel"></i></div>
                        <p style="font-weight:600;font-size:.88rem;color:var(--ink);margin-bottom:.2rem;">Drop image to filter</p>
                        <p style="font-size:.75rem;color:var(--muted);margin:0;">JPEG · PNG · WebP</p>
                    </div>
                    <template x-if="file">
                        <div>
                            <div class="d-flex flex-wrap gap-2 mb-4">
                                @foreach(['grayscale','sepia','invert','saturate','blur','none'] as $f)
                                <button @click="filter='{{ $f }}';apply()"
                                        :class="filter==='{{ $f }}'?'btn-red':'btn-outline'"
                                        class="btn-tool py-1 px-3">{{ ucfirst($f) }}</button>
                                @endforeach
                            </div>
                            <div class="row g-3 mb-4">
                                <div class="col-md-6">
                                    <label class="small fw-bold mb-1 d-block" style="color:var(--muted);font-size:.72rem;">Brightness: <span x-text="brightness+'%'"></span></label>
                                    <input type="range" class="form-range" min="10" max="200" x-model="brightness" @input="apply()">
                                </div>
                                <div class="col-md-6">
                                    <label class="small fw-bold mb-1 d-block" style="color:var(--muted);font-size:.72rem;">Contrast: <span x-text="contrast+'%'"></span></label>
                                    <input type="range" class="form-range" min="10" max="200" x-model="contrast" @input="apply()">
                                </div>
                            </div>
                            <template x-if="outputUrl">
                                <div class="mb-4 border rounded overflow-hidden" style="border-color:var(--card-border) !important;">
                                    <img :src="outputUrl" class="w-100 object-fit-contain" style="max-height:220px;background:var(--surface-2);">
                                </div>
                            </template>
                            <div class="d-flex justify-content-between">
                                <button class="btn-tool btn-outline" @click="file=null;outputUrl='';"><i class="bi bi-arrow-left"></i> New image</button>
                                <button class="btn-tool btn-red" @click="download()" :disabled="!outputUrl"><i class="bi bi-download"></i> Download</button>
                            </div>
                        </div>
                    </template>
                </div>
            </div>

            {{-- 5. Image Watermark --}}
            <div x-show="activeTool === 'watermark'" class="tool-panel"
                 x-data="{
                     file:null,originalUrl:'',outputUrl:'',isDragging:false,
                     text:'© My Brand',color:'#ffffff',opacity:70,fontSize:32,position:'bottom-right', loadedImage:null,
                     loadImage(f){
                         if(!f) return;
                         if(this.originalUrl) URL.revokeObjectURL(this.originalUrl);
                         this.file=f;this.originalUrl=URL.createObjectURL(f);
                         this.loadedImage=new Image();this.loadedImage.src=this.originalUrl;this.loadedImage.onload=()=>this.apply();
                     },
                     apply(){
                         if(!this.file || !this.loadedImage || !this.loadedImage.complete) return;
                         const c=document.createElement('canvas');c.width=this.loadedImage.width;c.height=this.loadedImage.height;
                         const ctx=c.getContext('2d');ctx.drawImage(this.loadedImage,0,0);
                             ctx.globalAlpha=this.opacity/100;
                             ctx.font='bold '+this.fontSize+'px sans-serif';
                             ctx.fillStyle=this.color;
                             const m=ctx.measureText(this.text);
                             const pad=20;
                             let x=pad,y=this.fontSize+pad;
                             if(this.position==='bottom-right'){x=img.width-m.width-pad;y=img.height-pad;}
                             else if(this.position==='bottom-left'){x=pad;y=img.height-pad;}
                             else if(this.position==='top-right'){x=img.width-m.width-pad;y=this.fontSize+pad;}
                             else if(this.position==='center'){x=(this.loadedImage.width-m.width)/2;y=this.loadedImage.height/2;}
                             ctx.fillText(this.text,x,y);
                             ctx.globalAlpha=1;
                             this.outputUrl=c.toDataURL('image/jpeg',0.92);
                         };
                     },
                     download(){const a=document.createElement('a');a.href=this.outputUrl;a.download='watermarked.jpg';a.click();}
                 }">
                <div class="tool-panel-header">
                    <h3 class="tool-panel-title"><i class="bi bi-droplet" style="color:#dc2626;"></i> Text Watermark</h3>
                    <p class="tool-panel-desc">Stamp a customizable text watermark onto any image.</p>
                </div>
                <div class="tool-body">
                    <div class="dropzone mb-4" x-show="!file" :class="isDragging?'dragover':''" @dragover.prevent="isDragging=true" @dragleave.prevent="isDragging=false" @drop.prevent="isDragging=false;loadImage($event.dataTransfer.files[0])" @click="$refs.imgWmInput.click()">
                        <input type="file" x-ref="imgWmInput" class="d-none" accept="image/*" @change="loadImage($event.target.files[0])">
                        <div style="font-size:2rem;color:#dc2626;margin-bottom:.6rem;"><i class="bi bi-image"></i></div>
                        <p style="font-weight:600;font-size:.88rem;color:var(--ink);margin-bottom:.2rem;">Drop image here</p>
                        <p style="font-size:.75rem;color:var(--muted);margin:0;">JPEG · PNG · WebP</p>
                    </div>
                    <template x-if="file">
                        <div>
                            <div class="row g-3 p-3 border rounded mb-4" style="background:var(--surface-2);border-color:var(--card-border) !important;">
                                <div class="col-md-6">
                                    <label class="small fw-bold mb-1 d-block" style="color:var(--muted);font-size:.72rem;text-transform:uppercase;">Watermark Text</label>
                                    <input type="text" class="form-control" x-model="text" @input="apply()" style="background:var(--card-bg);border-color:var(--card-border);color:var(--ink);">
                                </div>
                                <div class="col-md-3">
                                    <label class="small fw-bold mb-1 d-block" style="color:var(--muted);font-size:.72rem;text-transform:uppercase;">Color</label>
                                    <input type="color" class="form-control form-control-color w-100" x-model="color" @input="apply()">
                                </div>
                                <div class="col-md-3">
                                    <label class="small fw-bold mb-1 d-block" style="color:var(--muted);font-size:.72rem;text-transform:uppercase;">Size (px)</label>
                                    <input type="number" class="form-control" x-model.number="fontSize" @input="apply()" min="10" max="200" style="background:var(--card-bg);border-color:var(--card-border);color:var(--ink);">
                                </div>
                                <div class="col-md-6">
                                    <label class="small fw-bold mb-1 d-block" style="color:var(--muted);font-size:.72rem;text-transform:uppercase;">Position</label>
                                    <select class="form-select" x-model="position" @change="apply()" style="background:var(--card-bg);border-color:var(--card-border);color:var(--ink);">
                                        <option value="top-left">Top Left</option>
                                        <option value="top-right">Top Right</option>
                                        <option value="bottom-left">Bottom Left</option>
                                        <option value="bottom-right">Bottom Right</option>
                                        <option value="center">Center</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="small fw-bold mb-1 d-block" style="color:var(--muted);font-size:.72rem;text-transform:uppercase;">Opacity: <span x-text="opacity+'%'"></span></label>
                                    <input type="range" class="form-range mt-1" min="10" max="100" x-model="opacity" @input="apply()">
                                </div>
                            </div>
                            <template x-if="outputUrl">
                                <div class="mb-4 border rounded overflow-hidden" style="border-color:var(--card-border) !important;">
                                    <img :src="outputUrl" class="w-100 object-fit-contain" style="max-height:220px;background:var(--surface-2);">
                                </div>
                            </template>
                            <div class="d-flex justify-content-between">
                                <button class="btn-tool btn-outline" @click="file=null;outputUrl='';"><i class="bi bi-arrow-left"></i> New image</button>
                                <button class="btn-tool btn-red" @click="download()" :disabled="!outputUrl"><i class="bi bi-download"></i> Download</button>
                            </div>
                        </div>
                    </template>
                </div>
            </div>

            {{-- 6. EXIF & Metadata --}}
            <div x-show="activeTool === 'metadata'" class="tool-panel"
                 x-data="{
                     file:null,metadata:[],originalUrl:'',isDragging:false,
                     readMetadata(f){this.file=f;this.originalUrl=URL.createObjectURL(f);const d=[{name:'File Name',val:f.name},{name:'File Size',val:(f.size/1024).toFixed(1)+' KB'},{name:'MIME Type',val:f.type},{name:'Last Modified',val:new Date(f.lastModified).toLocaleString()}];const img=new Image();img.src=this.originalUrl;img.onload=()=>{d.push({name:'Dimensions',val:img.width+' × '+img.height+' px'});d.push({name:'Aspect Ratio',val:(img.width/img.height).toFixed(3)});d.push({name:'Megapixels',val:((img.width*img.height)/1e6).toFixed(2)+' MP'});d.push({name:'Orientation',val:img.width>img.height?'Landscape':img.width===img.height?'Square':'Portrait'});this.metadata=d;};}
                 }">
                <div class="tool-panel-header">
                    <h3 class="tool-panel-title"><i class="bi bi-info-circle" style="color:#dc2626;"></i> Image Info & Metadata</h3>
                    <p class="tool-panel-desc">Extract dimensions, file size, MIME type, and image properties.</p>
                </div>
                <div class="tool-body">
                    <div class="dropzone mb-4" x-show="!file" :class="isDragging?'dragover':''" @dragover.prevent="isDragging=true" @dragleave.prevent="isDragging=false" @drop.prevent="isDragging=false;readMetadata($event.dataTransfer.files[0])" @click="$refs.metaInput.click()">
                        <input type="file" x-ref="metaInput" class="d-none" accept="image/*" @change="readMetadata($event.target.files[0])">
                        <div style="font-size:2rem;color:#dc2626;margin-bottom:.6rem;"><i class="bi bi-info-circle"></i></div>
                        <p style="font-weight:600;font-size:.88rem;color:var(--ink);margin-bottom:.2rem;">Drop an image to inspect</p>
                        <p style="font-size:.75rem;color:var(--muted);margin:0;">No data is uploaded — fully local</p>
                    </div>
                    <template x-if="file">
                        <div>
                            <div class="row g-4 mb-4">
                                <div class="col-md-5">
                                    <div class="border rounded overflow-hidden" style="border-color:var(--card-border) !important;">
                                        <img :src="originalUrl" class="w-100 object-fit-contain" style="max-height:240px;background:var(--surface-2);">
                                    </div>
                                </div>
                                <div class="col-md-7">
                                    <div class="d-flex flex-column gap-2">
                                        <template x-for="item in metadata" :key="item.name">
                                            <div class="stat-row">
                                                <span style="color:var(--muted);" x-text="item.name"></span>
                                                <span style="font-weight:600;color:var(--ink);" x-text="item.val"></span>
                                            </div>
                                        </template>
                                    </div>
                                </div>
                            </div>
                            <button class="btn-tool btn-outline" @click="file=null;originalUrl='';metadata=[];"><i class="bi bi-arrow-left"></i> Analyze another</button>
                        </div>
                    </template>
                </div>
            </div>

            {{-- 7. Flip & Rotate --}}
            <div x-show="activeTool === 'fliprotate'" class="tool-panel"
                 x-data="{
                     file:null,originalUrl:'',outputUrl:'',isDragging:false,
                     rotation:0,flipH:false,flipV:false,
                     loadImage(f){this.file=f;this.originalUrl=URL.createObjectURL(f);this.apply();},
                     apply(){
                         if(!this.file)return;
                         const img=new Image();img.src=this.originalUrl;
                         img.onload=()=>{
                             const rad=this.rotation*Math.PI/180;
                             const sw=Math.abs(img.width*Math.cos(rad))+Math.abs(img.height*Math.sin(rad));
                             const sh=Math.abs(img.width*Math.sin(rad))+Math.abs(img.height*Math.cos(rad));
                             const c=document.createElement('canvas');c.width=Math.round(sw);c.height=Math.round(sh);
                             const ctx=c.getContext('2d');
                             ctx.translate(sw/2,sh/2);
                             ctx.rotate(rad);
                             ctx.scale(this.flipH?-1:1,this.flipV?-1:1);
                             ctx.drawImage(img,-img.width/2,-img.height/2);
                             this.outputUrl=c.toDataURL('image/jpeg',0.92);
                         };
                     },
                     download(){const a=document.createElement('a');a.href=this.outputUrl;a.download='rotated.jpg';a.click();}
                 }">
                <div class="tool-panel-header">
                    <h3 class="tool-panel-title"><i class="bi bi-arrow-clockwise" style="color:#dc2626;"></i> Flip & Rotate</h3>
                    <p class="tool-panel-desc">Rotate by any angle or flip horizontally / vertically.</p>
                </div>
                <div class="tool-body">
                    <div class="dropzone mb-4" x-show="!file" :class="isDragging?'dragover':''" @dragover.prevent="isDragging=true" @dragleave.prevent="isDragging=false" @drop.prevent="isDragging=false;loadImage($event.dataTransfer.files[0])" @click="$refs.imgRotInput.click()">
                        <input type="file" x-ref="imgRotInput" class="d-none" accept="image/*" @change="loadImage($event.target.files[0])">
                        <div style="font-size:2rem;color:#dc2626;margin-bottom:.6rem;"><i class="bi bi-arrow-clockwise"></i></div>
                        <p style="font-weight:600;font-size:.88rem;color:var(--ink);margin-bottom:.2rem;">Drop image here</p>
                        <p style="font-size:.75rem;color:var(--muted);margin:0;">JPEG · PNG · WebP</p>
                    </div>
                    <template x-if="file">
                        <div>
                            <div class="row g-3 mb-4">
                                <div class="col-md-6">
                                    <div class="d-flex gap-2 flex-wrap">
                                        @foreach([0,90,180,270] as $deg)
                                        <button @click="rotation={{ $deg }};apply()" :class="rotation==={{ $deg }}?'btn-red':'btn-outline'" class="btn-tool py-1 px-3">{{ $deg }}°</button>
                                        @endforeach
                                    </div>
                                    <div class="mt-3">
                                        <label class="small fw-bold mb-1 d-block" style="color:var(--muted);font-size:.72rem;text-transform:uppercase;">Custom angle: <span x-text="rotation+'°'"></span></label>
                                        <input type="range" class="form-range" min="0" max="359" x-model="rotation" @input="apply()">
                                    </div>
                                </div>
                                <div class="col-md-6 d-flex align-items-center gap-3">
                                    <button @click="flipH=!flipH;apply()" :class="flipH?'btn-red':'btn-outline'" class="btn-tool"><i class="bi bi-symmetry-vertical"></i> Flip H</button>
                                    <button @click="flipV=!flipV;apply()" :class="flipV?'btn-red':'btn-outline'" class="btn-tool"><i class="bi bi-symmetry-horizontal"></i> Flip V</button>
                                </div>
                            </div>
                            <template x-if="outputUrl">
                                <div class="mb-4 border rounded overflow-hidden" style="border-color:var(--card-border) !important;">
                                    <img :src="outputUrl" class="w-100 object-fit-contain" style="max-height:220px;background:var(--surface-2);">
                                </div>
                            </template>
                            <div class="d-flex justify-content-between">
                                <button class="btn-tool btn-outline" @click="file=null;outputUrl='';"><i class="bi bi-arrow-left"></i> New image</button>
                                <button class="btn-tool btn-red" @click="download()" :disabled="!outputUrl"><i class="bi bi-download"></i> Download</button>
                            </div>
                        </div>
                    </template>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection