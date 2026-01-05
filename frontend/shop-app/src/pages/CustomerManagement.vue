<template>
  <div class="min-h-screen bg-gradient-to-br from-slate-900 via-purple-900 to-amber-900">
    <!-- Overlay -->
    <div v-if="sideMenuOpen" @click="toggleSideMenu" 
         class="fixed inset-0 bg-black/50 backdrop-blur-sm z-40 transition-all duration-300"></div>

    <!-- Luxury Side Menu (بهبود یافته) -->
    <aside 
      :class="[
        'fixed top-0 right-0 h-full w-80 bg-gradient-to-b from-amber-500/20 via-purple-500/20 to-slate-900/90 backdrop-blur-xl border-l-2 border-amber-400/50 shadow-2xl z-50 transform transition-all duration-500 ease-in-out',
        sideMenuOpen ? 'translate-x-0' : 'translate-x-full'
      ]"
    >
      <!-- Header Menu -->
      <div class="p-8 border-b border-amber-400/30">
        <div class="flex items-center justify-between mb-6">
          <div class="flex items-center space-x-3">
            <div class="w-12 h-12 bg-gradient-to-r from-amber-400 to-yellow-500 rounded-2xl shadow-lg flex items-center justify-center">
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
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
          </button>
        </div>
      </div>

      <!-- Navigation کامل -->
      <nav class="p-4 space-y-2 overflow-y-auto h-[calc(100vh-200px)]">
        <router-link 
          v-for="(item, index) in menuItems" 
          :key="index" 
          :to="item.path" 
          @click="toggleSideMenu"
          class="group flex items-center space-x-4 p-4 rounded-2xl bg-white/5 hover:bg-gradient-to-r hover:from-amber-400/20 hover:to-purple-500/20 hover:shadow-lg transition-all duration-300 hover:translate-x-2 border border-transparent hover:border-amber-400/30 block w-full"
        >
          <span class="w-12 h-12 bg-gradient-to-r from-amber-400/30 to-purple-500/30 rounded-xl flex items-center justify-center shadow-md group-hover:scale-110 transition-transform duration-200 flex-shrink-0">
            <i :class="item.icon" class="text-xl text-amber-300 drop-shadow-lg"></i>
          </span>
          <div class="min-w-0 flex-1">
            <p class="font-bold text-white text-lg drop-shadow-md truncate">{{ item.title }}</p>
            <p class="text-xs text-purple-200 opacity-70 truncate">{{ item.subtitle }}</p>
          </div>
        </router-link>
      </nav>
    </aside>

    <!-- Main Content -->
    <main class="w-full min-h-screen transition-all duration-300" :class="{ 'mr-80': sideMenuOpen }">
      <!-- Header Section -->
      <header class="bg-gradient-to-r from-slate-900/95 via-purple-900/80 to-amber-900/90 backdrop-blur-xl border-b-2 border-amber-400/30 shadow-2xl p-8 sticky top-0 z-30">
        <div class="flex items-center justify-between">
          <div>
            <h1 class="text-4xl md:text-5xl font-black bg-gradient-to-r from-amber-400 via-yellow-400 to-orange-500 bg-clip-text text-transparent drop-shadow-2xl">
              🎮 Game Empire
            </h1>
            <p class="text-xl text-purple-200/80 mt-2 font-medium drop-shadow-lg">مدیریت کامل فروشگاه گیم</p>
          </div>
          <button @click="toggleSideMenu" 
                  class="p-4 bg-gradient-to-r from-amber-500/20 to-purple-500/20 backdrop-blur-sm rounded-2xl border border-amber-400/40 shadow-xl hover:shadow-2xl hover:scale-105 transition-all duration-300 flex items-center space-x-3 text-white font-bold">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
            </svg>
            <span>منو</span>
          </button>
        </div>
      </header>

      <!-- Customers Management -->
      <div class="container mx-auto p-8 lg:p-12 text-white">
        <!-- Header & Create Button -->
        <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center mb-8 gap-4">
          <h2 class="text-3xl font-black bg-gradient-to-r from-amber-400 to-yellow-500 bg-clip-text text-transparent">
            مدیریت مشتریان
          </h2>
          <button @click="$router.push('/customers/create')"
                  class="bg-gradient-to-r from-amber-500 to-yellow-500 hover:from-amber-600 hover:to-yellow-600 text-black font-bold py-3 px-8 rounded-2xl shadow-2xl hover:shadow-3xl hover:scale-105 transition-all duration-300">
            + ایجاد مشتری جدید
          </button>
        </div>

        <!-- Search & Filters -->
        <div class="bg-slate-800/50 backdrop-blur-xl rounded-2xl p-6 mb-8 border border-amber-400/30">
          <div class="flex flex-col lg:flex-row gap-4">
            <input
              v-model="searchQuery"
              @input="debouncedSearch"
              type="text"
              placeholder="🔍 جستجو در نام، ایمیل..."
              class="flex-1 p-4 rounded-xl text-black font-medium bg-white/80 backdrop-blur-sm border border-amber-400/50 focus:border-amber-400 focus:outline-none focus:ring-4 focus:ring-amber-400/30 transition-all duration-300"
            />
          </div>
        </div>

        <!-- Customers Table -->
        <div class="bg-gradient-to-r from-slate-900/90 via-purple-900/70 to-amber-900/90 backdrop-blur-xl rounded-3xl p-8 border border-amber-400/30 shadow-2xl">
          <!-- Loading -->
          <div v-if="loading" class="text-center py-12">
            <div class="inline-block animate-spin rounded-full h-12 w-12 border-b-2 border-amber-400 mx-auto mb-4"></div>
            <p class="text-purple-300">در حال بارگذاری مشتریان...</p>
          </div>

          <!-- Table -->
          <div v-else>
            <div class="overflow-x-auto">
              <table class="min-w-full text-sm lg:text-base">
                <thead class="bg-gradient-to-r from-amber-400 to-yellow-500 text-white rounded-t-2xl">
                  <tr>
                    <th class="px-4 py-4 font-bold">ID</th>
                    <th class="px-4 py-4 font-bold text-right">نام</th>
                    <th class="px-4 py-4 font-bold text-right">ایمیل</th>
                    <th class="px-4 py-4 font-bold text-right">شماره تماس</th>
                    <th class="px-8 py-4 font-bold text-center">عملیات</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="customer in customers" :key="customer.id" 
                      class="border-b border-amber-400/30 hover:bg-white/10 transition-all duration-200">
                    <td class="px-4 py-4 font-mono font-bold text-amber-400">{{ customer.id }}</td>
                    <td class="px-4 py-4 text-right font-medium max-w-xs truncate" :title="customer.name">
                      {{ customer.name }}
                    </td>
                    <td class="px-4 py-4 text-right font-mono max-w-xs truncate" :title="customer.email">
                      {{ customer.email }}
                    </td>
                    <td class="px-4 py-4 text-right font-mono max-w-xs truncate" :title="customer.phone ?? 'ندارد'">
                      {{ customer.phone ?? '❌ ندارد' }}
                    </td>
                    <td class="px-4 py-4 space-x-2 text-center">
                      <button @click="$router.push(`/customers/${customer.id}`)"
                              class="bg-green-500/90 hover:bg-green-600 text-white rounded-xl py-2 px-4 font-medium shadow-lg hover:shadow-xl hover:scale-105 transition-all duration-200 mr-1">
                        مشاهده
                      </button>
                      <button @click="$router.push(`/customers/${customer.id}/edit`)"
                              class="bg-blue-500/90 hover:bg-blue-600 text-white rounded-xl py-2 px-4 font-medium shadow-lg hover:shadow-xl hover:scale-105 transition-all duration-200 mr-1">
                        ویرایش
                      </button>
                      <button @click="deleteCustomer(customer.id)"
                              class="bg-red-500/90 hover:bg-red-600 text-white rounded-xl py-2 px-4 font-medium shadow-lg hover:shadow-xl hover:scale-105 transition-all duration-200">
                        حذف
                      </button>
                    </td>
                  </tr>
                  <tr v-if="customers.length === 0">
                    <td colspan="5" class="px-4 py-12 text-center text-purple-300 font-medium">
                      هیچ مشتری پیدا نشد 😔
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>

            <!-- Pagination -->
            <div v-if="totalPages > 1" class="flex justify-center items-center mt-8 space-x-4">
              <button @click="previousPage" 
                      :disabled="currentPage === 1"
                      class="bg-purple-500/80 disabled:bg-purple-400 text-white py-2 px-6 rounded-xl font-bold hover:bg-purple-600 disabled:cursor-not-allowed hover:scale-105 transition-all duration-200"
                      :class="{ 'opacity-50': currentPage === 1 }">
                قبلی
              </button>
              <span class="text-lg font-bold text-amber-400 bg-black/30 px-4 py-2 rounded-xl">
                صفحه {{ currentPage }} از {{ totalPages }}
              </span>
              <button @click="nextPage"
                      :disabled="currentPage === totalPages"
                      class="bg-purple-500/80 disabled:bg-purple-400 text-white py-2 px-6 rounded-xl font-bold hover:bg-purple-600 disabled:cursor-not-allowed hover:scale-105 transition-all duration-200"
                      :class="{ 'opacity-50': currentPage === totalPages }">
                بعدی
              </button>
            </div>
          </div>
        </div>
      </div>
    </main>
  </div>
</template>

<script>
import axios from 'axios';

export default {
  data() {
    return {
      sideMenuOpen: false,
      customers: [],
      currentPage: 1,
      totalPages: 1,
      searchQuery: '',
      loading: false,
      timeout: null,
      menuItems: [
        { path: '/dashboard', title: 'داشبورد', subtitle: 'نمای کلی', icon: 'fa-house' },
        { path: '/customers', title: 'مشتریان', subtitle: 'مدیریت کاربران', icon: 'fa-users' },
        { path: '/orders', title: 'سفارشات', subtitle: 'مدیریت سفارش‌ها', icon: 'fa-shopping-cart' },
        { path: '/products', title: 'محصولات', subtitle: 'مدیریت کالا', icon: 'fa-gamepad' },
        { path: '/invoices', title: 'فاکتورها', subtitle: 'صورتحساب‌ها', icon: 'fa-file-invoice' }
      ]
    };
  },
  methods: {
    toggleSideMenu() {
      this.sideMenuOpen = !this.sideMenuOpen;
    },
    
    debouncedSearch() {
      clearTimeout(this.timeout);
      this.timeout = setTimeout(() => {
        this.currentPage = 1;
        this.fetchCustomers();
      }, 500);
    },

    async fetchCustomers() {
      this.loading = true;
      try {
        const params = {
          page: this.currentPage,
          search: this.searchQuery
        };
        const response = await axios.get('/api/customers', { params });
        this.customers = response.data.data || [];
        this.totalPages = response.data.last_page || 1;
      } catch (error) {
        console.error('خطا در بارگذاری مشتریان:', error);
        this.customers = [];
      } finally {
        this.loading = false;
      }
    },

    nextPage() {
      if (this.currentPage < this.totalPages) {
        this.currentPage++;
        this.fetchCustomers();
      }
    },

    previousPage() {
      if (this.currentPage > 1) {
        this.currentPage--;
        this.fetchCustomers();
      }
    },

    async deleteCustomer(id) {
      if (!confirm('آیا از حذف این مشتری اطمینان دارید؟')) return;
      
      try {
        await axios.delete(`/api/customers/${id}`);
        this.fetchCustomers();
        alert('مشتری با موفقیت حذف شد ✅');
      } catch (error) {
        console.error('خطا در حذف:', error);
        alert('خطا در حذف مشتری!');
      }
    }
  },

  mounted() {
    this.fetchCustomers();
  },

  beforeUnmount() {
    if (this.timeout) clearTimeout(this.timeout);
  }
};
</script>

<style scoped>
.fa {
  font-family: "Font Awesome 6 Free";
  font-weight: 900;
}
</style>
