<template>
  <v-app>
      <navbar/>
      <v-main class="mx-2 my-2 grey lighten-4">
          <router-view></router-view>
      </v-main>
  </v-app>
</template>

<script>
import navbar from '../js/components/NavBar';
export default {
components:{navbar}
}
</script>

<style>

</style>