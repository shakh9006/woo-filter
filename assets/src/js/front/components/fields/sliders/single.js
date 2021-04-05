import { enableRipple } from '@syncfusion/ej2-base'
enableRipple(true)
import { Slider } from '@syncfusion/ej2-inputs'

export default {
	props: ['field'],

	data() {
		return {
			rangeField: null,
			min: 0,
			max: 100,
			step: 1,
			rangeValue: 0,
		}
	},

	created() {
		this.rangeField = this.clone(this.field)
		if ( this.rangeField.id ) {
			this.min  = this.rangeField.min
			this.max  = this.rangeField.max
			this.step = this.rangeField.step
		}
	},

	mounted() {
		this.renderRange()
	},

	methods: {
		clone(data) {
			return JSON.parse(JSON.stringify(data))
		},

		renderRange() {
			const vm = this;
			this.min = +this.min
			this.max = +this.max

			vm.rangeObj = new Slider({
				min: this.min,
				max: this.max,
				value: this.rangeValue,
				step: this.step,
				type: 'MinRange',
				tooltip: {
					isVisible: true,
					placement: 'Before'
				},
				change: args => {
					this.rangeValue = args.value
					// this.change();
				},
			});

			vm.rangeObj.appendTo( `.wf_slider_${this.rangeField.id} .wf_range_${vm.rangeField.id}`)
		}
	},

	template: `
			<div class="wf-range" :class="'wf_slider_' + field.id">
				<div class="wf-item__title"  style="display: flex; justify-content: space-between">
					<span v-if="field.label_toggle">{{ field.title }}</span>
					<span> {{ rangeValue }} </span>
				</div>
				<div :class="['wf_range_' + field.id]"></div>
			</div>
	`
}