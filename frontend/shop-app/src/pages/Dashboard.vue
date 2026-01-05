<template>
  <div class="min-h-screen bg-gradient-to-br from-slate-900 via-purple-900 to-amber-900">
    <!-- Overlay -->
    <div v-if="sideMenuOpen" @click="toggleSideMenu"
      class="fixed inset-0 bg-black/50 backdrop-blur-sm z-40 transition-all duration-300"></div>

    <!-- Luxury Side Menu (کشویی کامل) -->
    <aside :class="[
      'fixed top-0 right-0 h-full w-80 bg-gradient-to-b from-amber-500/20 via-purple-500/20 to-slate-900/90 backdrop-blur-xl border-l-2 border-amber-400/50 shadow-2xl z-50 transform transition-all duration-500 ease-in-out',
      sideMenuOpen ? 'translate-x-0' : 'translate-x-full'
    ]">
      <!-- Header Menu -->
      <div class="p-8 border-b border-amber-400/30">
        <div class="flex items-center justify-between mb-6">
          <div class="flex items-center space-x-3">
            <div
              class="w-12 h-12 bg-gradient-to-r from-amber-400 to-yellow-500 rounded-2xl shadow-lg flex items-center justify-center">
              <span class="text-2xl">👑</span>
            </div>
            <div>
              <h2 class="text-2xl font-black text-amber-300 drop-shadow-lg">Game Empire</h2>
              <p class="text-xs text-purple-200 opacity-80">Admin Panel</p>
            </div>
          </div>
          <button @click="toggleSideMenu"
            class="p-2 hover:bg-white/10 rounded-xl transition-all duration-200 hover:scale-110">
            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
          </button>
        </div>
      </div>

      <!-- Navigation -->
      <nav class="p-4 space-y-2">
        <router-link v-for="(item, index) in menuItems" :key="index" :to="item.path" @click="toggleSideMenu"
          class="group flex items-center space-x-4 p-4 rounded-2xl bg-white/5 hover:bg-gradient-to-r hover:from-amber-400/20 hover:to-purple-500/20 hover:shadow-lg transition-all duration-300 hover:translate-x-2 border border-transparent hover:border-amber-400/30">
          <span
            class="w-12 h-12 bg-gradient-to-r from-amber-400/30 to-purple-500/30 rounded-xl flex items-center justify-center shadow-md group-hover:scale-110 transition-transform duration-200">
            <component :is="item.icon" class="text-xl text-amber-300 drop-shadow-lg" />
          </span>
          <div>
            <p class="font-bold text-white text-lg drop-shadow-md">{{ item.title }}</p>
            <p class="text-xs text-purple-200 opacity-70">{{ item.subtitle }}</p>
          </div>
        </router-link>
      </nav>
    </aside>

    <!-- Main Content -->
    <main class="w-full min-h-screen transition-all duration-300" :class="{ 'mr-80': sideMenuOpen }">
      <!-- Luxury Header -->
      <header
        class="bg-gradient-to-r from-slate-900/95 via-purple-900/80 to-amber-900/90 backdrop-blur-xl border-b-2 border-amber-400/30 shadow-2xl p-8 sticky top-0 z-30">
        <div class="flex items-center justify-between">
          <div>
            <h1
              class="text-4xl md:text-5xl font-black bg-gradient-to-r from-amber-400 via-yellow-400 to-orange-500 bg-clip-text text-transparent drop-shadow-2xl">
              🎮 Game Empire
            </h1>
            <p class="text-xl text-purple-200/80 mt-2 font-medium drop-shadow-lg">مدیریت کامل فروشگاه گیم</p>
          </div>
          <button @click="toggleSideMenu"
            class="p-4 bg-gradient-to-r from-amber-500/20 to-purple-500/20 backdrop-blur-sm rounded-2xl border border-amber-400/40 shadow-xl hover:shadow-2xl hover:scale-105 transition-all duration-300 flex items-center space-x-3 text-white font-bold">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
            <span>منو</span>
          </button>
        </div>
      </header>

      <!-- Luxury Cards Grid -->
      <div class="container mx-auto p-8 lg:p-12">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 mb-16">
          <!-- Active Players -->
          <div
            class="group relative bg-gradient-to-br from-amber-500/10 via-purple-500/10 to-slate-900/90 backdrop-blur-xl rounded-3xl p-8 border border-amber-400/30 shadow-2xl hover:shadow-3xl hover:-translate-y-4 transition-all duration-500 overflow-hidden">
            <div
              class="absolute inset-0 bg-gradient-to-r from-amber-400/20 to-purple-500/20 -translate-x-full group-hover:translate-x-full transition-transform duration-1000">
            </div>
            <div class="relative">
              <div
                class="w-20 h-20 bg-gradient-to-r from-amber-400 to-yellow-500 rounded-2xl shadow-2xl flex items-center justify-center mb-6 group-hover:rotate-12 transition-transform duration-500">
                <span class="text-3xl">🎮</span>
              </div>
              <h3 class="text-2xl font-black text-white mb-2 drop-shadow-lg">تعداد مشتری ها</h3>
              <div
                class="text-4xl font-black bg-gradient-to-r from-amber-400 to-yellow-500 bg-clip-text text-transparent drop-shadow-2xl mb-4">
                500
              </div>
              <div class="flex items-center text-green-400 text-sm font-bold">
                <span>↑ 23%</span>
                <svg class="w-4 h-4 ml-1" fill="currentColor" viewBox="0 0 20 20">
                  <path fill-rule="evenodd"
                    d="M5.293 7.707a1 1 0 010-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 01-1.414 1.414L11 5.414V17a1 1 0 11-2 0V5.414L6.707 7.707a1 1 0 01-1.414 0z" />
                </svg>
              </div>
            </div>
          </div>

          <!-- Total Wins -->
          <div
            class="group relative bg-gradient-to-br from-purple-500/10 via-indigo-500/10 to-slate-900/90 backdrop-blur-xl rounded-3xl p-8 border border-purple-400/30 shadow-2xl hover:shadow-3xl hover:-translate-y-4 transition-all duration-500 overflow-hidden">
            <div
              class="absolute inset-0 bg-gradient-to-r from-purple-400/20 to-indigo-500/20 -translate-y-full group-hover:translate-y-full transition-transform duration-1000 rotate-45">
            </div>
            <div class="relative">
              <div
                class="w-20 h-20 bg-gradient-to-r from-purple-400 to-indigo-500 rounded-2xl shadow-2xl flex items-center justify-center mb-6 group-hover:rotate-6 transition-transform duration-500">
                <span class="text-3xl">🏆</span>
              </div>
              <h3 class="text-2xl font-black text-white mb-2 drop-shadow-lg">مجموع محصولات</h3>
              <div
                class="text-4xl font-black bg-gradient-to-r from-purple-400 to-indigo-500 bg-clip-text text-transparent drop-shadow-2xl mb-4">
                1500
              </div>
              <div class="flex items-center text-green-400 text-sm font-bold">
                <span>+ 15%</span>
                <svg class="w-4 h-4 ml-1" fill="currentColor" viewBox="0 0 20 20">
                  <path fill-rule="evenodd"
                    d="M5.293 7.707a1 1 0 010-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 01-1.414 1.414L11 5.414V17a1 1 0 11-2 0V5.414L6.707 7.707a1 1 0 01-1.414 0z" />
                </svg>
              </div>
            </div>
          </div>

          <!-- Win Rate -->
          <div
            class="group relative bg-gradient-to-br from-emerald-500/10 via-green-500/10 to-slate-900/90 backdrop-blur-xl rounded-3xl p-8 border border-emerald-400/30 shadow-2xl hover:shadow-3xl hover:-translate-y-4 transition-all duration-500 overflow-hidden">
            <div
              class="absolute inset-0 bg-gradient-to-r from-emerald-400/20 to-green-500/20 opacity-0 group-hover:opacity-100 transition-all duration-1000">
            </div>
            <div class="relative">
              <div
                class="w-20 h-20 bg-gradient-to-r from-emerald-400 to-green-500 rounded-2xl shadow-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-500">
                <span class="text-3xl">📊</span>
              </div>
              <h3 class="text-2xl font-black text-white mb-2 drop-shadow-lg">نرخ رشد فروش</h3>
              <div
                class="text-4xl font-black bg-gradient-to-r from-emerald-400 to-green-500 bg-clip-text text-transparent drop-shadow-2xl mb-4">
                92.7%
              </div>
              <div class="flex items-center text-green-400 text-sm font-bold">
                <span>بهترین ماه</span>
              </div>
            </div>
          </div>
        </div>

        <!-- Performance Chart -->
        <div
          class="bg-gradient-to-r from-slate-900/90 via-purple-900/70 to-amber-900/90 backdrop-blur-xl rounded-3xl p-10 border border-amber-400/20 shadow-3xl border-opacity-50">
          <div class="flex items-center justify-between mb-8">
            <div>
              <h2 class="text-3xl font-black text-white drop-shadow-xl mb-2">تحلیل عملکرد</h2>
              <p class="text-xl text-purple-200 opacity-80">آمار کامل ماه های گذشته</p>
            </div>
            <div class="w-3 h-3 bg-gradient-to-r from-amber-400 to-yellow-500 rounded-full animate-ping"></div>
          </div>
          <div class="h-96">
            <canvas ref="performanceChart" class="w-full h-full"></canvas>
          </div>
        </div>
      </div>
    </main>
  </div>
</template>

<script>
import { onMounted, ref } from 'vue';
import Chart from 'chart.js/auto';
import {
  HomeIcon,
  UsersIcon,
  ChartBarIcon,
  CogIcon,
  BuildingStorefrontIcon
} from '@heroicons/vue/24/outline';

export default {
  components: {
    HomeIcon,
    UsersIcon,
    ChartBarIcon,
    CogIcon,
    BuildingStorefrontIcon
  },
  setup() {
    const performanceChart = ref(null);
    const sideMenuOpen = ref(false);

    const menuItems = [
      { path: '/dashboard', title: 'داشبورد', subtitle: 'نمای کلی', icon: 'HomeIcon' },
      { path: '/customers', title: 'مدیریت مشتری', subtitle: 'پنل کاربران', icon: 'UsersIcon' },
      {
        path: '/inventory',
        title: 'انبار و خدمات',
        subtitle: 'مدیریت موجودی',
        icon: BuildingStorefrontIcon
      },
      { path: '/analytics', title: 'آمار و تحلیل', subtitle: 'گزارشات دقیق', icon: 'ChartBarIcon' },
      { path: '/settings', title: 'تنظیمات', subtitle: 'مدیریت سیستم', icon: 'CogIcon' }
    ];

    onMounted(() => {
      if (performanceChart.value) {
        const ctx = performanceChart.value.getContext('2d');
        new Chart(ctx, {
          type: 'line',
          data: {
            labels: ['دی', 'آذر', 'آبان', 'مهر', 'شهریور', 'مرداد'],
            datasets: [{
              label: 'عملکرد',
              data: [65, 59, 80, 81, 56, 92],
              borderColor: '#FCD34D',
              backgroundColor: 'rgba(252, 211, 77, 0.1)',
              borderWidth: 4,
              tension: 0.4,
              fill: true,
              pointBackgroundColor: '#FCD34D',
              pointBorderColor: '#92400E',
              pointBorderWidth: 3,
              pointHoverRadius: 8,
              pointRadius: 6
            }]
          },
          options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
              legend: {
                display: false
              }
            },
            scales: {
              x: {
                grid: { color: 'rgba(255,255,255,0.1)' },
                ticks: { color: 'white', font: { size: 14 } }
              },
              y: {
                grid: { color: 'rgba(255,255,255,0.1)' },
                ticks: { color: 'white', font: { size: 14 } }
              }
            }
          }
        });
      }
    });

    const toggleSideMenu = () => {
      sideMenuOpen.value = !sideMenuOpen.value;
    };

    return {
      performanceChart,
      sideMenuOpen,
      toggleSideMenu,
      menuItems
    };
  }
};
</script>

<style scoped>
@import 'animate.css';

.animate-ping {
  animation: ping 1s cubic-bezier(0, 0, 0.2, 1) infinite;
}
</style>
