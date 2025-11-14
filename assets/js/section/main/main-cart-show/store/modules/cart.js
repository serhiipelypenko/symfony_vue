import axios from "axios";
import {apiConfig,apiConfigPatch} from "../../../../../utils/settings";
import {StatusCodes} from "http-status-codes";
import {concatUrlByParams, getUrlProductsByCategory} from "../../../../../utils/url-generator";

const state = () => ({
    cart: {},
    staticStore: {
        url: {
            apiCart: window.staticStore.urlCart,
            apiCartProduct: window.staticStore.urlCartProduct,
            viewProduct: window.staticStore.urlViewProduct,
            assetImageProducts: window.staticStore.urlAssetImageProducts,
        }
    }
})

const getters = {
};

const actions = {
    async getCart ({state, commit}) {
        const url = state.staticStore.url.apiCart;
        const result = await axios.get(url, apiConfig);

        if(result.data && result.status === StatusCodes.OK) {
            //commit('setCart', result.data["hydra:member"]);
            commit('setCart', result.data.member[0]);
        }
    },

    async removeCartProduct ({state, dispatch}, cartProductId) {
        const url = concatUrlByParams(
            state.staticStore.url.apiCartProduct,
            cartProductId
        );
        const result = await axios.delete(url, apiConfig);
        if(result.status === StatusCodes.NO_CONTENT) {
            dispatch('getCart');
        }
    },

    async updateCartProductQuantity ({state, dispatch}, payload) {
        const url = concatUrlByParams(
            state.staticStore.url.apiCartProduct,
            payload.cartProductId
        );

        const data ={
            "quantity": parseInt(payload.quantity)
        }
        const result = await axios.patch(url, data, apiConfigPatch);
        if(result.status === StatusCodes.OK) {
            dispatch('getCart');
        }
    },
};

const mutations = {
    setCart(state, cart){
        state.cart = cart;
    }
}

export default {
    namespaced: true,
    state,
    getters,
    actions,
    mutations
}
