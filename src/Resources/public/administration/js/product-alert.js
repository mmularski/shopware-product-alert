(this.webpackJsonp=this.webpackJsonp||[]).push([["product-alert"],{Dl8D:function(t,e,a){"use strict";a.r(e);var r=a("Va4c"),o=a.n(r);const{Criteria:l}=Shopware.Data;Shopware.Component.register("product-alert-list",{template:o.a,inject:["repositoryFactory"],metaInfo(){return{title:this.$createTitle()}},data:()=>({repository:null,rows:null}),created(){this.repository=this.repositoryFactory.create("product_alert");const t=new l;t.addFilter(l.equals("product_alert.isSent",!1)),t.addAssociation("product"),this.repository.search(t,Shopware.Context.api).then(t=>{this.rows=t})},computed:{columns(){return[{property:"product.id",dataIndex:"product.id",label:this.$tc("product-alert.list.columnId"),allowResize:!0,primary:!0,routerLink:"product.alert.details"},{property:"product.name",dataIndex:"product.name",label:this.$tc("product-alert.list.columnName"),allowResize:!0,primary:!1,routerLink:"product.alert.details"},{property:"email",dataIndex:"email",label:this.$tc("product-alert.list.columnEmail"),allowResize:!0,primary:!1}]}}});var i=a("peO7"),n=a.n(i);const{Criteria:s}=Shopware.Data;Shopware.Component.register("product-alert-details",{template:n.a,inject:["repositoryFactory"],metaInfo(){return{title:this.$createTitle()}},data:()=>({alertData:{id:null,name:null,count:null},entity:null}),created(){this.repository=this.repositoryFactory.create("product_alert");const t=this.getCriteria();t.addFilter(s.equals("product_alert.id",this.$route.params.id)),this.repository.search(t,Shopware.Context.api).then(t=>{this.entity=t[0],this.alertData.id=this.entity.product.id,this.alertData.name=this.entity.product.name,this.getCount()})},methods:{getCriteria(){const t=new s;return t.addFilter(s.equals("product_alert.isSent",!1)),t.addAssociation("product"),t},getCount(){const t=this.getCriteria();t.addFilter(s.equals("product_alert.productId",this.entity.product.id)),t.addAggregation(s.terms("count","product.id")),this.repository.search(t,Shopware.Context.api).then(t=>{console.log(t),this.alertData.count=t.aggregations.count.buckets[0].count})}}});var c=a("R2MV");Shopware.Module.register("product-alert",{color:"#ff3d58",icon:"default-chart-bar",title:"Product Alert",description:"List of products and subscribers count for out of stock notification.",snippets:{"en-GB":c},routes:{list:{component:"product-alert-list",path:"list"},details:{component:"product-alert-details",path:"details/:id",meta:{parentPath:"product.alert.list"}}},navigation:[{label:"Product Alert",color:"#ff3d58",path:"product.alert.list",icon:"default-chart-bar",parent:"sw-catalogue",position:100}]})},R2MV:function(t){t.exports=JSON.parse('{"product-alert":{"list":{"columnId":"Product ID","columnName":"Product Name","columnEmail":"Email"},"details":{"defaultPlaceholder":"Fetching data...","idLabel":"Product ID","nameLabel":"Product Name","countLabel":"Number of subscribers for out of stock notification","countLabelPlaceholder":"Calculating..."}}}')},Va4c:function(t,e){t.exports='{% block product_alert_list %}\n    <sw-page class="product-alert-list">\n        <template slot="content">\n            {% block product_alert_list_content %}\n                <div class="sw-promotion-list__content">\n                    {% block product_alert_list_content_grid %}\n                        <sw-entity-listing\n                                v-if="rows"\n                                :items="rows"\n                                :repository="repository"\n                                :showSelection="false"\n                                :columns="columns">\n                        </sw-entity-listing>\n                    {% endblock %}\n                </div>\n            {% endblock %}\n        </template>\n    </sw-page>\n{% endblock %}'},peO7:function(t,e){t.exports='{% block product_alert_list %}\n    <sw-page class="swag-bundle-detail">\n        <template slot="content">\n            <sw-card-view>\n                <sw-card v-if="alertData">\n                    <sw-field :label="$t(\'product-alert.details.idLabel\')"\n                              :placeholder="$tc(\'product-alert.details.defaultPlaceholder\')"\n                              v-model="alertData.id"\n                              disabled\n                    ></sw-field>\n                    <sw-field :label="$t(\'product-alert.details.nameLabel\')"\n                              :placeholder="$tc(\'product-alert.details.defaultPlaceholder\')"\n                              v-model="alertData.name"\n                              disabled\n                    ></sw-field>\n                    <sw-field :label="$t(\'product-alert.details.countLabel\')"\n                              :placeholder="$tc(\'product-alert.details.countLabelPlaceholder\')"\n                              v-model="alertData.count"\n                              disabled\n                    ></sw-field>\n                </sw-card>\n            </sw-card-view>\n        </template>\n    </sw-page>\n{% endblock %}'}},[["Dl8D","runtime"]]]);