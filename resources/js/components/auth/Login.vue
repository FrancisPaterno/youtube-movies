<template>
  <v-card class="my-5 mx-auto" max-width="500">
    <v-card-title>Login</v-card-title>
    <v-card-text>
      <v-form>
        <v-text-field
        label="Username"
        prepend-icon="person"
        v-model="form.username"
        @input="$v.form.username.$touch()" 
        @blur="$v.form.username.$touch()"
        :error-messages="usernameErrors"
        >
        </v-text-field>
        <span v-if="errors.username" class="error--text ml-8">{{errors.username[0]}}</span>
        <v-text-field
        label="Password"
        prepend-icon="lock"
        v-model="form.password"
        :append-icon="showPassword?'visibility_off':'visibility'"
        :type="showPassword?'text':'password'"
        @click:append="showPassword = !showPassword"
        @input="$v.form.password.$touch()" 
        @blur="$v.form.password.$touch()"
        :error-messages="passwordErrors"
        >
        </v-text-field>
        <span v-if="errors.password" class="error--text ml-8">{{errors.password[0]}}</span>
      </v-form>
      <v-card-actions>
        <v-btn class="success" width="100%" :loading="logging" @click="userLogin">Login</v-btn>
      </v-card-actions>
    </v-card-text>
  </v-card>
</template>

<script>
import {validationMixin} from 'vuelidate';
import {required, maxLength, minLength} from 'vuelidate/lib/validators';
import User from '../../apis/User';

export default {
  mixins:[validationMixin],
  validations:{
    form:{
      username:{required},
      password:{required}
    }
  },
  data(){
    return {
      form:{
        username:'',
        password:'',
        device_name:'browser'
      },
      showPassword:false,
      logging:false,
      errors:[]
    }
  },
  computed:{
    usernameErrors(){
      const errors = [];
      if(!this.$v.form.username.$dirty) return errors
        
      !this.$v.form.username.required && errors.push('Username is required.')

      return errors
    },
    passwordErrors(){
      const errors = [];
      if(!this.$v.form.password.$dirty) return errors
        
      !this.$v.form.password.required && errors.push('Password is required.')

      return errors
    }
  },
  methods:{
    userLogin(){
      this.$v.$touch();
      if(!this.$v.form.$invalid){
        this.logging = true
        User.login(this.form)
      .then((response)=>{
        localStorage.setItem('token', response.data);
        this.$root.$emit('login', true);
        this.logging = false;
        this.$router.push({name:'Home'});
      })
      .catch((errors)=>{
        if(errors.response.status === 422){
          this.errors = errors.response.data.errors;
        }
        this.logging = false;
      })
      }
    }
  }
}
</script>

<style>

</style>