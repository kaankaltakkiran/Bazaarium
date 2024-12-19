# Proje Notları

- Register sayfasına drop down menu de normal kullanıcı veya satıcı seçme olmalı
- Register sayfasında eğer `satıcı` seçilirse aşşığısına görünmez olan bir input çıkar ve shop name yazsın.

- navbar desktop ve mobil için compenentelere ayır.
- dark mode,büyültme navabar ekle

```js
     <!--Koyu Mod-->
          <q-btn
            flat
            dense
            @click="$q.dark.toggle()"
            :icon="$q.dark.isActive ? 'light_mode' : 'dark_mode'"
            :label="$q.dark.isActive ? 'Koyu' : 'Açık'"
            :no-caps="true"
            class="q-mx-md"
          />
```
