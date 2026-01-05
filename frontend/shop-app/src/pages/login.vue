<template>
  <div class="min-h-screen relative overflow-hidden bg-gradient-to-br from-slate-900 via-purple-900/80 to-amber-900/90"
    @mousemove="updateEyePosition">
    <!-- Animated Background Particles -->
    <div class="absolute inset-0">
      <div class="absolute top-20 left-10 w-72 h-72 bg-amber-400/10 rounded-full blur-3xl animate-pulse"></div>
      <div class="absolute top-1/2 right-20 w-96 h-96 bg-purple-500/10 rounded-full blur-3xl animate-pulse delay-1000">
      </div>
      <div
        class="absolute bottom-20 left-1/2 w-80 h-80 bg-gradient-to-r from-amber-400/20 to-purple-500/20 rounded-full blur-3xl animate-bounce delay-2000">
      </div>
    </div>

    <!-- Luxury Eye Animation (Floating) -->
    <div class="eye-container absolute bottom-8 left-8 z-50">
      <div class="eye ring-4 ring-amber-400/30 bg-gradient-to-r from-slate-100 to-white/80 shadow-2xl">
        <div class="pupil" :style="pupilStyle"></div>
        <div
          class="pupil-glow absolute inset-0 bg-gradient-to-r from-amber-400/50 to-purple-500/50 rounded-full blur opacity-0 animate-ping">
        </div>
      </div>
    </div>

    <!-- Main Login Card -->
    <div class="relative z-10 flex justify-center items-center min-h-screen px-6">
      <div class="w-full max-w-md group">
        <!-- Card Background Glow -->
        <div
          class="absolute inset-0 bg-gradient-to-r from-amber-500/10 via-purple-500/10 to-slate-900/90 backdrop-blur-xl rounded-3xl -z-10 blur-xl animate-pulse">
        </div>

        <!-- Main Card -->
        <div
          class="relative bg-gradient-to-b from-slate-900/90 via-white/5 to-amber-900/90 backdrop-blur-2xl rounded-3xl p-10 border border-amber-400/30 shadow-2xl hover:shadow-3xl transition-all duration-500 group-hover:-translate-y-2 border-opacity-50">

          <!-- Logo/Title -->
          <div class="text-center mb-10 animate__animated animate__fadeInDown">
            <div
              class="w-24 h-24 mx-auto mb-6 bg-gradient-to-r from-amber-400 via-yellow-400 to-orange-500 rounded-3xl shadow-2xl flex items-center justify-center animate-spin-slow">
              <span class="text-3xl drop-shadow-lg">👁️</span>
            </div>
            <h2
              class="text-4xl md:text-5xl font-black bg-gradient-to-r from-amber-400 via-yellow-400 to-orange-500 bg-clip-text text-transparent drop-shadow-2xl mb-2">
              Game Empire
            </h2>
            <p class="text-xl text-purple-200/80 font-medium drop-shadow-lg">ورود به پنل مدیریت</p>
          </div>

          <!-- Login Form -->
          <form @submit.prevent="handleLogin" class="space-y-8">
            <!-- Email -->
            <div class="relative group/form-field">
              <label for="email" class="block text-lg font-black text-white/90 mb-3 drop-shadow-md tracking-wide">📧
                ایمیل</label>
              <input v-model="email" type="email" id="email" required
                class="w-full p-6 bg-white/20 border-2 border-amber-400/50 rounded-2xl focus:outline-none focus:border-amber-400 focus:ring-4 focus:ring-amber-400/50 transition-all duration-500 text-slate-900 placeholder-purple-300 peer text-lg font-semibold !backdrop-blur-none"
                placeholder="ایمیل خود را وارد کنید" />
            </div>

            <!-- Password -->
            <div class="relative group/form-field">
              <label for="password" class="block text-lg font-black text-white/90 mb-3 drop-shadow-md tracking-wide">🔒
                رمز عبور</label>
              <div class="relative">
                <input v-model="password" :type="passwordVisible ? 'text' : 'password'" id="password" required
                  class="w-full p-6 bg-white/20 border-2 border-purple-400/50 rounded-2xl focus:outline-none focus:border-purple-400 focus:ring-4 focus:ring-purple-400/50 transition-all duration-500 text-slate-900 placeholder-purple-300 peer text-lg font-semibold pr-14 !backdrop-blur-none"
                  placeholder="رمز عبور خود را وارد کنید" />
                <button type="button" @click="togglePasswordVisibility"
                  class="absolute right-4 top-1/2 -translate-y-1/2 p-2 text-amber-300 hover:text-amber-400 transition-colors duration-200 hover:scale-110">
                  <i :class="passwordVisible ? 'fa-solid fa-eye' : 'fa-solid fa-eye-slash'"></i>
                </button>
              </div>
            </div>


            <!-- Error Message -->
            <transition name="slide-fade">
              <p v-if="errorMessage"
                class="text-red-400 text-sm text-center bg-red-500/10 backdrop-blur-sm rounded-xl p-4 border border-red-400/30 animate__animated animate__shakeX">
                {{ errorMessage }}
              </p>
            </transition>

            <!-- Submit Button -->
            <button type="submit" :disabled="loading"
              class="w-full py-6 bg-gradient-to-r from-amber-500 via-yellow-400 to-orange-500 hover:from-amber-600 hover:via-yellow-500 hover:to-orange-600 text-slate-900 font-black text-xl rounded-2xl shadow-2xl hover:shadow-3xl hover:-translate-y-1 transform transition-all duration-300 disabled:opacity-50 disabled:cursor-not-allowed disabled:transform-none relative overflow-hidden group/btn">
              <div
                class="absolute inset-0 bg-white/20 blur opacity-0 group-hover/btn:opacity-100 transition-opacity duration-300">
              </div>
              <span class="relative z-10 flex items-center justify-center space-x-2">
                <svg v-if="loading" class="w-6 h-6 animate-spin" fill="none" viewBox="0 0 24 24">
                  <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                  <path class="opacity-75" fill="currentColor"
                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                  </path>
                </svg>
                {{ loading ? 'در حال ورود...' : 'ورود به داشبورد' }}
              </span>
            </button>
          </form>

          <!-- Decorative Elements -->
          <div
            class="absolute -top-6 -right-6 w-24 h-24 bg-gradient-to-r from-amber-400/30 to-purple-500/30 rounded-full blur-xl animate-pulse">
          </div>
          <div
            class="absolute -bottom-6 -left-6 w-20 h-20 bg-gradient-to-r from-purple-500/30 to-amber-400/30 rounded-full blur-xl animate-pulse delay-1000">
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import axios from '../api/axios';

export default {
  data() {
    return {
      email: '',
      password: '',
      errorMessage: '',
      loading: false,
      passwordVisible: false,
      mouseX: 0,
      mouseY: 0,
    };
  },
  methods: {
    async handleLogin() {
      this.errorMessage = '';
      this.loading = true;

      try {
        const response = await axios.post('/login', {
          email: this.email,
          password: this.password,
        });

        localStorage.setItem('token', response.data.token);
        this.$router.push('/dashboard');
      } catch (error) {
        this.errorMessage = error.response?.data?.message || 'اطلاعات وارد شده اشتباه است.';
      } finally {
        this.loading = false;
      }
    },
    togglePasswordVisibility() {
      this.passwordVisible = !this.passwordVisible;
    },
    updateEyePosition(event) {
      this.mouseX = event.clientX;
      this.mouseY = event.clientY;
    }
  },
  computed: {
    pupilStyle() {
      // 360 degree realistic eye movement
      const centerX = window.innerWidth / 2;
      const centerY = window.innerHeight / 2;
      const maxDistance = 12;

      const deltaX = (this.mouseX - centerX) / centerX;
      const deltaY = (this.mouseY - centerY) / centerY;

      const distance = Math.sqrt(deltaX * deltaX + deltaY * deltaY);
      const normalizedDistance = Math.min(distance, 1);

      const offsetX = deltaX * maxDistance * normalizedDistance;
      const offsetY = deltaY * maxDistance * normalizedDistance;

      return {
        transform: `translate(${offsetX}px, ${offsetY}px)`
      };
    }
  },
  mounted() {
    // Preload Font Awesome
    const link = document.createElement('link');
    link.rel = 'stylesheet';
    link.href = 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css';
    document.head.appendChild(link);
  }
};
</script>

<style scoped>
@keyframes spin-slow {
  from {
    transform: rotate(0deg);
  }

  to {
    transform: rotate(360deg);
  }
}

.animate-spin-slow {
  animation: spin-slow 20s linear infinite;
}

.slide-fade-enter-active {
  transition: all 0.3s ease;
}

.slide-fade-leave-active {
  transition: all 0.2s ease;
}

.slide-fade-enter-from,
.slide-fade-leave-to {
  transform: translateY(-10px);
  opacity: 0;
}

/* Eye Animation */
.eye-container {
  width: 80px;
  height: 80px;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
}

.eye {
  width: 60px;
  height: 60px;
  border-radius: 50%;
  position: relative;
  overflow: hidden;
  transition: all 0.3s ease;
}

.pupil {
  position: absolute;
  top: 50%;
  left: 50%;
  width: 22px;
  height: 22px;
  background: radial-gradient(circle, #000 40%, #333 70%, transparent 80%);
  border-radius: 50%;
  transform: translate(-50%, -50%);
  transition: transform 0.1s ease-out;
  box-shadow: inset 0 2px 4px rgba(255, 255, 255, 0.3);
}

.pupil-glow {
  box-shadow: 0 0 20px rgba(255, 215, 0, 0.6);
}

/* Form Field Enhancements */
input::placeholder {
  transition: all 0.3s ease;
}

input:focus::placeholder {
  opacity: 0;
  transform: translateX(20px);
}

.peer:focus~.form-glow {
  opacity: 1;
  transform: scale(1.05);
}
</style>
