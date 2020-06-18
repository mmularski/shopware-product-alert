/**
 * @package Divante\ProductAlert
 * @author Marek Mularczyk <mmularczyk@divante.pl>
 * @copyright 2020 Divante Sp. z o.o.
 * @license See LICENSE_DIVANTE.txt for license details.
 */

import Plugin from 'src/plugin-system/plugin.class';
import DomAccess from 'src/helper/dom-access.helper';
import FormSerializeUtil from 'src/utility/form/form-serialize.util';
import StoreApiClient from 'src/service/store-api-client.service';
import ButtonLoadingIndicator from 'src/utility/loading-indicator/button-loading-indicator.util';

export default class ProductAlert extends Plugin {
    init() {
        this._getForm();
        this._getSubmitButton();

        if (!this._form) {
            throw new Error(`No form found for the plugin: ${this.constructor.name}`);
        }

        this._client = new StoreApiClient();

        this._registerEvents();
    }

    /**
     * tries to get the closest form
     *
     * @returns {HTMLElement|boolean}
     * @private
     */
    _getForm() {
        if (this.el && this.el.nodeName === 'FORM') {
            this._form = this.el;
        } else {
            this._form = this.el.closest('form');
        }
    }

    /**
     * tries to get the submit button fo the form
     *
     * @returns {HTMLElement|boolean}
     * @private
     */
    _getSubmitButton() {
        this._submitButton = DomAccess.querySelector(this._form, 'button[type=submit]');

        return true;
    }

    /**
     * @private
     */
    _registerEvents() {
        this.el.addEventListener('submit', this._formSubmit.bind(this));
    }

    /**
     * @param {Event} event
     * @private
     */
    _formSubmit(event) {
        event.preventDefault();

        this._displayLoader();

        const requestUrl = DomAccess.getAttribute(this._form, 'action');
        const formData = FormSerializeUtil.serialize(this._form);

        this._fireRequest(requestUrl, formData);
    }

    /**
     * fire the ajax request for the form
     *
     * @private
     */
    _fireRequest(requestUrl, formData) {
        this._client.post(requestUrl, formData, this._responseCallback.bind(this));
    }

    /**
     * @private
     */
    _responseCallback(response) {
        response = JSON.parse(response);

        if (response.error) {
            this._alert('warning', response.message);
        } else {
            this._alert('success', response.message);
        }

        this._stopLoader();
    }

    /**
     * @private
     */
    _displayLoader() {
        if (!this._loader) {
            this._loader = new ButtonLoadingIndicator(this._submitButton, 'beforeend');
        }

        this._loader.create();
    }

    /**
     * @private
     */
    _stopLoader() {
        this._loader.remove();
    }

    _alert(type, message) {
        const alert = DomAccess.querySelector(this._form, '#product-alert-alert');

        alert.innerHTML = '<div role="alert" class="alert alert-' + type + '"><div class="alert-content-container">' +
            '<div class="alert-content">' + message + '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>' +
            '</div></div></div>';
    }
}
