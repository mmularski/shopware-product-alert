/**
 * @package Divante\ProductAlert
 * @author Marek Mularczyk <mmularczyk@divante.pl>
 * @copyright 2020 Divante Sp. z o.o.
 * @license See LICENSE_DIVANTE.txt for license details.
 */

import ProductAlert from './product-alert/product-alert.plugin';

const PluginManager = window.PluginManager;
PluginManager.register('ProductAlert', ProductAlert, '[data-product-alert]');