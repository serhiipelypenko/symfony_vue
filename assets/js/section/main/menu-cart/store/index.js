import { createStore } from 'vuex';
import cart from './modules/cart';
const debug = process.env.NODE_ENV !== 'production';

export default createStore({
    state: { },
    mutations: { },
    actions: { },
    getters: { },
    modules: {
        cart,
    },
    strict: debug,
});
