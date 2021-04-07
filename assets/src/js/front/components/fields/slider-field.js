import { enableRipple } from '@syncfusion/ej2-base';
enableRipple(true);
import { Slider } from '@syncfusion/ej2-inputs';

export default {
	props: ['field', 'value'],

	data() {
		return {
			rangeField: null,
			min: 0,
			max: 100,
			step: 1,
			rangeValue: 0,
			leftVal: 0,
			rightVal: 100,
		}
	},

	created() {
		this.rangeField = this.clone(this.field)
		if ( this.rangeField.id ) {
			this.min  = this.rangeField.min
			this.max  = this.rangeField.max
			this.step = this.rangeField.step

			if ( this.value ) {
				const [left, right] = this.value?.value || [0, 100]
				this.leftVal  = +left
				this.rightVal = +right
			}
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

			let rangeObj = new Slider({
				min: +this.min, max: +this.max,
				value: [this.leftVal, this.rightVal],
				step: +this.step,
				type: 'Range',
				tooltip: {
					isVisible: true,
					showOn: 'Focus',
					placement: 'Before'
				},
				change: args => {
					const [left, right] = args.value;
					vm.leftVal = left;
					vm.rightVal = right;
					this.change();
				}
			});

			rangeObj.appendTo( `.wf_slider_${this.rangeField.id} .wf_range_${vm.rangeField.id}`)
			const handles = Array.from(document.querySelectorAll('.e-handle'))
			handles.forEach(h => h.addEventListener('mouseup', () => {
				let toolTipActiveOpen = Array.from(document.querySelectorAll('.e-popup-open'))
				toolTipActiveOpen.forEach(e => {
					e.classList.remove('e-popup-open')
				})
			}))
		},

		change() {
			this.$emit('update', this.field.tag, [this.leftVal, this.rightVal])
		},
	},

	template: `
			<div class="wf-slider-wrapper">
				<div class="wf-range" :class="'wf_slider_' + field.id">
					<div class="wf-item__title"  style="display: flex; justify-content: space-between">
						<span v-if="field.label_toggle">{{ field.title }}</span>
						<span> {{ leftVal }} - {{ rightVal }} </span>
					</div>
					<div :class="['wf_range_' + field.id]"></div>
				</div>
			</div>
	`
}