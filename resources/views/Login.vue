<template>
    <div class="container">
        <div class="card">
            Hello!
            <div class="card-header">Login</div>
            <div class="card-body">
                <form method="POST" action="../views/Login.vue">
                    <div class="form-control">
                        <label for="email">Email:</label>
                        <input type="email" class="form-control" id="email" v-model="email">
                    </div>
                    <div class="form-control">
                        <label for="password">Password:</label>
                        <input type="password" class="form-control" id="password" v-model="password">
                    </div>
                    <button type="submit" class="btn btn-primary" @click="handleSubmit($event)">Login</button>
                </form>
            </div>
        </div>
    </div>
</template>
<script>
    export default{
        data(){
            return{
            email:"",
            password:""
        }
        },
        methods:{
            handleSubmit(e){
                console.log("Inside");
                e.preventDefault();
                if( this.password.length>0)
                {
                    axios.post('http://127.0.0.1:8000/api/login',{
                        email:this.email,
                        password:this.password
                    })
                    .then(response =>{
                    localStorage.setItem('user',response.data.success.name);
                    localStorage.setItem('jwt', response.data.success.token);
                    console.log("Hello");
                        if(localStorage.getItem('jwt')!=null) {
                            let val=true;

                            this.$store.commit('change');
                            console.log(this.$store.getters.isLoggedIn);

                            this.$router.push('/board');

                        }
                    })
                    .catch(error=> {
                        console.error(error)
                    });
                }
            }
        },
        beforeRouteEnter(to,from,next){
            if(localStorage.getItem('jwt'))
             {
                return next('board');
                }
            next();
        }

    }
</script>
