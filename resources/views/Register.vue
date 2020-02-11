<template>
    <div class="container">
        <div class="card">
            <div class="card-header"> Register</div>
            <div class="card-body">
                <form method="POST" action="/register" >
                    <div class="form-group">
                        <label for="name">Name:</label>
                        <input type="text" class="form-control" id="name" v-model="name" required >
                    </div>
                    <div class="form-group">
                        <label for="email">Email Address:</label>
                        <input type="email" class="form-control" id="email" v-model="email" required>
                    </div>


                    <div class="form-group">
                        <label for="password">Password:</label>
                        <input type="password" class="form-control" id="password" v-model="password">
                    </div>
                    <div class="form-group">
                        <label for="password-confirm">Password:</label>
                        <input type="password" class="form-control" id="password-confirm" v-model="password_confirmation">
                    </div>
                    <button type="submit" class="btn btn-primary" @click="handleSubmit($event)">Register</button>
                </form>
            </div>
        </div>

    </div>
</template>
<script>

    export default{
        data(){
            return {
                name:"",
                email:"",
                password:"",
                password_confirmation:""
            }
        },
        methods:{

            handleSubmit(e){
               console.log("Hello Im here!")
                e.preventDefault()
                if(this.password === this.password_confirmation && this.password.length>0 )
                {
                    axios.post('api/register',{
                        name:this.name,
                        email:this.email,
                        password:this.password,
                        c_password: this.password_confirmation
                    })
                    .then(response => {
                        localStorage.setItem('user',response.data.success.name);
                        localStorage.setItem('jwt',response.data.success.token);
                        if(localStorage.getItem('jwt')!= null)
                        {   this.$store.commit('change');
                            this.$router.push('/board');
                        }
                    })
                        .catch(function(error){
                        console.error(error);
                    });
                }
                else
                {
                    this.password="";
                    this.password_confirmation="";
                    return alert('Passwords do not match');
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
