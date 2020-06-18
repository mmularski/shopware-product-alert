<template>
    <div class="product-detail-product-alert" @keyup.enter="signToAlert">
        <div class="product-alert-info">
            <span class="info-container">Notify me when product is back to stock</span>
        </div>

        <div class="alert-container" v-if="alertVisible">
            <ResultAlert :message="result.message" :type="result.type" @click:close="alertVisible = false"/>
        </div>
        <div class="product-alert-form">
            <SwInput
                    v-model="email"
                    name="email"
                    label="Enter your email"
                    class="form__input"
                    :valid="!$v.email.$error"
                    :disabled="isLoading"
                    :value="email"
                    error-message="Email is required"
                    @blur="$v.email.$touch()"
            />
            <SwButton
                    class="sf-button form__button"
                    :disabled="isLoading"
                    @click="signToAlert"
            >
                Sign In
            </SwButton>
        </div>
    </div>
</template>

<script>
    import SwInput from "@shopware-pwa/default-theme/components/atoms/SwInput"
    import SwButton from "@shopware-pwa/default-theme/components/atoms/SwButton"
    import {validationMixin} from "vuelidate"
    import {required, email} from "vuelidate/lib/validators"
    import {useUser} from "@shopware-pwa/composables"
    import {productAlertService} from "./composables/productAlertService"
    import ResultAlert from "./components/ResultAlert";

    export default {
        name: "ProductAlert",
        components: {
            SwInput,
            SwButton,
            ResultAlert
        },
        mixins: [validationMixin],
        props: {
            slotContext: {
                default: null
            }
        },
        data() {
            return {
                email: this.user?.email,
                alertVisible: false,
                result: {}
            }
        },
        setup() {
            const {signToProductAlert} = productAlertService();
            const isLoading = false;
            const {user} = useUser();

            return {
                user,
                isLoading,
                signToProductAlert
            }
        },
        validations: {
            email: {
                required,
                email,
            }
        },
        methods: {
            async signToAlert() {
                this.$v.$touch()
                if (this.$v.$invalid) {
                    return;
                }

                this.isLoading = true;

                await this.signToProductAlert({email: this.email, productId: this.slotContext.id}).then((response) => {
                    this.result.message = response.data.message;
                    this.result.type = response.data.error ? 'warning' : 'success';

                    this.isLoading = false;
                    this.alertVisible = true;
                });
            }
        },
    }
</script>

<style lang="scss" scoped>
    div.product-detail-product-alert {
        border-top: solid 1px;
        margin-top: 10px;

        & .product-alert-info {
            margin-bottom: 20px;
        }
    }

    .product-alert-form {
        display: flex;
        max-height: 50px;

        & .form__input {
            width: 100%;
        }

        & .form__button {
            --button-width: 33%;
        }
    }
</style>