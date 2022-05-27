export  default  {
    setName ({commit}, value) {
         commit('setName', value);
    },
    setEmail ({commit}, value) {
        commit('setEmail', value);
    },
    setImage ({commit}, value) {
        commit('setImage', value);
    },
    setDetails ({commit}, value){
        commit('setDetails', value);
    },
    setIdRoom ({commit}, id_room_gruop){
        commit('setIdRoom' ,id_room_gruop )
    },
    setCountUser ({commit}, countUser){
        commit('setCountUser' ,countUser);
    }
}
