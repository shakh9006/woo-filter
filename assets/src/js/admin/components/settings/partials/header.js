import { mapGetters, mapActions, mapMutations } from "vuex";

export default {
	computed: {
		...mapGetters([
			'getPropertyByName'
		]),

		step: {
			get() {
				return this.getPropertyByName('step')
			},

			set(value) {
				this.setPropertyByName({name: 'step', value})
			}
		}
	},

	methods: {
		...mapActions([
			'saveSettings',
		]),

		...mapMutations([
			'setPropertyByName'
		]),

		save() {
			const url = this.getPropertyByName('ajaxUrl')
			this.saveSettings({url})

		}
	},

	template: `
		<div class="wf-settings-header">
			<div v-if="step === 'builder'">Edit Filter </div>
			<div v-else>Home Page</div>
			<button type="button" class="btn btn-primary btn-lg" @click.prevent="save" v-if="step === 'builder'">Save Settings</button>
			<button type="button" class="btn btn-primary btn-lg" @click.prevent="step = 'builder'" v-else>Create New</button>
		</div>
	`
}