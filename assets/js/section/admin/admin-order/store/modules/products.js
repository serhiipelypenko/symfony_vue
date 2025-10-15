import {concatUrlByParams, getUrlProductsByCategory} from "../../../../../utils/url-generator";
import axios from "axios";
import {apiConfig} from "../../../../../utils/settings";
import {StatusCodes} from "http-status-codes";

const state = () => ({
    categories: [],
    categoryProducts: [],
    orderProducts: [],
    busyProductsIds: [],
    newOrderProduct: {
        categoryId: "",
        productId: "",
        quantity: "",
        pricePerOne: ""
    },
    staticStore: {
        orderId: window.staticStore.orderId,
        url:{
            viewProduct: window.staticStore.urlViewProduct,
            apiOrderProduct: window.staticStore.urlAPIOrderProduct,
            apiCategory: window.staticStore.urlApiCategory,
            apiProduct: window.staticStore.urlApiProduct,
            apiOrder: window.staticStore.urlApiOrder
        }
    },
    viewProductLimit: 25
})

const getters = {
    freeCategoryProducts(state) {
        return state.categoryProducts.filter(
            product => state.busyProductsIds.indexOf(product.id) === -1);
    }
};

const actions = {
    async getOrderProducts({ commit, state }) {
        const url = concatUrlByParams(
            state.staticStore.url.apiOrder,
            state.staticStore.orderId
        );
        const result = await axios.get(url, apiConfig);
        if(result.data && result.status === StatusCodes.OK){
            commit('setOrderProducts', result.data.orderProducts);
            commit('setBusyProductsIds');
        }
    },
    async getProductsByCategory({ commit, state }) {
        ///api/products?page=1&isPublish=true&category=1
        const url = getUrlProductsByCategory(
            state.staticStore.url.apiProduct,
            state.newOrderProduct.categoryId,
            1,
            state.viewProductLimit
        );
        const result = await axios.get(url, apiConfig);
        if(result.data && result.status === StatusCodes.OK){
            commit('setCategoryProducts', result.data["member"]);
        }
    },
    async getCategories({ commit, state }) {
        const url = state.staticStore.url.apiCategory;
        const result = await axios.get(url, apiConfig);

        if(result.data && result.status === StatusCodes.OK){
            commit('setCategories', result.data["member"]);
        }
    },
    async addNewOrderProduct({ state, dispatch }) {
        const url = state.staticStore.url.apiOrderProduct;
        const data = {
            pricePerOne: (state.newOrderProduct.pricePerOne).toString(),
            quantity: parseInt(state.newOrderProduct.quantity),
            product: "/api/products/" + state.newOrderProduct.productId,
            appOrder: "/api/orders/" + state.staticStore.orderId
        }
        const result = await axios.post(url, data, apiConfig);

        if(result.data && result.status === StatusCodes.CREATED){
            dispatch('getOrderProducts');
        }
    },
    async removeOrderProduct({state,dispatch},orderProductId) {
        const url = concatUrlByParams(state.staticStore.url.apiOrderProduct,orderProductId);
        const result = await axios.delete(url, apiConfig);
        if(result.status === StatusCodes.NO_CONTENT){
            dispatch('getOrderProducts');
        }
    }
};

const mutations = {
    setCategories(state, categories) {
        state.categories = categories;
    },
    setOrderProducts(state, orderProducts) {
        state.orderProducts = orderProducts;
    },
    setNewProductInfo(state, formData) {
        state.newOrderProduct.categoryId = formData.categoryId;
        state.newOrderProduct.productId = formData.productId;
        state.newOrderProduct.quantity = formData.quantity;
        state.newOrderProduct.pricePerOne = formData.pricePerOne;
    },
    setCategoryProducts(state, categoryProducts) {
        state.categoryProducts = categoryProducts;
    },
    setBusyProductsIds(state) {
        state.busyProductsIds = state.orderProducts.map(item => item.product.id);
    }
}

export default {
    namespaced: true,
    state,
    getters,
    actions,
    mutations
}
