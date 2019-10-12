<template>
  <div>
	<div class="page-header mb-1">
      <div class="description">
        <h3>Praėjimo kontrolė</h3>
        <h1></h1>
      </div>
  </div>
  <div class="page-content justify-content-center mt-4">
    <div class="card big mt-5">
      <div class="card-header">
        Naujausi užfiksuoti apsilankymai
      </div>
      <div class="card-body">
       <div class="table-responsive">
                    <table class="table card-table table-vcenter text-nowrap datatable dataTable no-footer" id="DataTables_Table_0" role="grid" aria-describedby="DataTables_Table_0_info">
                      <thead>
                        <tr role="row">
                          <th class="w-1 sorting_asc" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1" aria-sort="ascending" aria-label="No.: activate to sort column descending" style="width: 45px;">Unik. Nr.</th>
                          <th class="sorting" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1" aria-label="Invoice Subject: activate to sort column ascending" style="width: 171px;">Vardas, pavardė</th>
                          <th class="sorting" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1" aria-label="Client: activate to sort column ascending" style="width: 130px;">Grupė</th>
                          <th class="sorting" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1" aria-label="VAT No.: activate to sort column ascending" style="width: 81px;">Data ir laikas</th>
                          <th class="sorting" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1" aria-label="Status: activate to sort column ascending" style="width: 200px;">Mokumas</th>
                          <th class="sorting" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1" aria-label="Status: activate to sort column ascending" style="width: 146px;">Būsena</th>
                        </tr>
                      </thead>
                      <tbody>
                      <tr role="row" class="odd" v-for="result in API_results">
                          <td class="sorting_1"><span class="text-muted">{{result.id}}</span></td>
                          <td><a href="invoice.html" class="text-inherit">{{result.firstName}} {{result.lastName}}</a></td>
                          <td>
                            {{result.group}}
                          </td>
                          <td>
                            {{result.created_at}}
                          </td>
                          <td>
                            <div v-if="result.balance > 0">
                              <span class="status-icon bg-success"></span> Apmoketa
                            </div>
                            <div v-if="result.balance <= 0">
                              <span class="status-icon bg-danger"></span> Neapmoketa
                            </div>
                          </td>
                          <td>
                            <span class="status-icon bg-success"></span> Užfiksuotas sėkmingai
                          </td>
                        </tr>
                      </tbody>
                    </table>
        </div>
        <!-- <div class="alert alert-primary">
          Atsiprašome, šiuo metu veiksmų centras yra tik kūrimo stadijoje
        </div> -->
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
  		}
  	},
    mounted() {
      axios.get('api/entries/all').then(response => {
        this.API_results = response.data.entries;
      });

    }
  }
</script>

<style>
</style>
