<template>
  <div class="usuarios-container">
    <div class="acciones">
      <button @click="showModal = true" class="agregar-btn">AGREGAR USUARIO</button>
      <!-- Modal para agregar usuario -->
      <div v-if="showModal" class="modal-overlay">
        <div class="modal modal-dark">
          <h2 class="modal-title">Agregar Usuario</h2>
          <form @submit.prevent="agregarUsuario" class="modal-form">
            <div class="input-group">
              <span class="input-icon">👤</span>
              <input v-model="nuevoUsuario.nombre" type="text" placeholder="Nombre" required />
            </div>
            <div class="input-group">
              <span class="input-icon">✉️</span>
              <input v-model="nuevoUsuario.email" type="email" placeholder="Email" required />
            </div>
            <div class="input-group">
              <span class="input-icon">🔒</span>
              <input v-model="nuevoUsuario.password" type="password" placeholder="Contraseña" required />
            </div>
            <div class="input-group">
              <span class="input-icon">⚡</span>
              <select v-model="nuevoUsuario.rol" required>
                <option value="usuario">Usuario</option>
                <option value="admin">Admin</option>
              </select>
            </div>
            <div class="modal-actions">
              <button type="submit" class="btn btn-blue">Guardar</button>
              <button type="button" class="btn btn-light" @click="showModal = false">Cancelar</button>
            </div>
          </form>
        </div>
      </div>
    </div>
    <div class="tabla-usuarios">
      <h2>Usuarios</h2>
      <table>
        <thead>
          <tr>
            <th>Nombre</th>
            <th>Email</th>
            <th>Rol</th>
            <th>Fecha de creación</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="usuario in usuarios" :key="usuario.id">
            <td>{{ usuario.nombre }}</td>
            <td>{{ usuario.email }}</td>
            <td>{{ usuario.rol }}</td>
            <td>{{ usuario.created_at ? new Date(usuario.created_at).toLocaleDateString() : '' }}</td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';

const usuarios = ref([]);
const showModal = ref(false);
const nuevoUsuario = ref({ nombre: '', email: '', password: '', rol: 'usuario' });


const obtenerUsuarios = async () => {
  try {
    const res = await axios.get('/api/usuarios/listUsers');
    usuarios.value = res.data;
  } catch (error) {
    console.error('Error al obtener usuarios:', error);
  }
};

const agregarUsuario = async () => {
  try {
    await axios.post('/api/usuarios/addUser', nuevoUsuario.value);
    showModal.value = false;
  nuevoUsuario.value = { nombre: '', email: '', password: '', rol: 'usuario' };
    obtenerUsuarios();
  } catch (error) {
    alert('Error al agregar usuario');
  }
};

onMounted(() => {
  obtenerUsuarios();
});
</script>

<style scoped>
.usuarios-container {
  display: flex;
  gap: 2rem;
}
.acciones {
  min-width: 250px;
}
.agregar-btn {
  background: #1976d2;
  color: #fff;
  border: none;
  padding: 0.5rem 1rem;
  margin-bottom: 1rem;
  cursor: pointer;
  border-radius: 4px;
  font-family: 'Inter', 'Segoe UI', 'Arial', sans-serif !important;
  font-weight: bold;
  font-size: 1rem;
}
.tabla-usuarios {
  flex: 1;
}
table {
  width: 100%;
  border-collapse: collapse;
}
th, td {
  border: 1px solid #ddd;
  padding: 0.5rem;
  font-family: 'Inter', 'Segoe UI', 'Arial', sans-serif !important;
}
.tabla-usuarios h2 {
  font-family: 'Inter', 'Segoe UI', 'Arial', sans-serif !important;
  font-size: 2rem;
  font-weight: bold;
}
th {
  font-family: 'Inter', 'Segoe UI', 'Arial', sans-serif !important;
}
.modal-overlay {
  position: fixed;
  top: 0; left: 0; right: 0; bottom: 0;
  background: rgba(0,0,0,0.3);
  display: flex;
  align-items: center;
  justify-content: center;
}
.modal-dark {
  background: #23272f;
  color: #fff;
  border-radius: 18px;
  box-shadow: 0 4px 24px rgba(0,0,0,0.2);
  min-width: 350px;
  max-width: 400px;
  margin: auto;
  padding: 2.5rem 2rem 2rem 2rem;
}
.modal-title {
  text-align: center;
  font-size: 2rem;
  font-weight: bold;
  margin-bottom: 1.5rem;
  color: #fff;
  border-bottom: 2px solid #1976d2;
  padding-bottom: 0.5rem;
  font-family: 'Inter', 'Segoe UI', 'Arial', sans-serif !important;
}
.modal-form {
  display: flex;
  flex-direction: column;
  gap: 1rem;
}
.input-group {
  display: flex;
  align-items: center;
  background: #2c303a;
  border-radius: 8px;
  padding: 0.5rem 1rem;
  margin-bottom: 0.5rem;
  font-family: 'Inter', 'Segoe UI', 'Arial', sans-serif;
}
.input-icon {
  margin-right: 0.7rem;
  font-size: 1.2rem;
  color: #1976d2;
}
input, select {
  background: transparent;
  border: none;
  color: #fff;
  font-size: 1rem;
  flex: 1;
  outline: none;
  padding: 0.5rem 0;
}
select {
  appearance: none;
  -webkit-appearance: none;
  -moz-appearance: none;
  font-family: 'Inter', 'Segoe UI', 'Arial', sans-serif;
  background: #fff;
  color: #23272f;
  border-radius: 6px;
  padding: 0.5rem;
  font-family: 'Inter', 'Segoe UI', 'Arial', sans-serif;
  font-weight: bold;
}
.btn {
  font-family: 'Inter', 'Segoe UI', 'Arial', sans-serif;
  padding: 0.6rem 1.5rem;
  border-radius: 8px;
  font-size: 1rem;
  border: none;
  cursor: pointer;
  margin-right: 0.5rem;
  margin-bottom: 0.5rem;
  transition: background 0.2s;
}
.btn-blue {
  background: #1976d2;
  color: #fff;
  font-weight: bold;
}
.btn-blue:hover {
  background: #1565c0;
}
.btn-light {
  background: #fff;
  color: #23272f;
  font-weight: bold;
  border: 1px solid #ccc;
}
.btn-light:hover {
  background: #eee;
}
</style>
  font-family: 'Inter', 'Segoe UI', 'Arial', sans-serif;
