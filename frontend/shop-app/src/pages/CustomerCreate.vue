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

    <!-- Loading State (فقط برای consistency) -->
    <div v-if="loading" class="text-center py-24">
      <div class="inline-block animate-spin rounded-full h-20 w-20 border-b-2 border-amber-400 mx-auto mb-8 shadow-2xl">
      </div>
      <h2 class="text-2xl font-bold text-purple-300 mb-4">در حال آماده‌سازی...</h2>
      <p class="text-purple-400">لطفاً صبر کنید</p>
    </div>

    <!-- Form for Creating Customer -->
    <div v-else class="bg-gradient-to-r from-slate-900/95 via-purple-900/80 to-amber-900/90 backdrop-blur-2xl rounded-3xl p-8 border-2 border-amber-400/40 shadow-3xl">
      <div class="flex items-center justify-between mb-8">
        <h2 class="text-3xl font-black bg-gradient-to-r from-amber-400 via-yellow-400 to-orange-500 bg-clip-text text-transparent text-right">
          ✨ ایجاد مشتری جدید
        </h2>
        <router-link to="/customers" 
          class="bg-gradient-to-r from-blue-500/90 to-blue-600/90 hover:from-blue-600 hover:to-blue-700 text-white px-6 py-3 rounded-2xl font-bold shadow-2xl hover:shadow-3xl hover:scale-105 transition-all whitespace-nowrap">
          👥 لیست مشتریان
        </router-link>
      </div>

      <!-- Customer Create Form -->
      <form @submit.prevent="createCustomer" class="space-y-6" novalidate>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <!-- Name -->
          <div>
            <label for="name" class="block text-white font-semibold mb-2 text-right">
              نام مشتری <span class="text-red-400">*</span>
            </label>
            <input 
              v-model.trim="form.name" 
              id="name" 
              type="text" 
              placeholder="نام کامل مشتری را وارد کنید" 
              required
              autocomplete="name"
              :class="[
                'w-full p-4 bg-slate-800/70 hover:bg-slate-800/90 text-white rounded-2xl border-2 focus:outline-none transition-all duration-300 shadow-lg text-lg',
                errors.name ? 'border-red-400/70 bg-red-900/20 ring-2 ring-red-500/30' : 'border-amber-400/40 focus:border-amber-400/70 focus:ring-2 focus:ring-amber-400/30'
              ]" />
            <div v-if="errors.name" class="text-red-400 text-sm mt-1 flex items-center gap-1">
              <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
              </svg>
              {{ errors.name[0] || errors.name }}
            </div>
          </div>

          <!-- Email -->
          <div>
            <label for="email" class="block text-white font-semibold mb-2 text-right">ایمیل</label>
            <input 
              v-model.trim="form.email" 
              id="email" 
              type="email" 
              placeholder="example@domain.com"
              autocomplete="email"
              :class="[
                'w-full p-4 bg-slate-800/70 hover:bg-slate-800/90 text-white rounded-2xl border-2 focus:outline-none transition-all duration-300 shadow-lg text-lg',
                errors.email ? 'border-red-400/70 bg-red-900/20 ring-2 ring-red-500/30' : 'border-green-400/40 focus:border-green-400/70 focus:ring-2 focus:ring-green-400/30'
              ]" />
            <div v-if="errors.email" class="text-red-400 text-sm mt-1 flex items-center gap-1">
              <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
              </svg>
              {{ errors.email[0] || errors.email }}
            </div>
          </div>

          <!-- Phone -->
          <div>
            <label for="phone" class="block text-white font-semibold mb-2 text-right">شماره تماس</label>
            <input 
              v-model.trim="form.phone" 
              id="phone" 
              type="tel" 
              placeholder="09123456789"
              autocomplete="tel"
              :class="[
                'w-full p-4 bg-slate-800/70 hover:bg-slate-800/90 text-white rounded-2xl border-2 focus:outline-none transition-all duration-300 shadow-lg text-lg dir-ltr',
                errors.phone ? 'border-red-400/70 bg-red-900/20 ring-2 ring-red-500/30' : 'border-emerald-400/40 focus:border-emerald-400/70 focus:ring-2 focus:ring-emerald-400/30'
              ]" />
            <div v-if="errors.phone" class="text-red-400 text-sm mt-1 flex items-center gap-1">
              <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
              </svg>
              {{ errors.phone[0] || errors.phone }}
            </div>
          </div>

          <!-- Address -->
          <div class="md:col-span-2">
            <label for="address" class="block text-white font-semibold mb-2 text-right">آدرس کامل</label>
            <textarea 
              v-model.trim="form.address" 
              id="address" 
              rows="4" 
              placeholder="آدرس کامل مشتری را وارد کنید"
              :class="[
                'w-full p-4 bg-slate-800/70 hover:bg-slate-800/90 text-white rounded-2xl border-2 focus:outline-none transition-all duration-300 shadow-lg resize-vertical text-lg',
                errors.address ? 'border-red-400/70 bg-red-900/20 ring-2 ring-red-500/30' : 'border-purple-400/40 focus:border-purple-400/70 focus:ring-2 focus:ring-purple-400/30'
              ]"></textarea>
            <div v-if="errors.address" class="text-red-400 text-sm mt-1 flex items-center gap-1">
              <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
              </svg>
              {{ errors.address[0] || errors.address }}
            </div>
          </div>
        </div>

        <!-- Buttons -->
        <div class="flex flex-col sm:flex-row gap-4 justify-end pt-6 border-t-2 border-amber-400/30 bg-gradient-to-r from-slate-900/50 to-transparent p-6 rounded-2xl backdrop-blur-sm">
          <button type="reset" @click="resetForm"
            class="flex-1 sm:flex-none bg-gradient-to-r from-gray-600/50 to-gray-700/50 hover:from-gray-700 hover:to-gray-800 text-white px-8 py-4 rounded-2xl font-bold shadow-xl hover:shadow-2xl hover:scale-105 transition-all duration-300 border border-gray-500/50">
            🔄 بازنشانی فرم
          </button>
          <button type="submit" :disabled="creating"
            class="flex-1 sm:flex-none bg-gradient-to-r from-emerald-500 via-teal-500 to-green-600 hover:from-emerald-600 hover:via-teal-600 hover:to-green-700 text-white px-10 py-4 rounded-2xl font-bold text-lg shadow-2xl hover:shadow-3xl hover:scale-105 transition-all duration-300 disabled:opacity-50 disabled:scale-100 disabled:cursor-not-allowed flex items-center justify-center gap-2">
            <span v-if="creating">
              <svg class="animate-spin -ml-1 mr-2 h-5 w-5" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
              </svg>
              در حال ایجاد...
            </span>
            <span v-else>
              💾 ایجاد مشتری جدید
            </span>
          </button>
        </div>
      </form>
    </div>
  </div>
</template>

<script>
import axios from 'axios';

export default {
  name: 'CustomerCreate',
  data() {
    return {
      form: {
        name: '',
        email: '',
        phone: '',
        address: ''
      },
      creating: false,
      errors: {},
      loading: false // برای consistency با edit
    };
  },
  methods: {
    async createCustomer() {
      this.errors = {};
      this.creating = true;
      
      try {
        // Frontend validation
        if (!this.form.name.trim()) {
          this.errors.name = 'نام مشتری الزامی است';
          return;
        }
        
        if (this.form.email && !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(this.form.email)) {
          this.errors.email = 'فرمت ایمیل صحیح نیست';
          return;
        }
        
        if (this.form.phone && !/^09\d{9}$/.test(this.form.phone.replace(/[^0-9]/g, ''))) {
          this.errors.phone = 'فرمت شماره موبایل صحیح نیست (09xxxxxxxxx)';
          return;
        }

        const response = await axios.post('/api/customers', this.form);
        
        // Success feedback
        this.$toast?.success(`مشتری "${this.form.name}" با موفقیت ایجاد شد!`);
        this.$router.push(`/customers/${response.data.data.id}`);
        
      } catch (error) {
        console.error('Customer creation failed:', error);
        
        // Laravel validation errors
        if (error.response?.status === 422) {
          const backendErrors = error.response.data.errors || {};
          this.errors = {
            name: backendErrors.name?.[0],
            email: backendErrors.email?.[0],
            phone: backendErrors.phone?.[0],
            address: backendErrors.address?.[0]
          };
        } else {
          this.$toast?.error('خطا در ایجاد مشتری جدید. لطفاً دوباره تلاش کنید.');
        }
      } finally {
        this.creating = false;
      }
    },
    
    resetForm() {
      this.form = {
        name: '',
        email: '',
        phone: '',
        address: ''
      };
      this.errors = {};
      // Clear validation
      const inputs = this.$el.querySelectorAll('input, textarea');
      inputs.forEach(input => {
        input.classList.remove('border-red-400/70', 'bg-red-900/20', 'ring-2', 'ring-red-500/30');
      });
    }
  }
};
</script>

<style scoped>
.dir-ltr {
  direction: ltr;
}

textarea {
  font-family: inherit;
  line-height: 1.5;
}

/* Focus ring removal for better UX */
input:focus, textarea:focus {
  box-shadow: 0 0 0 3px rgba(251, 191, 36, 0.3);
}
</style>
