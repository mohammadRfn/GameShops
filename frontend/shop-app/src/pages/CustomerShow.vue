<template>
  <div class="min-h-screen bg-gradient-to-br from-slate-900 via-purple-900/80 to-amber-900/80 p-8">
    <!-- Back Button -->
    <div class="flex items-center mb-12">
      <button @click="$router.go(-1)"
        class="flex items-center space-x-3 bg-gradient-to-r from-amber-500/20 to-purple-500/20 p-4 rounded-2xl border border-amber-400/40 hover:scale-105 hover:shadow-2xl transition-all duration-300 text-white font-bold shadow-xl">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
        </svg>
        <span>بازگشت</span>
      </button>
    </div>


    <!-- Loading State -->
    <div v-if="loading" class="text-center py-24">
      <div class="inline-block animate-spin rounded-full h-20 w-20 border-b-2 border-amber-400 mx-auto mb-8 shadow-2xl">
      </div>
      <h2 class="text-2xl font-bold text-purple-300 mb-4">در حال بارگذاری اطلاعات مشتری...</h2>
      <p class="text-purple-400">لطفاً صبر کنید</p>
    </div>

    <!-- Debug Info (Development Only) -->
    <div v-else-if="!customer && !loading"
      class="mb-8 p-6 bg-red-900/30 border-2 border-red-500 rounded-3xl backdrop-blur-xl">
      <details class="text-white cursor-pointer">
        <summary class="font-bold mb-2 text-lg flex items-center gap-2">
          🔍 Debug Information (کلیک کن)
        </summary>
        <div class="mt-4 p-4 bg-black/50 rounded-2xl text-sm overflow-auto max-h-48">
          <pre class="text-xs">{{ debugData }}</pre>
        </div>
      </details>
    </div>

    <!-- Customer Data -->
    <div v-else>
      <!-- Filters & Search Section -->
      <div
        class="bg-gradient-to-r from-slate-900/95 via-purple-900/80 to-amber-900/90 backdrop-blur-2xl rounded-3xl p-8 border-2 border-amber-400/40 shadow-3xl mb-12">
        <h3
          class="text-3xl font-black bg-gradient-to-r from-amber-400 via-yellow-400 to-orange-500 bg-clip-text text-transparent mb-6 text-right">
          🔍 فیلتر و جستجوی پیشرفته
        </h3>

        <!-- Search & Filters Row -->
        <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-6 mb-8">
          <!-- Global Search -->
          <div class="relative md:col-span-2 lg:col-span-2">
            <input v-model="searchQuery" @input="debounceFilter" :placeholder="searchPlaceholder"
              class="w-full pl-14 pr-4 py-4 bg-slate-800/70 hover:bg-slate-800/90 backdrop-blur-xl rounded-3xl border-2 border-amber-400/40 focus:border-amber-400/70 focus:outline-none text-lg text-white placeholder-purple-400 transition-all duration-300 shadow-xl" />
            <svg class="absolute left-5 top-1/2 transform -translate-y-1/2 w-6 h-6 text-amber-400" fill="none"
              stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
          </div>

          <!-- Request Status Filter -->
          <select v-model="requestFilter" @change="filterData"
            class="bg-slate-800/70 hover:bg-slate-800/90 backdrop-blur-xl p-4 rounded-3xl border-2 border-green-400/40 focus:border-green-400/70 text-lg text-white focus:outline-none transition-all duration-300 shadow-xl">
            <option value="">همه درخواست‌ها ({{ originalRequests.length }})</option>
            <option value="new">🆕 جدید</option>
            <option value="processing">⏳ در حال انجام</option>
            <option value="completed">✅ تکمیل شده</option>
            <option value="canceled">❌ لغو شده</option>
          </select>

          <!-- Invoice Status Filter - FIXED for enum('confirmed', 'not_confirmed') -->
          <select v-model="invoiceFilter" @change="filterData"
            class="bg-slate-800/70 hover:bg-slate-800/90 backdrop-blur-xl p-4 rounded-3xl border-2 border-amber-400/40 focus:border-amber-400/70 text-lg text-white focus:outline-none transition-all duration-300 shadow-xl">
            <option value="">همه فاکتورها ({{ originalInvoices.length }})</option>
            <option value="confirmed">✅ تایید شده</option>
            <option value="not_confirmed">❌ رد شده</option>
            <option value="null">➖ در حال بررسی</option>
          </select>
        </div>

        <!-- Results Summary -->
        <div
          class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 text-right text-purple-300 text-lg bg-black/20 p-4 rounded-2xl backdrop-blur-sm border border-purple-500/30">
          <div>
            <span class="font-bold text-amber-400">{{ filteredRequests.length }}</span>
            <span class="ml-1">درخواست</span> از {{ originalRequests.length }}
          </div>
          <div>
            <span class="font-bold text-amber-400">{{ filteredInvoices.length }}</span>
            <span class="ml-1">فاکتور</span> از {{ originalInvoices.length }}
          </div>
          <div v-if="searchQuery" class="text-sm opacity-75">
            🔍 {{ searchQuery }} ({{ filteredRequests.length + filteredInvoices.length }} نتیجه)
          </div>
        </div>
      </div>

      <!-- Customer Header -->
      <div
        class="bg-gradient-to-r from-slate-900/95 via-purple-900/80 to-amber-900/90 backdrop-blur-2xl rounded-3xl p-10 border-2 border-amber-400/40 shadow-3xl mb-12">
        <div class="flex flex-col lg:flex-row items-start lg:items-center justify-between mb-8 gap-6">
          <div class="text-right flex-1">
            <h1
              class="text-5xl lg:text-6xl font-black bg-gradient-to-r from-amber-400 via-yellow-400 to-orange-500 bg-clip-text text-transparent mb-3 leading-tight drop-shadow-2xl">
              {{ customer.name || 'نامشخص' }}
            </h1>
            <div class="text-xl text-purple-200 font-semibold flex flex-wrap items-center gap-4">
              <span>کد مشتری:</span>
              <span
                class="text-amber-400 font-mono font-bold bg-black/40 px-4 py-2 rounded-xl border border-amber-400/50 shadow-lg">
                #{{ customer.id }}
              </span>
              <span class="text-sm bg-purple-500/30 px-3 py-1 rounded-lg border border-purple-400/50">
                {{ totalStats }}
              </span>
            </div>
          </div>
          <router-link :to="`/customers/${customer.id}/edit`"
            class="bg-gradient-to-r from-blue-500/90 to-blue-600/90 hover:from-blue-600 hover:to-blue-700 text-white px-10 py-5 rounded-3xl font-bold text-lg shadow-2xl hover:shadow-3xl hover:scale-105 transition-all duration-300 border-2 border-blue-400/50 whitespace-nowrap flex items-center gap-2">
            ✏️ ویرایش اطلاعات
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.5h3m-3 0H3m-2-5h18" />
            </svg>
          </router-link>
        </div>

        <!-- Basic Information Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
          <div
            class="group bg-slate-800/60 hover:bg-slate-800/80 backdrop-blur-xl p-7 rounded-3xl border border-amber-400/40 hover:border-amber-400/60 shadow-2xl hover:shadow-3xl hover:scale-[1.02] transition-all duration-300">
            <div class="flex items-center mb-5">
              <div
                class="w-14 h-14 bg-gradient-to-r from-amber-400 to-yellow-500 rounded-2xl flex items-center justify-center shadow-2xl mr-4 group-hover:scale-110 transition-all duration-300 flex-shrink-0">
                <span class="text-2xl">📧</span>
              </div>
              <h3 class="font-black text-amber-400 text-xl">ایمیل</h3>
            </div>
            <p class="text-lg text-white font-mono break-all leading-relaxed">{{ customer.email || '❌ ثبت نشده' }}</p>
          </div>

          <div
            class="group bg-slate-800/60 hover:bg-slate-800/80 backdrop-blur-xl p-7 rounded-3xl border border-amber-400/40 hover:border-amber-400/60 shadow-2xl hover:shadow-3xl hover:scale-[1.02] transition-all duration-300">
            <div class="flex items-center mb-5">
              <div
                class="w-14 h-14 bg-gradient-to-r from-green-400 to-emerald-500 rounded-2xl flex items-center justify-center shadow-2xl mr-4 group-hover:scale-110 transition-all duration-300 flex-shrink-0">
                <span class="text-2xl">📱</span>
              </div>
              <h3 class="font-black text-green-400 text-xl">شماره تماس</h3>
            </div>
            <p class="text-lg text-white font-mono font-bold">{{ customer.phone || '❌ ثبت نشده' }}</p>
          </div>

          <div
            class="group bg-slate-800/60 hover:bg-slate-800/80 backdrop-blur-xl p-7 rounded-3xl border border-amber-400/40 hover:border-amber-400/60 shadow-2xl hover:shadow-3xl hover:scale-[1.02] transition-all duration-300">
            <div class="flex items-center mb-5">
              <div
                class="w-14 h-14 bg-gradient-to-r from-purple-400 to-violet-500 rounded-2xl flex items-center justify-center shadow-2xl mr-4 group-hover:scale-110 transition-all duration-300 flex-shrink-0">
                <span class="text-2xl">📍</span>
              </div>
              <h3 class="font-black text-purple-400 text-xl">آدرس</h3>
            </div>
            <p class="text-base text-white leading-relaxed max-h-20 overflow-hidden line-clamp-3">{{ customer.address ||
              '❌ ثبت نشده' }}</p>
          </div>

          <div
            class="group bg-slate-800/60 hover:bg-slate-800/80 backdrop-blur-xl p-7 rounded-3xl border border-amber-400/40 hover:border-amber-400/60 shadow-2xl hover:shadow-3xl hover:scale-[1.02] transition-all duration-300">
            <div class="flex items-center mb-5">
              <div
                class="w-14 h-14 bg-gradient-to-r from-emerald-400 to-teal-500 rounded-2xl flex items-center justify-center shadow-2xl mr-4 group-hover:scale-110 transition-all duration-300 flex-shrink-0">
                <span class="text-2xl">📅</span>
              </div>
              <h3 class="font-black text-emerald-400 text-xl">تاریخ ثبت‌نام</h3>
            </div>
            <p class="text-lg text-white font-mono font-bold">{{ formatDate(customer.created_at) }}</p>
          </div>

          <div
            class="group bg-slate-800/60 hover:bg-slate-800/80 backdrop-blur-xl p-7 rounded-3xl border border-amber-400/40 hover:border-amber-400/60 shadow-2xl hover:shadow-3xl hover:scale-[1.02] transition-all duration-300">
            <div class="flex items-center mb-5">
              <div
                class="w-14 h-14 bg-gradient-to-r from-blue-400 to-cyan-500 rounded-2xl flex items-center justify-center shadow-2xl mr-4 group-hover:scale-110 transition-all duration-300 flex-shrink-0">
                <span class="text-2xl">🔄</span>
              </div>
              <h3 class="font-black text-blue-400 text-xl">آخرین بروزرسانی</h3>
            </div>
            <p class="text-lg text-white font-mono font-bold">{{ formatDate(customer.updated_at) }}</p>
          </div>
        </div>
      </div>

      <!-- Requests Section -->
      <div v-if="originalRequests.length > 0" class="mb-12">
        <div
          class="bg-gradient-to-r from-slate-900/95 via-green-900/70 to-emerald-900/70 backdrop-blur-2xl rounded-3xl p-10 border-2 border-green-400/50 shadow-3xl mb-8"
          :class="{ 'opacity-50 pointer-events-none': filteredRequests.length === 0 }">
          <h2
            class="text-4xl font-black bg-gradient-to-r from-green-400 via-emerald-400 to-teal-500 bg-clip-text text-transparent mb-8 text-right drop-shadow-2xl">
            📋 درخواست‌های مشتری ({{ filteredRequests.length }})
          </h2>
          <div v-if="filteredRequests.length === 0" class="text-center py-12">
            <div class="text-6xl mb-4 opacity-50">🔍</div>
            <p class="text-xl text-green-300">هیچ درخواستی با فیلترهای انتخابی یافت نشد</p>
            <p class="text-purple-400 text-sm mt-2">فیلترها را تغییر دهید یا جستجو را پاک کنید</p>
          </div>
          <div v-else class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <div v-for="request in filteredRequests" :key="request.id"
              class="bg-slate-800/70 hover:bg-slate-800/90 backdrop-blur-xl p-7 rounded-3xl border border-green-400/50 hover:border-green-400/70 shadow-2xl hover:shadow-3xl hover:scale-[1.02] transition-all duration-300 group">
              <div class="flex items-start justify-between mb-5">
                <div class="flex-1 pr-4">
                  <h4
                    class="font-black text-xl text-green-300 text-right leading-tight mb-2 line-clamp-2 group-hover:text-green-200">
                    {{ request.customer_name || 'بدون عنوان' }}
                  </h4>
                  <div v-if="request.categories && request.categories.length > 0" class="flex flex-wrap gap-2 mb-3">
                    <span v-for="category in request.categories.slice(0, 3)" :key="category"
                      class="px-3 py-1.5 bg-green-500/40 text-green-200 text-sm font-bold rounded-xl border-2 border-green-400/60 hover:bg-green-500/60 transition-all duration-200 whitespace-nowrap">
                      {{ category }}
                    </span>
                    <span v-if="request.categories.length > 3"
                      class="px-3 py-1.5 bg-gray-700/50 text-gray-300 text-sm rounded-xl border border-gray-500/50">
                      +{{ request.categories.length - 3 }}
                    </span>
                  </div>
                </div>
                <span :class="[
                  'px-4 py-2 font-bold rounded-2xl border-2 shadow-lg whitespace-nowrap text-center min-w-[110px]',
                  getRequestStatusClass(request.status)
                ]">
                  {{ getRequestStatusText(request.status) }}
                </span>
              </div>
              <p class="text-base text-purple-200 mb-5 leading-relaxed text-right line-clamp-3">{{ request.description
                || 'توضیحات موجود نیست' }}</p>
              <div class="flex justify-between items-center text-sm text-purple-400 pt-3 border-t border-green-500/30">
                <span>ID: #{{ request.id }}</span>
                <span>{{ formatDateShort(request.created_at) }}</span>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Empty Requests State -->
      <div v-else
        class="text-center py-20 bg-slate-800/50 rounded-3xl border-2 border-green-400/30 mb-12 backdrop-blur-xl">
        <div class="text-7xl mb-6 opacity-40">📭</div>
        <h3 class="text-3xl font-black text-green-300 mb-4">هیچ درخواستی یافت نشد</h3>
        <p class="text-xl text-purple-400 mb-8 max-w-md mx-auto">این مشتری هنوز درخواستی ثبت نکرده است</p>
      </div>

      <!-- Invoices Section - FIXED for correct enum values -->
      <div v-if="originalInvoices.length > 0" class="mb-12">
        <div
          class="bg-gradient-to-r from-slate-900/95 via-amber-900/70 to-orange-900/70 backdrop-blur-2xl rounded-3xl p-10 border-2 border-amber-400/50 shadow-3xl mb-8"
          :class="{ 'opacity-50 pointer-events-none': filteredInvoices.length === 0 }">
          <h2
            class="text-4xl font-black bg-gradient-to-r from-amber-400 via-yellow-400 to-orange-500 bg-clip-text text-transparent mb-8 text-right drop-shadow-2xl">
            💰 فاکتورهای مشتری ({{ filteredInvoices.length }})
          </h2>
          <div v-if="filteredInvoices.length === 0" class="text-center py-12">
            <div class="text-6xl mb-4 opacity-50">🔍</div>
            <p class="text-xl text-amber-300">هیچ فاکتوری با فیلترهای انتخابی یافت نشد</p>
            <p class="text-purple-400 text-sm mt-2">فیلترها را تغییر دهید یا جستجو را پاک کنید</p>
          </div>
          <div v-else
            class="overflow-x-auto rounded-3xl bg-slate-800/60 backdrop-blur-2xl border border-amber-400/40 shadow-2xl">
            <table class="min-w-full">
              <thead class="bg-gradient-to-r from-amber-500/50 to-yellow-500/50 backdrop-blur-md rounded-t-3xl">
                <tr>
                  <th class="p-6 text-right font-black text-amber-200 text-lg border-b-2 border-amber-400/60">شماره
                    فاکتور</th>
                  <th class="p-6 text-right font-black text-amber-200 text-lg border-b-2 border-amber-400/60">مبلغ کل
                  </th>
                  <th class="p-6 text-right font-black text-amber-200 text-lg border-b-2 border-amber-400/60">وضعیت</th>
                  <th class="p-6 text-right font-black text-amber-200 text-lg border-b-2 border-amber-400/60">تاریخ صدور
                  </th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="invoice in filteredInvoices" :key="invoice.id"
                  class="border-b-2 border-amber-400/40 hover:bg-white/10 transition-all duration-300 hover:shadow-lg">
                  <td class="p-6 font-mono text-xl font-black text-white text-right">
                    #{{ invoice.invoice_number || invoice.id }}
                  </td>
                  <td class="p-6 text-right">
                    <span class="text-2xl font-black text-green-400 drop-shadow-2xl font-mono">
                      {{ formatPrice(invoice.total_amount || invoice.amount) }}
                    </span>
                  </td>
                  <td class="p-6 text-right">
                    <span :class="[
                      'px-6 py-3 rounded-2xl text-lg font-black inline-block shadow-2xl border-3 min-w-[140px] text-center',
                      getInvoiceStatusClass(invoice.is_confirmed)
                    ]">
                      {{getInvoiceStatusText(invoice.is_confirmed)}}
                    </span>
                  </td>
                  <td class="p-6 font-mono text-lg text-purple-300 text-right">
                    {{ formatDateShort(invoice.created_at) }}
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>

      <!-- Empty Invoices State -->
      <div v-else-if="!loading"
        class="text-center py-20 bg-slate-800/50 rounded-3xl border-2 border-amber-400/30 backdrop-blur-xl">
        <div class="text-7xl mb-6 opacity-40">💸</div>
        <h3 class="text-3xl font-black text-amber-300 mb-4">هیچ فاکتوری یافت نشد</h3>
        <p class="text-xl text-purple-400 mb-8 max-w-md mx-auto">این مشتری هنوز فاکتوری صادر نشده است</p>
      </div>
    </div>

    <!-- No Customer Found -->
    <div v-if="!loading && !customer" class="text-center py-32">
      <div class="text-8xl mb-8 opacity-50 animate-pulse">😔</div>
      <h2 class="text-4xl font-black text-purple-300 mb-6">اطلاعات مشتری یافت نشد</h2>
      <p class="text-xl text-purple-400 mb-12 max-w-2xl mx-auto leading-relaxed">
        ممکن است مشتری حذف شده باشد یا ID اشتباه وارد شده باشد
      </p>
      <router-link to="/customers"
        class="bg-gradient-to-r from-amber-500 to-yellow-500 text-black px-16 py-6 rounded-3xl font-bold text-2xl shadow-3xl hover:shadow-4xl hover:scale-105 transition-all duration-300 inline-flex items-center gap-3">
        🚀 بازگشت به لیست مشتریان
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
        </svg>
      </router-link>
    </div>
  </div>
</template>

<script>
import api from '../api/axios'; 

export default {
  name: 'CustomerShow',
  data() {
    return {
      customer: null,
      loading: true,
      rawResponse: null,
      searchQuery: '',
      requestFilter: '',
      invoiceFilter: '',
      filterTimeout: null,
      originalRequests: [],
      originalInvoices: []
    }
  },
  computed: {
    debugData() {
      return {
        '📍 Route ID': this.$route.params.id || 'نامشخص',
        '📡 API Response': this.rawResponse?.data ? 'OK' : 'خالی',
        '🎯 Customer Keys': this.customer ? Object.keys(this.customer).join(', ') : 'خالی',
        '👤 Customer Name': this.customer?.name || 'ندارد',
        '📧 Customer Email': this.customer?.email || 'ندارد',
        '📋 Total Requests': this.originalRequests.length,
        '💰 Total Invoices': this.originalInvoices.length,
        '🔍 Search Query': this.searchQuery || 'خالی',
        '✅ Data Loaded': !!this.customer,
        '📊 Request Statuses': [...new Set(this.originalRequests.map(r => r.status))].join(', '),
        '💳 Invoice Statuses': [...new Set(this.originalInvoices.map(i => i.status))].join(', ')
      };
    },
    searchPlaceholder() {
      return this.searchQuery ? '' : 'جستجو در نام، عنوان، توضیحات، دسته‌بندی‌ها، شماره فاکتور، مبلغ...';
    },
    totalStats() {
      const reqCount = this.originalRequests.length;
      const invCount = this.originalInvoices.length;
      const totalAmount = this.originalInvoices.reduce((sum, inv) => sum + (inv.total_amount || inv.amount || 0), 0);
      return `${reqCount} درخواست | ${invCount} فاکتور | ${this.formatPrice(totalAmount)}`;
    },
    filteredRequests() {
      let requests = [...this.originalRequests];

      // Status filter
      if (this.requestFilter) {
        requests = requests.filter(request => request.status === this.requestFilter);
      }

      // Search filter
      if (this.searchQuery.trim()) {
        const query = this.searchQuery.toLowerCase().trim();
        requests = requests.filter(request =>
          (request.customer_name || '').toLowerCase().includes(query) ||
          (request.description || '').toLowerCase().includes(query) ||
          (request.status || '').toLowerCase().includes(query) ||
          (request.categories || []).some(cat =>
            (cat || '').toString().toLowerCase().includes(query)
          )
        );
      }

      return requests;
    },
    filteredInvoices() {
      let invoices = [...this.originalInvoices];

      // FIXED: Status filter for is_confirmed ('confirmed', 'not_confirmed', or null)
      if (this.invoiceFilter !== '') {
        if (this.invoiceFilter === 'null') {
          invoices = invoices.filter(invoice => invoice.is_confirmed === null);
        } else {
          invoices = invoices.filter(invoice => invoice.is_confirmed === this.invoiceFilter);
        }
      }

      // Search filter
      if (this.searchQuery.trim()) {
        const query = this.searchQuery.toLowerCase().trim();
        invoices = invoices.filter(invoice =>
          (invoice.invoice_number || '').toLowerCase().includes(query) ||
          (invoice.total_amount || invoice.amount || '').toString().includes(query) ||
          (invoice.categories || []).some(cat =>
            (cat || '').toString().toLowerCase().includes(query)
          )
        );
      }

      return invoices;
    }

  },
  watch: {
    '$route.params.id': {
      handler(newId) {
        console.log('🔄 Route changed to:', newId);
        this.fetchCustomer();
      },
      immediate: true
    },
    'customer'(newCustomer) {
      if (newCustomer) {
        // Store original unfiltered data
        this.originalRequests = newCustomer.requests || [];
        this.originalInvoices = newCustomer.invoices || [];
        console.log(`✅ Loaded ${this.originalRequests.length} requests, ${this.originalInvoices.length} invoices`);
        console.log('📋 Request statuses:', this.originalRequests.map(r => r.status));
        console.log('💰 Invoice statuses:', this.originalInvoices.map(i => i.status));
      }
    }
  },
  async mounted() {
    await this.fetchCustomer();
  },
  methods: {
    debounceFilter() {
      clearTimeout(this.filterTimeout);
      this.filterTimeout = setTimeout(() => {
        // Filtering handled by computed properties
      }, 350);
    },

    // Request Status Helpers
    getRequestStatusText(status) {
      const map = {
        'new': '🆕 جدید',
        'processing': '⏳ در حال انجام',
        'completed': '✅ تکمیل شده',
        'canceled': '❌ لغو شده'
      };
      return map[status] || status || 'نامشخص';
    },
    getRequestStatusClass(status) {
      const classes = {
        'new': 'bg-blue-500/40 text-blue-200 border-blue-400/70',
        'processing': 'bg-yellow-500/40 text-yellow-200 border-yellow-400/70',
        'completed': 'bg-green-500/50 text-green-100 border-green-400/70',
        'canceled': 'bg-red-500/50 text-red-100 border-red-400/70'
      };
      return classes[status] || 'bg-gray-500/40 text-gray-200 border-gray-400/70';
    },

    // ✅ FIXED - Uses is_confirmed (Backend field) + all cases
    getInvoiceStatusText(isConfirmed) {
      // Backend: is_confirmed = 'confirmed' → پرداخت شده
      if (isConfirmed === 'confirmed') {
        return '✅ پرداخت شده';
      }
      // Backend: is_confirmed = 'not_confirmed' → پرداخت نشده  
      if (isConfirmed === 'not_confirmed') {
        return '❌ پرداخت نشده';
      }
      // null → در حال بررسی
      return '➖ در حال بررسی';
    },

    getInvoiceStatusClass(isConfirmed) {
      if (isConfirmed === 'confirmed') {
        return 'bg-gradient-to-r from-green-500/80 to-emerald-500/80 text-white border-green-500 shadow-lg';
      } else if (isConfirmed === 'not_confirmed') {
        return 'bg-gradient-to-r from-red-500/80 to-rose-500/80 text-white border-red-500 shadow-lg';
      }
      // default: null = زرد
      return 'bg-gradient-to-r from-yellow-500/80 to-amber-500/80 text-white border-yellow-500 shadow-lg';
    },





    async fetchCustomer() {
      console.log('🚀 Fetching customer:', this.$route.params.id);

      this.loading = true;
      this.customer = null;
      this.originalRequests = [];
      this.originalInvoices = [];

      try {
        // Load ALL data without backend filters
        const response = await api.get(`/customers/${this.$route.params.id}`);

        this.rawResponse = response;
        this.customer = response.data.data || response.data;

        console.log('✅ Customer loaded successfully');
        console.log('- Requests:', this.customer.requests?.length || 0);
        console.log('- Invoices:', this.customer.invoices?.length || 0);

      } catch (error) {
        console.error('❌ Fetch error:', error.response?.status, error.message);
        this.customer = null;
      } finally {
        this.loading = false;
      }
    },

    formatDate(dateString) {
      if (!dateString) return '❌ ندارد';
      try {
        const date = new Date(dateString);
        return date.toLocaleDateString('fa-IR', {
          year: 'numeric', month: 'long', day: 'numeric',
          hour: '2-digit', minute: '2-digit'
        });
      } catch {
        return dateString;
      }
    },

    formatDateShort(dateString) {
      if (!dateString) return '❌ ندارد';
      try {
        const date = new Date(dateString);
        return date.toLocaleDateString('fa-IR', {
          year: 'numeric', month: 'short', day: 'numeric'
        });
      } catch {
        return dateString;
      }
    },

    formatPrice(amount) {
      if (!amount || isNaN(amount)) return '0 تومان';
      return new Intl.NumberFormat('fa-IR').format(Number(amount)) + ' تومان';
    }
  }
};
</script>

<style scoped>
/* Custom scrollbar */
::-webkit-scrollbar {
  width: 8px;
}

::-webkit-scrollbar-track {
  background: rgba(15, 23, 42, 0.5);
  border-radius: 4px;
}

::-webkit-scrollbar-thumb {
  background: linear-gradient(to right, #f59e0b, #eab308);
  border-radius: 4px;
}

::-webkit-scrollbar-thumb:hover {
  background: linear-gradient(to right, #d97706, #ca8a04);
}

/* Details styling */
details summary {
  list-style: none;
  cursor: pointer;
}

details summary::-webkit-details-marker {
  display: none;
}

details[open] summary {
  @apply font-bold text-lg;
}

/* Line clamp for text overflow */
.line-clamp-2 {
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
}

.line-clamp-3 {
  display: -webkit-box;
  -webkit-line-clamp: 3;
  -webkit-box-orient: vertical;
  overflow: hidden;
}

/* Group hover effects */
.group:hover .group-hover\:scale-110 {
  transform: scale(1.1);
}
</style>
