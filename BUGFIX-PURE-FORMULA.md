# 🐛 Bug Fix: C1-C14 (USER Criteria) Tidak Masuk TOPSIS

## 📋 Masalah yang Dilaporkan

User melaporkan bahwa saat melakukan assessment, **hanya kriteria C15-C21 yang muncul di output TOPSIS**, sedangkan **C1-C14 (USER criteria) tidak masuk** dalam perhitungan.

```
Weight Display Output (WRONG):
C15 cost 0.033998 0.041652
C16 cost 0.016248 0.075823
C17 cost 0.099421 0.026148
C18 cost 0.042157 0.037632
C19 cost 0.029937 0.033998
C20 benefit 0.054388 0.027149
C21 benefit 0.048622 0.024311

❌ C1-C14 TIDAK ADA!
```

---

## 🔍 Root Cause Analysis

### 1. **Database Column Default Value SALAH**

File: `assessments` table, kolom `pure_formula`

```sql
Column: pure_formula
Type: tinyint(1)
Default: 1  ❌ WRONG! (TRUE by default)
```

**Dampak:**
- Saat assessment dibuat tanpa explicitly set `pure_formula`, database otomatis set ke `TRUE`
- Ini menyebabkan TopsisService memfilter dan **HANYA** mengambil kriteria MOUNTAIN/ROUTE
- USER criteria (C1-C14) diabaikan sepenuhnya

### 2. **Logika Filter di TopsisService**

File: `app/Services/TopsisService.php` (line 36-40)

```php
// Filter kriteria berdasarkan assessment.pure_formula
$criteria = Criterion::where('active', 1)
    ->when($a->pure_formula, fn($q) => $q->whereIn('source', ['MOUNTAIN', 'ROUTE']))
    ->orderBy('sort_order')
    ->orderByRaw("CAST(SUBSTRING(code, 2) AS UNSIGNED)")
    ->get();
```

**Penjelasan:**
- Ketika `pure_formula = TRUE`, query hanya ambil kriteria dengan `source` IN ('MOUNTAIN', 'ROUTE')
- Ini **mengecualikan semua kriteria USER** (source = 'USER')
- Hasilnya: TOPSIS hanya pakai 7 kriteria (C15-C21), bukan 18 kriteria

### 3. **Controller Tidak Set Flag**

File: `app/Http/Controllers/LandingController.php` (line 154-160)

```php
// BEFORE (WRONG)
$a = Assessment::create([
    'user_id' => auth()->id(),
    'title' => $r->input('title', $titlePrefix . ' - Assessment ' . now()->format('Y-m-d H:i')),
    'status' => 'draft',
    'top_k' => (int)($r->input('top_k', 5)),
    // ❌ pure_formula TIDAK di-set, jadi pakai database default (TRUE)
]);
```

---

## ✅ Solusi yang Diterapkan

### Fix #1: Migration - Ubah Database Default

File: `database/migrations/2025_12_04_223028_fix_pure_formula_default_value.php`

```php
public function up(): void
{
    Schema::table('assessments', function (Blueprint $table) {
        // Change pure_formula default from 1 (TRUE) to 0 (FALSE)
        // This ensures new assessments include ALL criteria by default
        $table->boolean('pure_formula')->default(false)->change();
    });
}
```

**Hasil:**
```sql
Column: pure_formula
Type: tinyint(1)
Default: 0  ✅ CORRECT! (FALSE by default)
```

### Fix #2: Explicit Set di Controller

File: `app/Http/Controllers/LandingController.php` (line 154-160)

```php
// AFTER (CORRECT)
$a = Assessment::create([
    'user_id' => auth()->id(),
    'title' => $r->input('title', $titlePrefix . ' - Assessment ' . now()->format('Y-m-d H:i')),
    'status' => 'draft',
    'top_k' => (int)($r->input('top_k', 5)),
    'pure_formula' => false, // ✅ Include ALL criteria (USER + MOUNTAIN/ROUTE)
]);
```

---

## 📊 Verifikasi Setelah Fix

### Test: Assessment Baru

```php
// Created assessment
ID: 98
pure_formula: FALSE  ✅
status: done
n_criteria: 18  ✅ (was 7 before)
n_alternatives: 10
```

### Criteria Breakdown (18 Total)

```
📊 Kriteria yang Digunakan:

🔮 USER Criteria (11):
   C1  - Usia
   C2  - Kondisi Fisik
   C3  - Riwayat Penyakit
   C5  - Kepemilikan Peralatan
   C7  - Motivasi Pendakian
   C8  - Pengalaman Pendakian
   C9  - Perencanaan Logistik
   C10 - Keterampilan Alat Pendakian
   C11 - Kemampuan Bertahan Hidup
   C12 - Kesiapan Anggota Pendakian
   C13 - Kehadiran Pemandu

🗻 MOUNTAIN/ROUTE Criteria (7):
   C15 - Ketinggian Gunung (mdpl)
   C16 - Elevasi Jalur (m)
   C17 - Tutupan Lahan
   C18 - Panjang Jalur (km)
   C19 - Kecuraman Jalur
   C20 - Ketersediaan Sumber Air
   C21 - Ketersediaan Sarana Pendukung
```

---

## 🎯 Impact Summary

### BEFORE Fix:
- ❌ Assessment hanya menggunakan **7 kriteria** (C15-C21)
- ❌ Semua jawaban user (C1-C14) **diabaikan**
- ❌ Rekomendasi TOPSIS **tidak akurat** karena tidak memperhitungkan profil pendaki
- ❌ n_criteria = 7 (WRONG)

### AFTER Fix:
- ✅ Assessment menggunakan **18 kriteria** lengkap
- ✅ Jawaban user (C1-C14) **masuk perhitungan**
- ✅ Rekomendasi TOPSIS **akurat** sesuai profil pendaki
- ✅ n_criteria = 18 (CORRECT)

---

## 🧪 Testing Checklist

- [x] Database default value changed to FALSE
- [x] Controller explicitly sets pure_formula = false
- [x] New assessments created with pure_formula = false
- [x] TOPSIS calculation includes all 18 criteria
- [x] USER criteria (C1-C14) included in MATRIX_X
- [x] ROUTE criteria (C15-C21) included in MATRIX_X
- [x] n_criteria = 18 in completed assessments

---

## 📝 Files Changed

1. **database/migrations/2025_12_04_223028_fix_pure_formula_default_value.php** (NEW)
   - Migration to fix database default value

2. **app/Http/Controllers/LandingController.php** (MODIFIED)
   - Line 159: Added `'pure_formula' => false,`

---

## 🚀 Deployment Steps

```bash
# 1. Run migration
php artisan migrate

# 2. Verify default value
php check-column-default.php

# 3. Test with new assessment
# - Create assessment via UI
# - Run TOPSIS
# - Verify n_criteria = 18
# - Check MATRIX_X includes C1-C21

# 4. Verify old assessments still work
# - Assessments with pure_formula=TRUE still work (backward compatible)
```

---

## 💡 Lesson Learned

**ALWAYS set explicit default values for boolean flags in Laravel migrations!**

```php
// ❌ BAD - Unclear default
$table->boolean('pure_formula');

// ✅ GOOD - Explicit default
$table->boolean('pure_formula')->default(false);
```

---

## ✨ Status: FIXED ✅

- Bug identified: ✅
- Root cause found: ✅
- Solution implemented: ✅
- Migration created: ✅
- Controller updated: ✅
- Tested and verified: ✅

**All new assessments will now include USER criteria (C1-C14) in TOPSIS calculation!**
