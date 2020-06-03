<template>
    <div :class="`color-${type}`" class="sf-alert">
        <!--@slot Custom alert icon. Slot content will replace default icon <SfIcon/> tag.-->
        <slot name="icon" v-bind="{ icon }">
            <SfIcon aria-hidden="true" :icon="icon" class="sf-alert__icon"/>
        </slot>
        <!--@slot Custom message . Slot content will replace default message <span> tag.-->
        <slot name="message" v-bind="{ message }">
            <span v-if="message" class="sf-alert__message">{{ message }}</span>
        </slot>
        <slot name="close">
            <SfButton
                    class="sf-button--pure sf-bar__icon"
                    aria-label="close"
                    @click="$emit('click:close')"
            >
                <SfIcon icon="cross" size="14px"/>
            </SfButton>
        </slot>
    </div>
</template>
<script>
    import SfIcon from "@storefront-ui/vue/src/components/atoms/SfIcon/SfIcon.vue";
    import SfButton from "@storefront-ui/vue/src/components/atoms/SfButton/SfButton.vue";

    export default {
        name: "ResultAlert",
        components: {
            SfIcon,
            SfButton
        },
        props: {
            message: {
                type: String,
                default: "",
            },
            type: {
                type: String,
                default: "success",
                validator: function (value) {
                    return ["success", "warning"].includes(
                        value
                    );
                },
            }
        },
        computed: {
            icon() {
                switch (this.type) {
                    case "success":
                        return "check";
                    default:
                        return "info_shield";
                }
            },
        }
    };
</script>
<style lang="scss">
    @import "~@storefront-ui/shared/styles/components/molecules/SfAlert.scss";

    .sf-alert {
        margin-bottom: 10px;
    }

    .sf-alert__message {
        width: 100%;
    }
</style>
