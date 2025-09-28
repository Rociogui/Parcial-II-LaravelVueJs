<template>
  <div>
    <h2>Login</h2>
    <input v-model="email" placeholder="email"/>
    <input v-model="password" placeholder="password" type="password"/>
    <button @click="login">Entrar</button>
    <p v-if="error">{{ error }}</p>
  </div>
</template>

<script>
export default {
  data(){ return { email:'', password:'', error:'' } },
  methods: {
    async login(){
      try{
        const res = await axios.post('/login', { email: this.email, password: this.password });
        const token = res.data.access_token;
        window.setAuthToken(token);
        // redirige o carga datos
        this.$router.push({ name: 'tareas' });
      } catch(e){
        this.error = e.response?.data?.message || 'Error';
      }
    }
  }
}
</script>
