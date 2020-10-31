<template>
    <v-card class="mx-auto" max-width="100%" max-height="90%">
        <v-container class="mx-auto">
            <v-row v-for="(movie, index) in getMovies" :key="index" class="mx-auto" >
                <v-col cols="5" offset="1" class="px-1">
                    <v-card flat>
                        <v-card-text>
                            <iframe width="560" height="315" v-bind:src="video_prefix + movie.video_id" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                        </v-card-text>
                    </v-card>
                </v-col>
                <v-col cols="5" offset="1" class="px-1">
                    <v-card max-height="100%" flat>
                        <v-card-title>
                                <span class="red--text">{{movie.title}}</span>
                        </v-card-title>
                        <v-card-subtitle class="py-0">
                            <!-- <div class="d-flex flex-column"> -->
                                {{`Shared by : ${movie.user.email}`}}
                            <!-- </div> -->
                        </v-card-subtitle>
                        <v-card-actions class="py-0" v-if="isLoggedIn">
                            <v-btn small icon @click="vote(movie.id, 0)">{{movie.up}}<v-icon :ref="movie.id" :color="getCurrentVote(movie.movie_votes, movie.id)===0?'blue':'black'"  small right>thumb_up</v-icon></v-btn>
                            <v-btn small text @click="vote(movie.id, 1, $event)">{{movie.down}}<v-icon :color="getCurrentVote(movie.movie_votes, movie.id)===1?'blue':'black'" small right>thumb_down</v-icon></v-btn>
                        </v-card-actions>
                        <v-card-text>
                            <div class="d-flex flex-column">
                                <span>Description:</span>
                                <strong>{{movie.description}}</strong>
                            </div>
                        </v-card-text>
                    </v-card>
                </v-col>
            </v-row>
        </v-container>
        <v-footer absolute class="font-weight-medium">
            <rowperpage collection='movies'
            store='youtube'
            v-on:updaterow="displayData"
            />

            <paginate 
            collection='movies'
            class="mx-auto"
            store='youtube'
            :getItem="getAllMovies"
            moduleCurrentPage='setCurrentPage'
            />
        </v-footer>
    </v-card>
</template>

<script>
// import Youtube from '../apis/youtube';
import {mapGetters} from 'vuex';
import youtube from '../apis/youtube';
import youtubeVote from '../apis/youtube_vote';
import User from '../apis/User';
import paginate from './paginate';
import rowperpage from './rowperpage';

export default {
    data(){
        return {
            errors:[],
            video_prefix:'https://www.youtube.com/embed/',
            user:null,
            isLoggedIn:false,
            votes:[],
            movieId:null
        }
    },
    components:{
        paginate, 
        rowperpage
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
        ...mapGetters(["getMovies", "getPerPage"]),
    },
    methods:{
        displayData(){
            this.getAllMovies({page:1, rows:this.getPerPage});
        },
        getAllMovies(param){
            youtube.getVideos(param)
            .then((response)=>{
                this.$store.commit('setMovies', response.data)
            })
            .catch((errors)=>{
                console.log(errors);
                if(errors.response.status === 422){
                    this.errors = errors.response.data.errors;
                }
            })
        },
        getThumbsUp(data, user_id){
            console.log(user_id);
            if(data.length === 0) return null;
            //return data.length;
          // let up = 

        },
        getUser(){
             User.auth()
            .then(response=>{
                this.user = response.data;
            })
            .catch((error)=>{
                console.log('user error', error)
            })
        },
        vote(movie_id, vote){
            
            youtubeVote.vote({user_id:this.user.id, movie_id:movie_id, vote:vote})
            .then((response)=>{
                this.$store.commit('updateMovie', response.data);
            })
            .catch((errors)=>{
                console.log('vote error', errors);
                if(errors.response.status === 422){
                    this.errors = errors.response.data.errors;
                }
            })
        },
        getCurrentVote(votes, movie_id){
            if(votes && this.user){
                 const index = votes.findIndex(mov=>(mov.shared_movie_id === movie_id && mov.user_id === this.user.id));
                if(index !== -1){
                    let vote = votes.slice(index, index+1);
                    console.log('vote', vote);
                    return vote[0].vote;
                }
            }
        }
    },
    created(){
        this.isLoggedIn = !!localStorage.getItem('token');
        if(this.isLoggedIn){
            this.getUser();
        }
        this.getAllMovies({page:1, rows:this.getPerPage});
    }
}
</script>

<style>

</style>