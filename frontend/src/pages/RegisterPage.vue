<template>
  <q-layout view="lHh Lpr lFf">
    <q-page-container>
      <q-page class="flex flex-center">
        <q-card class="registration-card q-pa-lg">
          <q-card-section>
            <div class="text-h4 text-center q-mb-md">Register</div>

            <q-form @submit="onSubmit" class="q-gutter-md q-mt-lg">
              <!-- Name Fields -->
              <q-input
                v-model="form.first_name"
                label="First Name *"
                filled
                :rules="[(val) => !!val || 'First name is required']"
              />
              <q-input
                v-model="form.last_name"
                label="Last Name *"
                filled
                :rules="[(val) => !!val || 'Last name is required']"
              />

              <!-- Username and Email -->
              <q-input
                v-model="form.username"
                label="Username *"
                filled
                :rules="[(val) => !!val || 'Username is required']"
              />
              <q-input
                v-model="form.email"
                label="Email *"
                filled
                type="email"
                :rules="[
                  (val) => !!val || 'Email is required',
                  (val) => /.+@.+\..+/.test(val) || 'Invalid email',
                ]"
              />

              <!-- Birth Date -->
              <q-input
                v-model="form.birth_of_date"
                label="Birth Date *"
                filled
                type="date"
                :rules="[(val) => !!val || 'Birth date is required']"
              />

              <!-- Password -->
              <q-input
                v-model="form.password"
                label="Password *"
                filled
                :type="isPwd ? 'password' : 'text'"
                :rules="[(val) => !!val || 'Password is required']"
              >
                <template v-slot:append>
                  <q-icon
                    :name="isPwd ? 'visibility_off' : 'visibility'"
                    class="cursor-pointer"
                    @click="isPwd = !isPwd"
                  />
                </template>
              </q-input>

              <!-- City and District Selection -->
              <q-select
                v-model="form.city"
                :options="cities"
                label="City *"
                filled
                :rules="[(val) => !!val || 'Please select a city']"
                @update:model-value="updateDistricts"
              />

              <q-select
                v-model="form.district"
                :options="districts"
                label="District *"
                filled
                :rules="[(val) => !!val || 'Please select a district']"
                :disable="!form.city"
              />

              <!-- Phone Number -->
              <q-input
                v-model="form.phone_number"
                label="Phone Number"
                filled
                mask="+90 (###) ### ## ##"
                unmasked-value
              />

              <!-- User Type -->
              <q-select
                v-model="form.user_type"
                :options="['customer', 'seller']"
                label="Account Type *"
                filled
                :rules="[(val) => !!val || 'Please select account type']"
              />
              <!-- Avatar Upload -->
              <q-file
                v-model="form.avatar"
                label="Profile Picture"
                filled
                bottom-slots
                accept="image/*"
              >
                <template v-slot:prepend>
                  <q-icon name="attach_file" />
                </template>
              </q-file>

              <!-- Submit Button -->
              <div class="full-width q-mt-lg">
                <q-btn label="Register" type="submit" color="primary" class="full-width" />
              </div>

              <!-- Login Link -->
              <div class="text-center q-mt-md">
                Already have an account?
                <router-link to="/login" class="text-primary">Log in</router-link>
              </div>
            </q-form>
          </q-card-section>
        </q-card>
      </q-page>
    </q-page-container>
  </q-layout>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { api } from 'src/boot/axios' // Axios örneğini içe aktarın
import { useQuasar } from 'quasar'
import { useRouter } from 'vue-router'

const $q = useQuasar()
const router = useRouter()

const isPwd = ref(true)

// Şehirler ve ilçeler için state'ler
const cities = ref([])
const districts = ref<string[]>([])

// Add these interfaces
interface CityOption {
  label: string
  value: number
}

interface FormData {
  avatar: File | null
  first_name: string
  last_name: string
  username: string
  email: string
  password: string
  birth_of_date: string
  phone_number: string
  user_type: string
  city: CityOption | string
  district: CityOption | string
}

// Update the form ref with proper typing
const form = ref<FormData>({
  avatar: null,
  first_name: '',
  last_name: '',
  username: '',
  email: '',
  password: '',
  birth_of_date: '',
  phone_number: '',
  user_type: 'customer',
  city: '',
  district: '',
})

// Şehirleri API'den çek
const fetchCities = async () => {
  try {
    const response = await api.get('/kaan.php?action=provinces')
    if (response.data && response.data.status === 'success') {
      cities.value = response.data.data.map((city: { id: number; il_adi: string }) => ({
        label: city.il_adi,
        value: city.id,
      }))
    }
  } catch (error) {
    console.error('Error fetching cities:', error)
  }
}

const updateDistricts = async (selectedCity: CityOption) => {
  try {
    const cityId = selectedCity.value
    const response = await api.get(`/kaan.php?action=districts&id=${cityId}`)
    if (response.data && response.data.status === 'success') {
      districts.value = response.data.data.map((district: { id: number; ilce_adi: string }) => ({
        label: district.ilce_adi,
        value: district.id,
      }))
    }
  } catch (error) {
    console.error('Error fetching districts:', error)
  }
  form.value.district = ''
}

// Şehirler yüklendiğinde API çağrısını yap
onMounted(() => {
  fetchCities()
})

// Add this interface for API error responses
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
    let avatarBase64 = null;
    
    // Handle avatar file
    if (form.value.avatar instanceof File) {
      const reader = new FileReader();
      avatarBase64 = await new Promise((resolve) => {
        reader.onload = (e) => {
          const result = e.target?.result as string;
          console.log('Image converted to base64');
          resolve(result);
        };
        reader.readAsDataURL(form.value.avatar as File);
      });
    }

    const formData = {
      ...form.value,
      avatar: avatarBase64, // Send base64 string
      city: typeof form.value.city === 'object' ? form.value.city.value : form.value.city,
      district: typeof form.value.district === 'object' ? form.value.district.value : form.value.district,
    };

    console.log('Sending registration data...');
    const response = await api.post('/kaan.php?action=register', formData);

    if (response.data.status === 'success') {
      // Show success message with avatar URL if available
      const successMessage = response.data.avatar_url 
        ? `Registration successful! Avatar saved at: ${response.data.avatar_url}`
        : 'Registration successful! You can now login.';

      $q.notify({
        type: 'positive',
        message: successMessage,
        position: 'top',
      });
      
      router.push('/login');
    }
  } catch (error: unknown) {
    console.error('Registration error:', error);

    const apiError = error as ApiError
    let errorMessage = 'Registration failed'

    if (apiError.response?.data?.message) {
      errorMessage = apiError.response.data.message
    }

    $q.notify({
      type: 'negative',
      message: errorMessage,
      position: 'top',
      timeout: 3000,
    })
  }
}
</script>

<style scoped>
.registration-card {
  width: 100%;
  max-width: 600px;
  margin: 20px;
}
</style>
