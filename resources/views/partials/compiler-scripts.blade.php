{{--
    resources/views/partials/compiler-scripts.blade.php
    Place at bottom of each compiler page (before @endsection).
    Expects PHP var $language to be set, e.g. 'python'
--}}
<script>
function compilerPage() {
    return {
        status: 'checking',
        async pingHealth() {
            try {
                const r = await fetch('{{ route("compiler.health") }}');
                this.status = r.ok ? 'online' : 'offline';
            } catch {
                this.status = 'offline';
            }
        }
    };
}

function compilerRunner(defaultCode) {
    return {
        code:      defaultCode,
        output:    '',
        exitCode:  null,
        isError:   false,
        isLoading: false,
        lang:      '{{ $language }}',

        async run() {
            this.isLoading = true;
            this.isError   = false;
            this.exitCode  = null;
            this.output    = '';

            try {
                const res = await fetch('{{ route("compiler.run") }}', {
                    method:  'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body:    JSON.stringify({ language: this.lang, code: this.code }),
                });

                const data = await res.json();

                if (!res.ok) {
                    throw new Error(data.output || data.detail || `HTTP ${res.status}`);
                }

                this.exitCode = data.exit_code ?? null;
                this.isError  = data.exit_code !== 0 || data.timed_out;
                this.output   = data.output || '(no output)';

            } catch (err) {
                this.isError  = true;
                this.exitCode = -1;
                this.output   = '⚠ ' + err.message;
            } finally {
                this.isLoading = false;
            }
        }
    };
}
</script>