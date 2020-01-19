$(document).ready(function () {
    var $table          = $( '.wc-shipping-zone-methods' ),
    $tbody          = $( '.wc-shipping-zone-method-rows' ),
    $save_button    = $( '.wc-shipping-zone-method-save' );
    //$row_template   = wp.template( 'wc-shipping-zone-method-row' ),
    //$blank_template = wp.template( 'wc-shipping-zone-method-row-blank' )
    var $thead = $('thead');
    var $hdata = [
        {title:"Title"},
        {title:"Title"},
        {title:"Title"},
    ];

    var backModel = Backbone.Model.extend({
        defaults:{
            title: 'My service',
            price: 100,
            checked: false
        }
    });

    var backCollection = Backbone.Collection.extend({
        model: backModel,

    });

    var bCollections = new backCollection();

    var backView = Backbone.View.extend({
        render:function(){
            console.log( this.model );
        }
    });

    var viewInst = new backView({
       el:  "#container",
       model:backModel
    });
    viewInst.render();

    //console.log($hdata);


});