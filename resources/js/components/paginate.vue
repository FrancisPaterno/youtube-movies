<template>
    <v-pagination
        v-model="currentPage"
        :length="lastPage"
        :total-visible="10"
    ></v-pagination>
</template>

<script>
import {mapGetters} from 'vuex';

export default {
    props: {
        store:'',
        collection:'',
        getItem:null,
        search:'',
        moduleCurrentPage:''
    },
    watch: {
        currentPage(newVal, oldVal) {
            if(oldVal !== undefined){
                  this.paginatePage(newVal);
            }
        }
    },
    computed: {
        ...mapGetters(['getPerPage']),
        currentPage: {
            get() {
                return this.$store.state[this.store][this.collection]
                    .current_page;
            },
            set(value) {
                this.$store.commit(this.moduleCurrentPage, value);
            }
        },
        lastPage: {
            get() {
                return this.$store.state[this.store][this.collection].last_page;
            }
        }
    },
    methods: {
        paginatePage(pageNumber) {
            this.getItem({page:pageNumber,rows:this.getPerPage});
        }
    }
};
</script>

<style></style>
