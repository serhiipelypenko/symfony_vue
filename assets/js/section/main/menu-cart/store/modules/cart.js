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
    staticStore: {
        url: {
            apiCart: window.staticStore.urlCart,
            apiCartProduct: window.staticStore.urlCartProduct,
            viewProduct: window.staticStore.urlViewProduct,
            viewCart: window.staticStore.urlViewCart,
            assetImageProducts: window.staticStore.urlAssetImageProducts,
        }
    }
})

const getters = {
    totalPrice(state){
        let result = 0;
        /*if(!state.cart && !state.cart.cartProducts){
            return 0;
        }*/
        if(state.cart && state.cart.cartProducts){
            state.cart.cartProducts.forEach(
                cartProduct => {
                    result += cartProduct.product.price * cartProduct.quantity;
                }
            );
        }

        return result;
    }
};

const actions = {
    async getCart ({state, commit, dispatch}) {
        const url = state.staticStore.url.apiCart;
        const result = await axios.get(url, apiConfig);

        if(result.data && result.status === StatusCodes.OK && result.data.member[0]) {
            //commit('setCart', result.data["hydra:member"]);
            commit('setCart', result.data.member[0]);
        }else{
            dispatch('createCart');
            commit('setAlert', {
                type: 'info',
                message: 'Your cart is empty...'
            });
        }
    },

    async cleanCart ({state, commit}) {
        const url = concatUrlByParams(
            state.staticStore.url.apiCart,
            state.cart.id
        )
        const result = await axios.delete(url, apiConfig);

        if(result.status === StatusCodes.NO_CONTENT) {
            commit('setCart', {});
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

    addCartProduct ({state, dispatch}, productData) {
        const existCartProduct = state.cart.cartProducts.find(
            cartProduct => cartProduct.product.uuid === productData.uuid
        );

        if(existCartProduct) {
            dispatch('addExistCartProduct', existCartProduct);
        }else{
            dispatch('addNewCartProduct', productData);
        }
    },

    async createCart({state,dispatch}){
        const url = state.staticStore.url.apiCart
        const result = await axios.post(url, {},apiConfig);
        if(result.data && result.status === StatusCodes.CREATED) {
            dispatch('getCart');
        }
    },

    async addExistCartProduct({state, dispatch}, existCartProduct){
        const url = concatUrlByParams(
            state.staticStore.url.apiCartProduct,
            existCartProduct.id
        );

        const data ={
            "quantity": existCartProduct.quantity + 1
        }
        const result = await axios.patch(url, data, apiConfigPatch);
        if(result.status === StatusCodes.OK) {
            dispatch('getCart');
        }
    },

    async addNewCartProduct({state, dispatch},productData){
        const url = state.staticStore.url.apiCartProduct;
        const data = {
            //cart: "/api/carts/" + state.cart.id,
            product: "/api/products/" + productData.uuid,
            quantity: 1
        };
        const result = await axios.post(url, data, apiConfig);
        if(result.data && result.status === StatusCodes.CREATED) {
            dispatch('getCart');
        }
    }
};

const mutations = {
    setCart(state, cart){
        state.cart = cart;
    },
}

export default {
    namespaced: true,
    state,
    getters,
    actions,
    mutations
}
