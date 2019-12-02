<template>
<div>
  <div class="page-header mb-1">
    <div class="description">
      <h3>Nustatymai ir informacija</h3>
      <h1>Prėjimo kontrolės įrašai</h1>
      <h4>{{_from}} – {{_to}}</h4>
    </div>

    <div class="card col-2" ref="nonActive" style="margin-left: 4rem">
      <div class="card-body text-center">
        <div class="h6">Šiandien atėję nariai</div>
        <h1 class="font-weight-bold mb-4">{{todayCount}}</h1>
      </div>
    </div>

  </div>
  <div class="page-content justify-content-center mt-4">
    <div class="card big mt-5">
      <div class="card-body">
        <div class="table-responsisve">
          <table class="table card-table table-vcenter text-nowrap datatable dataTable no-footer" id="DataTables_Table_0" role="grid" aria-describedby="DataTables_Table_0_info">
            <thead>
              <tr role="row">
                <th class="sorting" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1" style="width: 200px;">Eil. nr</th>
                <th class="sorting" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1" style="width: 100px;">Narys</th>
                <th class="sorting" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1" style="width: 150px;">Data ir laikas</th>
                <th class="sorting" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1" style="width: 200px;">Grupė</th>
                <th class="sorting" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1" style="width: 200px;">Mokumo būsena</th>
                <th class="sorting" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1" style="width: 150px;"> </th>
              </tr>
            </thead>
            <tbody>
              <tr role="row" class="odd" :class="{'today' : entrie.today}" v-for="entrie in entries" >
                <td>
                  <div>
                    {{entrie.id}}
                  </div>
                </td>
                <td>
                  <div class="bg-label bg-label-danger" v-if="entrie.firstName == null && entrie.lastName == null">
                    Narys nebuvo atpažintas arba neegzistuoja
                  </div>
                  {{entrie.firstName}} {{entrie.lastName}}
                </td>
                <td>
                  {{entrie.created_at}}
                </td>

                <td>
                  {{entrie.group}}
                </td>

                <td class="text-center">
                  <div class="bg-label bg-label-danger" v-if="entrie.balance < 0">Neapmokėta</div>
                  <div class="bg-label bg-label-success" v-if="entrie.balance > 0">Apmokėta</div>
                  <div v-else-if="entrie.balance == 0" class="small text-muted">Yra sumokėta už praeitą mėnesį</div>
                </td>
                <td>

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
          <li class="page-item"><a  @click="topage(firstUrl)" class="page-link">First</a></li>
          <li class="page-item"><a @click="topage(prevUrl)"  class="page-link">Previous</a></li>
          <li class="page-item"><a  @click="topage(nextUrl)" class="page-link">Next</a></li>
          <li class="page-item"><a  @click="topage(lastUrl)" class="page-link">Last</a></li>
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
      entries: [],
      firstUrl: null,
      lastUrl: null,
      nextUrl: null,
      prevUrl: null,
      _from: null,
      _to: null,
      todayCount: 0,
    }
  },
  methods: {
    grey() {
      this.$refs["nonActive"].style.background = "grey";
    },
    topage(url) {
      if(url == null) return;
      axios.get(url).then(response => {
        this.entries = response.data.entries.data;
        this.firstUrl = response.data.entries.first_page_url;
        this.nextUrl = response.data.entries.next_page_url;
        this.prevUrl = response.data.entries.prev_page_url;
        this.lastUrl = response.data.entries.last_page_url;
        this._from = response.data.entries.from;
        this._to = response.data.entries.to;
      });
    },
    isRed(val) {
      return (parseFloat(val) > 70 ? "red" : "");
    }
  },
  mounted() {
    axios.get('api/entries/all').then(response => {
      this.entries = response.data.entries.data;
      this.firstUrl = response.data.entries.first_page_url;
      this.nextUrl = response.data.entries.next_page_url;
      this.prevUrl = response.data.entries.prev_page_url;
      this.lastUrl = response.data.entries.last_page_url;
      this._from = response.data.entries.from;
      this._to = response.data.entries.to;
      this.todayCount = response.data.today;
    });

  },
  watch: {
    //nothing to watch here :(
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
    color: rgb(47, 77, 231);
}

h4 {
  color: #282525;
}

.today {
  border-left: 0.3rem solid #316CBE;
}
</style>
