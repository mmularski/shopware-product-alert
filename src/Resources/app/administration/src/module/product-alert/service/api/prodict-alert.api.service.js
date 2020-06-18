const ApiService = Shopware.Classes.ApiService;

class ProductAlertApiService extends ApiService {
    constructor(httpClient, loginService, apiEndpoint = 'product.alert') {
        super(httpClient, loginService, apiEndpoint);
    }

    process() {
        const headers = this.getHeaders();
        return this.httpClient.get('/_action/product/alert/process', { headers });
    }

    getHeaders() {
        return {
            Accept: 'application/json',
            Authorization: `Bearer ${this.loginService.getToken()}`,
            'Content-Type': 'application/json'
        };
    }
}

export default ProductAlertApiService;
