import axios from "axios";
import {apiConfig,apiConfigPatch} from "../../../../../utils/settings";
import {StatusCodes} from "http-status-codes";
import {concatUrlByParams, getUrlProductsByCategory} from "../../../../../utils/url-generator";

function getAlertStructure(){
    return {
        type: null,
        message: null,
    };
}

const state = () => ({
    cart: {},
    alert: getAlertStructure(),
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
    totalPrice(state){
        let result = 0;
        if(!state.cart.cartProducts){
            return 0;
        }
        state.cart.cartProducts.forEach(
            cartProduct => {
                result += cartProduct.product.price * cartProduct.quantity;
            }
        );

        return result;
    }
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

    async removeCartProduct ({state, commit, dispatch}, cartProductId) {
        const url = concatUrlByParams(
            state.staticStore.url.apiCartProduct,
            cartProductId
        );
        const result = await axios.delete(url, apiConfig);
        if(result.status === StatusCodes.NO_CONTENT) {
            dispatch('getCart');
            //commit('cleanAlert');
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
    },
    cleanAlert(state){
        state.alert = getAlertStructure()
    },
    setAlert(state, model){
        state.alert = {
            type: model.type,
            message: model.message,
        }
    }
}

export default {
    namespaced: true,
    state,
    getters,
    actions,
    mutations
}
