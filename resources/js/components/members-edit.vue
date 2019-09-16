<template>
<div>
  <div class="page-header mb-4">
      <div class="description">
        <h3>Redaguoti nari</h3>
        <h1>{{fullName}}</h1>
        <span class="tag tag-green" v-if="balance > 0">Apmokėta</span>
        <span class="tag tag-red" v-if="balance == 0">Neapmokėta</span>
        <span class="tag tag-red" v-if="balance < 0">Skola!</span>
      </div>
    </div>

    <div class="page-content justify-content-center" >
    <div class="row">
      <div class="col-md-12 card card-big">
        <div class="card-body">
          <div class="col-md-12">
                          <div class="form-row">
                              <div class="col-md-4">
                                     <router-link to="/members" class="btn btn-danger">
                                        <span>Atgal</span>
                                      </router-link>
                                </div>
                              <div class="col-md-4">
                                  <button @click="pay()" class="btn btn-primary">Naujas mokėjimas</button>
                              </div>
                          </div>
          </div>
        </div>
      </div>
      <div class="col-md-12 card card-big">
         <div class="alert alert-success" v-if="operationStatus">
          <h2 style="text-transform: uppercase;color: #fff;font-weight: bolder;">Operacija sekmingai atlikta!</h2>
          <h4>Dziaugemes us Jus!</h4>
        </div>
        <div class="alert alert-danger" v-if="serverError">
          <h2 style="text-transform: uppercase;color: #fff;font-weight: bolder;">Serverio klaida</h2>
          <h4>Pabandykite atlikti si veiksma po keletos minuciu ir jei tai nepadeda susisiekite su techninio aptarnavimo personalu</h4>
          <h5 style="color: #fff;">Klaidos tekstas: {{serverErrorMessage}} </h5>
        </div>
        <div class="alert alert-danger" v-if="alreadyExcists">
          <h2 style="text-transform: uppercase;color: #fff;font-weight: bolder;">Toks narys jau egzistuoja</h2>
          <h4>Pasinaudokite paieska ir suraskite ji</h4>
          <h5 style="color: #fff;">Klaidos tekstas: {{serverErrorMessage}} </h5>
        </div>
         <div class="alert alert-warning" v-if="validationFailed">
          <h2 style="text-transform: uppercase;color: #fff;font-weight: bolder;">Neuzpildyti visi butini laukeliai</h2>
          <h4>Dar karta perziurekite raudonai pazymetus laukelius</h4>
        </div>
        <div class="card-header">
          <h2>Pagrindinė informacija</h2>
        </div>
        <div class="card-body">
          <div class="col-md-12">
                          <div class="form-row">
                            <div class="form-group col-md-6">
                                  <div class="description">
                                    <h3>Asmeninė informacija</h3>
                                  </div>
                            </div>
                          </div>
                          <div class="form-row">
                            <div class="form-group col-md-6">
                              <label for="inputBname">Miestas</label>
                              <select class="form-control form-control-select" v-model="city" v-bind:class="{form_control_danger: city_required}">
                                <option value="Vilnius">Vilnius</option>
                                <option value="Klaipėda">Klaipėda</option>
                              </select>
                              <label class="tag tag-red" v-if="city == null || city == ''">Šis laukelis privalomas</label>
                            </div>
                            <div class="form-group col-md-6">
                              <label for="inputBname">Gimimo data</label>
                             <!--  <input type="date" class="form-control" id="inputBname" placeholder="" v-model="birthDate" v-bind:class="{form_control_danger: birthDate_required}"> -->
                              <date-picker v-model="birthDate" :config="options"></date-picker>
                              <label class="tag tag-red" v-if="birthDate == ''">Šis laukelis privalomas</label>
                            </div>
                          </div>
                          <div class="form-row">
                            <div class="form-group col-md-6">
                                  <div class="description">
                                    <h3>Kontaktinė informacija</h3>
                                  </div>
                            </div>
                          </div>
                          <div class="form-row">
                              <div class="form-group col-md-4">
                                  <label for="inputBname">Telefono numeris</label>
                                  <input type="tel" class="form-control" id="inputBname" placeholder="+3706xxxxx" v-model="primaryPhone" v-bind:class="{form_control_danger: primaryPhone_required}">
                                  <label class="tag tag-red" v-if="primaryPhone == ''">Šis laukelis privalomas</label>
                              </div>
                              <div class="form-group col-md-4">
                                  <label for="inputBname">Antras telefono numeris</label>
                                  <input type="tel" class="form-control" id="inputBname" placeholder="+3706xxxxx" v-model="secondaryPhone" v-bind:class="{form_control_danger: secondaryPhone_required}">
                              </div>
                              <div class="form-group col-md-8">
                                  <label for="inputBname">El. pasto adresas</label>
                                  <input type="text" class="form-control" id="inputBname" placeholder="hello@sfinx.lt" v-model="email">
                              </div>
                              <div class="form-group col-md-5">
                                  <label for="inputBname">Facebook vartotojas</label>
                                  <input type="text" class="form-control" id="inputBname" placeholder="hello@sfinx.lt" v-model="facebook">
                              </div>
                              <div class="form-group col-md-4">
                                  <label for="inputBname">Instagram vartotojas</label>
                                  <input type="text" class="form-control" id="inputBname" placeholder="hello@sfinx.lt" v-model="instagram">
                              </div>
                          </div>
                           <div class="form-row mt-5 mb-0">
                                <div class="form-group col-md-6">
                                      <div class="description">
                                        <h3>Asmeninė informacija</h3>
                                      </div>
                                </div>
                                 <div class="form-group col-md-8">
                                    <label>RFID kortelė/apyrankė</label>
                                    <input class="form-control form-control-warning" type="number" placeholder="Nuskaitykite RFID" v-model="rfid_id" v-bind:class="{form_control_danger: rfid_id_required}"/>
                                    <label class="tag tag-red" v-if="rfid_id == ''">Šis laukelis privalomas</label>
                                </div>
                              <div class="form-group col-md-8">
                                  <label for="inputBname">Pastabos, komentarai</label>
                                  <textarea type="text" class="form-control" id="inputBname" placeholder="----APRASYMA RASYKITE CIA----" v-model="description"></textarea>
                              </div>
                        </div>
                            <div class="form-row mt-5 mb-0">
                                 <div class="form-group col-md-6">
                                       <div class="description">
                                         <h3>Mokejimų informacija</h3>
                                         <label class="bg-label bg-label-success" v-if="balance > 0">Geras mokumas</label>
                                         <label class="bg-label bg-label-warning" v-if="balance > 35">Permoka</label>
                                         <label class="bg-label bg-label-danger" v-if="balance == 0">Nesumoketa uz si menesi</label>
                                         <label class="bg-label bg-label-danger" v-if="balance < 0">Yra skoloje</label>
                                       </div>
                                 </div>
                             </div>

                             <div class="form-row">
                               <div class="form-group col-md-6">
                                   <label for="inputBname">Mėnesinis mokęstis</label>
                                   <input class="form-control" placeholder="" v-model="fee"/>
                               </div>
                               <div class="form-group col-md-6">
                                   <label for="inputBname">Dabartinis balansas</label>
                                   <input class="form-control" placeholder="" v-model="balance" style="border: none !important;" disabled/>
                               </div>
                             </div>

                          <a href="#" class="btn btn-primary" v-on:click="memberCreate">Atnaujinti informaciją</a>
          </div>
        </div>
      </div>
      <div class="col-md- card">
         <div class="alert alert-success" v-if="operationStatus">
          <h2 style="text-transform: uppercase;color: #fff;font-weight: bolder;">Operacija sekmingai atlikta!</h2>
          <h4>Dziaugemes us Jus!</h4>
        </div>
        <div class="alert alert-danger" v-if="serverError">
          <h2 style="text-transform: uppercase;color: #fff;font-weight: bolder;">Serverio klaida</h2>
          <h4>Pabandykite atlikti si veiksma po keletos minuciu ir jei tai nepadeda susisiekite su techninio aptarnavimo personalu</h4>
          <h5 style="color: #fff;">Klaidos tekstas: {{serverErrorMessage}} </h5>
        </div>
        <div class="alert alert-danger" v-if="alreadyExcists">
          <h2 style="text-transform: uppercase;color: #fff;font-weight: bolder;">Toks narys jau egzistuoja</h2>
          <h4>Pasinaudokite paieska ir suraskite ji</h4>
          <h5 style="color: #fff;">Klaidos tekstas: {{serverErrorMessage}} </h5>
        </div>
         <div class="alert alert-warning" v-if="validationFailed">
          <h2 style="text-transform: uppercase;color: #fff;font-weight: bolder;">Neuzpildyti visi butini laukeliai</h2>
          <h4>Dar karta perziurekite raudonai pazymetus laukelius</h4>
        </div>
        <div class="card-header">
          <h2>Mokejimu istorija</h2>
        </div>

        <!-- <div class="card-body">
          <div class="col-md-12">


          </div>
        </div> -->
      </div>
      </div>
    </div>
  </div>
</template>

<script>
  export default {
    data(){
      return{
        MemberID: this.$route.params.id,
        operationStatus: false,
        serverError: null,
        serverErrorMessage: null,
        validationFailed: null,
        alreadyExcists: false,
        fullName: null,
        fullName_required: false,
        birthDate: null,
        birthDate_required: false,
        options: {
          format: 'YYYY-MM-DD',
          useCurrent: true,
        },
        primaryPhone: null,
        primaryPhone_required: false,
        secondaryPhone: null,
        secondaryPhone_required: false,
        email: null,
        email_required: false,
        instagram: null,
        instagram_required: false,
        facebook: null,
        facebook_required: false,
        description: null,
        description_required: false,
        city: null,
        city_required: false,
        balance: null,
        fee: null,
        API_results: null,
        rfid_id: null,
        rfid_id_required: null
      }
    },
    mounted() {
      axios.get('/api/members/'+this.MemberID).then(response => {
        this.API_results = response.data.member;
        this.fullName = this.API_results.firstName + " " + this.API_results.lastName;
        this.birthDate = this.API_results.birthDate;
        this.primaryPhone = this.API_results.primaryPhone;
        this.secondaryPhone = this.API_results.secondaryPhone;
        this.email = this.API_results.email;
        this.instagram = this.API_results.instagram;
        this.facebook = this.API_results.facebook;
        this.description = this.API_results.description;
        this.city = this.API_results.city;
        this.balance = this.API_results.balance;
        this.fee = this.API_results.fee;
        this.balance = response.data.balance;
        this.rfid_id = this.API_results.rfid_id;
      });
    },
    methods: {
      memberCreate: function(){
        var verificationStatus = false;
        if(this.fullName == '' || this.fullName == null) { this.fullName_required = true; verificationStatus = false }
        if(this.primaryPhone == '' || this.primaryPhone == null) { this.primaryPhone_required = true;verificationStatus = false }
        if(this.birthDate == '' || this.birthDate == null) { this.birthDate_required = true; verificationStatus = false }
        if(this.city == '' || this.city == null) { this.city_required = true; verificationStatus = false }
        if(this.rfid_id == '' || this.rfid_id == null) { this.rfid_id_required = true; verificationStatus = false }

        var fullFullName = this.fullName.split(" ");
        axios.post('/api/members/update', {
          id: this.$route.params.id,
          firstName: fullFullName[0],
          lastName: fullFullName[1],
          primaryPhone: this.primaryPhone,
          secondaryPhone: this.secondaryPhone,
          email: this.email,
          instagram: this.instagram,
          facebook: this.facebook,
          fee: this.fee,

          birthDate: this.birthDate,
          description: this.description,
          city: this.city,
          rfid_id: this.rfid_id
        }).then(response => {
          if (response.data.status == 'OK')
          {
            this.operationStatus = true;
            setTimeout(this.$router.push('/members'), 3000);
          }else{
            if(response.data.cause == 1){
              this.validationFailed = true;
            }else{
              this.serverError = true;
              this.serverErrorMessage = response.data;
            }

            if(response.data.cause == 3){this.alreadyExcists = true}
          }
        });
      },
      pay() {
        this.$parent.newPayment(this.MemberID, this.API_results);
      }
    }
  }
</script>

<style>
</style>
