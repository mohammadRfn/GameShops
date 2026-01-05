<template>
  <div class="min-h-screen bg-gradient-to-br from-slate-900 via-emerald-900/80 to-amber-900/80 p-8">
    <!-- Header -->
    <div class="flex items-center justify-between mb-12">
      <div class="text-right">
        <h1 class="text-5xl font-black bg-gradient-to-r from-emerald-400 via-green-400 to-teal-500 bg-clip-text text-transparent mb-4 drop-shadow-2xl">
          🏪 مدیریت انبار و خدمات
        </h1>
        <p class="text-xl text-purple-200 font-semibold">مدیریت موجودی محصولات و خدمات</p>
      </div>
      <router-link to="/dashboard" 
        class="bg-gradient-to-r from-amber-500/20 to-purple-500/20 p-4 rounded-2xl border border-emerald-400/40 hover:scale-105 hover:shadow-2xl transition-all text-white font-bold shadow-xl">
        ← بازگشت به داشبورد
      </router-link>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-12">
      <div class="group bg-gradient-to-br from-emerald-500/10 to-green-500/10 backdrop-blur-xl p-8 rounded-3xl border border-emerald-400/30 shadow-2xl hover:shadow-3xl hover:-translate-y-2 transition-all">
        <div class="w-16 h-16 bg-gradient-to-r from-emerald-400 to-green-500 rounded-2xl flex items-center justify-center mb-4 shadow-xl group-hover:scale-110">
          📦
        </div>
        <h3 class="text-xl font-black text-white mb-2">موجودی کل</h3>
        <div class="text-3xl font-black text-emerald-400">1,247</div>
      </div>
      
      <div class="group bg-gradient-to-br from-amber-500/10 to-orange-500/10 backdrop-blur-xl p-8 rounded-3xl border border-amber-400/30 shadow-2xl hover:shadow-3xl hover:-translate-y-2 transition-all">
        <div class="w-16 h-16 bg-gradient-to-r from-amber-400 to-orange-500 rounded-2xl flex items-center justify-center mb-4 shadow-xl group-hover:scale-110">
          ⚠️
        </div>
        <h3 class="text-xl font-black text-white mb-2">کم موجودی</h3>
        <div class="text-3xl font-black text-amber-400">23</div>
      </div>
      
      <div class="group bg-gradient-to-br from-purple-500/10 to-violet-500/10 backdrop-blur-xl p-8 rounded-3xl border border-purple-400/30 shadow-2xl hover:shadow-3xl hover:-translate-y-2 transition-all">
        <div class="w-16 h-16 bg-gradient-to-r from-purple-400 to-violet-500 rounded-2xl flex items-center justify-center mb-4 shadow-xl group-hover:scale-110">
          🔧
        </div>
        <h3 class="text-xl font-black text-white mb-2">خدمات</h3>
        <div class="text-3xl font-black text-purple-400">156</div>
      </div>
      
      <div class="group bg-gradient-to-br from-blue-500/10 to-cyan-500/10 backdrop-blur-xl p-8 rounded-3xl border border-blue-400/30 shadow-2xl hover:shadow-3xl hover:-translate-y-2 transition-all">
        <div class="w-16 h-16 bg-gradient-to-r from-blue-400 to-cyan-500 rounded-2xl flex items-center justify-center mb-4 shadow-xl group-hover:scale-110">
          ➕
        </div>
        <h3 class="text-xl font-black text-white mb-2">اقدامات</h3>
        <div class="text-3xl font-black text-blue-400">12</div>
      </div>
    </div>

    <!-- Action Buttons -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-12">
      <router-link to="/inventory/products/new" 
        class="group bg-gradient-to-r from-emerald-500/90 to-green-600/90 hover:from-emerald-600 hover:to-green-700 text-white p-8 rounded-3xl font-bold text-xl shadow-2xl hover:shadow-3xl hover:scale-105 transition-all text-center flex flex-col items-center gap-4">
        <div class="w-20 h-20 bg-white/20 rounded-2xl flex items-center justify-center text-3xl">➕</div>
        <span>محصول جدید</span>
      </router-link>
      
      <router-link to="/inventory/services/new" 
        class="group bg-gradient-to-r from-purple-500/90 to-violet-600/90 hover:from-purple-600 hover:to-violet-700 text-white p-8 rounded-3xl font-bold text-xl shadow-2xl hover:shadow-3xl hover:scale-105 transition-all text-center flex flex-col items-center gap-4">
        <div class="w-20 h-20 bg-white/20 rounded-2xl flex items-center justify-center text-3xl">🔧</div>
        <span>خدمت جدید</span>
      </router-link>
      
      <router-link to="/inventory/import" 
        class="group bg-gradient-to-r from-amber-500/90 to-orange-600/90 hover:from-amber-600 hover:to-orange-700 text-white p-8 rounded-3xl font-bold text-xl shadow-2xl hover:shadow-3xl hover:scale-105 transition-all text-center flex flex-col items-center gap-4">
        <div class="w-20 h-20 bg-white/20 rounded-2xl flex items-center justify-center text-3xl">📥</div>
        <span>ورود انبار</span>
      </router-link>
    </div>

    <!-- Inventory Table -->
    <div class="bg-gradient-to-r from-slate-900/90 to-emerald-900/90 backdrop-blur-xl rounded-3xl p-8 border border-emerald-400/20 shadow-3xl">
      <div class="flex items-center justify-between mb-6">
        <h3 class="text-2xl font-black text-white">جدول موجودی</h3>
        <button class="bg-gradient-to-r from-blue-500 to-cyan-500 hover:from-blue-600 hover:to-cyan-600 text-white px-6 py-2 rounded-xl font-bold shadow-xl hover:shadow-2xl hover:scale-105 transition-all">
          فیلترها
        </button>
      </div>
      <!-- جدول اینجا قرار می‌گیره -->
      <div class="overflow-x-auto rounded-2xl bg-slate-800/50 backdrop-blur-xl">
        <table class="min-w-full">
          <thead class="bg-gradient-to-r from-emerald-500/50 to-green-500/50">
            <tr>
              <th class="p-4 text-right font-black text-white">محصول</th>
              <th class="p-4 text-right font-black text-white">موجودی</th>
              <th class="p-4 text-right font-black text-white">قیمت</th>
              <th class="p-4 text-right font-black text-white">اقدامات</th>
            </tr>
          </thead>
          <tbody>
            <tr class="border-b border-emerald-400/30 hover:bg-white/10 transition-all">
              <td class="p-4 font-bold text-white">PlayStation 5</td>
              <td class="p-4 text-emerald-400 font-bold">45</td>
              <td class="p-4 text-amber-400 font-mono">25,000,000 تومان</td>
              <td class="p-4">
                <button class="bg-blue-500 hover:bg-blue-600 px-3 py-1 rounded-lg text-sm font-bold mr-2">ویرایش</button>
                <button class="bg-red-500 hover:bg-red-600 px-3 py-1 rounded-lg text-sm font-bold">حذف</button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'InventoryManager'
};
</script>
