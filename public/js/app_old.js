var urlUsers = 'users';
new Vue({
	el: '#foot',
	created: function() {
		this.getUsers();
	},
	data: {
		list:[]
	},
	methods: {
		getUsers: function(){
			axios.get(urlUsers).then(response =>{
				this.lists = response.data ;
				
			});
		}

	}
});

