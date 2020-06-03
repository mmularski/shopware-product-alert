import {getProductAlertSignEndpoint} from "./endpoints";
import {invokePost} from "@shopware-pwa/shopware-6-client";

export const productAlertService = () => {
    /**
     * Sign to product alert
     *
     * @throws ClientApiError
     */
    async function signToProductAlert(data) {
        return await invokePost({address: getProductAlertSignEndpoint(), payload: data});
    }

    return {
        signToProductAlert
    }
}
