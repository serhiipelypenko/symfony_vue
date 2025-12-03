import { createApp } from 'vue';
import App from './App.vue';
import store from './store';

const vueMenuCartInstance = createApp(App);
vueMenuCartInstance.use(store);
vueMenuCartInstance.mount('#appMainMenuCart');

window.vueMenuCartInstance = {};
window.vueMenuCartInstance.addCartProduct =
    (productData) => store.dispatch('cart/addCartProduct', productData);
