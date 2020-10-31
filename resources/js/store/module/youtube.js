const state = {
    movies:[], perpage:5
};

const getters = {
    getMovies:state=>state.movies.data,
    getPerPage:state=>state.perpage
};

const actions = {};

const mutations = {
    setMovies:(state, movies)=> (state.movies = movies),
    updateMovie:(state, updMovie)=>{
        const index = state.movies.data.findIndex(mov=>mov.id === updMovie.id);
        if(index !== -1){
            state.movies.data.splice(index, 1, updMovie);
        }
    },
    setCurrentPage:(state, data)=>(state.movies.current_page = data),
    setPerPage:(state, rows)=>(state.perpage = rows)
};

export default{
    state,
    getters,
    actions,
    mutations
};