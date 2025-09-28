<template>
  <div class="app-main">
    <Usuarios />
    <Tareas />
  </div>
</template>

<script setup>
import Usuarios from './Usuarios.vue';
import Tareas from './Tareas.vue';
</script>

<style scoped>
  .app-main {
    display: flex;
    gap: 3rem;
  }
</style>
