<template>
<div>
  <div class="page-header mb-1">
    <div class="description">
      <h3>Lankomumas</h3>
      <h1>Treniruotės</h1>
    </div>
  </div>
  <div class="page-content justify-content-center mt-4">
    <div class="pl-3 row">
      <div class="card col-md-3">
        <div class="card-body text-center" title="SVARBU! Neįvykusios treniruotės (turinčios raudonus žymėjimus ir brūkšnelius vietoje laiko) lankomumas neskaičiuojamas ir prie studijos lankomumo nepridedamas.">
          <div class="h6">Studijos lankomumas</div>
          <h1 class="font-weight-bold mb-4">{{attend}}%</h1>
          <div class="progress progress-sm">
            <div class="progress-bar bg-yellow" :style="{width: attend + '%'}"></div>
          </div>
        </div>
      </div>
      <div class="card col-md-3" ref="nonActive" style="transition: all 0.5s ease;">
        <div class="card-body text-center">
          <div class="h6">Neaktyvūs nariai</div>
          <h1 class="font-weight-bold mb-4">{{nonActive.first}}/{{nonActive.second}}</h1>
          <div class="progress progress-sm">
            <div class="progress-bar bg-red" :style="{width: (nonActive.first/nonActive.second*100)+'%'}"></div>
          </div>
        </div>
      </div>
      <!-- <div class="card col-md-3">
        <div class="card-body text-center">
            <div class="h6">Narys laikomas neaktyviu, jei paskutinį kartą lankėsi prieš:  </div>
            <div class="input-group text-center">
              <input type="number" class="form-control col-md-5" v-model="inactive" @change="grey">

              <div class="input-group-append text-center">
                <span class="input-group-text h3">dienų</span>
              </div>
          </div>
        </div>
      </div> -->
    </div>
    <div class="card big mt-5">
      <div class="card-header">
        Įvykusios treniruotės  | {{page}}
      </div>
      <div class="card-body">
        <div class="table-responsisve">
          <table class="table card-table table-vcenter text-nowrap datatable dataTable no-footer" id="DataTables_Table_0" role="grid" aria-describedby="DataTables_Table_0_info">
            <thead>
              <tr role="row">
                <th class="sorting" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1" aria-label="VAT No.: activate to sort column ascending" style="width: 200px;">Treniruotė</th>
                <th class="sorting" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1" aria-label="Invoice Subject: activate to sort column ascending" style="width: 100px;">Data</th>
                <th class="sorting" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1" aria-label="Invoice Subject: activate to sort column ascending" style="width: 150px;">Laikas</th>
                <th class="sorting" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1" aria-label="Status: activate to sort column ascending" style="width: 200px;">Apsilankė %</th>
                <th class="sorting" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1" aria-label="Status: activate to sort column ascending" style="width: 200px;">Neapsilankė narių</th>
                <th class="sorting" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1" aria-label="Status: activate to sort column ascending" style="width: 150px;"> </th>
              </tr>
            </thead>
            <tbody>
              <tr role="row" class="odd" :class="{'td-active': training.today}" v-for="training in API_results">
                <td>
                  <div>
                    {{training.group.groupName}}
                  </div>
                  <div class="small text-muted">
                    {{training.group.leader}}
                  </div>
                </td>
                <td>
                  <b v-if="training.today">{{(training.created_at.split(' '))[0]}}</b>
                  <span v-else> {{(training.created_at.split(' '))[0]}}</span>
                </td>
                <td>
                  <div>
                    <span class="tag tag-grey mr-1">{{training.start}}</span>
                    <span class="text-muted">-</span>
                    <span class="tag tag-grey ml-1">{{training.end}}</span>
                  </div>
                </td>
                <td>
                  <div class="clearfix">
                    <div class="float-left">
                      <strong>{{training.was}}%</strong>
                    </div>
                    <div class="float-right">
                      <small class="text-muted">nuo {{training.start}} iki 00:00</small>
                    </div>
                  </div>
                  <div class="progress progress-xs">
                    <div v-if="parseFloat(training.was) > 60" class="progress-bar bg-green" role="progressbar" v-bind:style="{width: parseFloat(training.was) + '%' }" aria-valuenow="62" aria-valuemin="0" aria-valuemax="100"></div>
                    <div v-if="parseFloat(training.was) >= 30 && parseFloat(training.was) <= 60" class="progress-bar bg-yellow" role="progressbar" v-bind:style="{width: parseFloat(training.was) + '%' }" aria-valuenow="62" aria-valuemin="0"
                      aria-valuemax="100"></div>
                    <div v-if="parseFloat(training.was) < 30" class="progress-bar bg-red" role="progressbar" v-bind:style="{width: parseFloat(training.was) + '%' }" aria-valuenow="62" aria-valuemin="0" aria-valuemax="100"></div>
                  </div>
                </td>

                <td>
                  <div class="clearfix">
                    <div class="text-center">
                      <strong class="h1" :class="isRed(training.not_in_p)"> {{training.not}}</strong>&nbsp;&nbsp;&nbsp;
                      <strong class="h4"> ({{training.not_in_p}}%)</strong>
                    </div>
                    <!-- <div class="float-right">
                                <small class="text-muted">tai yra {{training.not_in_p}}% visų grupės narių</small>
                              </div> -->
                  </div>
                  <!-- <div class="progress progress-xs">
                              <div v-if="parseFloat(training.not_in_p) >= 80" class="progress-bar bg-red" role="progressbar" v-bind:style="{width: parseFloat(training.not_in_p) + '%' }" aria-valuenow="62" aria-valuemin="0" aria-valuemax="100"></div>
                              <div v-if="parseFloat(training.not_in_p) > 40 && parseFloat(training.not_in_p) < 80" class="progress-bar bg-yellow" role="progressbar" v-bind:style="{width: parseFloat(training.not_in_p) + '%' }" aria-valuenow="62" aria-valuemin="0" aria-valuemax="100"></div>
                              <div v-if="parseFloat(training.not_in_p) <= 40" class="progress-bar bg-green" role="progressbar" v-bind:style="{width: parseFloat(training.not_in_p) + '%' }" aria-valuenow="62" aria-valuemin="0" aria-valuemax="100"></div>
                            </div> -->
                </td>

                <td class="text-center">
                  <div class="item-action dropdown">
                    <a @click="showTrainingDetails(training.id, training.was);" data-toggle="dropdown" class="icon"><i class="icon fe fe-activity mr-3"></i> Plačiau</a>
                  </div>
                </td>
              </tr>

            </tbody>
          </table>
        </div>
        <!-- <div class="alert alert-primary">
          Atsiprašome, šiuo metu veiksmų centras yra tik kūrimo stadijoje
        </div> -->
      </div>

      <div class="flex-inline justify-content-center">
        <ul class="pagination">
          <li class="page-item"><a @click="topage(1)" class="page-link">First</a></li>
          <li class="page-item"><a @click="topage(page-1)" class="page-link">Previous</a></li>
          <li class="page-item"><a @click="topage(page+1)" class="page-link">Next</a></li>
        </ul>
      </div>

    </div>
  </div>
</div>
</div>
</template>

<script>
export default {
  data() {
    return {
      API_results: [],
      attend: 0,
      nonActive: [],
      inactive: 0,
      page: 1,
    }
  },
  methods: {
    showTrainingDetails(id, proc_rate) {
      this.$router.push('trainings/' + id + "/" + proc_rate);
    },
    grey() {
      this.$refs["nonActive"].style.background = "grey";
    },
    isRed(val) {
      return (parseFloat(val) > 70 ? "red" : "");
    },
    topage(url) {
      if (url <= 0) {
        this.page = 1;
        url = 1;
      } else this.page = url;
      axios.post("/api/trainings/all", {
        "page": url
      }).then(response => {
        this.API_results = response.data.trainings;
        this.attend = response.data.attend;
        this.nonActive = response.data.nonActive;
        this.inactive = response.data.inactive;
      });
    }
  },
  mounted() {
    axios.post('api/trainings/all', {
      "page": this.page
    }).then(response => {
      this.API_results = response.data.trainings;
      this.attend = response.data.attend;
      this.nonActive = response.data.nonActive;
      this.inactive = response.data.inactive;

    });

  },
  watch: {
    inactive: function(val, old) {
      if (parseInt(val) < 1 || val == "") {
        this.inactive = 1;
        return;
      }
      axios.post('/api/inactive', {
        val: val
      });

    }
  }
}
</script>

<style scoped>
.td-active {
  border-left: solid 0.3rem #316CBE
}

.red {
  color: red;
}

.page-link {
  cursor: pointer;
}
</style>
