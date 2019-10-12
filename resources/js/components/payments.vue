<template>
  <div>
	<div class="page-header mb-4">
      <div class="description">
        <h3>Nariai</h3>
        <h1>Mokėjimai ir finansai</h1>
      </div>
      <!-- <div class="ml-5 stats">
        <div class="stat mr-5">
          <span class="mt-1 status status-ok"></span>
          <h1 class="number mt-3 ml-2">657 Eur</h1>
          <div class="txt mt-2 ml-2">
            <h2>Daugiau pajamų šį mėnesį</h2>
            <h3>Nuo 2039-02-02</h3>
          </div>
        </div>

        <div class="stat">
          <span class="mt-1 status status-danger"></span>
          <h1 class="number mt-3 ml-2">43%</h1>
          <div class="txt mt-2 ml-2">
            <h2>Mažesnis mokumas procentais</h2>
            <h3>Lyginant su 2039-02-02</h3>
          </div>
        </div>
      </div> -->
    </div>


    <div class="page-content justify-content-center">

      <div class="card big col-md-12">
        <div class="card-header">
          Visi atlikti mokėjimai
        </div>
        <div class="table-responsive">
                     <table class="table card-table table-vcenter text-nowrap datatable dataTable no-footer" id="DataTables_Table_0" role="grid" aria-describedby="DataTables_Table_0_info">
                       <thead>
                         <tr role="row">
                           <th class="w-1 sorting_asc" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1" aria-sort="ascending" aria-label="No.: activate to sort column descending" style="width: 45px;">Unikalus mokėjimo nr.</th>
                           <th class="sorting" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1" aria-label="Invoice Subject: activate to sort column ascending" style="width: 171px;">Vardas, pavardė</th>
                           <th class="sorting" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1" aria-label="VAT No.: activate to sort column ascending" style="width: 81px;">Data ir laikas</th>
                           <th class="sorting" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1" aria-label="Status: activate to sort column ascending" style="width: 200px;">Suma</th>
                           <th class="sorting" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1" aria-label="Status: activate to sort column ascending" style="width: 146px;">Sumokėta už laikotarpį</th>
                         </tr>
                       </thead>
                       <tbody>
                       <tr role="row" class="odd" v-for="result in API_results">
                           <td class="sorting_1"><span class="text-muted">{{result.id}}</span></td>
                           <td><a href="invoice.html" class="text-inherit">{{result.firstName}} {{result.lastName}}</a></td>

                           <td>
                             {{result.created_at}}
                           </td>
                           <td>
                             {{result.price}} eurų
                           </td>
                           <td>
                             <span v-if="result.for_month == 1">Sausis</span>
                             <span v-if="result.for_month == 2">Vasaris</span>
                             <span v-if="result.for_month == 3">Kovas</span>
                             <span v-if="result.for_month == 4">Balandis</span>
                             <span v-if="result.for_month == 5">Gegužė</span>
                             <span v-if="result.for_month == 6">Birželis</span>
                             <span v-if="result.for_month == 7">Liepa</span>
                             <span v-if="result.for_month == 8">Rugpjūtis</span>
                             <span v-if="result.for_month == 9">Rugsėjis</span>
                             <span v-if="result.for_month == 10">Spalis</span>
                             <span v-if="result.for_month == 11">Lapkritis</span>
                             <span v-if="result.for_month == 12">Gruodis</span>
                             <span v-if="result.for_month == null">Rugsėjis</span>
                           </td>
                         </tr>
                       </tbody>
                     </table>
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
        desptorResults: [],
        monthlyPayments: [],

          options: {
              chart: {
                id: 'vuechart-example'
              },
              xaxis: {
                categories: ['Rugsejis', 'Spalis', 'Lapkritis', 'Gruodis', 'Sausis', 'Kovas', 'Balandis', 'Geguze', 'Birzelis', 'Liepa', 'Rugjputis']
              }
            },
            series: [{
              name: 'series-1',
              data: null,
            }],
          }
  	},
    mounted() {

      axios.get('/api/payments').then(response => {
          this.API_results = response.data;
        });

      // axios.get('/api/payments/deptors').then(response => {
      //     this.desptorResults = response.data;
      //   });
      axios.get('/api/stats/payments').then(response =>{
        this.series[0].data = response.data.payments;
      });
    }
  }
</script>

<style>
</style>
