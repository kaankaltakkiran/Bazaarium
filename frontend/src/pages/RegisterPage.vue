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

              <!-- Birth Date -->
              <q-input v-model="form.birth_of_date" label="Date of Birth" filled type="date" />

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
            </q-form>
          </q-card-section>
        </q-card>
      </q-page>
    </q-page-container>
  </q-layout>
</template>

<script setup lang="ts">
import { ref } from 'vue'

const isPwd = ref(true)

const cities = ['Istanbul', 'Ankara', 'Izmir', 'Bursa', 'Antalya']
const districtMap = {
  Istanbul: ['Kadikoy', 'Besiktas', 'Sisli', 'Uskudar', 'Fatih'],
  Ankara: ['Cankaya', 'Kecioren', 'Yenimahalle', 'Mamak', 'Etimesgut'],
  Izmir: ['Konak', 'Karsiyaka', 'Bornova', 'Buca', 'Cigli'],
  Bursa: ['Nilufer', 'Osmangazi', 'Yildirim', 'Gemlik', 'Mudanya'],
  Antalya: ['Muratpasa', 'Kepez', 'Konyaalti', 'Manavgat', 'Alanya'],
}

const districts = ref<string[]>([])

const form = ref({
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

const updateDistricts = (city: string) => {
  districts.value = districtMap[city as keyof typeof districtMap] || []
  form.value.district = ''
}

const onSubmit = () => {
  // Handle form submission here
  console.log('Form submitted:', form.value)
}
</script>

<style scoped>
.registration-card {
  width: 100%;
  max-width: 600px;
  margin: 20px;
}
</style>
