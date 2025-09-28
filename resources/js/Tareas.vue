<template>
  <div class="tareas-container">
    <div class="acciones">
      <button @click="showModal = true" class="agregar-btn">AGREGAR TAREA</button>
      <!-- Modal para agregar tarea -->
      <div v-if="showModal" class="modal-overlay">
        <div class="modal modal-dark">
          <h2 class="modal-title">Agregar Tarea</h2>
          <form @submit.prevent="agregarTarea" class="modal-form">
            <div class="input-group">
              <span class="input-icon">📝</span>
              <input v-model="nuevaTarea.titulo" type="text" placeholder="Título" required />
            </div>
            <div class="input-group">
              <span class="input-icon">📄</span>
              <textarea v-model="nuevaTarea.descripcion" placeholder="Descripción"></textarea>
            </div>
            <div class="input-group">
              <span class="input-icon">🔄</span>
              <select v-model="nuevaTarea.estado" required>
                <option value="pendiente">Pendiente</option>
                <option value="en_progreso">En progreso</option>
                <option value="completada">Completada</option>
              </select>
            </div>
            <div class="input-group">
              <span class="input-icon">📅</span>
              <input v-model="nuevaTarea.fecha_vencimiento" type="date" placeholder="Fecha de vencimiento" />
            </div>
            <div class="input-group">
              <span class="input-icon">👤</span>
              <select v-model="nuevaTarea.usuario_id" required>
                <option value="" disabled>Seleccione un usuario</option>
                <option v-for="usuario in usuarios" :key="usuario.id" :value="usuario.id">
                  {{ usuario.nombre }}
                </option>
              </select>
            </div>
            <div class="modal-actions">
              <button type="submit" class="btn btn-green">Guardar</button>
              <button type="button" class="btn btn-light" @click="showModal = false">Cancelar</button>
            </div>
          </form>
        </div>
      </div>
    </div>
    <div class="tabla-tareas">
      <h2>Tareas</h2>
      <table>
        <thead>
          <tr>
            <th>Título</th>
            <th>Descripción</th>
            <th>Estado</th>
            <th>Fecha de vencimiento</th>
            <th>Usuario</th>
            <th>Fecha de creación</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="tarea in tareas" :key="tarea.id">
            <td>{{ tarea.titulo }}</td>
            <td>{{ tarea.descripcion }}</td>
            <td>{{ tarea.estado }}</td>
            <td>{{ tarea.fecha_vencimiento }}</td>
            <td>{{ tarea.usuario ? tarea.usuario.nombre : '' }}</td>
            <td>{{ tarea.created_at ? new Date(tarea.created_at).toLocaleDateString() : '' }}</td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';

const tareas = ref([]);
const usuarios = ref([]);
const showModal = ref(false);
const nuevaTarea = ref({
  titulo: '',
  descripcion: '',
  estado: 'pendiente',
  fecha_vencimiento: '',
  usuario_id: ''
});

const obtenerTareas = async () => {
  try {
    const res = await axios.get('/api/tareas');
    tareas.value = res.data;
  } catch (error) {
    console.error('Error al obtener tareas:', error);
  }
};

const obtenerUsuarios = async () => {
  try {
    const res = await axios.get('/api/usuarios/listUsers');
    usuarios.value = res.data;
  } catch (error) {
    console.error('Error al obtener usuarios:', error);
  }
};

const agregarTarea = async () => {
  try {
    await axios.post('/api/tareas', nuevaTarea.value);
    showModal.value = false;
    nuevaTarea.value = { titulo: '', descripcion: '', estado: 'pendiente', fecha_vencimiento: '', usuario_id: '' };
    obtenerTareas();
  } catch (error) {
    alert('Error al agregar tarea');
  }
};

onMounted(() => {
  obtenerTareas();
  obtenerUsuarios();
});
</script>

<style scoped>
.tareas-container {
  display: flex;
  gap: 2rem;
}
.acciones {
  min-width: 250px;
}
  .agregar-btn {
    background: #388e3c;
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
  .tabla-tareas {
    flex: 1;
  }
  .tabla-tareas h2 {
    font-family: 'Inter', 'Segoe UI', 'Arial', sans-serif !important;
    font-size: 2rem;
    font-weight: bold;
  }
  th, td {
    font-family: 'Inter', 'Segoe UI', 'Arial', sans-serif !important;
  }
table {
  width: 100%;
  border-collapse: collapse;
}
th, td {
  border: 1px solid #ddd;
  padding: 0.5rem;
}
th {
  background: #f5f5f5;
}
.modal-overlay {
  position: fixed;
  top: 0; left: 0; right: 0; bottom: 0;
  background: rgba(0,0,0,0.3);
  display: flex;
  align-items: center;
  justify-content: center;
}
.modal {
  background: #fff;
  padding: 2rem;
  border-radius: 8px;
  min-width: 300px;
}
.modal-actions {
  display: flex;
  gap: 1rem;
  margin-top: 1rem;
}
  .modal-dark {
    background: #23272f;
    color: #fff;
    border-radius: 18px;
    box-shadow: 0 4px 24px rgba(0,0,0,0.2);
    min-width: 350px;
    max-width: 400px;
    margin: auto;
  }
  .modal-title {
    text-align: center;
    font-size: 2rem;
    font-weight: bold;
    margin-bottom: 1.5rem;
    color: #fff;
    border-bottom: 2px solid #2ecc71;
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
  }
  .input-icon {
    margin-right: 0.7rem;
    font-size: 1.2rem;
    color: #2ecc71;
  }
  input, textarea, select {
    background: transparent;
    border: none;
    color: #fff;
    font-size: 1rem;
    flex: 1;
    outline: none;
    padding: 0.5rem 0;
  }
  textarea {
    resize: vertical;
    min-height: 40px;
  }
  select {
    appearance: none;
    -webkit-appearance: none;
    -moz-appearance: none;
    background: #fff;
    color: #23272f;
    border-radius: 6px;
    padding: 0.5rem;
    font-weight: bold;
  }
  .btn {
    padding: 0.6rem 1.5rem;
    border-radius: 8px;
    font-size: 1rem;
    border: none;
    cursor: pointer;
    margin-right: 0.5rem;
    margin-bottom: 0.5rem;
    transition: background 0.2s;
  }
  .btn-green {
    background: #2ecc71;
    color: #fff;
    font-weight: bold;
  }
  .btn-green:hover {
    background: #27ae60;
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
