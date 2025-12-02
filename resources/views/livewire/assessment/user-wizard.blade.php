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
    <div class="relative w-full max-w-4xl mx-3 sm:mx-4 md:mx-6 lg:mx-8 bg-white rounded-2xl shadow-soft border border-neutral-line overflow-hidden max-h-[95vh] flex flex-col">
        {{-- header (fixed) --}}
        <div class="px-6 py-5 border-b border-neutral-line flex-shrink-0">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-lg font-semibold text-neutral-text">Assessment Pendaki</h2>
                    <p class="text-sm text-neutral-sub mt-1">Evaluasi kemampuan dan preferensi pendakian Anda</p>
                </div>
                <div class="flex items-center gap-3">
                    {{-- Autosave indicator --}}
                    <div
                        x-show="$wire.saved"
                        x-transition:enter="transition ease-out duration-300"
                        x-transition:enter-start="opacity-0 scale-95"
                        x-transition:enter-end="opacity-100 scale-100"
                        x-transition:leave="transition ease-in duration-200"
                        x-transition:leave-start="opacity-100 scale-100"
                        x-transition:leave-end="opacity-0 scale-95"
                        class="bg-ok text-white px-3 py-1.5 rounded-lg shadow-md text-xs flex items-center gap-1.5"
                    >
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        <span>Tersimpan</span>
                    </div>
                    <button @click="$wire.close()" class="text-neutral-sub hover:text-neutral-text text-xl leading-none p-2 rounded-full hover:bg-neutral-line transition-colors">✕</button>
                </div>
            </div>

            <div class="mt-4 h-3 bg-neutral-line rounded-full overflow-hidden">
                <div class="h-3 bg-gradient-to-r from-brand to-indigo-600 rounded-full transition-all duration-200 ease-out" :style="`width: ${$wire.progress}%`"></div>
            </div>

            <div class="mt-3">
                <div class="text-sm text-neutral-sub">
                    Langkah <span x-text="$wire.i + 1" class="font-semibold text-brand"></span> dari <span x-text="$wire.steps.length" class="font-semibold"></span>
                </div>
            </div>
        </div>

        {{-- slides container (scrollable) --}}
        <div class="px-8 py-6 flex-1 overflow-y-auto">
            <template x-for="(s, idx) in $wire.steps" :key="s.id">
                <div
                    x-show="$wire.i === idx"
                >
                    {{-- Question header with icon --}}
                    <div class="flex items-start gap-3 mb-6">
                        <div class="w-10 h-10 rounded-full bg-brand/10 flex items-center justify-center flex-shrink-0 mt-1">
                            <span class="text-brand font-bold text-sm" x-text="$wire.i + 1"></span>
                        </div>
                        <div class="flex-1">
                            <div class="text-sm text-brand font-medium mb-1" x-text="s.code"></div>
                            <div class="text-lg font-semibold text-neutral-text" x-text="s.name"></div>
                        </div>
                    </div>

                    {{-- Help text with better styling --}}
                    <div x-show="s.notes" class="mb-6 p-4 bg-blue-50 rounded-xl border border-blue-200">
                        <div class="flex items-start gap-2">
                            <svg class="w-5 h-5 text-blue-500 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <div class="text-sm text-blue-700" x-text="s.notes"></div>
                        </div>
                    </div>

                    {{-- Scale description --}}
                    <div class="mb-4 p-3 bg-gray-50 rounded-lg">
                        <div class="text-xs font-medium text-gray-600 mb-2">Skala Penilaian:</div>
                        <div class="grid grid-cols-5 gap-1 text-xs text-gray-500">
                            <div class="text-center">1 - Sangat Rendah</div>
                            <div class="text-center">2 - Rendah</div>
                            <div class="text-center">3 - Sedang</div>
                            <div class="text-center">4 - Tinggi</div>
                            <div class="text-center">5 - Sangat Tinggi</div>
                        </div>
                    </div>

                    {{-- NUMERIC (as multiple choice) --}}
                    <div x-show="s.scale === 'numeric'">
                        <div class="space-y-3">
                            <template x-for="option in getNumericOptions(s)" :key="option.value">
                                <label
                                    class="flex items-center gap-4 rounded-xl border px-4 py-4 cursor-pointer transition-colors duration-150 group"
                                    :class="$wire.answers[s.id] == option.value
                                        ? 'border-brand bg-brand/5 shadow-sm'
                                        : 'border-neutral-line bg-white hover:bg-neutral-line/20 hover:border-brand/30'"
                                >
                                    <input
                                        type="radio"
                                        class="accent-brand w-5 h-5"
                                        :name="`c_${s.id}`"
                                        :value="option.value"
                                        x-model="$wire.answers[s.id]"
                                        @change="$wire.saveCurrent()"
                                    >
                                    <div class="flex-1">
                                        <div class="flex items-center justify-between">
                                            <span class="text-sm font-medium"
                                                  :class="$wire.answers[s.id] == option.value ? 'text-brand' : 'text-neutral-text'"
                                                  x-text="option.label"></span>
                                            <div class="flex items-center gap-1">
                                                <template x-for="i in 5" :key="i">
                                                    <div class="w-2 h-2 rounded-full"
                                                         :class="i <= option.value ? 'bg-brand' : 'bg-gray-200'"></div>
                                                </template>
                                            </div>
                                        </div>
                                        <div class="text-xs text-neutral-sub mt-1" x-text="getScaleDescription(option.value)"></div>
                                    </div>
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
                                <label class="flex items-center gap-4 rounded-xl border border-neutral-line px-4 py-4 cursor-pointer hover:bg-neutral-line/20 hover:border-brand/30 transition-colors duration-150 bg-white">
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
                        x-transition:enter="transition ease-out duration-150"
                        x-transition:enter-start="opacity-0"
                        x-transition:enter-end="opacity-100"
                        x-transition:leave="transition ease-in duration-150"
                        x-transition:leave-start="opacity-100"
                        x-transition:leave-end="opacity-0"
                        class="mt-3 p-2 bg-red-50 border border-red-200 rounded text-xs text-red-600"
                    >
                        <span x-text="$wire.errors['answers.' + s.id]"></span>
                    </div>
                </div>
            </template>
        </div>

        {{-- footer (fixed) --}}
        <div class="px-8 py-6 border-t border-neutral-line flex-shrink-0 bg-white">
            <div class="flex items-center justify-between">
                <button
                    @click="$wire.prev()"
                    :disabled="$wire.i === 0"
                    class="inline-flex items-center px-6 py-3 rounded-xl border border-neutral-line text-sm font-medium text-neutral-text disabled:opacity-50 disabled:cursor-not-allowed hover:bg-neutral-line/20 hover:border-brand/30 transition-colors duration-150"
                >
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                    Sebelumnya
                </button>

                {{-- Progress dots --}}
                <div class="flex items-center gap-2">
                    <template x-for="(step, idx) in $wire.steps" :key="idx">
                        <div class="w-2 h-2 rounded-full transition-colors duration-150"
                             :class="idx <= $wire.i ? 'bg-brand' : 'bg-gray-300'"></div>
                    </template>
                </div>

                {{-- Finish button (only on last step) --}}
                <div x-show="$wire.i === $wire.steps.length - 1">
                    <button
                        @click="$wire.finishAndRun()"
                        class="inline-flex items-center px-8 py-3 rounded-xl bg-green-600 text-white hover:bg-green-700 transition-colors duration-150 font-semibold shadow-lg"
                    >
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Hitung TOPSIS
                    </button>
                </div>

                {{-- Next button (not on last step) --}}
                <button
                    @click="$wire.next()"
                    x-show="$wire.i < $wire.steps.length - 1"
                    class="inline-flex items-center px-6 py-3 rounded-xl bg-brand text-white hover:bg-indigo-700 transition-colors duration-150 font-medium shadow-lg"
                >
                    Berikutnya
                    <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </button>
            </div>

            {{-- Help text --}}
            <div class="mt-4 text-center">
                <p class="text-xs text-neutral-sub">
                    💡 Tips: Pilih jawaban yang paling sesuai dengan kondisi Anda saat ini. Data akan otomatis tersimpan.
                </p>
            </div>
        </div>

    </div>

    {{-- Script and Style moved inside the root div --}}
    <script>
    function wizard() {
        return {
            // Helper function for scale descriptions
            getScaleDescription(value) {
                const descriptions = {
                    1: 'Sangat rendah/minimal',
                    2: 'Rendah/kurang',
                    3: 'Sedang/cukup',
                    4: 'Tinggi/baik',
                    5: 'Sangat tinggi/sangat baik'
                };
                return descriptions[value] || '';
            },

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
                    const response = await fetch(`/assessments/${assessmentId}/run`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded',
                            'X-CSRF-TOKEN': csrfToken.getAttribute('content'),
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        body: `_token=${encodeURIComponent(csrfToken.getAttribute('content'))}`
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
                    // Simple highlight for invalid inputs - no shake animation
                    const currentInput = document.querySelector('input:focus, input[aria-invalid="true"]');
                    if (currentInput) {
                        const parent = currentInput.closest('label');
                        if (parent) {
                            parent.classList.add('border-red-500');
                            setTimeout(() => {
                                parent.classList.remove('border-red-500');
                            }, 2000);
                        }
                    }
                });
            },
            
            slideStyle(idx, cur) {
                return `transform: translateX(${(idx - cur) * 100}%);`;
            }
        }
    }
    </script>

    {{-- Removed shake animation styles - using simpler highlighting instead --}}
</div>