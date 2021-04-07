import select from './vue-select'

export default {
	props: ['content', 'selected', 'index'],

	components: {
		'select-2': select
	},

	created() {
		if ( this.content ) {
			this.options  	  = this.content?.options
			this.multiple 	  = this.content?.multiple
			this.selected_val = this.selected
			this.$emit('update', this.selected_val)
		}
	},

	data () {
		return {
			options  	 : null,
			multiple     : false,
			selected_val : null,
		}
	},

	watch: {
		selected_val(value) {
			this.$emit('update', value, this.index)
		}
	},

	template: `
		<div class="container">
			<div class="row">
				  <div class="col-sm-12">
					  <select-2 :options="options" v-model="selected_val" :multiple=multiple></select-2>
				  </div>
			</div>
      </div>
	`
}