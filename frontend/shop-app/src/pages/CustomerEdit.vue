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

    <!-- Error State -->
    <div v-else-if="!customer" class="text-center py-24">
      <div class="text-6xl mb-6 opacity-50">❌</div>
      <h2 class="text-2xl font-bold text-red-400 mb-4">مشتری یافت نشد</h2>
      <router-link to="/customers" class="bg-gradient-to-r from-amber-500 to-yellow-500 text-black px-8 py-3 rounded-xl font-bold shadow-xl hover:scale-105 transition-all">
        بازگشت به لیست
      </router-link>
    </div>

    <!-- Form for Editing Customer -->
    <div v-else class="bg-gradient-to-r from-slate-900/95 via-purple-900/80 to-amber-900/90 backdrop-blur-2xl rounded-3xl p-8 border-2 border-amber-400/40 shadow-3xl">
      <div class="flex items-center justify-between mb-8">
        <h2 class="text-3xl font-black bg-gradient-to-r from-amber-400 via-yellow-400 to-orange-500 bg-clip-text text-transparent text-right">
          ✏️ ویرایش اطلاعات مشتری #{{ customer.id }}
        </h2>
        <router-link :to="`/customers/${customer.id}`" 
          class="bg-gradient-to-r from-blue-500/90 to-blue-600/90 hover:from-blue-600 hover:to-blue-700 text-white px-6 py-3 rounded-2xl font-bold shadow-2xl hover:shadow-3xl hover:scale-105 transition-all whitespace-nowrap">
          👁️ مشاهده
        </router-link>
      </div>

      <!-- Customer Edit Form -->
      <form @submit.prevent="updateCustomer" class="space-y-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <!-- Name -->
          <div>
            <label for="name" class="block text-white font-semibold mb-2 text-right">نام مشتری <span class="text-red-400">*</span></label>
            <input v-model="form.name" id="name" type="text" placeholder="نام کامل مشتری" required
              :class="[
                'w-full p-4 bg-slate-800/70 hover:bg-slate-800/90 text-white rounded-2xl border-2 focus:outline-none transition-all duration-300 shadow-lg',
                errors.name ? 'border-red-400/70 bg-red-900/20' : 'border-amber-400/40 focus:border-amber-400/70'
              ]" />
            <div v-if="errors.name" class="text-red-400 text-sm mt-1">{{ errors.name }}</div>
          </div>

          <!-- Email -->
          <div>
            <label for="email" class="block text-white font-semibold mb-2 text-right">ایمیل</label>
            <input v-model="form.email" id="email" type="email" placeholder="example@email.com"
              :class="[
                'w-full p-4 bg-slate-800/70 hover:bg-slate-800/90 text-white rounded-2xl border-2 focus:outline-none transition-all duration-300 shadow-lg',
                errors.email ? 'border-red-400/70 bg-red-900/20' : 'border-green-400/40 focus:border-green-400/70'
              ]" />
            <div v-if="errors.email" class="text-red-400 text-sm mt-1">{{ errors.email }}</div>
          </div>

          <!-- Phone -->
          <div>
            <label for="phone" class="block text-white font-semibold mb-2 text-right">شماره تماس</label>
            <input v-model="form.phone" id="phone" type="tel" placeholder="09xxxxxxxxx"
              :class="[
                'w-full p-4 bg-slate-800/70 hover:bg-slate-800/90 text-white rounded-2xl border-2 focus:outline-none transition-all duration-300 shadow-lg dir-ltr',
                errors.phone ? 'border-red-400/70 bg-red-900/20' : 'border-emerald-400/40 focus:border-emerald-400/70'
              ]" />
            <div v-if="errors.phone" class="text-red-400 text-sm mt-1">{{ errors.phone }}</div>
          </div>

          <!-- Address -->
          <div class="md:col-span-2">
            <label for="address" class="block text-white font-semibold mb-2 text-right">آدرس</label>
            <textarea v-model="form.address" id="address" rows="3" placeholder="آدرس کامل مشتری"
              :class="[
                'w-full p-4 bg-slate-800/70 hover:bg-slate-800/90 text-white rounded-2xl border-2 focus:outline-none transition-all duration-300 shadow-lg resize-vertical',
                errors.address ? 'border-red-400/70 bg-red-900/20' : 'border-purple-400/40 focus:border-purple-400/70'
              ]"></textarea>
            <div v-if="errors.address" class="text-red-400 text-sm mt-1">{{ errors.address }}</div>
          </div>
        </div>

        <!-- Buttons -->
        <div class="flex gap-4 justify-end pt-4 border-t border-amber-400/30">
          <button type="button" @click="resetForm" 
            class="bg-gradient-to-r from-gray-600/50 to-gray-700/50 hover:from-gray-700 hover:to-gray-800 text-white px-8 py-3 rounded-2xl font-bold shadow-xl hover:shadow-2xl hover:scale-105 transition-all duration-300 border border-gray-500/50">
            بازنشانی
          </button>
          <button type="submit" :disabled="updating"
            class="bg-gradient-to-r from-green-400 via-teal-400 to-emerald-500 hover:from-green-500 hover:via-teal-500 hover:to-emerald-600 text-white px-10 py-3 rounded-2xl font-bold shadow-xl hover:shadow-2xl hover:scale-105 transition-all duration-300 disabled:opacity-50 disabled:scale-100 flex items-center gap-2">
            <span v-if="updating" class="animate-spin">⏳</span>
            <span>{{ updating ? 'در حال بروزرسانی...' : '💾 ذخیره تغییرات' }}</span>
          </button>
        </div>
      </form>
    </div>
  </div>
</template>

<script>
import api from '../api/axios'; 

export default {
  data() {
    return {
      customer: null,
      form: {
        id: null,
        name: '',
        email: '',
        phone: '',
        address: ''
      },
      loading: true,
      updating: false,
      errors: {}
    };
  },
  async mounted() {
    await this.fetchCustomerData();
  },
  methods: {
    async fetchCustomerData() {
      try {
        const response = await api.get(`customers/${this.$route.params.id}`);  
        this.customer = response.data.data || response.data;
        
        // ✅ Sync form with customer data
        this.form = {
          id: this.customer.id,
          name: this.customer.name || '',
          email: this.customer.email || '',
          phone: this.customer.phone || '',
          address: this.customer.address || ''
        };
      } catch (error) {
        console.error('Failed to fetch customer data', error);
        this.$toast?.error('خطا در بارگذاری اطلاعات مشتری');
      } finally {
        this.loading = false;
      }
    },
    
    async updateCustomer() {
      this.errors = {};
      this.updating = true;
      
      try {
        // Validate
        if (!this.form.name.trim()) {
          this.errors.name = 'نام مشتری الزامی است';
          return;
        }
        
        const response = await api.put(`customers/${this.form.id}`, this.form);  
        
        // Success
        this.$toast?.success('اطلاعات مشتری بروزرسانی شد');
        this.$router.push(`/customers/${this.form.id}`);
        
      } catch (error) {
        // Handle validation errors
        if (error.response?.status === 422) {
          this.errors = error.response.data.errors || {};
        } else {
          console.error('Update failed', error);
          this.$toast?.error('خطا در بروزرسانی اطلاعات');
        }
      } finally {
        this.updating = false;
      }
    },
    
    resetForm() {
      this.form = {
        id: this.customer.id,
        name: this.customer.name || '',
        email: this.customer.email || '',
        phone: this.customer.phone || '',
        address: this.customer.address || ''
      };
      this.errors = {};
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
}
</style>
