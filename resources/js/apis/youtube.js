import Api from './API';

export default{
    searchVideo(search_query)
    {
        return Api().get('search',{params:search_query});
    },
    getVideos(params){
        return Api().get('movie', {params:params});
    },
    addVideo(params){
        return Api().post('movie', params);
    }
};
