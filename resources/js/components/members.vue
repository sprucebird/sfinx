<template>
  <div>
  <div class="page-header mb-4">
      <div class="description">
        <h3>Nariai</h3>
        <h1>Visi nariai</h1>
      </div>
      <div class="ml-5 stats">
        <div class="stat mr-5">
          <span class="mt-1 status status-ok"></span>
          <h1 class="number mt-3 ml-2">{{membersCount}}</h1>
          <div class="txt mt-2 ml-2">
            <h2>Iš viso narių</h2>
            <h3>registruota sistemoje</h3>
          </div>
        </div>

        <div class="stat">
          <div class="actions" style="float: right;">
            <router-link to="/members/add" class="btn btn-primary btn-small">
              <span data-feather="x-circle" class="icon"></span>
              <span>Pridėti naują narį</span>
            </router-link>
          </div>
        </div>
      </div>
    </div>

    <div class="mt-5 mb-5 filter">
        <div class="justify-content-center">
          <div class="row">
            <div class="col">
              <label class="label">Grupė</label>
              <select v-model="filterGroup" class="form-control white" name="group" @change="filterTable()">
                <option value="0">Visa studija</option>
                  <option v-for="group in groups" :value="group.id">{{group.groupName}}</option>
              </select>
            </div>

             <div class="col">
              <label class="label">Miestas</label>
              <select v-model="filterCity" class="form-control white" name="group" @change="filterTable()">
                  <option value="0">Visi</option>
                  <option value="klaipeda">Klaipeda</option>
                  <option value="vilnius">Vilnius</option>
              </select>
            </div>
          </div>
        </div>
    </div>
    <div class="page-content justify-content-center">
            <div class="card big">
                <div class="card-header flex-s">
                  <h2 class="vertical-align">Nariai</h2>
                </div>

                <div class="card-body">
                  <div class="alert alert-warning" v-if="API_results.length == null || API_results == '' || API_results.length == 0">
                    <span>Nerasta</span>
                  </div>


                    <table class="table table-hover table-outline table-vcenter text-nowrap card-table" v-if="API_results.length != 0">
                        <thead>
                            <tr>
                                <th>Vardas, pavardė</th>
                                <th>Miestas</th>
                                <th>Gimimo data</th>
                                <th>Telefono numeris</th>
                                <th>Grupė</th>
                                <th>Mokėjimai</th>
                                <th>Apsilankymai</th>
                                <th>Pastabos</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                                <tr v-for="result in API_results">
                                    <td>
                                      <div>{{result.firstName}} {{result.lastName}}</div>
                                      <div class="small text-muted">Narys nuo {{result.created_at}}</div>
                                    </td>
                                    <td> {{result.city}} </td>
                                    <td> {{result.birthDate}} </td>
                                    <td> {{result.primaryPhone}} </td>
                                    <td>
                                      <label class="bg-label bg-label-warning" v-if="result.groupName == null || result.groupName == ''" data-toggle="dropdown">Nepriskirtas(-a)</label>
                                      <label class="bg-label bg-label-main" v-if="result.groupName != null" data-toggle="dropdown">{{result.groupName}}</label>
                                      <div class="dropdown-menu">
                                        <div v-for="group in groups">
                                          <a class="dropdown-item" @click="changeMembersGroup(group.id, result.id)">{{group.groupName}}</a>
                                        </div>

                                      </div>
                                    </td>
                                    <td>
                                      <label class="bg-label bg-label-success" v-if="result.balance > 0">Apmokėta</label>
                                      <label class="bg-label bg-label-danger" v-if="result.balance <= 0">Neapmokėta</label>
                                    </td>
                                    <td>
                                      <label class="bg-label bg-label-main">{{result.entries}}</label>
                                    </td>
                                    <td>
                                      <label class="bg-label bg-label-primary" v-if="result.description != null">Yra</label>
                                    </td>
                                    <td>
                                        <div class="item-action dropdown">
                                          <a href="javascript:void(0)" data-toggle="dropdown" class="icon icon-table" aria-expanded="false"><i class="fe fe-more-vertical"></i></a>
                                          <div class="dropdown-menu dropdown-menu-right" x-placement="bottom-end" style="position: absolute; transform: translate3d(15px, 20px, 0px); top: 0px; left: 0px; will-change: transform;">
                                            <a href="javascript:void(0)" @click="showEditDialog(result.id)" class="dropdown-item"><i class="dropdown-icon fe fe-edit-2"></i> Redaguoti narį </a>
                                            <a href="javascript:void(0)" @click="deleteMember(result.id)" class="dropdown-item"><i class="dropdown-icon fe fe-trash"></i> Pašalinti iš sistemos</a>
                                            <div class="dropdown-divider"></div>
                                            <a href="javascript:void(0)" @click="pay(result.id, result)" class="dropdown-item"><i class="dropdown-icon fe fe-credit-card"></i> Naujas mokėjimas</a>
                                          </div>
                                        </div>
                                    </td>
                                </tr>
                        </tbody>
                    </table>
                    <div class="alert alert-warning" v-if="API_results == null">
                    </div>
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
        groups: [],
        membersCount: null,
        operationState: null,
        filterCity: 0,
        filterGroup: 0,
      }
    },
    methods: {
      /*
      Following bugs detected:
      1. All studio option is not working correctly
      2. Filters can't work together
      */
      showEditDialog(id){
        this.$router.push('/members/edit/'+id);
      },

      changeMembersGroup(id, member){
        axios.post('/api/members/changeGroup/' + member, {
          groupID: id,
        }).then(response => {
          if(response.data.status == 'OK'){
            this.operationState = 1;
            axios.get('/api/members').then(response => {
              this.API_results = response.data;
              this.membersCount = response.data.length;
            });
          }
        });
      },

      filterTable(){
        var config = {
            group: this.filterGroup,
            city: this.filterCity
        }

        var reqURL = "api/members/filter";
         axios.post(reqURL, config).then(response => {
           this.API_results = response.data;
         });

      },
      deleteMember(id) {
        swal({
          title: "Please confirm DANCER deletion",
          text: "You want to delete a dancer!",
          icon: "warning",
          buttons: true,
          closeModal: true,
          dangerMode: true
        }).then(value => {
          if(value)
          axios.post('/members/delete', {
            'id': id
          }).then(response => {
            if(response.status == 200) {
              this.updater();
              this.filterTable();
              swal({title: "Deleted", icon: "success"});
              setTimeout(()=> {swal.close()}, 1000);
            }
            else swal("Error!","" ,"error");
          });
        });
      },
      updater() {
        axios.get('/api/members').then(response => {
          this.API_results = response.data;
          this.membersCount = response.data.length;
        });
        axios.get('/api/groups').then(response => {
          this.groups = response.data;
        });
      },
      pay(id, member) {
        this.$parent.newPayment(id, member);
        this.updater();
      }
    },
    mounted() {
      this.updater();
    }
  }
</script>

<style scoped>
span, select, label, option {
  cursor: pointer;
}

.dropdown-item {
  cursor: pointer;
}

</style>
