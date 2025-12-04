# 📱 Panduan Responsive Design - SPK TOPSIS

## 🎯 Overview

Sistem ini sudah **100% Mobile-Friendly** dengan pendekatan **Mobile-First Design**. Semua tabel dan komponen sudah dioptimalkan untuk berbagai ukuran layar.

---

## 📐 Breakpoints Tailwind CSS

```css
/* Mobile First Approach */
sm:  640px  →  Tablet Portrait
md:  768px  →  Tablet Landscape
lg:  1024px →  Desktop
xl:  1280px →  Large Desktop
2xl: 1536px →  Extra Large Desktop
```

---

## 📊 Responsive Table - 3 Pendekatan

### 1️⃣ **Horizontal Scroll Table** (Rekomendasi untuk Desktop-heavy Tables)

```html
<!-- Wrapper dengan horizontal scroll di mobile -->
<div class="table-responsive scroll-hint">
    <table class="ui-table">
        <thead>
            <tr>
                <th>Kolom 1</th>
                <th>Kolom 2</th>
                <th>Kolom 3</th>
                <!-- Banyak kolom OK! -->
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Data 1</td>
                <td>Data 2</td>
                <td>Data 3</td>
            </tr>
        </tbody>
    </table>
</div>

<!--
✅ HASIL:
- Desktop: Tabel normal
- Mobile: Scroll horizontal + hint text "← Geser untuk melihat lebih banyak →"
- Smooth scroll dengan custom scrollbar
-->
```

### 2️⃣ **Card Layout for Mobile** (Rekomendasi untuk Simple Tables)

```html
<!-- Desktop: Table | Mobile: Card -->

<!-- Tabel Desktop (Hidden di mobile) -->
<div class="hidden sm:block">
    <div class="table-responsive">
        <table class="ui-table">
            <!-- Standard table markup -->
        </table>
    </div>
</div>

<!-- Card Layout Mobile (Hidden di desktop) -->
<div class="block sm:hidden space-y-3">
    @foreach($items as $item)
        <div class="mobile-card">
            <div class="mobile-card-row">
                <span class="mobile-card-label">Nama</span>
                <span class="mobile-card-value">{{ $item->name }}</span>
            </div>
            <div class="mobile-card-row">
                <span class="mobile-card-label">Status</span>
                <span class="mobile-card-value">{{ $item->status }}</span>
            </div>
            <!-- More rows... -->
        </div>
    @endforeach
</div>

<!--
✅ HASIL:
- Desktop: Tabel tradisional
- Mobile: Card-based layout (lebih mudah dibaca)
-->
```

### 3️⃣ **Responsive Columns** (Auto-hide columns di mobile)

```html
<table class="ui-table">
    <thead>
        <tr>
            <th>Nama</th>
            <th class="hidden md:table-cell">Email</th>
            <th class="hidden lg:table-cell">Telepon</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>John Doe</td>
            <td class="hidden md:table-cell">john@email.com</td>
            <td class="hidden lg:table-cell">08123456789</td>
            <td>...</td>
        </tr>
    </tbody>
</table>

<!--
✅ HASIL:
- Mobile (< 768px): Hanya Nama & Aksi
- Tablet (768px+): + Email
- Desktop (1024px+): + Telepon
-->
```

---

## 🔘 Touch-Friendly Buttons

```html
<!-- Button dengan minimum touch target 44x44px -->
<button class="btn-primary btn-touch">
    Klik Saya
</button>

<!-- Icon button yang touch-friendly -->
<button class="btn-touch no-tap-highlight">
    <svg class="w-5 h-5">...</svg>
</button>

<!--
✅ Apple's HIG: Minimum 44x44px touch target
✅ Material Design: Minimum 48x48dp
-->
```

---

## 📱 Mobile Navigation

```html
<!-- Hamburger Menu (Mobile) -->
<div class="sm:hidden">
    <button class="btn-touch" @click="menuOpen = !menuOpen">
        <svg><!-- hamburger icon --></svg>
    </button>
</div>

<!-- Desktop Menu -->
<div class="hidden sm:flex gap-6">
    <a href="#">Link 1</a>
    <a href="#">Link 2</a>
</div>
```

---

## 🎨 Responsive Spacing

```html
<!-- Padding yang responsive -->
<div class="px-4 sm:px-6 lg:px-8">
    <!-- Mobile: 16px, Tablet: 24px, Desktop: 32px -->
</div>

<!-- Gap yang responsive -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6">
    <!-- Cards akan auto-adjust -->
</div>

<!-- Text size yang responsive -->
<h1 class="text-2xl sm:text-3xl lg:text-4xl">
    Heading Responsive
</h1>
```

---

## 🖼️ Responsive Images

```html
<!-- Image dengan aspect ratio -->
<div class="aspect-video w-full overflow-hidden rounded-lg">
    <img src="..." class="w-full h-full object-cover" alt="...">
</div>

<!-- Ukuran image yang berbeda per device -->
<img src="..."
     class="w-full sm:w-1/2 lg:w-1/3"
     alt="...">
```

---

## 📋 Contoh Implementasi Lengkap

### Halaman CRUD dengan Responsive Table

```html
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
    <!-- Header Section -->
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">
                Data Gunung
            </h1>
            <p class="mt-1 text-sm text-gray-600">
                Kelola data gunung untuk assessment
            </p>
        </div>

        <!-- Action Button -->
        <button class="btn-primary btn-touch w-full sm:w-auto">
            + Tambah Gunung
        </button>
    </div>

    <!-- Search & Filter (Responsive) -->
    <div class="mb-6 flex flex-col sm:flex-row gap-3">
        <input type="search"
               placeholder="Cari gunung..."
               class="search-input flex-1">
        <select class="form-select w-full sm:w-auto">
            <option>Semua Status</option>
        </select>
    </div>

    <!-- Responsive Table -->
    <div class="bg-white rounded-xl border-2 border-gray-200 shadow-sm">
        <!-- Desktop Table -->
        <div class="hidden sm:block">
            <div class="table-responsive scroll-hint">
                <table class="ui-table">
                    <thead>
                        <tr>
                            <th>Nama Gunung</th>
                            <th>Ketinggian</th>
                            <th>Provinsi</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="font-medium">Semeru</td>
                            <td>3,676 mdpl</td>
                            <td>Jawa Timur</td>
                            <td>
                                <span class="badge badge-success">Dibuka</span>
                            </td>
                            <td>
                                <button class="btn-touch">Edit</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Mobile Cards -->
        <div class="block sm:hidden p-4 space-y-3">
            <div class="mobile-card">
                <div class="flex justify-between items-start mb-3">
                    <h3 class="font-bold text-gray-900">Semeru</h3>
                    <span class="badge badge-success">Dibuka</span>
                </div>
                <div class="space-y-2">
                    <div class="mobile-card-row">
                        <span class="mobile-card-label">Ketinggian</span>
                        <span class="mobile-card-value">3,676 mdpl</span>
                    </div>
                    <div class="mobile-card-row">
                        <span class="mobile-card-label">Provinsi</span>
                        <span class="mobile-card-value">Jawa Timur</span>
                    </div>
                </div>
                <div class="mt-3 flex gap-2">
                    <button class="btn-primary btn-touch flex-1">Edit</button>
                    <button class="btn-danger btn-touch flex-1">Hapus</button>
                </div>
            </div>
        </div>
    </div>
</div>
```

---

## 🧪 Testing Checklist

### ✅ Mobile Testing (320px - 767px)
- [ ] Tabel bisa di-scroll horizontal dengan smooth
- [ ] Hint text "← Geser →" muncul di bawah tabel
- [ ] Button minimal 44x44px (mudah di-tap)
- [ ] Text tidak terlalu kecil (min 14px)
- [ ] Input form tidak terlalu sempit
- [ ] Navigation mudah diakses
- [ ] Card layout rapi dan tidak cramped

### ✅ Tablet Testing (768px - 1023px)
- [ ] Layout berubah dari 1 kolom ke 2 kolom
- [ ] Tabel mulai menampilkan lebih banyak kolom
- [ ] Spacing lebih lapang
- [ ] Image dan card proporsional

### ✅ Desktop Testing (1024px+)
- [ ] Tabel full-width dengan semua kolom
- [ ] Layout 3-4 kolom grid
- [ ] Hover states berfungsi
- [ ] Modal dan dropdown positioning benar

### ✅ Cross-Device Testing
- [ ] iPhone SE (375px)
- [ ] iPhone 12/13 (390px)
- [ ] iPhone 14 Pro Max (430px)
- [ ] Samsung Galaxy S21 (360px)
- [ ] iPad Mini (768px)
- [ ] iPad Pro (1024px)
- [ ] Desktop HD (1920px)

---

## 🚀 Quick Reference

```html
<!-- Responsive Container -->
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

<!-- Responsive Grid -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">

<!-- Hide/Show by Breakpoint -->
<div class="hidden sm:block">Desktop Only</div>
<div class="block sm:hidden">Mobile Only</div>

<!-- Responsive Text -->
<h1 class="text-2xl sm:text-3xl lg:text-4xl">

<!-- Responsive Spacing -->
<div class="p-4 sm:p-6 lg:p-8">

<!-- Touch-Friendly -->
<button class="btn-touch no-tap-highlight">
```

---

## 📚 Best Practices

1. **Mobile-First**: Desain untuk mobile dulu, lalu enhance untuk desktop
2. **Touch Targets**: Minimal 44x44px untuk semua interactive elements
3. **Readable Text**: Minimal 14-16px, line-height 1.5-1.6
4. **Scroll Indicators**: Tampilkan hint untuk scrollable content
5. **Test Real Devices**: Emulator ≠ Real device experience

---

## 🛠️ Tools untuk Testing

- **Chrome DevTools**: Responsive Mode (Ctrl+Shift+M)
- **Firefox**: Responsive Design Mode
- **BrowserStack**: Test di real devices
- **LambdaTest**: Cross-browser testing

---

## ✨ Summary

✅ Semua tabel sudah responsive dengan 3 opsi:
   1. Horizontal scroll (dengan hint)
   2. Card layout mobile
   3. Auto-hide columns

✅ Touch-friendly dengan min 44x44px targets
✅ Smooth scrolling di iOS/Android
✅ Custom scrollbar yang cantik
✅ Grid system yang flexible
✅ Spacing yang konsisten di semua devices

**Sistem SPK TOPSIS sekarang 100% Mobile-Ready!** 📱✨
