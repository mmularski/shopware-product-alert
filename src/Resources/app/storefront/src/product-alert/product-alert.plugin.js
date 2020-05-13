/**
 * @package  ProductAlert\Controller
 * @author Marek Mularczyk <mmularczyk9@gmail.com>
 */

import Plugin from 'src/plugin-system/plugin.class';
import DomAccess from 'src/helper/dom-access.helper';
import FormSerializeUtil from 'src/utility/form/form-serialize.util';
import HttpClient from 'src/service/http-client.service';
import ButtonLoadingIndicator from 'src/utility/loading-indicator/button-loading-indicator.util';

export default class ProductAlert extends Plugin {
    init() {
        this._getForm();
        this._getSubmitButton();

        if (!this._form) {
            throw new Error(`No form found for the plugin: ${this.constructor.name}`);
        }

        this._client = new HttpClient(window.accessKey, window.contextToken);

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
     * @param response
     * @private
     */
    _responseCallback(response) {
        console.log(response);

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
}
