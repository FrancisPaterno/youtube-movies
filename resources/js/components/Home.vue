<template>
  <v-sheet outlined max-height="100%" class="mx-1" >
    <!-- <h1 v-if="!isLoggedIn">Guest</h1> -->
    <user />
  </v-sheet>
</template>

<script>
import user from './User';

export default {
  data(){
    return {
      isLoggedIn:false
    }
  },
  components:{
    user
  },
  mounted(){
     this.isLoggedIn = !!localStorage.getItem('token');
        this.$root.$on('login',(val)=>{
            this.isLoggedIn = val;
        });
  }
}
</script>

<style>

</style>