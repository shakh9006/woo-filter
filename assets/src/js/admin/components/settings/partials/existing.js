import { getRequest, postRequest, renderToast } from '@utils/index';
import { mapGetters, mapMutations } from 'vuex'

export default {

	data() {
		return {

		}
	},

	created() {
		this.loader = true
		const url = this.getPropertyByName('ajaxUrl')
		getRequest(url, {action: 'wf_filter_list'}, response => {
			this.loader = false
			this.list = response.filters || []
		})
	},

	computed: {
		...mapGetters([
			'getPropertyByName'
		]),

		list: {
			get() {
				return this.getPropertyByName('list') || []
			},

			set(value) {
				this.setPropertyByName({name: 'list', value})
			}
		},

		loader: {
			get() {
				return this.getPropertyByName('loader')
			},

			set(value) {
				this.setPropertyByName({name: 'loader', value})
			}
		},
	},

	methods: {
		...mapMutations([
			'setPropertyByName',
		]),

		deleteFilter(id) {
			if ( confirm('Are you sure to delete this filter?')) {
				this.loader = true
				const url = this.getPropertyByName('ajaxUrl')

				postRequest(url, {action: 'wf_delete_filter', id}, response => {
					this.loader = false
					renderToast(response.message, response.status)
					if ( response.success )
						this.list = this.list.filter(l => l.ID !== id)
				})
			}
		},

		editFilter(link) {
			if ( typeof link !== "undefined" && link )
				window.location.replace(link)
		},
	},

	template: `
		<div style="flex: 1" v-if="!loader">
			<div class="existing-header">
				<div class="existing-header__title">
					<i class="fas fa-stream"></i>
					<h4>My Filters</h4>
				</div>
				<div class="list-row calc-actions">
	
				</div>
			</div>
			<div class="existing-body">
				<div class="existing-wrapper" >
					<div class="existing-list header">
						<div class="list-title id">id</div>
						<div class="list-title title">filter name</div>
						<div class="list-title shortcode">shortcode</div>
						<div class="list-title actions">action</div>
					</div>
					<div class="existing-list"  v-for="(filter, id) in list" v-if="list.length > 0">
						<div class="list-title id">{{filter.ID}}</div>
						<div class="list-title title">{{filter.post_title ? filter.post_title : 'Empty'}}</div>
						<div class="list-title shortcode">
							<span>[wf-filter id="{{filter.ID}}"]</span>
						</div>
						<div class="list-title actions" style="display: flex; justify-content: space-evenly;">
							<div class="ccb-tooltip action" @click.prevent="editFilter(filter.edit)">
								<i class="icon--350"></i>
							</div>
			
							<div class="ccb-tooltip action" @click.prevnet="deleteFilter(filter.ID)">
								<i class="icon-3096673"></i>
							</div>
			
						</div>
					</div>
					<p v-if="!list.length" style="text-align: center; font-size: 17px; margin: 100px auto">No Filters yet! Please create new Filters</p>
				</div>
			</div>
		</div>
	`
}