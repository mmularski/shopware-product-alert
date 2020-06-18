import ProductAlertApiService
    from '../module/product-alert/service/api/prodict-alert.api.service';

const {Application} = Shopware;

Application.addServiceProvider('productAlertApiService', (container) => {
    const initContainer = Application.getContainer('init');

    return new ProductAlertApiService(initContainer.httpClient, container.loginService);
});