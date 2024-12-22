<!-- components/MobileNavbar.vue -->
<template>
  <!-- Mobile Drawer Start -->
  <q-drawer
    :model-value="modelValue"
    @update:model-value="$emit('update:modelValue', $event)"
    side="left"
    overlay
    behavior="mobile"
    bordered
    :breakpoint="1023"
  >
    <q-scroll-area class="fit">
      <q-item-label class="text-primary text-center" header>Bazaarium</q-item-label>
      <q-list padding>
        <q-item
          clickable
          :to="{ path: '/' }"
          v-close-popup
          :active="route.path === '/'"
          active-class="text-primary"
        >
          <q-item-section avatar> <q-icon name="home" /> </q-item-section>
          <q-item-section>Ana Sayfa</q-item-section>
        </q-item>

        <q-item
          clickable
          :to="{ path: '/vendors' }"
          v-close-popup
          :active="route.path === '/vendors'"
          active-class="text-primary"
        >
          <q-item-section avatar>
            <q-icon name="store" />
          </q-item-section>
          <q-item-section>Mağzalar</q-item-section>
        </q-item>

        <q-item
          clickable
          :to="{ path: '/register' }"
          v-close-popup
          :active="route.path === '/register'"
          active-class="text-primary"
        >
          <q-item-section avatar>
            <q-icon name="person_add" />
          </q-item-section>
          <q-item-section>Register</q-item-section>
        </q-item>

        <q-item
          clickable
          :to="{ path: '/login' }"
          v-close-popup
          :active="route.path === '/login'"
          active-class="text-primary"
        >
          <q-item-section avatar>
            <q-icon name="login" />
          </q-item-section>
          <q-item-section>Login</q-item-section>
        </q-item>

        <q-item>
          <!--Language Choice -->
          <q-btn-dropdown
            push
            glossy
            no-caps
            icon="language"
            color="primary"
            label="Türkçe"
            class="full-width"
          >
            <q-list>
              <q-item clickable v-close-popup>
                <q-item-section>
                  <q-item-label>English</q-item-label>
                </q-item-section>
              </q-item>

              <q-item clickable v-close-popup>
                <q-item-section>
                  <q-item-label>German</q-item-label>
                </q-item-section>
              </q-item>

              <q-item clickable v-close-popup>
                <q-item-section>
                  <q-item-label>İtaliano</q-item-label>
                </q-item-section>
              </q-item>
            </q-list>
          </q-btn-dropdown>
        </q-item>
        <!-- Dark Mode Toggle -->
        <q-item>
          <q-btn
            flat
            dense
            @click="$q.dark.toggle()"
            :icon="$q.dark.isActive ? 'light_mode' : 'dark_mode'"
            :label="$q.dark.isActive ? 'Koyu' : 'Açık'"
            :no-caps="true"
            class="full-width"
          />
        </q-item>
        <!-- Mobile Profile Section -->
        <q-item>
          <q-btn-dropdown class="full-width" color="secondary" label="Hesap">
            <div class="row no-wrap q-pa-md">
              <div class="column items-center full-width">
                <div class="text-h6 q-mb-md">Profile</div>
                <q-avatar size="72px">
                  <img src="https://cdn.quasar.dev/img/boy-avatar.png" />
                </q-avatar>
                <div class="text-subtitle1 q-mt-md q-mb-xs">John Doe</div>
                <q-btn
                  color="primary"
                  label="Logout"
                  push
                  size="sm"
                  v-close-popup
                  @click="handleLogout"
                  class="q-mt-sm"
                />
              </div>
            </div>
          </q-btn-dropdown>
        </q-item>
      </q-list>
    </q-scroll-area>
  </q-drawer>
  <!-- Mobile Drawer End -->
</template>

<script setup lang="ts">
import { useRouter, useRoute } from 'vue-router'

const router = useRouter()
const route = useRoute()

defineProps<{
  modelValue: boolean
}>()

defineEmits<{
  (e: 'update:modelValue', value: boolean): void
}>()

const handleLogout = () => {
  // Add your logout logic here
  console.log('Logging out...')
  // After logout, redirect to home or login page
  router.push('/')
}
</script>

<style scoped>
.q-item.q-router-link--active {
  color: var(--q-primary);
  font-weight: 600;
}
</style>
