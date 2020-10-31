<template>
  <v-card>
      <v-app-bar app color="success">
          <v-row justify="center" align="center">
              <!-- <v-app-bar-nav-icon class="white--text"></v-app-bar-nav-icon> -->
        <v-btn text route to="/"><v-icon class="white--text" x-large>home</v-icon></v-btn>
        <v-divider vertical class="mx-2"></v-divider>
        <v-toolbar-title class="white--text display-1"><h3>Funny Movies</h3></v-toolbar-title>
        <v-spacer></v-spacer>
        <span v-if="user" class="white--text">Welcome {{user.email}}</span>
        <v-divider v-if="isLoggedIn" vertical class="mx-2"></v-divider>
        <v-dialog v-model="shareMovie" max-width="500" v-if="isLoggedIn">
            <template v-slot:activator="{on, attrs}">
                <v-btn text class="white--text" v-bind="attrs" v-on="on">
                    Share a movie
                </v-btn>
            </template>
            <v-card>
                <v-card-title class="headline">Share a youtube movie</v-card-title>
                <v-card-text>
                    <v-text-field 
                    label="Youtube URL" 
                    prepend-icon="http" 
                    v-model="youtubeURL" 
                    @input="$v.youtubeURL.$touch()" 
                    @blur="$v.youtubeURL.$touch()"
                    :error-messages="videoURLErrors"
                    ></v-text-field>
                    <v-btn color="success" width="100%" @click="shareYTMovie" :loading="saving"><v-icon left>share</v-icon>Share</v-btn>
                </v-card-text>
            </v-card>
        </v-dialog>
        <v-divider v-if="isLoggedIn" vertical class="mx-2"></v-divider>
        <v-btn text color="white" v-if="isLoggedIn" @click="userLogout">
            <span>Sign Out</span>
            <v-icon right>exit_to_app</v-icon>
        </v-btn>
        <v-menu left bottom v-if="!isLoggedIn">
            <template v-slot:activator="{on, attrs}">
                <v-btn icon v-bind="attrs" v-on="on">
                    <v-icon color="white">more_vert</v-icon>
                </v-btn>
            </template>
            <v-list>
                <v-list-item 
                v-for="(link, index) in navlinks"
                :key="index"
                route
                :to="link.route"
                >
                <v-list-item-icon>
                    <v-icon>{{link.icon}}</v-icon>
                </v-list-item-icon>
                <v-list-item-title>{{link.text}}</v-list-item-title>
                </v-list-item>
            </v-list>
        </v-menu>
          </v-row>
      </v-app-bar>
  </v-card>
</template>

<script>
import User from '../apis/User';
import {validationMixin} from 'vuelidate';
import {required} from 'vuelidate/lib/validators';
import youtube from '../apis/youtube';

export default {
    mixins:[validationMixin],
    validations:{
        youtubeURL:{required}
    },
    data(){
        return {
            navlinks:[
                {icon:'login', text:'Login', route:'/login'},
                {icon:'fingerprint', text:'Register', route:'/register'}
            ],
            isLoggedIn:false,
            shareMovie:false,
            youtubeURL:'',
            user:null,
            saving:false
        }
    },
    mounted(){
        this.isLoggedIn = !!localStorage.getItem('token');
        this.$root.$on('login',(val)=>{
            this.isLoggedIn = val;
            if(this.isLoggedIn){
                this.getUser();
            }
        });
        if(this.isLoggedIn){
            this.getUser();
        }
    },
    computed:{
        videoURLErrors(){
            const errors = [];
            if(!this.$v.youtubeURL.$dirty) return errors

            !this.$v.youtubeURL.required && errors.push('Youtube url is required.')
            !this.validateUrl() && errors.push('Youtube url is not valid.')

            return errors
        }
    },
    methods:{
        userLogout(){
            User.logout()
            .then(()=>{
                localStorage.removeItem('token');
                this.$root.$emit('login', false);
                this.isLoggedIn = false;
                this.user = null;
            })
        },
        shareYTMovie(){
            this.$v.$touch();
            if(!this.$v.youtubeURL.$invalid){
                this.saving = true;
                let videoId = this.validateUrl()[2];
                youtube.addVideo({youtubeURL:videoId, user:this.user.id})
                .then((response)=>{
                    console.log(response.data);
                    this.shareMovie = false;
                    this.saving = false;
                })
            }
        },
        closeShare(){
            this.youtubeURL = '';
            this.$v.$reset();
        },
        getUser(){
             User.auth()
            .then(response=>{
                this.user = response.data;
            })
        },
        validateUrl(){
            if(this.youtubeURL !== undefined || this.youtubeURL !== ''){
                let regExp = /^.*(youtu.be\/|v\/|u\/\w\/|embed\/|watch\?v=|\&v=|\?v=)([^#\&\?]*).*/;
                let match = this.youtubeURL.match(regExp);
                return match;
            }
        }
    },
    watch:{
        shareMovie(val){
            val || this.closeShare();
        }
    }
}
</script>

<style>

</style>