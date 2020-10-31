import Api from './API';

export default{
    vote(param){
        return Api().post('vote', param);
    }
};