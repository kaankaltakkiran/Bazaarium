<template>
  <q-layout view="lHh Lpr lFf">
    <q-header elevated>
      <q-toolbar class="bg-primary text-white shadow-2 rounded-borders">
        <!-- Hamburger Menu Start -->
        <q-btn v-if="$q.screen.lt.md" dense flat round icon="menu" @click="toggleLeftDrawer" />

        <!-- Logo -->
        <q-toolbar-title>
          <div
            :class="{
              'absolute-center text-center': $q.screen.gt.sm,
              'row items-center': $q.screen.lt.md,
            }"
          >
            <span
              :class="{
                'text-h6': $q.screen.gt.xs,
                'text-subtitle1': $q.screen.xs,
              }"
            >
              Bazaarium
            </span>
          </div>
        </q-toolbar-title>

        <q-space />

        <!-- Tab Menu - Show only on desktop -->
        <q-tabs v-if="$q.screen.gt.sm" v-model="tab" shrink>
          <q-tab name="tab1" label="Ana Sayfa" />
          <q-tab name="tab2" label="Mağzalar" />
        </q-tabs>
        <!--Language Choice Start -->
        <q-btn-dropdown
          v-if="$q.screen.gt.sm"
          push
          glossy
          no-caps
          icon="language"
          color="primary"
          label="Türkçe"
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
        <!--Language Choice End -->

        <!-- Shopping Cart -->
        <q-btn dense round icon="shopping_bag" :class="{ 'q-ml-md': $q.screen.gt.sm }">
          <q-badge color="red" floating>4</q-badge>
        </q-btn>

        <!-- Profile Dropdown - Desktop Version -->
        <q-btn-dropdown
          v-if="$q.screen.gt.sm"
          class="glossy q-ml-md"
          color="secondary"
          label="Hesap"
        >
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
      </q-toolbar>
    </q-header>
    <!-- Mobile Drawer -->
    <q-drawer
      v-model="leftDrawerOpen"
      side="left"
      overlay
      behavior="mobile"
      bordered
      :breakpoint="1023"
    >
      <q-scroll-area class="fit">
        <q-item-label class="text-primary text-center" header> Bazaarium </q-item-label>
        <q-list padding>
          <q-item clickable exact to="/">
            <q-item-section avatar>
              <q-icon name="home" />
            </q-item-section>
            <q-item-section>Ana Sayfa</q-item-section>
          </q-item>

          <q-item clickable to="/vendors">
            <q-item-section avatar>
              <q-icon name="store" />
            </q-item-section>
            <q-item-section>Mağzalar</q-item-section>
          </q-item>

          <q-item clickable>
            <!--Language Choice Start -->
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
            <!--Language Choice End -->
          </q-item>

          <!-- Mobile Profile Section -->
          <q-item-label header class="text-weight-bold">Profile</q-item-label>
          <q-item clickable>
            <q-item-section avatar>
              <q-avatar>
                <img src="https://cdn.quasar.dev/img/boy-avatar.png" />
              </q-avatar>
            </q-item-section>
            <q-item-section>John Doe</q-item-section>
          </q-item>

          <q-item clickable>
            <q-item-section avatar>
              <q-icon name="logout" />
            </q-item-section>
            <q-item-section>Logout</q-item-section>
          </q-item>
        </q-list>
      </q-scroll-area>
    </q-drawer>

    <q-page-container>
      <router-view />
    </q-page-container>
  </q-layout>
</template>

<script setup lang="ts">
import { ref } from 'vue'
import { useQuasar } from 'quasar'

const $q = useQuasar()
const tab = ref<string>('tab1')
const leftDrawerOpen = ref(false)

function toggleLeftDrawer() {
  leftDrawerOpen.value = !leftDrawerOpen.value
}
</script>
