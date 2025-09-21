<div
    x-data="wizard()"
    x-init="init(@this)"
    x-show="$wire.open"
    x-trap.noscroll="$wire.open"
    class="fixed inset-0 z-50 flex items-center justify-center"
    aria-modal="true" 
    role="dialog" 
    aria-label="Assessment Pendaki"
    data-assessment-id="{{ $assessmentId }}"
>
    {{-- backdrop --}}
    <div class="absolute inset-0 bg-black/50" @click="$wire.close()"></div>

    {{-- modal --}}
    <div class="relative w-full max-w-2xl mx-4 sm:mx-6 lg:mx-8 bg-white rounded-2xl shadow-soft border border-neutral-line overflow-hidden">
        {{-- header --}}
        <div class="px-6 py-5 border-b border-neutral-line">
            <div class="flex items-center justify-between">
                <h2 class="text-lg font-semibold text-neutral-text">Assessment Pendaki</h2>
                <button @click="$wire.close()" class="text-neutral-sub hover:text-neutral-text text-xl leading-none p-1 rounded-full hover:bg-neutral-line transition-colors">✕</button>
            </div>
            <div class="mt-4 h-2 bg-neutral-line rounded-full">
                <div class="h-2 bg-brand rounded-full transition-all duration-300" :style="`width: ${$wire.progress}%`"></div>
            </div>
            <div class="mt-3 text-sm text-neutral-sub">
                Step <span x-text="$wire.i + 1"></span> / <span x-text="$wire.steps.length"></span>
            </div>
        </div>

        {{-- Autosave indicator --}}
        <div 
            x-show="$wire.saved" 
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-y-2"
            x-transition:enter-end="opacity-100 translate-y-0"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 translate-y-0"
            x-transition:leave-end="opacity-0 translate-y-2"
            class="mx-6 mb-2 bg-ok text-neutral-text px-3 py-1 rounded-full text-xs flex items-center gap-1 w-fit"
        >
            <span>✓</span>
            <span>Tersimpan</span>
        </div>

        {{-- slides container --}}
        <div class="px-8 py-6">
            <template x-for="(s, idx) in $wire.steps" :key="s.id">
                <div
                    x-show="$wire.i === idx"
                    x-transition:enter="transform ease-out duration-500"
                    x-transition:enter-start="translate-x-4 opacity-0 scale-95"
                    x-transition:enter-end="translate-x-0 opacity-100 scale-100"
                    x-transition:leave="transform ease-in duration-300"
                    x-transition:leave-start="translate-x-0 opacity-100 scale-100"
                    x-transition:leave-end="-translate-x-4 opacity-0 scale-95"
                >
                    <div class="text-sm text-neutral-sub mb-2" x-text="s.code"></div>
                    <div class="text-base font-semibold text-neutral-text mb-6" x-text="s.name"></div>

                    {{-- Help text --}}
                    <div x-show="s.notes" class="mb-4 p-3 bg-white rounded-lg border border-neutral-line">
                        <div class="text-xs text-neutral-sub" x-text="s.notes"></div>
                    </div>

                    {{-- NUMERIC (as multiple choice) --}}
                    <div x-show="s.scale === 'numeric'">
                        <div class="space-y-3">
                            <template x-for="option in getNumericOptions(s)" :key="option.value">
                                <label class="flex items-center gap-4 rounded-xl border border-neutral-line px-4 py-4 cursor-pointer hover:bg-neutral-line/20 hover:border-brand/30 transition-all duration-200 bg-white">
                                    <input 
                                        type="radio" 
                                        class="accent-brand w-4 h-4" 
                                        :name="`c_${s.id}`"
                                        :value="option.value" 
                                        x-model="$wire.answers[s.id]"
                                        @change="$wire.saveCurrent()"
                                    >
                                    <span class="text-sm text-neutral-text font-medium" x-text="option.label"></span>
                                </label>
                            </template>
                        </div>
                        <div class="mt-2 text-xs text-neutral-sub" x-show="s.unit">
                            Satuan: <span x-text="s.unit"></span>
                        </div>
                    </div>

                    {{-- CATEGORICAL --}}
                    <div x-show="s.scale === 'categorical'">
                        <div class="space-y-3">
                            <template x-for="opt in (s.options || [])" :key="opt.key">
                                <label class="flex items-center gap-4 rounded-xl border border-neutral-line px-4 py-4 cursor-pointer hover:bg-neutral-line/20 hover:border-brand/30 transition-all duration-200 bg-white">
                                    <input 
                                        type="radio" 
                                        class="accent-brand w-4 h-4" 
                                        :name="`c_${s.id}`"
                                        :value="opt.key" 
                                        x-model="$wire.answers[s.id]"
                                        @change="$wire.saveCurrent()"
                                    >
                                    <span class="text-sm text-neutral-text font-medium" x-text="opt.label"></span>
                                </label>
                            </template>
                        </div>
                    </div>

                    {{-- Error message --}}
                    <div 
                        x-show="$wire.errors && $wire.errors['answers.' + s.id]" 
                        x-transition:enter="transition ease-out duration-300"
                        x-transition:enter-start="opacity-0 scale-95"
                        x-transition:enter-end="opacity-100 scale-100"
                        x-transition:leave="transition ease-in duration-200"
                        x-transition:leave-start="opacity-100 scale-100"
                        x-transition:leave-end="opacity-0 scale-95"
                        class="mt-3 p-2 bg-red-50 border border-red-200 rounded text-xs text-red-600"
                    >
                        <span x-text="$wire.errors['answers.' + s.id]"></span>
                    </div>
                </div>
            </template>
        </div>

        {{-- footer --}}
        <div class="px-8 py-6 border-t border-neutral-line flex items-center justify-between">
            <button 
                @click="$wire.prev()" 
                :disabled="$wire.i === 0"
                class="px-6 py-3 rounded-xl border border-neutral-line text-sm font-medium text-neutral-text disabled:opacity-50 disabled:cursor-not-allowed hover:bg-neutral-line/20 hover:border-brand/30 transition-all duration-200"
            >
                ← Sebelumnya
            </button>

            {{-- Finish button (only on last step) --}}
            <div class="flex items-center gap-4" x-show="$wire.i === $wire.steps.length - 1">
                <button 
                    @click="$wire.finishAndRun()" 
                    class="px-8 py-3 rounded-xl bg-brand text-white hover:bg-indigo-700 transition-all duration-200 font-medium shadow-lg hover:shadow-xl"
                >
                    Hitung TOPSIS
                </button>
            </div>

            {{-- Next button (not on last step) --}}
            <button 
                @click="$wire.next()" 
                x-show="$wire.i < $wire.steps.length - 1"
                class="px-6 py-3 rounded-xl bg-brand text-white hover:bg-indigo-700 transition-all duration-200 font-medium shadow-lg hover:shadow-xl"
            >
                Berikutnya →
            </button>
        </div>

    </div>

    {{-- Script and Style moved inside the root div --}}
    <script>
    function wizard() {
        return {
            // Generate numeric options based on criteria type
            getNumericOptions(criterion) {
                const code = criterion.code;
                const name = criterion.name.toLowerCase();
                
                // Special cases for specific criteria
                if (code === 'C1' || name.includes('usia') || name.includes('age')) {
                    // Age: 18-65 years
                    return [
                        { value: 18, label: '18-25 tahun (Sangat Muda)' },
                        { value: 30, label: '26-35 tahun (Muda)' },
                        { value: 40, label: '36-45 tahun (Dewasa)' },
                        { value: 50, label: '46-55 tahun (Matang)' },
                        { value: 60, label: '56-65 tahun (Senior)' }
                    ];
                }
                
                if (code === 'C2' || name.includes('fitness') || name.includes('kebugaran')) {
                    // Physical Fitness: 1-5 scale
                    return [
                        { value: 1, label: '1 - Sangat Lemah (Tidak pernah olahraga)' },
                        { value: 2, label: '2 - Lemah (Olahraga ringan sesekali)' },
                        { value: 3, label: '3 - Sedang (Olahraga rutin 1-2x/minggu)' },
                        { value: 4, label: '4 - Kuat (Olahraga rutin 3-4x/minggu)' },
                        { value: 5, label: '5 - Sangat Kuat (Olahraga intensif 5x+/minggu)' }
                    ];
                }
                
                if (code === 'C3' || name.includes('kardiovaskular') || name.includes('jantung')) {
                    // Cardiovascular History: 1-5 scale (cost criteria - lower is better)
                    return [
                        { value: 1, label: '1 - Sangat Baik (Tidak ada riwayat)' },
                        { value: 2, label: '2 - Baik (Riwayat ringan, terkontrol)' },
                        { value: 3, label: '3 - Sedang (Riwayat sedang, perlu monitoring)' },
                        { value: 4, label: '4 - Buruk (Riwayat serius, butuh perhatian)' },
                        { value: 5, label: '5 - Sangat Buruk (Riwayat kritis, berisiko tinggi)' }
                    ];
                }
                
                if (code === 'C5' || name.includes('peralatan') || name.includes('equipment')) {
                    // Equipment ownership: 1-5 scale
                    return [
                        { value: 1, label: '1 - Tidak Ada (0% peralatan)' },
                        { value: 2, label: '2 - Sedikit (25% peralatan dasar)' },
                        { value: 3, label: '3 - Cukup (50% peralatan standar)' },
                        { value: 4, label: '4 - Lengkap (75% peralatan lengkap)' },
                        { value: 5, label: '5 - Sangat Lengkap (100% peralatan profesional)' }
                    ];
                }
                
                if (code === 'C6' || name.includes('p3k') || name.includes('first aid')) {
                    // First Aid Knowledge: 1-5 scale
                    return [
                        { value: 1, label: '1 - Tidak Tahu (Tidak ada pengetahuan P3K)' },
                        { value: 2, label: '2 - Sedikit (Pengetahuan dasar P3K)' },
                        { value: 3, label: '3 - Cukup (Pengetahuan standar P3K)' },
                        { value: 4, label: '4 - Baik (Pengetahuan lanjutan P3K)' },
                        { value: 5, label: '5 - Sangat Baik (Sertifikasi P3K profesional)' }
                    ];
                }
                
                if (code === 'C7' || name.includes('motivasi')) {
                    // Motivation: 1-5 scale
                    return [
                        { value: 1, label: '1 - Sangat Rendah (Tidak ada motivasi)' },
                        { value: 2, label: '2 - Rendah (Motivasi minimal)' },
                        { value: 3, label: '3 - Sedang (Motivasi standar)' },
                        { value: 4, label: '4 - Tinggi (Motivasi kuat)' },
                        { value: 5, label: '5 - Sangat Tinggi (Motivasi luar biasa)' }
                    ];
                }
                
                if (code === 'C8' || name.includes('pengalaman') || name.includes('experience')) {
                    // Hiking Experience: 1-5 scale
                    return [
                        { value: 1, label: '1 - Pemula (0-2 pendakian)' },
                        { value: 2, label: '2 - Sedikit (3-5 pendakian)' },
                        { value: 3, label: '3 - Cukup (6-10 pendakian)' },
                        { value: 4, label: '4 - Berpengalaman (11-20 pendakian)' },
                        { value: 5, label: '5 - Sangat Berpengalaman (20+ pendakian)' }
                    ];
                }
                
                if (code === 'C9' || name.includes('logistik') || name.includes('logistic')) {
                    // Logistics Planning: 1-5 scale
                    return [
                        { value: 1, label: '1 - Tidak Ada (Tidak ada perencanaan)' },
                        { value: 2, label: '2 - Minimal (Perencanaan dasar)' },
                        { value: 3, label: '3 - Standar (Perencanaan cukup)' },
                        { value: 4, label: '4 - Baik (Perencanaan detail)' },
                        { value: 5, label: '5 - Sangat Baik (Perencanaan komprehensif)' }
                    ];
                }
                
                if (code === 'C10' || name.includes('skill') || name.includes('alat')) {
                    // Tool Usage Skills: 1-5 scale
                    return [
                        { value: 1, label: '1 - Tidak Bisa (Tidak tahu cara pakai alat)' },
                        { value: 2, label: '2 - Sedikit (Bisa pakai alat dasar)' },
                        { value: 3, label: '3 - Cukup (Bisa pakai alat standar)' },
                        { value: 4, label: '4 - Baik (Mahir pakai alat pendakian)' },
                        { value: 5, label: '5 - Sangat Baik (Expert semua alat pendakian)' }
                    ];
                }
                
                if (code === 'C11' || name.includes('survival')) {
                    // Survival Skills: 1-5 scale
                    return [
                        { value: 1, label: '1 - Tidak Ada (Tidak ada skill survival)' },
                        { value: 2, label: '2 - Dasar (Skill survival minimal)' },
                        { value: 3, label: '3 - Standar (Skill survival cukup)' },
                        { value: 4, label: '4 - Baik (Skill survival lanjutan)' },
                        { value: 5, label: '5 - Expert (Skill survival profesional)' }
                    ];
                }
                
                if (code === 'C12' || name.includes('tim') || name.includes('team')) {
                    // Team Readiness: 1-5 scale
                    return [
                        { value: 1, label: '1 - Tidak Siap (Tim tidak siap)' },
                        { value: 2, label: '2 - Kurang Siap (Tim kurang siap)' },
                        { value: 3, label: '3 - Cukup Siap (Tim cukup siap)' },
                        { value: 4, label: '4 - Siap (Tim siap)' },
                        { value: 5, label: '5 - Sangat Siap (Tim sangat siap)' }
                    ];
                }
                
                if (code === 'C13' || name.includes('pemandu') || name.includes('guide')) {
                    // Guide Presence: 1-5 scale
                    return [
                        { value: 1, label: '1 - Tidak Ada (Tidak ada pemandu)' },
                        { value: 2, label: '2 - Minimal (Pemandu tidak berpengalaman)' },
                        { value: 3, label: '3 - Standar (Pemandu cukup berpengalaman)' },
                        { value: 4, label: '4 - Baik (Pemandu berpengalaman)' },
                        { value: 5, label: '5 - Sangat Baik (Pemandu profesional bersertifikat)' }
                    ];
                }
                
                if (code === 'C14' || name.includes('tugas') || name.includes('task')) {
                    // Task Distribution: 1-5 scale
                    return [
                        { value: 1, label: '1 - Tidak Ada (Tidak ada pembagian tugas)' },
                        { value: 2, label: '2 - Minimal (Pembagian tugas dasar)' },
                        { value: 3, label: '3 - Standar (Pembagian tugas cukup)' },
                        { value: 4, label: '4 - Baik (Pembagian tugas detail)' },
                        { value: 5, label: '5 - Sangat Baik (Pembagian tugas optimal)' }
                    ];
                }
                
                // Default 1-5 scale for other criteria
                return [
                    { value: 1, label: '1 - Sangat Rendah' },
                    { value: 2, label: '2 - Rendah' },
                    { value: 3, label: '3 - Sedang' },
                    { value: 4, label: '4 - Tinggi' },
                    { value: 5, label: '5 - Sangat Tinggi' }
                ];
            },
            
            async submitTopsisCalculation(assessmentId) {
                console.log('Submitting TOPSIS calculation:', { assessmentId });
                
                try {
                    // Get CSRF token
                    const csrfToken = document.querySelector('meta[name="csrf-token"]');
                    if (!csrfToken) {
                        console.error('CSRF token not found!');
                        return;
                    }
                    
                    // Use fetch API to submit POST request
                    const response = await fetch(`/assessments/${assessmentId}/run-both`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded',
                            'X-CSRF-TOKEN': csrfToken.getAttribute('content'),
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        body: `_token=${encodeURIComponent(csrfToken.getAttribute('content'))}&v=${encodeURIComponent(v)}`
                    });
                    
                    if (response.ok) {
                        const data = await response.json();
                        if (data.success && data.redirect_url) {
                            window.location.href = data.redirect_url;
                        } else {
                            // Fallback: redirect to result page
                            window.location.href = `/assessments/${assessmentId}/result`;
                        }
                    } else {
                        console.error('Error submitting form:', response.status, response.statusText);
                        alert('Terjadi kesalahan saat menjalankan perhitungan. Silakan coba lagi.');
                    }
                } catch (error) {
                    console.error('Error:', error);
                    alert('Terjadi kesalahan saat menjalankan perhitungan. Silakan coba lagi.');
                }
            },
            
            init($wire) {
                // Store wire reference
                this.wire = $wire;
                
                // Listen for run-topsis-calculation event
                this.$wire.on('run-topsis-calculation', (data) => {
                    console.log('Received run-topsis-calculation event:', data);
                    console.log('Assessment ID:', data.assessmentId, 'Type:', typeof data.assessmentId);
                    
                    let assessmentId = data.assessmentId;
                    
                    // Fallback: get assessmentId from data attribute or wire property
                    if (!assessmentId || assessmentId === 'undefined') {
                        console.warn('Assessment ID from event is invalid, trying fallbacks...');
                        assessmentId = this.$el.dataset.assessmentId || this.wire.assessmentId;
                        console.log('Fallback Assessment ID:', assessmentId);
                    }
                    
                    if (!assessmentId || assessmentId === 'undefined') {
                        console.error('Assessment ID is undefined or invalid!');
                        alert('Error: Assessment ID tidak valid. Silakan refresh halaman.');
                        return;
                    }
                    
                    this.submitTopsisCalculation(assessmentId);
                });
                
                // keyboard shortcuts
                window.addEventListener('keydown', (e) => {
                    if (!this.$wire.open) return;
                    
                    if (e.key === 'Escape') {
                        this.$wire.close();
                    }
                    if (e.key === 'ArrowRight' && !this.$wire.isLastStep) {
                        this.$wire.next();
                    }
                    if (e.key === 'ArrowLeft' && !this.$wire.isFirstStep) {
                        this.$wire.prev();
                    }
                    if (e.key === 'Enter' && !this.$wire.isLastStep) {
                        e.preventDefault();
                        this.$wire.next();
                    }
                });
                
                // prevent body scroll
                document.body.style.overflow = 'hidden';
                this.$watch('$wire.open', v => {
                    document.body.style.overflow = v ? 'hidden' : '';
                });
                
                // Auto-hide saved indicator
                this.$watch('$wire.saved', (saved) => {
                    if (saved) {
                        setTimeout(() => {
                            this.$wire.saved = false;
                        }, 2000);
                    }
                });
                
                // Handle validation errors
                this.$wire.on('validation-error', () => {
                    // Shake animation for invalid inputs
                    const currentInput = document.querySelector('input:focus, input[aria-invalid="true"]');
                    if (currentInput) {
                        currentInput.classList.add('animate-shake');
                        setTimeout(() => {
                            currentInput.classList.remove('animate-shake');
                        }, 200);
                    }
                });
            },
            
            slideStyle(idx, cur) {
                return `transform: translateX(${(idx - cur) * 100}%);`;
            }
        }
    }
    </script>

    <style>
    @keyframes shake {
        0%, 100% { transform: translateX(0); }
        25% { transform: translateX(-5px); }
        75% { transform: translateX(5px); }
    }

    .animate-shake {
        animation: shake 0.2s ease-in-out;
    }

    </style>
</div>