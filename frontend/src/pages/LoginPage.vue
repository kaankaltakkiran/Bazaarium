<template>
  <q-page class="flex flex-center">
    <q-card class="login-card q-pa-lg">
      <q-card-section>
        <div class="text-h4 text-center q-mb-md">Login</div>

        <q-form @submit="onSubmit" class="q-gutter-md">
          <!-- Email -->
          <q-input
            v-model="form.email"
            label="Email *"
            filled
            type="email"
            :rules="[
              (val) => !!val || 'Email is required',
              (val) => /.+@.+\..+/.test(val) || 'Invalid email',
            ]"
          >
            <template v-slot:prepend>
              <q-icon name="email" />
            </template>
          </q-input>

          <!-- Password -->
          <q-input
            v-model="form.password"
            label="Password *"
            filled
            :type="isPwd ? 'password' : 'text'"
            :rules="[(val) => !!val || 'Password is required']"
          >
            <template v-slot:prepend>
              <q-icon name="lock" />
            </template>
            <template v-slot:append>
              <q-icon
                :name="isPwd ? 'visibility_off' : 'visibility'"
                class="cursor-pointer"
                @click="isPwd = !isPwd"
              />
            </template>
          </q-input>

          <!-- Submit Button -->
          <div class="full-width q-mt-md">
            <q-btn label="Login" type="submit" color="primary" class="full-width" />
          </div>

          <!-- Register Link -->
          <div class="text-center q-mt-sm">
            Don't have an account?
            <router-link to="/register" class="text-primary">Register</router-link>
          </div>
        </q-form>
      </q-card-section>
    </q-card>
  </q-page>
</template>

<script setup lang="ts">
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import { useQuasar } from 'quasar'
import { api } from '../boot/axios'
import { useAuth } from '../composables/useAuth'

const router = useRouter()
const $q = useQuasar()
const { login } = useAuth()
const isPwd = ref(true)

const form = ref({
  email: '',
  password: '',
})

interface ApiError {
  response?: {
    data?: {
      message?: string
      status?: string
    }
    status?: number
  }
}

const onSubmit = async () => {
  try {
    const response = await api.post('/kaan.php?action=login', form.value)
    
    if (response.data.status === 'success') {
      await login(response.data.user)
      
      $q.notify({
        type: 'positive',
        message: 'Login successful!',
        position: 'top',
      })
      
      router.push('/')
    }
  } catch (error: unknown) {
    console.error('Login error:', error)
    const apiError = error as ApiError
    
    $q.notify({
      type: 'negative',
      message: apiError.response?.data?.message || 'Login failed',
      position: 'top',
    })
  }
}
</script>

<style scoped>
.login-card {
  width: 100%;
  max-width: 400px;
  margin: 20px;
}
</style>
