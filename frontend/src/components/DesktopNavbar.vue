<!-- components/DesktopNavbar.vue -->
<template>
  <div class="row items-center">
    <!-- Tab Menu -->
    <q-tabs v-model="tab" shrink>
      <q-tab name="tab1" label="Ana Sayfa" />
      <q-tab name="tab2" label="Mağzalar" />
    </q-tabs>

    <!--Language Choice -->
    <q-btn-dropdown push glossy no-caps icon="language" color="primary" label="Türkçe">
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

    <!-- Dark Mode Toggle -->
    <q-btn
      flat
      dense
      @click="$q.dark.toggle()"
      :icon="$q.dark.isActive ? 'light_mode' : 'dark_mode'"
      :label="$q.dark.isActive ? 'Koyu' : 'Açık'"
      :no-caps="true"
      class="q-mx-md"
    />

    <!-- Shopping Cart -->
    <q-btn dense round icon="shopping_bag" class="q-ml-md">
      <q-badge color="red" floating>4</q-badge>
    </q-btn>

    <!-- Profile Dropdown -->
    <q-btn-dropdown class="glossy q-ml-md" color="secondary" label="Hesap">
      <div class="row no-wrap q-pa-md">
        <div class="column items-center">
          <div class="text-h6 q-mb-md">Profile</div>
          <q-avatar size="72px">
            <img src="https://cdn.quasar.dev/img/boy-avatar.png" />
          </q-avatar>
          <div class="text-subtitle1 q-mt-md q-mb-xs">John Doe</div>
          <q-btn color="primary" label="Logout" push size="sm" v-close-popup />
        </div>
      </div>
    </q-btn-dropdown>
  </div>
</template>

<script setup lang="ts">
import { ref } from 'vue'

const tab = ref<string>('tab1')
</script>
