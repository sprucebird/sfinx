<template>
  <div>
  <div class="page-header mb-1">
      <div class="description">
        <h3>Sistema</h3>
        <h1>Nustatymai ir informacija</h1>
      </div>
  </div>
  <div class="page-content justify-content-center mt-4">
    <div class="row">
      <router-link to="/entries" class="col-md-4">
        <div class="card">
          <div class="card-body text-center">
              <div class="h4">Praėjimo kontrolės įrašai</div>
              <div class="h6" style="opacity: .6; font-weight: lighter;">
                Kekvieną kartą, kada Jūsų narys pasižymės savo RFID įrenginį (kortelę, apyrankę ar telefoną) jo apsilankymas bus užfiksuotas čia.
              </div>
          </div>
        </div>
      </router-link>
      <a href="#" class="col-md-4">
        <div class="card">
          <div class="card-body text-center">
              <div class="h4">Mokėjimų įrašai ir ataskaitos</div>
              <div class="h6" style="opacity: .6; font-weight: lighter;">
                Kekvieną kartą, kada sistemoje bus užfiksuotas naujas mokėjimas, jis bus įrašytas čia bei bus sukurta nauja mokėjimų ataskaita.
              </div>
          </div>
        </div>
      </a>
      <a href="#" class="col-md-4">
        <div class="card">
          <div class="card-body text-center">
              <div class="h4">Darbuotojai ir personalas</div>
              <div class="h6" style="opacity: .6; font-weight: lighter;">
                Čia galite peržiūrėti savo darbuotojus bei aptarnaujantį personalą, jų duomenis ir statistiką.
              </div>
          </div>
        </div>
      </a>
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
      axios.get('/api/users').then(response => {
        this.API_results = response.data;
      });

    }
  }
</script>

<style>
</style>
