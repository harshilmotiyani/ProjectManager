<template>
    <div>
      <div class="container">
            <router-link :to="{name: 'home'}" class="navbar-brand">Traum</router-link>
            <router-link :to="{name: 'login'}"  v-if="!$store.getters.isLoggedIn" class="navbar-brand">login</router-link>

          <router-link :to="{name: 'register'}" v-if="!$store.getters.isLoggedIn" class="navbar-brand">register</router-link>
          <router-link :to="{name: 'board'}" v-if="$store.getters.isLoggedIn" class="navbar-brand">board</router-link>
         <button v-if="$store.getters.isLoggedIn" @click="logout" >Logout</button>
<div class="container">


</div>

      </div>



        <router-view></router-view>
    </div>
</template>
<script>
    export default {
        data() {

        },
        methods: {
            logout(e) {
                if (confirm("Are you sure you want to log out?")) {
                    this.$store.commit('change');

                    axios.get('api/logout').then(response => {
                        localStorage.removeItem('jwt');
                        localStorage.removeItem('user');



                        delete axios.defaults.headers.common['Authorization'];

                        this.$router.push('/login');
                    })
                        .catch(error => {

                            localStorage.removeItem('user');
                            localStorage.removeItem('jwt');
                            delete axios.defaults.headers.common['Authorization'];
                            this.$router.push('/login');
                        });
                }
            },

        }
    }
</script>
