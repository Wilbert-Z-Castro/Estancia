<template>
  <div v-if="links.length > 3">
    <div class="flex flex-wrap -mb-1">
      <template v-for="(link, key) in links">
        <div v-if="link.url === null" :key="key" class="px-2 py-1 mr-1 mb-1 text-sm leading-4 text-gray-400 rounded border">
          {{ getTextForLink(link) }}
        </div>
        <Link
          v-else
          :key="key + 1"
          class="px-3 py-2 mr-2 mb-2 text-sm leading-4 rounded border hover:bg-blue-600 hover:text-white focus:border-blue-600 focus:text-blue-600"
          :class="{ 'text-white bg-blue-600': link.active }"
          :href="link.url"
        >
          {{ getTextForLink(link) }}
        </Link>
      </template>
    </div>
  </div>
</template>

<script setup>
import { Link } from "@inertiajs/vue3";

const props = defineProps({
  links: Array,
});

const getTextForLink = (link) => {
  if (link.label.includes('Next')) {
    return 'Siguiente';
  } else if (link.label.includes('Previous')) {
    return 'Anterior';
  } else {
    return link.label;
  }
};
</script>
