<template>
    <div class="row">
        <div class="col-lg-12 order-block">
            <div class="order-content">
                <div v-if="showCartContent">
                    <CartProductList/>
                    <CartTotalPrice/>
                    <a class="btn btn-success text-white"
                       @click="makeOrder"
                    >Make Order</a>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import CartProductList from "./components/CartProductList.vue";
import CartTotalPrice from "./components/CartTotalPrice.vue";
import {mapActions} from "vuex";

export default {
    name: "App",
    components: {CartTotalPrice, CartProductList},
    created() {
        this.getCart()
    },
    computed: {
        showCartContent(){
            return true;
        }
    },
    methods: {
        ...mapActions("cart", ["getCart"]),
        makeOrder(){
            return true;
        }
    }
}
</script>

<style scoped>

</style>
