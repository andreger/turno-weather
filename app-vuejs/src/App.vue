<template>
  <div class="header">
      <img class="logo" alt="Turno" src="@/assets/logo.png">
      <LoggedUser />
  </div>
  <AlertMessage />
  <router-view/>
</template>

<script setup lang="ts">
import LoggedUser from '@/components/common/LoggedUser.vue';
import AlertMessage from './components/common/AlertMessage.vue';
</script>

<style>
:root {
  --blue: #3d4465;
  --green: #29c2af;
  --white: #fff;
  --dark-red: #cf0a1f;
  --light-red: #f8d7da;
}

#app {
  font-family: Avenir, Helvetica, Arial, sans-serif;
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
  max-width: 420px;
  margin: auto;
  text-align: center;
  color: var(--blue);
}

form {
    background: white;
    text-align: left;
    padding: 0 20px;
    border-radius: 10px;
}

label {
    color: var(--blue);
    display: inline-block;
    margin: 25px 0 15px;
    font-size: 0.8em;
    text-transform: uppercase;
    letter-spacing: 1px;
    font-weight: bold;
}

input {
    display: block;
    padding: 10px 6px;
    width: 100%;
    box-sizing: border-box;
    border: none;
    border-bottom: 1px solid var(--blue);
    color: var(--blue);
}

button {
    background: var(--blue);
    border: 0;
    border-radius: 5px;
    color: white;
    cursor: pointer;
    padding: 10px 20px;
}

button:disabled {
  background-color: #cccccc;
  color: #666666;
}

.action {
  cursor: pointer;
  font-size: 0.8rem;
  font-weight: bold;
}

.logo {
  margin: 20px 0;
}

.header {
  display: flex;
  justify-content: space-between;
  align-items: center;
}
</style>
