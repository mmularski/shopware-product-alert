/**
 * @package  ProductAlert\Controller
 * @author Marek Mularczyk <mmularczyk9@gmail.com>
 */

import ProductAlert from './product-alert/product-alert.plugin';

const PluginManager = window.PluginManager;
PluginManager.register('ProductAlert', ProductAlert, '[data-product-alert]');