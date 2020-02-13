<template>
    <div class="container">
        <div class="row justify-content-center">
            <draggable element="div" class="col-md-12" :list="categories" v-model="categories" :options="dragOptions">
                <transition-group class="row">
                    <div class="col-md-4" v-for="(element,index) in categories" :key="element.id" :id="element.id">

                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">{{element.name}}</h4>
                            </div>
                            <div class="card-body card-body-dark">
                                <draggable :options="dragOptions" element="div" @end="changeOrder" v-model="element.tasks" :list="element.tasks">
                                    <transition-group :id="element.id">
                                        <div v-for="task in element.tasks" :key="task.category_id+','+task.id" class="transit-1" :id="task.id" :categoryId="element.id">
                                            <div class="small-card">
                                                <textarea v-if="task === editingTask" class="text-input" @keyup.enter="endEditing(task)" @blur="endEditing(task)" v-model="task.name"></textarea>
                                                <label  v-if="task !== editingTask" @dblclick="editTask(task)">{{ task.name }}</label>
                                                <button @click="DeleteTask(task,index)">Delete</button>
                                            </div>
                                        </div>
                                    </transition-group>
                                </draggable>
                                <div class="small-card">
                                    <h5 class="text-center" @click="addNew(index)">Add new card</h5>
                                </div>
                            </div>
                        </div>
<!--                        <button slot="footer">Add tasks here</button>-->
                    </div>
                </transition-group>

            </draggable>
        </div>
    </div>
</template>

<style scoped>
    .card {
        border:0;
        border-radius: 0.5rem;
    }
    .transit-1 {
        transition: all 1s;
    }
    .small-card {
        padding: 1rem;
        background: #f5f8fa;
        margin-bottom: 5px;
        border-radius: .25rem;
    }
    .card-body-dark{
        background-color: #ccc;
    }
    textarea {
        overflow: visible;
        outline: 1px dashed black;
        border: 0;
        padding: 6px 0 2px 8px;
        width: 100%;
        height: 100%;
        resize: none;
    }
</style>

<script>
    import draggable from 'vuedraggable'
    export default {
        components: {
            draggable
        },
        data(){
            return {
                categories : [],
                editingTask : null
            }
        },
        methods : {
            addNew(id) {
                let user_id = 1;
                let name = "New task";
                let category_id = this.categories[id].id;
                let order = this.categories[id].tasks.length;
                axios.post('api/task', {name, category_id, user_id,order}).then(response => {
                    this.categories[id].tasks.push(response.data.data)
                })
            },
            loadTasks() {
                this.categories.map(category => {
                    axios.get(`api/category/${category.id}/tasks`).then(response => {
                        category.tasks = response.data
                    })
                })
            },
            changeOrder(data) {
                console.log("Inside changeOrder")
                console.log('Data: ', data)
                console.log('Data to: ', data.to)
                let toTask = data.to;
                let fromTask = data.from;
                let task_id = data.item.id;
                // console.log('Data: ', data);
                let category_id = fromTask.id == toTask.id ? null : toTask.id;
                let order = data.newIndex == data.oldIndex ? false : data.newIndex;
              //  location.reload();
                console.log(category_id);
                console.log(order);
                console.log(data.newIndex);
                if (order !== false&&category_id!=null) {
                    console.log("sdfg");
                    //if(this.categories[categories.id])
                    console.log('Task ID: ', task_id);
                    console.log('Order: ', order);
                    console.log('Category ID: ', category_id);
                    axios.patch(`api/task/${task_id}`, {order, category_id}).then(response => {
                        // Do anything you want here

                    });
                }
            },
            endEditing(task) {
                this.editingTask = null
                axios.patch(`api/task/${task.id}`, {name: task.name}).then(response => {
                    // You can do anything you wan't here.
                })
            },
            editTask(task) {
                this.editingTask = task
            },
            DeleteTask(task,id)
            {
                axios.delete(`api/task/${task.id}`).then(response=>{
                    // Find category with task ID equal to task.id
                    // Also, remove it from there.
                    const index = this.categories[id].tasks.findIndex(currentTask => currentTask.id === task.id);
                    this.categories[id].tasks.splice(index, 1);
                    // this.categories[id].tasks = this.categories[id].tasks.filter(currentTask => currentTask.id !== task.id)
                    // this.$delete(this.categories[id].tasks,task.id);
                   //  alert("Task Deleted Successfully");
                   //  location.reload();
                })

            }
        },
        mounted() {
            let token = localStorage.getItem('jwt')
            axios.defaults.headers.common['Content-Type'] = 'application/json'
            axios.defaults.headers.common['Authorization'] = 'Bearer ' + token
            axios.get('api/category').then(response => {
                response.data.forEach((data) => {
                    this.categories.push({
                        id : data.id,
                        name : data.name,
                        tasks : []
                    })
                });
                this.loadTasks();
                this.$store.commit('change');
            })
        },
        computed: {
            dragOptions () {
                return  {
                    animation: 1,
                    group: 'description',
                    ghostClass: 'ghost'
                };
            },
        },
        beforeRouteEnter (to, from, next) {
            if ( ! localStorage.getItem('jwt')) {
                return next('login')
            }
            next()
        }
    }
</script>
