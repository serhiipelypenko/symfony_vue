<template>
    <div class="row">
        <div class="col-lg-12 order-block">
            <div class="order-content">
                <Alert />
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
import {mapActions, mapGetters, mapMutations, mapState} from "vuex";
import Alert from "./components/Alert.vue";

export default {
    name: "App",
    components: {Alert, CartTotalPrice, CartProductList},
    created() {
        this.getCart();
        this.setAlert( {type: 'warning', message: 'You can see your cart!'});
    },
    computed: {
        ...mapState("cart", ["isSentForm"]),
        showCartContent(){
            return !this.isSentForm;
        }
    },
    methods: {
        ...mapActions("cart", ["getCart","makeOrder"]),
        ...mapMutations("cart", ["setAlert"]),
    }
}
</script>

<style scoped>

</style>
