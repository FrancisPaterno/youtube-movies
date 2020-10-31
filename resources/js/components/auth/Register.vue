<template>
  <v-card class="my-5 mx-auto" max-width="500">
    <v-card-title>Register User</v-card-title>
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
        label="Email"
        prepend-icon="email"
        v-model="form.email"
        @input="$v.form.email.$touch()" 
        @blur="$v.form.email.$touch()"
        :error-messages="emailErrors"
        >
        </v-text-field>
        <span v-if="errors.email" class="error--text ml-8">{{errors.email[0]}}</span>
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
        <v-text-field
        label="Confirm Password"
        prepend-icon="lock"
        v-model="form.password_confirmation"
        :append-icon="showConfirmPassword?'visibility_off':'visibility'"
        :type="showConfirmPassword?'text':'password'"
        @click:append="showConfirmPassword = !showConfirmPassword"
        @input="$v.form.password_confirmation.$touch()" 
        @blur="$v.form.password_confirmation.$touch()"
        :error-messages="confirmpasswordErrors"
        >
        </v-text-field>
      </v-form>
    </v-card-text>
    <v-card-actions>
      <v-btn class="success mx-auto" width="100%" :loading="saving" @click="registerUser">Register</v-btn>
    </v-card-actions>
    <v-dialog v-model="dialog" persistent max-width="300">
      <v-card>
        <v-card-title class="headline">Registration</v-card-title>
        <v-card-text>Your are now registered! Pleade click ok to login.</v-card-text>
        <v-card-actions>
          <v-btn color="success" class="mx-auto" text @click="login">OK</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
  </v-card>
</template>

<script>
import User from '../../apis/User';
import {validationMixin} from 'vuelidate';
import {maxLength, minLength, required, email} from 'vuelidate/lib/validators';

export default {
  mixins:[validationMixin],
  validations:{
    form:{
      username:{required, maxLength:maxLength(255)},
      email:{email, required, maxLength:maxLength(255)},
      password:{required, minLength:minLength(8), maxLength:maxLength(255)},
      password_confirmation:{required, minLength:minLength(8), maxLength:maxLength(255)}
    }
  },
  data(){
    return {
      form:{
        username:'',
        email:'',
        password:'',
        password_confirmation:''
      },
      errors:[],
      showPassword:false,
      showConfirmPassword:false,
      saving:false,
      dialog:false
    }
  },
  methods:{
    registerUser(){
      this.$v.$touch();

      if(!this.$v.form.$invalid){
        this.saving = true;
        User.register(this.form)
        .then(()=>{
          this.saving = false;
          this.dialog = true;
        })
        .catch((errors)=>{
          if(errors.response.status === 422){
            this.errors = errors.response.data.errors;
          }
          this.saving = false;
        })
      }
    },
    login(){
      this.dialog = false;
       this.$router.push({name:'Login'});
    }
  },
  computed:{
    usernameErrors(){
      const errors = [];
      if(!this.$v.form.username.$dirty) return errors
        
      !this.$v.form.username.maxLength && errors.push('Username must be at most 255 characters long.')
      !this.$v.form.username.required && errors.push('Username is required.')

      return errors
    },
    emailErrors(){
      const errors = [];
      if(!this.$v.form.email.$dirty) return errors
        
      !this.$v.form.email.maxLength && errors.push('Email must be at most 255 characters long.')
      !this.$v.form.email.required && errors.push('Email is required.')
      !this.$v.form.email.email && errors.push('Email is not valid')

      return errors
    },
    passwordErrors(){
      const errors = [];
      if(!this.$v.form.password.$dirty) return errors
        
      !this.$v.form.password.maxLength && errors.push('password must be at most 255 characters long.')
      !this.$v.form.password.required && errors.push('Password is required.')
      !this.$v.form.password.minLength && errors.push('password must be at least 8 characters long.')

      return errors
    },
    confirmpasswordErrors(){
      const errors = [];
      if(!this.$v.form.password_confirmation.$dirty) return errors
        
      !this.$v.form.password_confirmation.maxLength && errors.push('password must be at most 255 characters long.')
      !this.$v.form.password_confirmation.required && errors.push('Password is required.')
      !this.$v.form.password_confirmation.minLength && errors.push('password must be at least 8 characters long.')

      this.form.password !== this.form.password_confirmation? errors.push('password did not match.'):''

      return errors
    }
  }
}
</script>

<style>

</style>