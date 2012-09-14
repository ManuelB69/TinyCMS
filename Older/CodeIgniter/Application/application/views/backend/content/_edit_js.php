
var contentEdit = {

	paragraphs: null,

	init: function( paragraphsSel ) {

		//---------------------------------
		//  initialize paragraph labels
		//
		this.paragraphs = $(paragraphsSel);
		$.extend( this.paragraphs, {

			propertySheet: null,

			hideAllPropertySheets : function() {
				this.find('> div.image_property').hide();
				this.find('> div.link_property').hide();
			},

			findPropertySheet: function( propertyData ) {
				switch( propertyData.type ) {
					case 'image': return this.find('#image_property_' + propertyData.id );
					case 'link':  return this.find('#link_property_' + propertyData.id );
				}
				return null;
			},

			deactivatePropertySheet : function() {
				if( this.propertySheet ) {
					var propertyToClear = this.findPropertySheet( this.propertySheet );
					if( propertyToClear ) propertyToClear.hide();
					this.propertySheet = null;
				}
			},

			activatePropertySheet : function( type, id ) {
				this.deactivatePropertySheet();
				var propertyToSet = this.findPropertySheet({ type:type, id:id });
				if( propertyToSet )	{
					propertyToSet.show();
					this.propertySheet = {	type:type, id:id };
				}
			},

			togglePropertySheet : function( type, id ) {
				if( this.propertySheet && this.propertySheet.type === type && this.propertySheet.id === id ) {
					this.deactivatePropertySheet();
				}
				else this.activatePropertySheet( type, id );
			},
		});

		var paragraphs = this.paragraphs;
		paragraphs.hideAllPropertySheets();

		paragraphs.find('> div.images > a[id^=image_]').click(function(event){
			paragraphs.togglePropertySheet('image', $(this).attr('id').match(/\d+$/)[0] );
			return false;
		});

		paragraphs.find('> div.links a[id^=link_]').click(function(event){
			paragraphs.togglePropertySheet('link', $(this).attr('id').match(/\d+$/)[0] );
			return false;
		});
	},
};

contentEdit.init('#content div.paragraph_label');


	