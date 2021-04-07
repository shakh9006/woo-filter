const $ = jQuery

export default {
	template: '<select v-bind:name="name" class="form-control" v-bind:multiple="multiple"></select>',
	props: {
		name: '',
		options: {
			Object
		},
		value: null,
		multiple: {
			Boolean,
			default: false
		}
	},

	data() {
		return {
			select2data: []
		}
	},

	mounted() {
		this.formatOptions()
		let vm = this
		let select = $(this.$el)

		select
			.select2({
				placeholder: 'Select Options',
				theme: 'bootstrap',
				width: '100%',
				allowClear: true,
				data: this.select2data
			})
			.on('change', function () {
				vm.$emit('input', select.val())
			})
		select.val(this.value).trigger('change')
	},

	methods: {
		formatOptions() {
			this.select2data.push({ id: '', text: 'Select' })
			;(this.options || []).forEach(element => this.select2data.push({ id: element.id, text: element.text }))
		}
	},

	destroyed() {
		$(this.$el).off().select2('destroy')
	}
}