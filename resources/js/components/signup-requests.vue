<template>
  <div>
	<div class="page-header mb-4">
      <div class="description">
        <h3 :class="{mport: $root.dark}">Registracijos</h3>
        <h1 :class="{mport: $root.dark}">Nepatvirtintos registracijos</h1>
        <h4>{{_from}} – {{_to}}</h4>
      </div>
      <div class="ml-5 stats">
        <div class="stat mr-5">

          <!-- <span class="mt-1 status status-ok" v-if="newSignups.count > 0"></span>
          <span class="mt-1 status status-danger" v-if="newSignups.count <= 0"></span>
          <h1 class="number mt-3 ml-2"><span class="count">{{newSignups.count}}</span></h1>
          <div class="txt mt-2 ml-2">
            <h2>Nauju registraciju</h2>
            <h3>Nuo {{newSignups.date}}</h3>
          </div> -->
        </div>
      </div>
    </div>

    <div class="mt-5 mb-5 filter">
        <div class="justify-content-center w-50">
          <div class="row">
             <div class="col">
              <label class="label">Miestas</label>
              <select class="form-control white" name="group" @change="filterTable('city', $event)">
                  <option value="1">Klaipėda</option>
                  <option value="2">Vilnius</option>
              </select>
            </div>
          </div>
        </div>
    </div>
    <div class="page-content justify-content-center">
            <div class="card big">
                <div class="card-header flex-s">
                  <h2 class="vertical-align" :class="{mport: $root.dark}">Nepatvirtintos registracijos</h2>
                </div>

                <div class="card-body">
                	<div class="alert alert-warning" v-if="API_results.length === 0">
						Nerasta
                	</div>
                    <table v-if="API_results.length > 0" class="table card-table table-vcenter text-nowrap datatable dataTable no-footer">
                        <thead :class="{mport: $root.dark}" >
                            <tr>
                                <th>Vardas, pavardė</th>
                                <th>Gimimo data</th>
                                <th>Telefono numeris</th>
                                <th>Veiksmai</th>
                            </tr>
                        </thead>
                        <tbody :class="{mport: $root.dark}" >
                                <tr v-for="result in API_results">
                                    <td>
                                      {{result.firstName}} {{result.lastName}}
                                      <div class="small text-muted">Registracija pateikta {{result.created_at}}</div>
                                    </td>
                                    <td> {{result.birthDate}} </td>
                                    <td> {{result.primaryPhone}} </td>
                                    <td>
                                        <a href="javascript:void(0)" @click="showConfirmDialog(result.id)"><i class="dropdown-icon fe fe-check"></i></a>
                                        <a href="javascript:void(0)" class="ml-5" @click="deleteMember(result.id)"><i class="dropdown-icon fe fe-trash"></i></a>
                                    </td>
                                </tr>
                        </tbody>
                    </table>
                </div>

                <div class="flex-inline justify-content-center">
                  <ul class="pagination">
                    <li class="page-item"><a  @click="topage(firstUrl)" class="page-link">First</a></li>
                    <li class="page-item"><a @click="topage(prevUrl)"  class="page-link">Previous</a></li>
                    <li class="page-item"><a  @click="topage(nextUrl)" class="page-link">Next</a></li>
                    <li class="page-item"><a  @click="topage(lastUrl)" class="page-link">Last</a></li>
                  </ul>
                </div>

            </div>
    </div>
  </div>
</template>

<script>

  export default {
  	data(){
  		return{
  			API_results: [],
        newSignups: null,
        firstUrl: null,
        lastUrl: null,
        nextUrl: null,
        prevUrl: null,
        _from: null,
        _to: null,
  		}
  	},
  	methods: {
  		filterTable(inputType, event){
  			if(inputType === 'city'){
  				var reqURL = "api/signups/filter/city/" + event.target.value;
	  			axios.get(reqURL).then(response => {
	  				this.API_results = response.data;
	  			});
  			}
  		},
      showConfirmDialog(id){
        this.$router.push('/signups/confirm/'+id);
      },
      topage(url) {
        if(url == null) return;
        axios.get(url).then(response => {
          this.API_results = response.data.data;
          this.firstUrl = response.data.first_page_url;
          this.nextUrl = response.data.next_page_url;
          this.prevUrl = response.data.prev_page_url;
          this.lastUrl = response.data.last_page_url;
          this._from = response.data.from;
          this._to = response.data.to;
        });
      },
      deleteMember(id) {
        swal({
          title: "Registracijos užklausos pašalinimas iš sistemos",
          text: "Jeigu ištrinsite šią registraciją, jos atkurti bus neįmanoma. Tokiu atveju, narys turėtų prisiregistruoti iš naujo arba atvykti į Jūsų būstinę.",
          icon: "warning",
          buttons: true,
          closeModal: true,
          dangerMode: true
        }).then(value => {
          if(value)
          axios.post('/signups/delete', {
            'id': id
          }).then(response => {
            if(response.status == 200) {
              axios.get('/api/signups').then(response => {
                this.API_results = response.data.data;
                this.firstUrl = response.data.first_page_url;
                this.nextUrl = response.data.next_page_url;
                this.prevUrl = response.data.prev_page_url;
                this.lastUrl = response.data.last_page_url;
                this._from = response.data.from;
                this._to = response.data.to;
              });

              axios.get('/api/stats/signups/1').then(response => {
                this.newSignups = response.data;
              });
              swal({title: "Deleted", icon: "success"});
              setTimeout(()=> {swal.close()}, 1000);
            }
            else swal("Error!","" ,"error");
          });
        });
      }
  	},
    mounted() {

      axios.get('/api/signups').then(response => {
        this.API_results = response.data.data;
        this.firstUrl = response.data.first_page_url;
        this.nextUrl = response.data.next_page_url;
        this.prevUrl = response.data.prev_page_url;
        this.lastUrl = response.data.last_page_url;
        this._from = response.data.from;
        this._to = response.data.to;
      });

      axios.get('/api/stats/signups/1').then(response => {
        this.newSignups = response.data;
      });
    },
  }
</script>

<style scoped>
.link {
  cursor: pointer;
}
.mport {
	color: white;
	stroke: white;
}

.mport th, tr{
  color: white;
  stroke: white;
}

th, tr {
  color: inherit;
}

h4 {
  color: #282828;
}

.page-link {
  cursor: pointer;
}
</style>
