const state = () => ({
    categories: [],
    staticStore: {
        orderId: window.staticStore.orderId,
        orderProducts: window.staticStore.orderProducts,
        url:{
            viewProduct: window.staticStore.urlViewProduct,
        }
    }
})

const getters = {

};

const mutations = {

};

const actions = {

};

export default {
    namespaced: true,
    state,
    getters,
    actions,
    mutations
}
