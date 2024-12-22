<!-- components/MobileNavbar.vue -->
<template>
  <q-drawer v-model="drawer" side="left" bordered>
    <q-list padding>
      <!-- Regular navigation items -->
      <q-item clickable v-ripple to="/">
        <q-item-section avatar><q-icon name="home" /></q-item-section>
        <q-item-section>Ana Sayfa</q-item-section>
      </q-item>

      <q-item clickable v-ripple to="/vendors">
        <q-item-section avatar><q-icon name="store" /></q-item-section>
        <q-item-section>Mağzalar</q-item-section>
      </q-item>

      <!-- Auth items - show only when not logged in -->
      <template v-if="!user">
        <q-item clickable v-ripple to="/register">
          <q-item-section avatar><q-icon name="person_add" /></q-item-section>
          <q-item-section>Register</q-item-section>
        </q-item>

        <q-item clickable v-ripple to="/login">
          <q-item-section avatar><q-icon name="login" /></q-item-section>
          <q-item-section>Login</q-item-section>
        </q-item>
      </template>

      <!-- User profile - show only when logged in -->
      <template v-if="user">
        <q-item>
          <q-item-section avatar>
            <q-avatar>
              <img src="https://cdn.quasar.dev/img/boy-avatar.png" />
            </q-avatar>
          </q-item-section>
          <q-item-section>
            <q-item-label>{{ user.first_name }} {{ user.last_name }}</q-item-label>
            <q-item-label caption>{{ user.email }}</q-item-label>
          </q-item-section>
        </q-item>

        <q-item clickable v-ripple @click="handleLogout">
          <q-item-section avatar><q-icon name="logout" /></q-item-section>
          <q-item-section>Logout</q-item-section>
        </q-item>
      </template>
    </q-list>
  </q-drawer>
</template>

<script setup lang="ts">
import { computed } from 'vue'
import { useRouter } from 'vue-router'
import { useAuth } from '../composables/useAuth'

const props = defineProps<{
  modelValue: boolean
}>()

const emit = defineEmits(['update:modelValue'])
const router = useRouter()
const { user, logout } = useAuth()

const drawer = computed({
  get: () => props.modelValue,
  set: (value) => emit('update:modelValue', value)
})

const handleLogout = async () => {
  await logout()
  emit('update:modelValue', false)
  router.push('/login')
}
</script>

<style scoped>
.q-item.q-router-link--active {
  color: var(--q-primary);
  font-weight: 600;
}
</style>
