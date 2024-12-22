<!-- layouts/MainLayout.vue -->
<template>
  <q-layout view="lHh Lpr lFf">
    <q-header elevated>
      <q-toolbar class="bg-primary text-white shadow-2 rounded-borders">
        <!-- Hamburger Menu -->
        <q-btn v-if="$q.screen.lt.md" dense flat round icon="menu" @click="toggleLeftDrawer" />

        <!-- Logo -->
        <q-toolbar-title class="text-center">
          <span
            :class="{
              'text-h6': $q.screen.gt.xs,
              'text-subtitle1': $q.screen.xs,
            }"
          >
            Bazaarium
          </span>
        </q-toolbar-title>

        <!-- Desktop Navigation Component -->
        <DesktopNavbar v-if="$q.screen.gt.sm" />

        <!-- Shopping Cart for Mobile -->
        <q-btn v-if="$q.screen.lt.md" dense round icon="shopping_bag">
          <q-badge color="red" floating>4</q-badge>
        </q-btn>
      </q-toolbar>
    </q-header>

    <!-- Mobile Navigation Component -->
    <MobileNavbar v-model="leftDrawerOpen" />

    <q-page-container>
      <router-view />
    </q-page-container>
  </q-layout>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { useQuasar } from 'quasar'
import { useAuth } from '../composables/useAuth'
//Navbar Components
import DesktopNavbar from 'components/DesktopNavbar.vue'
import MobileNavbar from 'components/MobileNavbar.vue'

const $q = useQuasar()
const { initAuth } = useAuth()
const leftDrawerOpen = ref(false)

onMounted(() => {
  initAuth()
})

function toggleLeftDrawer() {
  leftDrawerOpen.value = !leftDrawerOpen.value
}
</script>
